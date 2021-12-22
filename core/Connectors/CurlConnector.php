<?php

namespace norb_api\Connectors;

class CurlConnector
{
    private $curl = null;

    public function __construct()
    {
        $this->curl = curl_init();
        \curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 1);
        \curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2);
        \curl_setopt($this->curl,CURLOPT_FOLLOWLOCATION,false);
        \curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    public function __destruct()
    {
        if(isset($this->curl))
        {
            curl_close($this->curl);
        }
    }

    public function getCurl()
    {
        return $this->curl;
    }
}
