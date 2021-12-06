<?php

namespace norb_api\Config;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Traits/AdminDN.php';
require_once __DIR__ . '/Traits/AdminPassword.php';
require_once __DIR__ . '/Traits/BaseDN.php';
require_once __DIR__ . '/Traits/Enabled.php';
require_once __DIR__ . '/Traits/Port.php';
require_once __DIR__ . '/Traits/URI.php';

class LDAPConfig extends Config
{
    use Enabled, URI, Port, AdminDN, AdminPassword, BaseDN;

    public function __construct()
    {
        $this->Enabled = false;
        $this->URI ="";
        $this->Port=389;
        $this->AdminDN ="";
        $this->AdminPassword ="";
        $this->BaseDN = "";
        parent::__construct(__DIR__.'/../../config/ldap.ini');
    }

    public function parse_file($ini_data)
    {
        if(isset($ini_data['enabled']) and is_bool($ini_data['enabled']))
        {
            $this->Enabled = (bool) $ini_data['enabled'];
        }
        if(isset($ini_data['uri']) and is_string($ini_data['uri']))
        {
            $this->URI = (string) $ini_data['uri'];
        }
        if(isset($ini_data['port']) and is_int($ini_data['port']))
        {
            $this->Port = abs((int) $ini_data['port']);
        }
        if(isset($ini_data['admin_dn']) and is_string($ini_data['admin_dn']))
        {
            $this->AdminDN = (string) $ini_data['admin_dn'];
        }
        if(isset($ini_data['admin_pw']) and is_string($ini_data['admin_pw']))
        {
            $this->AdminPassword = (string) $ini_data['admin_pw'];
        }
        if(isset($ini_data['base_dn']) and is_string($ini_data['base_dn']))
        {
            $this->BaseDN = (string) $ini_data['base_dn'];
        }
    }
}