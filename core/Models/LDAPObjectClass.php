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
