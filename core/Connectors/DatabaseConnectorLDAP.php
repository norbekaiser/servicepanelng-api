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

require_once __DIR__ . '/../Config/LDAPConfig.php';
require_once __DIR__ . '/../Exceptions/NoConnectivityLDAP.php';
require_once __DIR__ . '/DatabaseConnector.php';

use norb_api\Config\LDAPConfig;
use norb_api\Exceptions\NoConnectivityLDAP;

class DatabaseConnectorLDAP extends DatabaseConnector
{

    private $link;

    public function __construct(LDAPConfig $config)
    {
        $this->link = ldap_connect($config->getUri(),$config->getPort());
        ldap_set_option($this->link,LDAP_OPT_PROTOCOL_VERSION,3);
        ldap_set_option($this->link,LDAP_OPT_REFERRALS,0);

        $bind=false;
        if(empty($config->getAdminDn()) && empty($config->getAdminPassword()))
        {
            $bind = ldap_bind($this->link);
        }
        else
        {
            $bind = ldap_bind($this->link,$config->getAdminDn(),$config->getAdminPassword());
        }

        if(!$bind)
        {
            throw new NoConnectivityLDAP();//todo a more suitable name, sincethis implies it is a conenction
        }
    }

    public function __destruct()
    {
        ldap_close($this->link);
    }

    public function getConnection()
    {
        return $this->link;
    }
}
