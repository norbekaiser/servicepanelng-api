<?php

namespace norb_api\Exceptions;

require_once __DIR__ . '/Database_Exception.php';

class NoConnectivityRedis extends Database_Exception
{
    public function __construct()
    {
        parent::__construct("Could not Connect to Redis");
    }
}