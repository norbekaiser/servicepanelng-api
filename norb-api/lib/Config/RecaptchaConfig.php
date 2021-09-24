<?php

namespace norb_api\Config;

require_once __DIR__ . '/Traits/Version.php';
require_once __DIR__ . '/Traits/SecretKey.php';
require_once __DIR__ . '/Traits/Enabled.php';
require_once __DIR__ . '/Config.php';

class RecaptchaConfig extends Config
{
    use Version, SecretKey;

    public function __construct()
    {
        $this->Version = 2;
        $this->SecretKey ="";
        parent::__construct(__DIR__ . '/../../config/captcha.ini',true);
    }

    protected function parse_file($ini_data)
    {
        if(isset($ini_data['recaptcha']['Version']) and is_numeric($ini_data['recaptcha']['Version']))
        {
            $this->Version = (int) $ini_data['recaptcha']['Version'];
        }
        if(isset($ini_data['recaptcha']['SecretKey']) and is_string($ini_data['recaptcha']['SecretKey']))
        {
            $this->SecretKey = (string) $ini_data['recaptcha']['SecretKey'];
        }
    }
}