<?php

namespace norb_api\CommonGatewayInterfaces;

require_once __DIR__ . '/AbstractCGI.php';

abstract class AuthorizingAbstractCGI extends AbstractCGI
{
    protected $Authorization =null;

    public function __construct()
    {
        $this->ExtractAuthorizationToken();
        parent::__construct();

    }

    public function ExtractAuthorizationToken()
    {
        if(array_key_exists('Authorization',$_SERVER))
        {
            $this->Authorization = $_SERVER['Authorization'];
        }
        else if(array_key_exists('HTTP_AUTHORIZATION',$_SERVER))
        {
            $this->Authorization = $_SERVER['HTTP_AUTHORIZATION'];
        }
        else
        {
            $this->Authorization = "";
        }
    }
}