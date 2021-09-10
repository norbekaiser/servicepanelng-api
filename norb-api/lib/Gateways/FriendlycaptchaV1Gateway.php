<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/Traits/FriendlycaptchaGateway.php';

class FriendlycaptchaV1Gateway
{
    use FriendlycaptchaGateway;

    public function __construct()
    {
        $this->init_recaptcha();
    }

    /**
     * Verifies a Friendlycaptcha Response
     */
    public function verify(string $solution) : bool
    {
        $data = array(
            'solution' => $solution,
            'secret' => urlencode($this->SecretKey),
            'sitekey' => urlencode($this->SiteKey)
        );
        \curl_setopt($this->curl,CURLOPT_POSTFIELDS,http_build_query($data));
        $response = \curl_exec($this->curl);
        if(\curl_getinfo($this->curl,CURLINFO_HTTP_CODE)!=200)
        {
            return false;//TODO make it an exception maybe
        }
        $responseData = json_decode($response,true);
        if($responseData["success"])
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}