<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../Gateways/LdapPosixGroupGateway.php';
require_once __DIR__ . '/../Gateways/LdapPosixAccountGateway.php';
require_once __DIR__ . '/../../norb-api/lib/Gateways/SessionGateway.php';
require_once __DIR__ . '/../../norb-api/lib/Gateways/LocalLdapUserGateway.php';
require_once __DIR__ . '/../../norb-api/lib/Exceptions/HTTP404_NotFound.php';
require_once __DIR__ . '/../../norb-api/lib/Exceptions/HTTP403_Forbidden.php';
require_once __DIR__ . '/../../norb-api/lib/Controllers/AbstractSessionController.php';

use norb_api\Controllers\AbstractSessionController;
use norb_api\Exceptions\HTTP403_Forbidden;
use norb_api\Gateways\LocalLdapUserGateway;

class LDAPPosixGroupController extends AbstractSessionController
{
    private $PosixUser = null;
    private $LdapPosixGroupGateway = null;

    public function __construct(string $requestMethod, string $Authorization)
    {
        $this->LdapPosixGroupGateway = new LdapPosixGroupGateway();
        parent::__construct($requestMethod, $Authorization);
    }

    protected function ParseSession()
    {
        try
        {
            $localLdapUserGateway = new LocalLdapUserGateway();
            $LocalLDAPUser = $localLdapUserGateway->findUserID($this->Session->getUsrId());
            $ldapPosixAccountGateway = new LdapPosixAccountGateway();
            $this->PosixUser = $ldapPosixAccountGateway->find($LocalLDAPUser);
        }
        catch (\Exception $e)
        {
            $this->PosixUser= null;
        }
    }


    private function require_valid_posix_user()
    {
        $this->require_valid_session();
        if(is_null($this->PosixUser))
        {
            throw new HTTP403_Forbidden("Only Posix Users can access their PosixGroup Memberships");
        }
    }

    protected function GetRequest()
    {
        $this->require_valid_posix_user();
        $data = $this->LdapPosixGroupGateway->findAll($this->PosixUser);
        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        $resp['data'] = $data;
        return $resp;
    }


}