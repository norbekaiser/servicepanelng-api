<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../Gateways/Traits/LDAPGateway.php';
require_once __DIR__ . '/../Models/LDAPUser.php';
require_once __DIR__ . '/../Models/LDAPObjectClass.php';

use norb_api\Gateways\LDAPGateway;
use norb_api\Models\LDAPUser;

class LDAPObjectClassGateway
{
    use LDAPGateway;

    public function __construct()
    {
        $this->init_ldap();
    }

    public function find(LDAPUser $LDAPUser): LDAPObjectClass
    {
        $ldapObjectClass = new LDAPObjectClass();
        $search = ldap_read($this->ldap_db,$LDAPUser->getDN(),"objectClass=*",['dn','objectClass']);
        $data = ldap_get_entries($this->ldap_db,$search);

        if(!isset($data[0]))
        {
            throw new \Exception("User can not be determined to have any objectclass");
        }

        $ldapObjectClass->setDN($data[0]['dn']);
        //MAY
        if(isset($data[0]['objectclass']) && in_array('posixAccount',$data[0]['objectclass'],true)){
            $ldapObjectClass->setPosixAccount();
        }
        if(isset($data[0]['objectclass']) && in_array('inetOrgPerson',$data[0]['objectclass'],true)){
            $ldapObjectClass->setInetOrgPerson();
        }
        return $ldapObjectClass;
    }
}
