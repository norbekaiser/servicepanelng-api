<?php

namespace norb_api\Controllers;

require_once __DIR__ . '/../Exceptions/HTTP405_MethodNotAllowed.php';

use norb_api\Exceptions\HTTP405_MethodNotAllowed;

abstract class AbstractController //TODO consider, abstract authorized controlelr, der auch Authorization Header Parsen kann um davon ggf für me controlelr zu erben
{
    protected $requestMethod;

    public function __construct(string $requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    protected function GetRequest()
    {
        return $this->MethodNotAvailableResponse();
    }

    protected  function PostRequest()
    {
        return $this->MethodNotAvailableResponse();
    }

    protected function PutRequest()
    {
        return $this->MethodNotAvailableResponse();
    }

    protected function PatchRequest()
    {
        return $this->MethodNotAvailableResponse();
    }

    protected function DelRequest()
    {
        return $this->MethodNotAvailableResponse();
    }

    protected function OptionsRequest()
    {
        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        return $resp;
    }

    public function processRequest()
    {
        switch ($this->requestMethod)
        {
            case 'GET':
                $resp = $this->GetRequest();
                break;
            case 'POST':
                $resp = $this->PostRequest();
                break;
            case 'PUT':
                $resp = $this->PutRequest();
                break;
            case 'PATCH':
                $resp = $this->PatchRequest();
                break;
            case 'DELETE':
                $resp = $this->DelRequest();
                break;
            case 'OPTIONS':
                $resp = $this->OptionsRequest();
                break;
            default:
                $resp = $this->MethodNotAvailableResponse();
                break;
        }
        return $resp;
    }

    private function MethodNotAvailableResponse()
    {
        throw new HTTP405_MethodNotAllowed();
    }
}