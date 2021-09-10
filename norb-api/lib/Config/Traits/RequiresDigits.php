<?php

namespace norb_api\Config;

trait RequiresDigits
{
    private $RequiresDigits;

    public function getRequiresDigits(): bool
    {
        return $this->RequiresDigits;
    }
}
