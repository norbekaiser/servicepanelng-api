<?php

namespace norb_api\Models;

require_once __DIR__ .'/User.php';

class LDAPUser extends User
{

    private $DN;

    public function getDN(): string
    {
        return $this->DN;
    }

    public function setDN(string $DN): void
    {
        $this->DN = $DN;
    }
}