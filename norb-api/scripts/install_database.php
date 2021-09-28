<?php

require_once __DIR__ . '/../lib/Config/SQLConfig.php';
require_once __DIR__ . '/../lib/Connectors/DatabaseConnectorSQL.php';

use norb_api\Config\SQLConfig;
use norb_api\Connectors\DatabaseConnectorSQL;

$return=0;

function run_query(DatabaseConnectorSQL $dbcon,$filename){
    echo "--------------------\n";
    $query= file_get_contents($filename,false);
    echo "Running the Following Query:\n";
    echo $query;
    $res = $dbcon->getConnection()->query($query);
    if($res == true){
        echo "\nSuccessfull\n";
    }
    else{
        echo "\nFailed\n";
        $return=1;
    }
    echo "--------------------\n";
}

echo "Running Database Install Script\n";
echo "Connecting to Database\n";
$DBCSQL = new DatabaseConnectorSQL(new SQLConfig());

echo "Installing User ID\n";
run_query($DBCSQL,__DIR__.'/../SQL/users_id.sql');
echo "Installing LDAP User Table\n";
run_query($DBCSQL,__DIR__.'/../SQL/users_ldap.sql');
echo "Installing Local User Table\n";
run_query($DBCSQL,__DIR__.'/../SQL/users_local.sql');
echo "Finished Database Initialization\n";

exit($return);

