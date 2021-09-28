<?php

namespace servicepanel_ng;

require_once __DIR__ . '/../../norb-api/lib/Models/LDAPUser.php';

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