<?php

namespace norb_api\Config;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Traits/Enabled.php';
require_once __DIR__ . '/Traits/Version.php';
require_once __DIR__ . '/Traits/SecretKey.php';

class RecaptchaConfig extends Config
{
    use Enabled, Version, SecretKey;

    public function __construct()
    {
        $this->Enabled = false;
        $this->Version = 2;
        $this->SecretKey ="";
        parent::__construct(__DIR__ . '/../../config/recaptcha.ini');
    }

    protected function parse_file($ini_data)
    {
        if(is_bool($ini_data['Enabled']))
        {
            $this->Enabled = (bool) $ini_data['Enabled'];
        }

        if(is_numeric($ini_data['Version']))
        {
            $this->Version = (int) $ini_data['Version'];
        }
        if(is_string($ini_data['SecretKey']))
        {
            $this->SecretKey = (string) $ini_data['SecretKey'];
        }
    }
}