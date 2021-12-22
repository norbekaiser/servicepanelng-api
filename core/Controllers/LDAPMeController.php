<?php
//    Copyright (c) 2021 Norbert Rühl
//    
//    This software is provided 'as-is', without any express or implied warranty. In no event will the authors be held liable for any damages arising from the use of this software.
//    
//    Permission is granted to anyone to use this software for any purpose, including commercial applications, and to alter it and redistribute it freely, subject to the following restrictions:
//    
//        1. The origin of this software must not be misrepresented; you must not claim that you wrote the original software. If you use this software in a product, an acknowledgment in the product documentation would be appreciated but is not required.
//    
//        2. Altered source versions must be plainly marked as such, and must not be misrepresented as being the original software.
//    
//        3. This notice may not be removed or altered from any source distribution.
?>
<?php

namespace norb_api\Controllers;

require_once __DIR__ . '/../Config/RegistrationConfig.php';
require_once __DIR__ . '/../Exceptions/HTTP400_BadRequest.php';
require_once __DIR__ . '/../Exceptions/HTTP401_Unauthorized.php';
require_once __DIR__ . '/../Exceptions/HTTP403_Forbidden.php';
require_once __DIR__ . '/../Exceptions/HTTP422_UnprocessableEntity.php';
require_once __DIR__ . '/../Gateways/LdapUserGateway.php';
require_once __DIR__ . '/../Gateways/LocalLdapUserGateway.php';
require_once __DIR__ . '/../Gateways/SessionGateway.php';
require_once __DIR__ . '/../Models/LDAPUser.php';
require_once __DIR__ . '/AbstractSessionController.php';

use norb_api\Config\RegistrationConfig;
use norb_api\Exceptions\HTTP400_BadRequest;
use norb_api\Exceptions\HTTP401_Unauthorized;
use norb_api\Exceptions\HTTP403_Forbidden;
use norb_api\Exceptions\HTTP422_UnprocessableEntity;
use norb_api\Gateways\LdapUserGateway;
use norb_api\Gateways\LocalLdapUserGateway;
use norb_api\Gateways\SessionGateway;
use norb_api\Models\LDAPUser;

class LDAPMeController extends AbstractSessionController
{
    private $localLdapUserGateway = null;
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
        parent::__construct($requestMethod,$Authorization);
    }


    /**
     * Returns the User Data
     * @return mixed|void dn
     * @throws HTTP401_Unauthorized , requires a valid session and the user to be an ldap user
     * @throws HTTP422_UnprocessableEntity, if the user can't be found in the ldap database
     */
    protected function GetRequest()
    {
        $this->require_valid_ldap_user();
        $LdapUserGateway = new LdapUserGateway();
        try{
            $ldapuser = $LdapUserGateway->findByDN($this->ldap_user);
        }
        catch (\Exception $e)
        {
            throw new HTTP422_UnprocessableEntity();
        }
        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        $resp['data'] = $ldapuser;
        return $resp;
    }

    /**
     * Used to Modify User Data
     * @return mixed|void
     * @throws HTTP400_BadRequest if the format is not valid, e.g. not enough digits or no valid email
     * @throws HTTP401_Unauthorized if the user is ńot authorized to do this request, e.g. invalid session or not an ldap user
     * @throws HTTP403_Forbidden if the user is ńot allowe to change his password
     */
    protected function PatchRequest()
    {
        $this->require_valid_ldap_user();
        $input = (array) json_decode(file_get_contents('php://input'), true);
        $this->validatePatchData($input);
        $LdapUserGateway = new LdapUserGateway();
        try {
            if(isset($input['password']))
            {
                $LdapUserGateway->ChangePassword($this->ldap_user,$input['password']);
                $resp['data']['password'] = "modified";
            }
            else if(isset($input['email']))
            {
                $LdapUserGateway->ChangeEmail($this->ldap_user,$input['email']);
                $resp['data']['email'] = "modified";
            }
        }
        catch (\Exception $e)
        {
            throw new HTTP403_Forbidden($e->getMessage());
        }
        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        return $resp;
    }

    private function require_valid_ldap_user(){
        if(is_null($this->ldap_user))
        {
            throw new HTTP403_Forbidden("Unauthorized Access");
        }
    }

    /**
     * Parses The Authorization String, and create a session on demand
     */
    protected function ParseSession()
    {
        try
        {
            $this->ldap_user = $this->localLdapUserGateway->findUserID($this->Session->getUsrId());
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
