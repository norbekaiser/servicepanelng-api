<?php

namespace norb_api\Config;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Traits/Hostname.php';
require_once __DIR__ . '/Traits/Port.php';
require_once __DIR__ . '/Traits/DatabaseName.php';
require_once __DIR__ . '/Traits/UnixSocket.php';
require_once __DIR__ . '/Traits/Username.php';
require_once __DIR__ . '/Traits/Password.php';

class SQLConfig extends Config
{
    use Hostname, Port, DatabaseName, UnixSocket, Username, Password ;

    public function __construct()
    {
        $this->Hostname ='localhost';
        $this->Port = 3306;
        $this->DatabaseName = '';
        $this->UnixSocket = '/var/run/mysqld/mysqld.sock';
        $this->Username = '';
        $this->Password = '';
        parent::__construct(__DIR__.'/../../config/database.ini');
    }

    public function parse_file($ini_data)
    {
        if(is_string($ini_data['Hostname']))
        {
            $this->Hostname = (string)$ini_data['Hostname'];
        }
        if(is_string($ini_data['Port']))
        {
            $this->Port = (int)$ini_data['Hostname'];
        }
        if(is_string($ini_data['Name']))
        {
            $this->DatabaseName = (string)$ini_data['Name'];
        }
        if(is_string($ini_data['Socket']))
        {
            $this->UnixSocket = (string)$ini_data['Socket'];
        }
        if(is_string($ini_data['Username']))
        {
            $this->Username = (string)$ini_data['Username'];
        }
        if(is_string($ini_data['Password']))
        {
            $this->Password = (string)$ini_data['Password'];
        }
    }
}