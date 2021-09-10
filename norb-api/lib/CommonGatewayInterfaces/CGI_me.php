<?php

namespace norb_api\CommonGatewayInterfaces;

require_once __DIR__ . '/AuthorizingAbstractCGI.php';
require_once __DIR__ . '/../Controllers/MeController.php';

use norb_api\Controllers\MeController;

class CGI_me extends AuthorizingAbstractCGI
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processRequest()
    {
        $cont = new MeController($this->reqMeth,$this->Authorization);
        $this->resp = $cont->processRequest();
    }
}
