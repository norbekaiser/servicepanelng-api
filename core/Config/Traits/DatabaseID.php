<?php

namespace norb_api\Config;

trait DatabaseID
{
    private $DatabaseID;

    public function getDatabaseID(): int
    {
        return $this->DatabaseID;
    }
}
