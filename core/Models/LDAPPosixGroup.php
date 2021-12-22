<?php

namespace servicepanel_ng;

class LDAPPosixGroup implements \JsonSerializable
{


    private $DN;
    //Required
    /** @var string */
    private $cn;
    /** @var int */
    private $gidNumber;
//    private $memberUid;

    public function getCn() :string
    {
        return $this->cn;
    }

    public function setCn(string $cn): void
    {
        $this->cn = $cn;
    }

    public function getGidNumber(): int
    {
        return $this->gidNumber;
    }

    public function setGidNumber(int $gidNumber): void
    {
        $this->gidNumber = $gidNumber;
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
            'gidNumber' => $this->getGidNumber()
        );
    }


}