<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/Traits/RecaptchaGateway.php';

class RecaptchaV3Gateway
{
    use RecaptchaGateway;

    public function __construct()
    {
        $this->init_recaptcha();
        if($this->Version!=3)
        {
            throw new \Exception("RecaptchaV3 Gateway Initiated but different Version required");
        }
    }

    /**
     * Verifies a Google RecaptchaV3 Response
     */
    public function verify(string $g_recaptcha_response) : float
    {
        $data = array(
            'secret' => urlencode($this->SecretKey),
            'response' => urlencode($g_recaptcha_response)
        );
        \curl_setopt($this->curl,CURLOPT_POSTFIELDS,http_build_query($data));
        $response = \curl_exec($this->curl);
        if(\curl_getinfo($this->curl,CURLINFO_HTTP_CODE)!=200)
        {
            return 0.0;//TODO make it an exception maybe
        }
        $responseData = json_decode($response,true);
        if($responseData["success"] && isset($responseData["score"]))
        {
            return ((float) $responseData["score"]);
        }
        else
        {
            return 0.0;//TODO make it an exception maybe
        }
    }
}