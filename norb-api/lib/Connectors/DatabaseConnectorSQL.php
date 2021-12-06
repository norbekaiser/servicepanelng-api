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
