<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/../../Config/FriendlycaptchaConfig.php';
require_once __DIR__ . '/../../Connectors/CurlConnector.php';

use norb_api\Config\FriendlycaptchaConfig;
use norb_api\Connectors\CurlConnector;
use function curl_setopt;

trait FriendlycaptchaGateway
{
    private $SecretKey;
    private $SiteKey;
    private $CurlConnector = null;
    private $curl = null;

    private function init_recaptcha()
    {
        $FriendlycaptchaConfig = new FriendlycaptchaConfig();
        $this->CurlConnector = new CurlConnector();
        $this->curl = $this->CurlConnector->getCurl();
        curl_setopt($this->curl,CURLOPT_URL,'https://friendlycaptcha.com/api/v1/siteverify');
        curl_setopt($this->curl, CURLOPT_POST, true);
        $this->SecretKey = $FriendlycaptchaConfig->getSecretKey();
        $this->SiteKey = $FriendlycaptchaConfig->getSiteKey();
    }
}