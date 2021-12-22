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

class LDAPGroupOfNames implements \JsonSerializable
{


    private $DN;
    //Required
    /** @var string */
    private $cn;
    /** @var int */

    public function getCn() :string
    {
        return $this->cn;
    }

    public function setCn(string $cn): void
    {
        $this->cn = $cn;
    }

    public function getDN(): string
    {
        return $this->DN;
    }

    public function setDN(string $DN): void
    {
        $this->DN = $DN;
    }

    public function jsonSerialize()
    {
        return array(
            'dn' => $this->DN,
            'cn' => $this->cn,
        );
    }


}
