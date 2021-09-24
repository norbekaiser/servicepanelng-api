<?php

namespace norb_api\Config;

trait Version
{
    private $Version;

    public function getVersion(): int
    {
        return $this->Version;
    }
}
