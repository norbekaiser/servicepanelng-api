<?php
//    Copyright (c) 2021 Norbert Rühl
//    
//    This software is provided 'as-is', without any express or implied warranty. In no event will the authors be held liable for any damages arising from the use of this software.
//    
//    Permission is granted to anyone to use this software for any purpose, including commercial applications, and to alter it and redistribute it freely, subject to the following restrictions:
//    
//        1. The origin of this software must not be misrepresented; you must not claim that you wrote the original software. If you use this software in a product, an acknowledgment in the product documentation would be appreciated but is not required.
//    
//        2. Altered source versions must be plainly marked as such, and must not be misrepresented as being the original software.
//    
//        3. This notice may not be removed or altered from any source distribution.
?>
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
