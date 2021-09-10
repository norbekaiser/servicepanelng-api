<?php

namespace norb_api\Config;

trait AdminDN
{
    private $AdminDN;

    public function getAdminDN(): string
    {
        return $this->AdminDN;
    }
}
