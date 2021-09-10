<?php

namespace norb_api\CommonGatewayInterfaces;

require_once __DIR__ . '/../Exceptions/HTTP_Exception.php';
require_once __DIR__ . '/../Exceptions/Database_Exception.php';

use norb_api\Exceptions\HTTP_Exception;
use norb_api\Exceptions\Database_Exception;

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
        $this->answerRequest();
    }

    public function sendHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,PATCH,DELETE");
        header("Access-Control-Max-Age: 1800");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    public abstract function processRequest();

    public function answerRequest()
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