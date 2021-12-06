<?php

namespace norb_api\Config;

trait AdminPassword
{
    private $AdminPassword;

    public function getAdminPassword(): string
    {
        return $this->AdminPassword;
    }
}
