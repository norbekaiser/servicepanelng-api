<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../Models/LDAPInetOrgPerson.php';
require_once __DIR__ . '/../../norb-api/lib/Models/LDAPUser.php';
require_once __DIR__ . '/../../norb-api/lib/Gateways/Traits/LDAPGateway.php';

use norb_api\Models\LDAPUser;
use norb_api\Gateways\LDAPGateway;

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
        $search = ldap_read($this->ldap_db,$LDAPUser->getDN(),"objectClass=InetOrgPerson");
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