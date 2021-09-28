<?php

namespace norb_api\Controllers;

require_once __DIR__ . '/../Config/RegistrationConfig.php';
require_once __DIR__ . '/../Exceptions/HTTP401_Unauthorized.php';
require_once __DIR__ . '/../Exceptions/HTTP403_Forbidden.php';
require_once __DIR__ . '/../Exceptions/HTTP404_NotFound.php';
require_once __DIR__ . '/../Exceptions/HTTP422_UnprocessableEntity.php';
require_once __DIR__ . '/../Gateways/LdapUserGateway.php';
require_once __DIR__ . '/../Gateways/LocalUserGateway.php';
require_once __DIR__ . '/../Gateways/SessionGateway.php';
require_once __DIR__ . '/../Models/LocalUser.php';
require_once __DIR__ . '/AbstractSessionController.php';

use norb_api\Config\RegistrationConfig;
use norb_api\Exceptions\HTTP400_BadRequest;
use norb_api\Exceptions\HTTP403_Forbidden;
use norb_api\Gateways\LocalUserGateway;

class LocalMeController extends AbstractSessionController
{
    private $localUserGateway = null;
    private $local_user = null;

    /**
     * The Me Controller qruies a session and user gateway, other gateways are used on demand
     * MeController constructor.
     * @param string $requestMethod
     * @param string $Authorization
     */
    public function __construct(string $requestMethod,string $Authorization)
    {
        $this->localUserGateway = new LocalUserGateway();
        parent::__construct($requestMethod,$Authorization);
    }


    protected function GetRequest()
    {
        $this->require_valid_local_user();
        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        $resp['data'] = $this->local_user;
        return $resp;
    }

    protected function PatchRequest()
    {
        $this->require_valid_local_user();
        $input = (array) json_decode(file_get_contents('php://input'), true);
        $this->validatePatchData($input);
        $resp['status_code_header'] = 'HTTP/1.1 200 OK';//ggf modified?
        $LocalUserGateway = new LocalUserGateway();
        if(isset($input['password']))
        {
            $LocalUserGateway->ChangePassword($this->local_user,$input['password']);
        }
        $resp['data'] = $this->local_user;
        return $resp;
    }

    private function require_valid_local_user(){
        if(is_null($this->local_user))
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
            $this->local_user = $this->localUserGateway->findUserByUsrID($this->Session->getUsrId());
        }
        catch (\Exception $e)
        {
            $this->local_user = null;
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