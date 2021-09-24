<?php

namespace norb_api\Config;

abstract class Config
{
    protected $values;
    public function __construct($filename,$process_sections=false)
    {
        $values = @parse_ini_file($filename,$process_sections,INI_SCANNER_TYPED);
        if($values)
        {
            $this->parse_file($values);
        }
    }

    protected abstract function parse_file($ini_data);
}