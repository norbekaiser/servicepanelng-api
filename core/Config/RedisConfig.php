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

namespace norb_api\Config;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Traits/DatabaseID.php';
require_once __DIR__ . '/Traits/Hostname.php';
require_once __DIR__ . '/Traits/Password.php';
require_once __DIR__ . '/Traits/Port.php';

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
        if(isset($ini_data['Hostname']) and is_string($ini_data['Hostname']))
        {
            $this->Hostname = (string) $ini_data['Hostname'];
        }
        if(isset($ini_data['Port']) and is_numeric($ini_data['Port']))
        {
            $this->Port = (int) $ini_data['Port'];
        }
        if(isset($ini_data['Password']) and is_string($ini_data['Password']))
        {
            $this->Password = (string) $ini_data['Password'];
        }
        if(isset($ini_data['DatabaseID']) and is_numeric($ini_data['DatabaseID']))
        {
            $this->DatabaseID = (int) $ini_data['DatabaseID'];
        }
    }
}
