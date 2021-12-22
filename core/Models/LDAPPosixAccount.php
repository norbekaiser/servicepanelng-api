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

require_once __DIR__ . '/../Models/LDAPUser.php';

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

    public function jsonSerialize()
    {
        return
            array_filter(
                array_merge(parent::jsonSerialize(),
                    array(
                        "cn" => $this->cn,
                        "uid" => $this->uid,
                        "uidNumber" => $this->uidNumber,
                        "gidNumber" => $this->gidNUmber,
                        "homeDirectory" => $this->homeDirectory,
                        "loginShell" => $this->loginShell,
                    )));
    }
}
