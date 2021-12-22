<?php

namespace norb_api\Config;

trait Enabled
{
    private $Enabled;

    public function getEnabled(): bool
    {
        return $this->Enabled;
    }

}
