<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/../../Config/LDAPConfig.php';
require_once __DIR__ . '/../../Connectors/DatabaseConnectorLDAP.php';

use norb_api\Config\LDAPConfig;
use norb_api\Connectors\DatabaseConnectorLDAP;

/**
 * Trait LDAPGateway
 * This Trait provides a Connection for a Gateway to an LDAP
 */
trait LDAPGateway
{
    private $databaseConnectorLDAP = null;
    private $ldap_db = null;

    private function init_ldap()
    {
        $LDAPConfig = new LDAPConfig();
        $this->databaseConnectorLDAP = new databaseConnectorLDAP($LDAPConfig);
        $this->ldap_db = $this->databaseConnectorLDAP->getConnection();
    }
}