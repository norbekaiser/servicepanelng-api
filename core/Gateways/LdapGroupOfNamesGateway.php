<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../Gateways/Traits/LDAPGateway.php';
require_once __DIR__ . '/../Models/LDAPUser.php';
require_once __DIR__ . '/../Models/LDAPGroupOfNames.php';

use norb_api\Config\LDAPConfig;
use norb_api\Gateways\LDAPGateway;
use norb_api\Models\LDAPUser;

class LdapGroupOfNamesGateway
{
    use LDAPGateway;

    public function __construct()
    {
        $this->init_ldap();
    }

    public function findAll(LDAPUser $LDAPUser): array
    {
        $ldap_config = new LDAPConfig();
        $result = array();
        $search = ldap_search($this->ldap_db,$ldap_config->getBaseDN(),"(&(objectClass=groupOfNames)(member=".ldap_escape($LDAPUser->getDN(),"",LDAP_ESCAPE_FILTER)."))",["dn","cn"]);
        $data = ldap_get_entries($this->ldap_db,$search);

        if(!isset($data[0]))
        {
            throw new \Exception("No GroupOfNames can be found for the Posix User");
        }

        for($i = 0;$i<$data['count']; $i++)
        {
            $ldapGroupOfNames = new LDAPGroupOfNames();
            $ldapGroupOfNames->setDN($data[$i]['dn']);
            //MUST
            $ldapGroupOfNames->setCn($data[$i]['cn'][0]);
            array_push($result,$ldapGroupOfNames);
        }
        return $result;
    }
}
