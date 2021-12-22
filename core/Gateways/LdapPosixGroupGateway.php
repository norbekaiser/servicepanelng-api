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

namespace servicepanel_ng;

require_once __DIR__ . '/../Gateways/Traits/LDAPGateway.php';
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
