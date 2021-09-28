<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../../norb-api/lib/Gateways/Traits/LDAPGateway.php';
require_once __DIR__ . '/../Models/LDAPPosixGroup.php';

use norb_api\Config\LDAPConfig;
use norb_api\Gateways\LDAPGateway;

class LdapPosixGroupGateway
{
    use LDAPGateway;

    public function __construct()
    {
        $this->init_ldap();
    }

    public function findAll(LDAPPosixAccount $LDAPUser): array
    {
        $ldap_config = new LDAPConfig();
        $result = array();
        $search = ldap_search($this->ldap_db,$ldap_config->getBaseDN(),"(&(objectClass=PosixGroup)(memberUid=".ldap_escape($LDAPUser->getUid(),"",LDAP_ESCAPE_FILTER)."))",["dn","cn","gidNumber"]);
        $data = ldap_get_entries($this->ldap_db,$search);

        if(!isset($data[0]))
        {
            throw new \Exception("No Posix Groups can be found for the Posix User");
        }

        for($i = 0;$i<$data['count']; $i++)
        {
            $ldapPosixGroup = new LDAPPosixGroup();
            $ldapPosixGroup->setDN($data[$i]['dn']);
            //MUST
            $ldapPosixGroup->setCn($data[$i]['cn'][0]);
            $ldapPosixGroup->setGidNumber($data[$i]['gidnumber'][0]);
            array_push($result,$ldapPosixGroup);
        }
        return $result;
    }
}
