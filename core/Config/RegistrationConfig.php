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

namespace norb_api\Config;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Traits/Enabled.php';
require_once __DIR__ . '/Traits/MinimumLength.php';
require_once __DIR__ . '/Traits/RequiresDigits.php';
require_once __DIR__ . '/Traits/RequiresLetters.php';

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
        if(isset($ini_data['Enabled']) and is_bool($ini_data['Enabled']))
        {
            $this->Enabled = (bool) $ini_data['Enabled'];
        }
        if(isset($ini_data['MinLength']) and is_numeric($ini_data['MinLength']))
        {
            $this->MinimumLength = abs((int) $ini_data['MinLength']);
        }
        if(isset($ini_data['Letters']) and is_bool($ini_data['Letters']))
        {
            $this->RequiresLetters = (bool) $ini_data['Letters'];
        }
        if(isset($ini_data['Digits']) and is_bool($ini_data['Digits']))
        {
            $this->RequiresDigits = (bool) $ini_data['Digits'];
        }
    }
}
