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

namespace norb_api\Gateways;

require_once __DIR__ . '/../Models/LDAPUser.php';
require_once __DIR__ . '/Traits/LDAPGateway.php';
require_once __DIR__ . '/Traits/SQLGateway.php';

use norb_api\Config\LDAPConfig;
use norb_api\Models\LDAPUser;

/**
 * Class LdapUserGateway
 * This Provides a Gateway to the LDAP
 */
class LdapUserGateway
{
    use LDAPGateway;

    /**
     * The Gateway requires a an ldap connection
     *
     */
    public function __construct()
    {
        $this->init_ldap();
    }


    public function findByDN(LDAPUser $LDAPUser): LDAPUser
    {
        $search = ldap_read($this->ldap_db,$LDAPUser->getDN(),"objectClass=*");
        $data = ldap_get_entries($this->ldap_db,$search);
        if($data["count"]==0){
            throw new \Exception("No User with this DN could be found");
        }
        $LDAPUser->setDN($data[0]['dn']);
        return $LDAPUser;
    }

    public function AuthenticateUser($username,$password): LDAPUser
    {
        $ldap_config = new LDAPConfig();
        //Search Ldap for User
        $search = ldap_search($this->ldap_db,$ldap_config->getBaseDN(),"(&(objectClass=*)(uid:caseExactMatch:=".ldap_escape($username,"",LDAP_ESCAPE_FILTER)."))",['dn','uid']);
        $search_result = ldap_get_entries($this->ldap_db,$search);
        //only one result should be found, if it smaller, no user exists, if there is more than one, then its hard to decide whome to authenticate against
        if($search_result["count"] != 1)
        {
            throw new \Exception("Could not Authenticate against any User");
        }
        //ldap dn is extracted via previous search
        $ldap_username = $search_result[0]["dn"];
        //then authenticate with the found user against it
        $authenticated = @ldap_bind($this->ldap_db,$ldap_username,$password);
        if(!$authenticated)
        {
            throw new \Exception("Could not Authenticate against LDAP");
        }
        $LDAPUser = new LDAPUser();
        $LDAPUser->setDN($ldap_username);

        return $LDAPUser;
    }

    public function ChangePassword(LDAPUser $LDAPUser,string $password): void
    {
        $salt = substr(bin2hex(openssl_random_pseudo_bytes(16)),0,16);
        $values["userPassword"] = "{CRYPT}".crypt($password,'$6$'.$salt);
        if(! ldap_modify($this->ldap_db,$LDAPUser->getDn(),$values))
        {
            throw new \Exception("Password can not be modified");
        }
    }

    public function ChangeEmail(LDAPUser $LDAPUser,string $email): void
    {
        //delegated to the ldap, e.g blocking with the following acl
        //{1}to dn.children="ou=users,dc=ldap,dc=example,dc=om" attrs=mail by dn.children="cn=manager,dc=ldap,dc=example,dc=com" write by self read by anonymous none
        //so that only one self may change it
        $values["email"] = $email;
        if(! ldap_modify($this->ldap_db,$LDAPUser->getDn(),$values))
        {
            throw new \Exception("Email may not be modified");
        }
    }
}
