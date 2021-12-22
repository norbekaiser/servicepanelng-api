<?php

namespace norb_api\Controllers;

use norb_api\Exceptions\HTTP401_Unauthorized;
use norb_api\Gateways\SessionGateway;

require_once __DIR__ . '/../../lib/Gateways/SessionGateway.php';
require_once __DIR__ . '/../../lib/Exceptions/HTTP401_Unauthorized.php';
require_once __DIR__ . '/AbstractHeaderController.php';

abstract class AbstractSessionController extends AbstractHeaderController
{
    protected $Session = null;

    public function __construct(string $requestMethod,string $Authorization)
    {
        parent::__construct($requestMethod,$Authorization);
        $this->require_valid_session();
        $this->ParseSession();
    }

    protected function ParseAuthorization()
    {
        try
        {
            $SessionGateway = new SessionGateway();
            $this->Session = $SessionGateway->find_session($this->Authorization);
        }
        catch (\Exception $e)
        {
            $this->Session= null;
        }
    }


    protected function require_valid_session() :void
    {
        if(is_null($this->Session)){
            throw new HTTP401_Unauthorized("Valid Session Required");
        }
    }


    protected abstract function ParseSession();
}