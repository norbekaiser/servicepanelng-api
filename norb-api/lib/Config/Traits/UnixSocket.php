<?php

namespace norb_api\Config;

trait UnixSocket
{
    private $UnixSocket;

    public function getUnixSocket(): string
    {
        return $this->UnixSocket;
    }
}
