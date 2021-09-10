<?php

namespace norb_api\Config;

require_once __DIR__ . '/Traits/SecretKey.php';
require_once __DIR__ . '/Traits/SiteKey.php';
require_once __DIR__ . '/Config.php';

class FriendlycaptchaConfig extends Config
{
    use SiteKey, SecretKey;

    public function __construct()
    {
        $this->SecretKey ="";
        $this->SiteKey ="";
        parent::__construct(__DIR__ . '/../../config/captcha.ini',true);
    }

    protected function parse_file($ini_data)
    {
        if(isset($ini_data['friendlycaptcha']['SecretKey']) and is_string($ini_data['friendlycaptcha']['SecretKey']))
        {
            $this->SecretKey = (string) $ini_data['friendlycaptcha']['SecretKey'];
        }

        if(isset($ini_data['friendlycaptcha']['SiteKey']) and is_string($ini_data['friendlycaptcha']['SiteKey']))
        {
            $this->SiteKey = (string) $ini_data['friendlycaptcha']['SiteKey'];
        }
    }
}