<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../Models/LDAPPosixAccount.php';
require_once __DIR__ . '/../../norb-api/lib/Models/LDAPUser.php';
require_once __DIR__ . '/../../norb-api/lib/Gateways/Traits/LDAPGateway.php';

use norb_api\Models\LDAPUser;
use norb_api\Gateways\LDAPGateway;

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
        $search = ldap_read($this->ldap_db,$LDAPUser->getDN(),"objectClass=PosixAccount");
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
