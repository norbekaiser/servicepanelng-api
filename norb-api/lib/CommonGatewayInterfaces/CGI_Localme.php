<?php

namespace norb_api\CommonGatewayInterfaces;

require_once __DIR__ . '/../Controllers/LocalMeController.php';
require_once __DIR__ . '/AuthorizingAbstractCGI.php';

use norb_api\Controllers\LocalMeController;

class CGI_Localme extends AuthorizingAbstractCGI
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processRequest()
    {
        $cont = new LocalMeController($this->reqMeth,$this->Authorization);
        $this->resp = $cont->processRequest();
    }
}
