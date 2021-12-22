<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../CommonGatewayInterfaces/AuthorizingAbstractCGI.php';
require_once __DIR__ . '/../Controllers/LDAPPosixAccountController.php';

use norb_api\CommonGatewayInterfaces\AuthorizingAbstractCGI;

class CGI_LDAP_PosixAccount extends AuthorizingAbstractCGI
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processRequest()
    {
        $cont = new LDAPPosixAccountController($this->reqMeth,$this->Authorization);
        $this->resp = $cont->processRequest();
    }
}