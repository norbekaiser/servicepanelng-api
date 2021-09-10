<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../../norb-api/lib/Models/LDAPUser.php';

use norb_api\Models\LDAPUser;

class LDAPPosixAccount extends LDAPUser
{
    //Required
    /** @var string */
    private $cn;
    /** @var string */
    private $uid;
    /** @var int */
    private $uidNumber;
    /** @var int */
    private $gidNUmber;
    /** @var string */
    private $homeDirectory;
    //May
    /** @var string */
    private $loginShell;

    public function getCn() :string
    {
        return $this->cn;
    }

    public function setCn(string $cn): void
    {
        $this->cn = $cn;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function setUid(string $uid): void
    {
        $this->uid = $uid;
    }

    public function getUidNumber()
    {
        return $this->uidNumber;
    }

    public function setUidNumber(int $uidNumber): void
    {
        $this->uidNumber = $uidNumber;
    }

    public function getGidNUmber(): int
    {
        return $this->gidNUmber;
    }

    public function setGidNumber(int $gidNumber): void
    {
        $this->gidNUmber = $gidNumber;
    }

    public function getHomeDirectory(): string
    {
        return $this->homeDirectory;
    }

    public function setHomeDirectory(string $homeDirectory): void
    {
        $this->homeDirectory = $homeDirectory;
    }

    public function getLoginShell(): ?string
    {
        return $this->loginShell;
    }

    public function setLoginShell(string $loginShell): void
    {
        $this->loginShell = $loginShell;
    }


}