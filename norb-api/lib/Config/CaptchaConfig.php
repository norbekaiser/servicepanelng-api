<?php

namespace norb_api\Config;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Traits/Enabled.php';
require_once __DIR__ . '/Traits/Type.php';

class CaptchaConfig extends Config
{
    use Enabled,Type;

    public function __construct()
    {
        $this->Enabled = false;
        $this->Type = '';
        parent::__construct(__DIR__ . '/../../config/captcha.ini');
    }

    protected function parse_file($ini_data)
    {
        if(isset($ini_data['Enabled']) && is_bool($ini_data['Enabled']) && isset($ini_data['Type']) && is_string($ini_data['Type']))
        {
            $this->Enabled = (bool) $ini_data['Enabled'];
            $this->Type = (string) $ini_data['Type'];
        }
    }
}