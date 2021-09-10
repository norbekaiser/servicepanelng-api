<?php

namespace norb_api\Exceptions;

abstract class HTTP_Exception extends \Exception
{
    protected $status_code_header;
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public function getStatusCodeHeader() : string
    {
        return $this->status_code_header;
    }

    protected function setStatusCodeHeader(string $status_code_header): void
    {
        $this->status_code_header = $status_code_header;
    }
}