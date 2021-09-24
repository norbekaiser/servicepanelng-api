<?php

namespace norb_api\Connectors;

require_once __DIR__ . '/DatabaseConnector.php';
require_once __DIR__ . '/../Exceptions/NoConnectivityLDAP.php';
require_once __DIR__ . '/../Config/LDAPConfig.php';

use norb_api\Exceptions\NoConnectivityLDAP;
use norb_api\Config\LDAPConfig;

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
