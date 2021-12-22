<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/../../Config/RedisConfig.php';
require_once __DIR__ . '/../../Connectors/DatabaseConnectorRedis.php';

use norb_api\Config\RedisConfig;
use norb_api\Connectors\DatabaseConnectorRedis;

trait RedisGateway
{
    private $databaseConnectorRedis = null;
    private $redis_db = null;

    private function init_redis()
    {
        $RedisConfig = new RedisConfig();
        $this->databaseConnectorRedis = new databaseConnectorRedis($RedisConfig);
        $this->redis_db = $this->databaseConnectorRedis->getConnection();
    }
}