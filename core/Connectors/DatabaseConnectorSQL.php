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

namespace norb_api\Connectors;

require_once __DIR__ . '/../Config/SQLConfig.php';
require_once __DIR__ . '/../Exceptions/NoConnectivitySQL.php';
require_once __DIR__ . '/DatabaseConnector.php';

use norb_api\Config\SQLConfig;
use norb_api\Exceptions\NoConnectivitySQL;

class DatabaseConnectorSQL extends DatabaseConnector
{
    private $connection = null;

    public function __construct(SQLConfig $config)
    {
        try
        {
            $this->connection = mysqli_init();
//            $mysqli->options();
            $this->connection->real_connect($config->getHostname(),$config->getUsername(),$config->getPassword(),$config->getDatabaseName(),$config->getPort(),$config->getUnixSocket(),MYSQLI_CLIENT_FOUND_ROWS);
        }
        catch (\Exception $e)
        {
            throw new NoConnectivitySQL();
        }
    }

    public function __destruct()
    {
        if(isset($this->connection))
        {
            $this->connection->close();
        }
    }

    public function getConnection(): \mysqli
    {
         return $this->connection;
    }
}
