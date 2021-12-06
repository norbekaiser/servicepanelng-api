<?php

namespace norb_api\Exceptions;

abstract class Database_Exception extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}