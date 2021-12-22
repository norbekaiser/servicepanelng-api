<?php
//    Copyright (c) 2021 Norbert Rühl
//    
//    This software is provided 'as-is', without any express or implied warranty. In no event will the authors be held liable for any damages arising from the use of this software.
//    
//    Permission is granted to anyone to use this software for any purpose, including commercial applications, and to alter it and redistribute it freely, subject to the following restrictions:
//    
//        1. The origin of this software must not be misrepresented; you must not claim that you wrote the original software. If you use this software in a product, an acknowledgment in the product documentation would be appreciated but is not required.
//    
//        2. Altered source versions must be plainly marked as such, and must not be misrepresented as being the original software.
//    
//        3. This notice may not be removed or altered from any source distribution.
?>
<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/Traits/RecaptchaGateway.php';

class RecaptchaV2Gateway
{
    use RecaptchaGateway;

    public function __construct()
    {
        $this->init_recaptcha();
        if($this->Version!=2)
        {
            throw new \Exception("RecaptchaV2 Gateway Initiated but different Version required");
        }
    }

    /**
     * Verifies a Google RecaptchaV2 Response
     */
    public function verify(string $g_recaptcha_response) : bool
    {
        $data = array(
            'secret' => urlencode($this->SecretKey),
            'response' => urlencode($g_recaptcha_response)
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
