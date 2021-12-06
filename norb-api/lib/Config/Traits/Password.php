<?php

namespace norb_api\Config;

trait Password
{
    private $Password;

    public function getPassword(): string
    {
        return $this->Password;
    }
}
