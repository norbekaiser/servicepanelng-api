<?php

namespace norb_api\Controllers;

require_once __DIR__ .'/AbstractController.php';

abstract class AbstractHeaderController extends AbstractController
{
    protected $Authorization;

    public function __construct(string $requestMethod,string $Authorization)
    {
        parent::__construct($requestMethod);
        $this->Authorization = $Authorization;
        $this->ParseAuthorization();
    }

    protected abstract function ParseAuthorization();
}