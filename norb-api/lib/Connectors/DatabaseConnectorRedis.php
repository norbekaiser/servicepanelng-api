<?php

namespace norb_api\Connectors;

require_once __DIR__ . '/DatabaseConnector.php';
require_once __DIR__ . '/../Config/RedisConfig.php';
require_once __DIR__ . '/../Exceptions/NoConnectivityRedis.php';

use norb_api\Config\RedisConfig;
use norb_api\Exceptions\NoConnectivityRedis;

class DatabaseConnectorRedis extends DatabaseConnector
{
    private $redis = null;

    public function __construct(RedisConfig $config)
    {
        try
        {
            $this->redis = new \Redis();//not quite sure if it throws or what redis connect does
            $this->redis->connect($config->getHostname(),$config->getPort());
            if(!empty($config->getPassword()))
            {
                $this->redis->auth($config->getPassword());
            }
            $this->redis->select($config->getDatabaseId());
        }
        catch (\Exception $e)
        {
            throw new NoConnectivityRedis();
        }
    }

    public function __destruct()
    {
        if(isset($this->redis))
        {
            $this->redis->close();
        }
    }

    public function getConnection()
    {
         return $this->redis;
    }
}
