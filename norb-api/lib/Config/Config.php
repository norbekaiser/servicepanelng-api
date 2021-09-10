<?php

namespace norb_api\Config;

abstract class Config
{
    protected $values;
    public function __construct($filename)
    {
        $values = @parse_ini_file($filename,false,INI_SCANNER_TYPED);
        if($values)
        {
            $this->parse_file($values);
        }
    }

    protected abstract function parse_file($ini_data);

    protected function assign_value($source,$key,& $target)//Todo tpye hints or so might be nice
    {
        if(array_key_exists($key,$source))
        {
           $target = $source[$key];
        }
    }
}