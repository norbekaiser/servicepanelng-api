<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../Gateways/LdapInetOrgPersonGateway.php';
require_once __DIR__ . '/../../norb-api/lib/Gateways/SessionGateway.php';
require_once __DIR__ . '/../../norb-api/lib/Gateways/LocalLdapUserGateway.php';
require_once __DIR__ . '/../../norb-api/lib/Exceptions/HTTP404_NotFound.php';
require_once __DIR__ . '/../../norb-api/lib/Exceptions/HTTP401_Unauthorized.php';
require_once __DIR__ . '/../../norb-api/lib/Controllers/AbstractHeaderController.php';

use norb_api\Gateways\SessionGateway;
use norb_api\Gateways\LocalLdapUserGateway;
use norb_api\Exceptions\HTTP404_NotFound;
use norb_api\Exceptions\HTTP401_Unauthorized;
use norb_api\Controllers\AbstractHeaderController;

class LDAPInetOrgPersonController extends AbstractHeaderController
{
    private $LocalLDAPUser = null;
    private $LdapinetOrgPersonGateway = null;

    public function __construct(string $requestMethod, string $Authorization)
    {
        $this->LdapinetOrgPersonGateway = new LdapInetOrgPersonGateway();
        parent::__construct($requestMethod, $Authorization);
    }

    protected function ParseAuthorization()
    {
        try
        {
            $SessionGateway = new SessionGateway();
            $session = $SessionGateway->find_session($this->Authorization);
            $localLdapUserGateway = new LocalLdapUserGateway();
            $this->LocalLDAPUser = $localLdapUserGateway->findUserID($session->getUsrId());
        }
        catch (\Exception $e)
        {
            $this->LocalLDAPUser= null;
        }
    }

    private function require_valid_session()
    {
        if(is_null($this->LocalLDAPUser))
        {
            throw new HTTP401_Unauthorized();
        }
    }

    protected function GetRequest()
    {
        $this->require_valid_session();
        try{
            $data = $this->LdapinetOrgPersonGateway->find($this->LocalLDAPUser);
        }
        catch (\Exception $e){
            throw new HTTP404_NotFound();
        }

        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        $resp['data'] = array(
            'dn' => $data->getDN(),
            'cn' => $data->getCN(),
            'sn' => $data->getSN(),
            'mail' => $data->getMail(),
            'givenName' => $data->getGivenName()
        );
        return $resp;
    }


}