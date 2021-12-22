<?php

namespace norb_api\Exceptions;

require_once __DIR__ .'/HTTP_Exception.php';

class HTTP400_BadRequest extends HTTP_Exception
{
    public function __construct(string $message = "")
    {
        $this->setStatusCodeHeader("HTTP/1.1 400 Bad Request");
        parent::__construct($message);
    }

}