<?php

namespace norb_api\Exceptions;

require_once __DIR__ . '/Database_Exception.php';

class NoConnectivitySQL extends Database_Exception
{
    public function __construct()
    {
        parent::__construct("Could not Connect to SQL Database");
    }
}