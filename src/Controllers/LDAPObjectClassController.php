<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../../norb-api/lib/Controllers/AbstractSessionController.php';
require_once __DIR__ . '/../../norb-api/lib/Exceptions/HTTP403_Forbidden.php';
require_once __DIR__ . '/../../norb-api/lib/Gateways/LocalLdapUserGateway.php';
require_once __DIR__ . '/../../norb-api/lib/Gateways/SessionGateway.php';
require_once __DIR__ . '/../Gateways/LdapObjectClassGateway.php';

use norb_api\Controllers\AbstractSessionController;
use norb_api\Exceptions\HTTP403_Forbidden;
use norb_api\Exceptions\HTTP422_UnprocessableEntity;
use norb_api\Gateways\LocalLdapUserGateway;


class LDAPObjectClassController extends AbstractSessionController
{
    private $LocalLDAPUser = null;
    private $LdapObjectClassGateway = null;

    public function __construct(string $requestMethod, string $Authorization)
    {
        $this->LdapObjectClassGateway = new LDAPObjectClassGateway();
        parent::__construct($requestMethod, $Authorization);
    }

    protected function ParseSession()
    {
        try
        {
            $localLdapUserGateway = new LocalLdapUserGateway();
            $this->LocalLDAPUser = $localLdapUserGateway->findUserID($this->Session->getUsrId());
        }
        catch (\Exception $e)
        {
            $this->LocalLDAPUser= null;
        }
    }

    private function require_valid_local_ldap_user()
    {
        if(is_null($this->LocalLDAPUser))
        {
            throw new HTTP403_Forbidden("User must be an LDAP User to perform this request");
        }
    }

    protected function GetRequest()
    {
        $this->require_valid_local_ldap_user();
        try {
            $data = $this->LdapObjectClassGateway->find($this->LocalLDAPUser);
        }
        catch (\Exception $e){
            throw new HTTP422_UnprocessableEntity();
        }

        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        $resp['data'] = $data;
        return $resp;
    }
}