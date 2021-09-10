<?php

namespace norb_api\Exceptions;

require_once __DIR__ .'/HTTP_Exception.php';

class HTTP500_InternalServerError extends HTTP_Exception
{
    public function __construct(string $message = "")
    {
        $this->setStatusCodeHeader("HTTP/1.1 500 Internal Server Error");
        parent::__construct($message);
    }
}