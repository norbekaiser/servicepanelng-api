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

