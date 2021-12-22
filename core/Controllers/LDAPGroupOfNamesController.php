<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../Controllers/AbstractSessionController.php';
require_once __DIR__ . '/../Exceptions/HTTP204_NoContent.php';
require_once __DIR__ . '/../Exceptions/HTTP403_Forbidden.php';
require_once __DIR__ . '/../Exceptions/HTTP404_NotFound.php';
require_once __DIR__ . '/../Gateways/LdapUserGateway.php';
require_once __DIR__ . '/../Gateways/LocalLdapUserGateway.php';
require_once __DIR__ . '/../Gateways/SessionGateway.php';
require_once __DIR__ . '/../Gateways/LdapGroupOfNamesGateway.php';
require_once __DIR__ . '/../Gateways/LdapPosixAccountGateway.php';

use norb_api\Controllers\AbstractSessionController;
use norb_api\Exceptions\HTTP204_NoContent;
use norb_api\Exceptions\HTTP403_Forbidden;
use norb_api\Gateways\LdapUserGateway;
use norb_api\Gateways\LocalLdapUserGateway;

class LDAPGroupOfNamesController extends AbstractSessionController
{
    private $LdapUser = null;
    private $LdapGroupOfNamesGateway = null;

    public function __construct(string $requestMethod, string $Authorization)
    {
        $this->LdapGroupOfNamesGateway = new LdapGroupOfNamesGateway();
        parent::__construct($requestMethod, $Authorization);
    }

    protected function ParseSession()
    {
        try
        {
            $localLdapUserGateway = new LocalLdapUserGateway();
            $LocalLDAPUser = $localLdapUserGateway->findUserID($this->Session->getUsrId());
            $ldapUserGateway = new LdapUserGateway();
            $this->LdapUser = $ldapUserGateway->findByDN($LocalLDAPUser);
        }
        catch (\Exception $e)
        {
            $this->LdapUser= null;
        }
    }


    private function require_valid_posix_user()
    {
        $this->require_valid_session();
        if(is_null($this->LdapUser))
        {
            throw new HTTP403_Forbidden("Only LDAP Users can access their GroupOfNames Memberships");
        }
    }

    protected function GetRequest()
    {
        $this->require_valid_posix_user();
        try
        {
            $data = $this->LdapGroupOfNamesGateway->findAll($this->LdapUser);
        }
        catch (\Exception $e)
        {
            throw new HTTP204_NoContent("No GroupOfNames could be found");
        }

        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        $resp['data'] = $data;
        return $resp;
    }


}