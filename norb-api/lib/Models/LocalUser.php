<?php

namespace norb_api\Models;

require_once __DIR__ .'/User.php';

class LocalUser extends User
{
    private $username;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            array(
                "username" => $this->username
            )
        );
    }

}