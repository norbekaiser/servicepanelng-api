<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../Gateways/Traits/LDAPGateway.php';
require_once __DIR__ . '/../Models/LDAPUser.php';
require_once __DIR__ . '/../Models/LDAPInetOrgPerson.php';

use norb_api\Gateways\LDAPGateway;
use norb_api\Models\LDAPUser;

class LdapInetOrgPersonGateway
{
    use LDAPGateway;

    public function __construct()
    {
        $this->init_ldap();
    }

    public function find(LDAPUser $LDAPUser): LDAPInetOrgPerson
    {
        $ldapInetOrgPerson = new LDAPInetOrgPerson();
        $search = ldap_read($this->ldap_db,$LDAPUser->getDN(),"objectClass=InetOrgPerson",["dn","cn","sn","givenName","mail"]);
        $data = ldap_get_entries($this->ldap_db,$search);

        if(!isset($data[0]))
        {
            throw new \Exception("User can not be found as InetOrgPerson");
        }

        $ldapInetOrgPerson->setDN($data[0]['dn']);
        $ldapInetOrgPerson->setCN($data[0]['cn'][0]);
        $ldapInetOrgPerson->setSN($data[0]['sn'][0]);
        //MAY
        if(isset($data[0]['mail']['count'])&& $data[0]['mail']['count']==1){
            $ldapInetOrgPerson->setMail($data[0]['mail'][0]);
        }

        if(isset($data[0]['givenname']['count'])&& $data[0]['givenname']['count']>=1){
            $givenNames=array();
            for($i=0; $i < $data[0]['givenname']['count']; $i++) {
                $givenNames[$i] = $data[0]['givenname'][$i];
            }
            $ldapInetOrgPerson->setGivenName($givenNames);
        }

        return $ldapInetOrgPerson;
    }
}
