<?php

namespace norb_api\Config;

trait Hostname
{
    private $Hostname;

    public function getHostname(): string
    {
        return $this->Hostname;
    }
}
