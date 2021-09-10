<?php

namespace norb_api\Controllers;

require_once __DIR__ . '/AbstractController.php';
require_once __DIR__ . '/../Exceptions/HTTP422_UnprocessableEntity.php';
require_once __DIR__ . '/../Exceptions/HTTP400_BadRequest.php';
require_once __DIR__ . '/../Gateways/LocalUserGateway.php';
require_once __DIR__ . '/../Gateways/LdapUserGateway.php';
require_once __DIR__ . '/../Gateways/LocalLdapUserGateway.php';
require_once __DIR__ . '/../Gateways/RecaptchaV2Gateway.php';
require_once __DIR__ . '/../Gateways/RecaptchaV3Gateway.php';
require_once __DIR__ . '/../Gateways/SessionGateway.php';
require_once __DIR__ . '/../Config/RecaptchaConfig.php';


use norb_api\Gateways\LocalUserGateway;
use norb_api\Gateways\LdapUserGateway;
use norb_api\Gateways\LocalLdapUserGateway;
use norb_api\Gateways\SessionGateway;
use norb_api\Gateways\RecaptchaV2Gateway;
use norb_api\Gateways\RecaptchaV3Gateway;
use norb_api\Models\User;
use norb_api\Models\LocalUser;
use norb_api\Models\LDAPUser;
use norb_api\Exceptions\HTTP_Exception;
use norb_api\Exceptions\HTTP400_BadRequest;
use norb_api\Exceptions\HTTP422_UnprocessableEntity;
use norb_api\Config\RecaptchaConfig;

class AuthController extends AbstractController
{


    public function __construct(string $requestMethod)
    {
        parent::__construct($requestMethod);
    }

    private function AuthenticateLDAP(string $username,string $password): LDAPUser
    {
            $LdapUserGateway = new LdapUserGateway();
            $LocalUserLdapGateway = new LocalLdapUserGateway();
            $user = $LdapUserGateway->AuthenticateUser($username,$password);
            try{
                $user = $LocalUserLdapGateway->findUserDN($user->getDN());
            }
            catch (\Exception $e)
            {
                $user = $LocalUserLdapGateway->InsertUserDN($user->getDN());
            }
            return $user;
    }

    private function AuthenticateLocal(string $username,string $password): LocalUser
    {
            $LocalUserGateway = new LocalUserGateway();
            $user = $LocalUserGateway->Authenticate($username,$password);
            return $user;
    }

    private function Authenticate(string $username,string $password): User
    {
        $user= null;

        try {
            $user=$this->AuthenticateLDAP($username,$password);
        }
        catch (\Exception $e)
        {

        }
        try {
            $user=$this->AuthenticateLocal($username,$password);
        }
        catch (\Exception $e)
        {

        }


        //Check Against Local Database
        if(is_null($user))
        {
            throw new \Exception("Authentication Failed");
        }
        return $user;
    }

    protected function PostRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        try
        {
            $this->validateCandidate($input);
            $user = $this->Authenticate($input['username'],$input['password']);
        }
        catch (HTTP_Exception $e)
        {
            throw $e;
        }
        catch (\Exception $e)
        {
            throw new HTTP422_UnprocessableEntity($e->getMessage());
        }

        $sessionGateway = new SessionGateway();
        $new_session = $sessionGateway->create_session($user);

        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        $resp['data'] = array(
            'sessionid' => $new_session->getSessionId()
        );
        return $resp;
    }


    private function validateCandidate($input)
    {
        $CaptchaConfig = new RecaptchaConfig();
        /** Candidate must provide a username/email */
        if(!isset($input['username']))
        {
            throw new HTTP400_BadRequest("No Username Supplied");
        }
        /** Candidate must provide a password */
        if(!isset($input['password']))
        {
            throw new HTTP400_BadRequest("No Password Supplied");
        }

        if($CaptchaConfig->getEnabled() && !isset($input['g_recaptcha_response']))
        {
            throw new HTTP400_BadRequest("No Google Recaptcha Response Supplied");
        }

        if($CaptchaConfig->getEnabled() && $CaptchaConfig->getVersion()==2)
        {
            $captcha = new RecaptchaV2Gateway();
            if(! ($captcha->verify($input['g_recaptcha_response'])))
            {
                throw new HTTP422_UnprocessableEntity(("Recaptcha Failed"));
            }
        }
        else if($CaptchaConfig->getEnabled() && $CaptchaConfig->getVersion()==3)
        {
            $captcha = new RecaptchaV3Gateway();
            if(! (($captcha->verify($input['g_recaptcha_response']))>=0.5) )
            {
                throw new HTTP422_UnprocessableEntity(("Recaptcha Failed"));
            }
        }
    }

}