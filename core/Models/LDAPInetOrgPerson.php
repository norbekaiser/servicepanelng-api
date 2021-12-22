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

class LDAPInetOrgPerson extends LDAPUser
{
    //Required
    /** @var string */
    private $cn;
    /** @var string */
    private $sn;
    //May
    /** @var string */
    private $mail;
    /** @var array */
    private $givenName;

    public function getCN(): string
    {
        return $this->cn;
    }

    public function setCN(string $cn): void
    {
        $this->cn = $cn;
    }

    public function getSN(): string
    {
        return $this->sn;
    }

    public function setSN(string $sn): void
    {
        $this->sn = $sn;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function getGivenName(): ?array
    {
        return $this->givenName;
    }

    public function setGivenName(array $givenName): void
    {
        $this->givenName = $givenName;
    }

    public function jsonSerialize()
    {
        return
        array_filter(
            array_merge(parent::jsonSerialize(),
            array(
                "cn" => $this->cn,
                "sn" => $this->sn,
                "mail" => $this->mail,
                "givenName" => $this->givenName
        )));
    }
}
