<?php

namespace norb_api\Config;

trait SecretKey
{
    private $SecretKey;

    public function getSecretKey(): String
    {
        return $this->SecretKey;
    }
}
