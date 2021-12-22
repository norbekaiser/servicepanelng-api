<?php

namespace norb_api\Config;

trait SiteKey
{
    private $SiteKey;

    public function getSiteKey(): String
    {
        return $this->SiteKey;
    }
}
