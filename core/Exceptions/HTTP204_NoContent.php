<?php

namespace norb_api\Exceptions;

require_once __DIR__ .'/HTTP_Exception.php';

class HTTP204_NoContent extends HTTP_Exception
{
    public function __construct(string $message = "")
    {
        $this->setStatusCodeHeader("HTTP/1.1 204 No Content");
        parent::__construct($message);
    }
}