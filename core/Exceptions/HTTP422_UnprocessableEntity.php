<?php

namespace norb_api\Exceptions;

require_once __DIR__ .'/HTTP_Exception.php';

class HTTP422_UnprocessableEntity extends HTTP_Exception
{
    public function __construct(string $message = "")
    {
        $this->setStatusCodeHeader("HTTP/1.1 422 Unprocessable Entity");
        parent::__construct($message);
    }
}