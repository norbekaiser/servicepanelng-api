<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/../../Config/RecaptchaConfig.php';
require_once __DIR__ . '/../../Connectors/CurlConnector.php';

use norb_api\Config\RecaptchaConfig;
use norb_api\Connectors\CurlConnector;
use function curl_setopt;

trait RecaptchaGateway
{
    private $SecretKey;
    private $Version;
    private $CurlConnector = null;
    private $curl = null;

    private function init_recaptcha()
    {
        $RecaptchaConfig = new RecaptchaConfig();
        $this->CurlConnector = new CurlConnector();
        $this->curl = $this->CurlConnector->getCurl();
        curl_setopt($this->curl,CURLOPT_URL,'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($this->curl, CURLOPT_POST, true);
        $this->SecretKey = $RecaptchaConfig->getSecretKey();
        $this->Version = $RecaptchaConfig->getVersion();
    }
}