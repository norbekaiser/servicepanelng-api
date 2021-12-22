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