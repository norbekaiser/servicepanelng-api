<?php

namespace norb_api\CommonGatewayInterfaces;

require_once __DIR__ . '/../Controllers/AuthController.php';
require_once __DIR__ . '/AbstractCGI.php';

class CGI_2fa extends AbstractCGI
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processRequest()
    {

    }
}
