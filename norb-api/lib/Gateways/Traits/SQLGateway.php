<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/../../Config/SQLConfig.php';
require_once __DIR__ . '/../../Connectors/DatabaseConnectorSQL.php';

use norb_api\Config\SQLConfig;
use norb_api\Connectors\DatabaseConnectorSQL;

trait SQLGateway
{
    /** @var DatabaseConnectorSQL  */
    private $databaseConnectorSQL = null;
    /** @var \mysqli  */
    private $sql_db = null;

    private function init_sql()
    {
        $SQLConfig = new SQLConfig();
        $this->databaseConnectorSQL = new databaseConnectorSQL($SQLConfig);
        $this->sql_db = $this->databaseConnectorSQL->getConnection();
    }
}