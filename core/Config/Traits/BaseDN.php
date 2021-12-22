<?php

namespace norb_api\Config;

trait BaseDN
{
    private $BaseDN;

    public function getBaseDN(): string
    {
        return $this->BaseDN;
    }
}
