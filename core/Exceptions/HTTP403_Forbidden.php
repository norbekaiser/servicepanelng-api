<?php

namespace norb_api\Exceptions;

require_once __DIR__ .'/HTTP_Exception.php';

class HTTP403_Forbidden extends HTTP_Exception
{
    public function __construct(string $message = "")
    {
        $this->setStatusCodeHeader("HTTP/1.1 403 Forbidden");
        parent::__construct($message);
    }

}