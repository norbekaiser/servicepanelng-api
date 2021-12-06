<?php

namespace norb_api\CommonGatewayInterfaces;

require_once __DIR__ . '/../Controllers/RegisterController.php';
require_once __DIR__ . '/AbstractCGI.php';

use norb_api\Controllers\RegisterController;

class CGI_register extends AbstractCGI
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processRequest()
    {
        $cont = new RegisterController($this->reqMeth);
        $this->resp = $cont->processRequest();
    }
}
