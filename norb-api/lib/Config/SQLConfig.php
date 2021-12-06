<?php

namespace norb_api\Config;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Traits/DatabaseName.php';
require_once __DIR__ . '/Traits/Hostname.php';
require_once __DIR__ . '/Traits/Password.php';
require_once __DIR__ . '/Traits/Port.php';
require_once __DIR__ . '/Traits/UnixSocket.php';
require_once __DIR__ . '/Traits/Username.php';

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
        if(isset($ini_data['Hostname']) and is_string($ini_data['Hostname']))
        {
            $this->Hostname = (string)$ini_data['Hostname'];
        }
        if(isset($ini_data['Port']) and is_string($ini_data['Port']))
        {
            $this->Port = (int)$ini_data['Hostname'];
        }
        if(isset($ini_data['Name']) and is_string($ini_data['Name']))
        {
            $this->DatabaseName = (string)$ini_data['Name'];
        }
        if(isset($ini_data['Socket']) and is_string($ini_data['Socket']))
        {
            $this->UnixSocket = (string)$ini_data['Socket'];
        }
        if(isset($ini_data['Username']) and is_string($ini_data['Username']))
        {
            $this->Username = (string)$ini_data['Username'];
        }
        if(isset($ini_data['Password']) and is_string($ini_data['Password']))
        {
            $this->Password = (string)$ini_data['Password'];
        }
    }
}