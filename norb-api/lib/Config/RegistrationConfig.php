<?php

namespace norb_api\Config;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Traits/Enabled.php';
require_once __DIR__ . '/Traits/MinimumLength.php';
require_once __DIR__ . '/Traits/RequiresLetters.php';
require_once __DIR__ . '/Traits/RequiresDigits.php';

class RegistrationConfig extends Config
{
    use Enabled, MinimumLength, RequiresLetters, RequiresDigits;

    public function __construct()
    {
        $this->Enabled = false;
        $this->MinimumLength = 8;
        $this->RequiresLetters = true;
        $this->RequiresDigits = true;
        parent::__construct(__DIR__ . '/../../config/registration.ini');
    }

    protected function parse_file($ini_data)
    {
        if(is_bool($ini_data['Enabled']))
        {
            $this->Enabled = (bool) $ini_data['Enabled'];
        }
        if(is_numeric($ini_data['MinLength']))
        {
            $this->MinimumLength = abs((int) $ini_data['MinLength']);
        }
        if(is_bool($ini_data['Letters']))
        {
            $this->RequiresLetters = (bool) $ini_data['Letters'];
        }
        if(is_bool($ini_data['Digits']))
        {
            $this->RequiresDigits = (bool) $ini_data['Digits'];
        }
    }
}