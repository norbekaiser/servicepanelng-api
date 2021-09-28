<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../../norb-api/lib/Models/LDAPUser.php';

use norb_api\Models\LDAPUser;

class LDAPObjectClass extends LDAPUser
{
    /** @var boolean */
    private $posixAccount=false;
    /** @var boolean */
    private $inetOrgPerson=false;

    public function isPosixAccount(): bool
    {
        return $this->posixAccount;
    }

    public function setPosixAccount(bool $posixAccount = true): void
    {
        $this->posixAccount = $posixAccount;
    }

    public function isInetOrgPerson(): bool
    {
        return $this->inetOrgPerson;
    }

    public function setInetOrgPerson(bool $inetOrgPerson = true): void
    {
        $this->inetOrgPerson = $inetOrgPerson;
    }

    public function jsonSerialize()
    {
        return
        array_filter(
            array_merge(parent::jsonSerialize(),
            array(
                "posixAccount" => $this->posixAccount,
                "inetOrgPerson" => $this->inetOrgPerson
        )));
    }
}