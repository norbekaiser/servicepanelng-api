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

require_once __DIR__ . '/../Exceptions/Database_Exception.php';
require_once __DIR__ . '/../Exceptions/HTTP_Exception.php';

use norb_api\Exceptions\Database_Exception;
use norb_api\Exceptions\HTTP_Exception;

abstract class AbstractCGI
{
    protected $resp = null;
    protected $uri = null;
    protected $reqMeth = null;

    public function __construct()
    {
        $this->sendHeaders();
        $this->reqMeth = $_SERVER["REQUEST_METHOD"];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uri = explode('/', $this->uri);
        $this->uri = array_filter($this->uri);
        $this->uri = array_slice($this->uri,1,sizeof($this->uri));
        if($this->reqMeth=="OPTIONS")
        {
            $this->resp['status_code_header'] = 'HTTP/1.1 200 OK';
        }
        else
        {
            try
            {
                $this->processRequest();
            }
            catch (HTTP_EXCEPTION $e)
            {
                $this->resp['status_code_header'] = $e->getStatusCodeHeader();
                $this->resp['error'] = $e->getMessage();
            }
            catch (Database_Exception $e)
            {
                $this->resp['status_code_header'] = 500;
                $this->resp['error'] = $e->getMessage();
            }
            //todo maybe also catch server config problems like no sql or no redis or so thingies
        }
        $this->answerRequest();
    }

    public function sendHeaders() : void
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,PATCH,DELETE");
        header("Access-Control-Max-Age: 1800");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    public abstract function processRequest();

    public function answerRequest() : void
    {
        header($this->resp['status_code_header']);
        if(($this->resp['error'])){
            echo json_encode(['error' => $this->resp['error']]);
        }
        if ($this->resp['data'])
        {
            echo json_encode($this->resp['data']);
        }
    }
}
