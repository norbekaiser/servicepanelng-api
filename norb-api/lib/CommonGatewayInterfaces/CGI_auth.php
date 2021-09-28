<?php

namespace norb_api\CommonGatewayInterfaces;

require_once __DIR__ . '/../Controllers/AuthController.php';
require_once __DIR__ . '/AbstractCGI.php';

use norb_api\Controllers\AuthController;

class CGI_auth extends AbstractCGI
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processRequest()
    {
        $cont = new AuthController($this->reqMeth);
        $this->resp = $cont->processRequest();
    }
}
