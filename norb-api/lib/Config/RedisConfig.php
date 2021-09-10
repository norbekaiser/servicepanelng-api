<?php

namespace norb_api\Config;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Traits/Hostname.php';
require_once __DIR__ . '/Traits/Password.php';
require_once __DIR__ . '/Traits/Port.php';
require_once __DIR__ . '/Traits/DatabaseID.php';

class RedisConfig extends Config
{
    use Hostname, Password, Port, DatabaseID;

    public function __construct()
    {
        $this->Hostname = 'localhost';
        $this->Port = 6379;
        $this->Password ='';
        $this->DatabaseID = 0;
        parent::__construct(__DIR__ . '/../../config/redis.ini');
    }

    protected function parse_file($ini_data)
    {
        if(is_string($ini_data['Hostname']))
        {
            $this->Hostname = (string) $ini_data['Hostname'];
        }
        if(is_numeric($ini_data['Port']))
        {
            $this->Port = (int) $ini_data['Port'];
        }
        if(is_string($ini_data['Password']))
        {
            $this->Password = (string) $ini_data['Password'];
        }
        if(is_numeric($ini_data['DatabaseID']))
        {
            $this->DatabaseID = (int) $ini_data['DatabaseID'];
        }
    }
}