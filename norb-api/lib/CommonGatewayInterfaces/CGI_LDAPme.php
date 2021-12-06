<?php

namespace norb_api\CommonGatewayInterfaces;

require_once __DIR__ . '/../Controllers/LDAPMeController.php';
require_once __DIR__ . '/AuthorizingAbstractCGI.php';

use norb_api\Controllers\LDAPMeController;

class CGI_LDAPme extends AuthorizingAbstractCGI
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processRequest()
    {
        $cont = new LDAPMeController($this->reqMeth,$this->Authorization);
        $this->resp = $cont->processRequest();
    }
}
