<?php

namespace norb_api\Config;

trait Port
{
    /** @var int */
    private $Port;

    public function getPort(): int
    {
        return $this->Port;
    }
}
