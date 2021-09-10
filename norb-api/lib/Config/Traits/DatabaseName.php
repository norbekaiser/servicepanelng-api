<?php

namespace norb_api\Config;

trait DatabaseName
{
    private $DatabaseName;

    public function getDatabaseName(): string
    {
        return $this->DatabaseName;
    }
}
