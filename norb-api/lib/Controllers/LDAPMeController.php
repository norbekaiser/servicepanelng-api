<?php

namespace norb_api\Controllers;

require_once __DIR__ . '/AbstractHeaderController.php';
require_once __DIR__ . '/../Exceptions/HTTP400_BadRequest.php';
require_once __DIR__ . '/../Exceptions/HTTP401_Unauthorized.php';
require_once __DIR__ . '/../Exceptions/HTTP422_UnprocessableEntity.php';
require_once __DIR__ . '/../Config/RegistrationConfig.php';
require_once __DIR__ . '/../Gateways/LocalLdapUserGateway.php';
require_once __DIR__ . '/../Gateways/LdapUserGateway.php';
require_once __DIR__ . '/../Gateways/SessionGateway.php';

use norb_api\Gateways\LdapUserGateway;
use norb_api\Gateways\LocalLdapUserGateway;
use norb_api\Gateways\SessionGateway;
use norb_api\Models\LDAPUser;
use norb_api\Exceptions\HTTP400_BadRequest;
use norb_api\Exceptions\HTTP401_Unauthorized;
use norb_api\Exceptions\HTTP422_UnprocessableEntity;
use norb_api\Config\RegistrationConfig;

class LDAPMeController extends AbstractHeaderController
{
    private $localLdapUserGateway = null;
    private $sessionGateway = null;
    private $ldap_user = null;

    /**
     * The Me Controller requires a session and user gateway, other gateways are used on demand
     * MeController constructor.
     * @param string $requestMethod
     * @param string $Authorization
     */
    public function __construct(string $requestMethod,string $Authorization)
    {
        $this->localLdapUserGateway = new LocalLdapUserGateway();
        $this->sessionGateway = new SessionGateway();
        parent::__construct($requestMethod,$Authorization);
    }


    /**
     * Maps the LDAP user to the result
     * @param LDAPUser $user
     * @return array
     */
    private function LdapUser_data_to_resp(LDAPUser $user): array
    {
        return array(
            'dn' => $user->getDn(),
            'member_since' => $user->getMemberSince(),
        );
    }

    /**
     * Verifys that a session user was issued
     * @throws HTTP401_Unauthorized
     */
    private function require_valid_session()
    {
        if(is_null($this->ldap_user))
        {
            throw new HTTP401_Unauthorized();
        }
    }

    /**
     * Returns the User Data
     * @return mixed|void boils down to at least usr_id  and username/dn( also username)
     * @throws HTTP401_Unauthorized , requires a valid session
     * @throws HTTP422_UnprocessableEntity, if the user can't be found either in userlocal or userldap , this will hapoen
     */
    protected function GetRequest()
    {
        $this->require_valid_session();
        $LdapUserGateway = new LdapUserGateway();
        $ldapuser = $LdapUserGateway->findByDN($this->ldap_user);
        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        $resp['data'] = $ldapuser->getDN();
        return $resp;
    }

    /**
     * Used to Modify User Data
     * @return mixed|void
     * @throws HTTP422_UnprocessableEntity if the user is not n ldap user
     * @throws HTTP400_BadRequest if the format is not valid, e.g. not enough digits or no valid email
     * @throws HTTP401_Unauthorized if the user is ńot authorized to do this request
     */
    protected function PatchRequest()
    {
        $this->require_valid_session();
        $input = (array) json_decode(file_get_contents('php://input'), true);
        $this->validatePatchData($input);
        $LdapUserGateway = new LdapUserGateway();

        if(isset($input['password']))
        {
            $LdapUserGateway->ChangePassword($this->ldap_user,$input['password']);
            $resp['data']['password'] = "modified";
        }
        if(isset($input['email']))
        {
            $LdapUserGateway->ChangeEmail($this->ldap_user,$input['email']);
            $resp['data']['email'] = "modified";
        }
        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        return $resp;
    }

    /**
     * Parses The Authorization String, and create a session on demand
     */
    protected function ParseAuthorization()
    {
        try
        {
            $session = $this->sessionGateway->find_session($this->Authorization);
            $session->getUsrId();
            $this->ldap_user = $this->localLdapUserGateway->findUserID($session->getUsrId());
        }
        catch (\Exception $e)
        {
            $this->ldap_user = null;
        }
    }

    private function validatePatchData($input){
        $RegistrationConfig = new RegistrationConfig();
        /**
         * Verifys Data If A Password Change is Requried
         */
        if(isset($input['password']))
        {
            /** password must be at least 8 letters long */
            if(strlen($input['password']) < $RegistrationConfig->getMinimumLength())
            {

                throw new HTTP400_BadRequest("Password must Contain at least ".$RegistrationConfig->getMinimumLength()." Characters");
            }

            if($RegistrationConfig->getRequiresLetters() && !(preg_match('[\D]',$input['password'])))
            {
                throw new HTTP400_BadRequest("Password must Contain at least 1 Letter");
            }

            if($RegistrationConfig->getRequiresDigits() && !(preg_match('[\d]',$input['password'])))
            {
                throw new HTTP400_BadRequest("Password must Contain at least 1 Digit");
            }
        }

    }
}