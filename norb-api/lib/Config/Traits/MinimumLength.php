<?php

namespace norb_api\Config;

trait MinimumLength
{
    private $MinimumLength;

    public function getMinimumLength(): int
    {
        return $this->MinimumLength;
    }
}
