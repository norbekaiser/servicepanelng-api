<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/Traits/SQLGateway.php';
require_once __DIR__ . '/Traits/LDAPGateway.php';
require_once __DIR__ . '/../Models/LDAPUser.php';

use norb_api\Models\LDAPUser;
use norb_api\Config\LDAPConfig;

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
        $ldap_username = "cn=".ldap_escape($username,"",LDAP_ESCAPE_FILTER).",".$ldap_config->getBaseDN();
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
        //{1}to dn.children="ou=users,dc=ldap,dc=example,dc=om" attrs=mail by dn.children="ou=manager,dc=ldap,dc=example,dc=com" write by self read by anonymous none
        //so that only one self may change it
        $values["email"] = $email;
        if(! ldap_modify($this->ldap_db,$LDAPUser->getDn(),$values))
        {
            throw new \Exception("Enail can not be modified");
        }
    }
}
