<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../../norb-api/lib/CommonGatewayInterfaces/AuthorizingAbstractCGI.php';
require_once __DIR__ . '/../Controllers/LDAPPosixGroupController.php';

use norb_api\CommonGatewayInterfaces\AuthorizingAbstractCGI;

class CGI_LDAP_PosixGroup extends AuthorizingAbstractCGI
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processRequest()
    {
        $cont = new LDAPPosixGroupController($this->reqMeth,$this->Authorization);
        $this->resp = $cont->processRequest();
    }
}