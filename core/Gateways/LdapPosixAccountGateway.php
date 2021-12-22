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
require_once __DIR__ . '/../Models/LDAPUser.php';
require_once __DIR__ . '/../Models/LDAPPosixAccount.php';

use norb_api\Gateways\LDAPGateway;
use norb_api\Models\LDAPUser;

class LdapPosixAccountGateway
{
    use LDAPGateway;

    public function __construct()
    {
        $this->init_ldap();
    }

    public function find(LDAPUser $LDAPUser): LDAPPosixAccount
    {
        $ldapPosixAccount = new LDAPPosixAccount();
        $search = ldap_read($this->ldap_db,$LDAPUser->getDN(),"objectClass=PosixAccount",["dn","cn","uid","uidNumber","gidNumber","homeDirectory","loginshell"]);
        $data = ldap_get_entries($this->ldap_db,$search);

        if(!isset($data[0]))
        {
            throw new \Exception("User can not be found as PosixAccount");
        }

        $ldapPosixAccount->setDN($data[0]['dn']);
        //MUST
        $ldapPosixAccount->setCn($data[0]['cn'][0]);
        $ldapPosixAccount->setUid($data[0]['uid'][0]);
        $ldapPosixAccount->setGidNumber($data[0]['gidnumber'][0]);
        $ldapPosixAccount->setUidNumber($data[0]['uidnumber'][0]);
        $ldapPosixAccount->setHomeDirectory($data[0]['homedirectory'][0]);
        //MAY
        if(isset($data[0]['loginshell'][0])){
            $ldapPosixAccount->setLoginShell($data[0]['loginshell'][0]);
        }

        return $ldapPosixAccount;
    }
}
