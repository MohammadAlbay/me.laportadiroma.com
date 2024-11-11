<?php

namespace Config;

class Config {
    private static $current;

    public static function instance() {
        if(Config::$current == null)
            Config::$current = new COnfig();
        return Config::$current;
    }
    private function __construct($path = "env.json")
    {
        $envJSON = json_decode(file_get_contents(__DIR__.'/env.json'));
        $this->AppURL = $envJSON->AppURL;
        $this->AppURL = $envJSON->Routs;
        $this->ControllersPath = $envJSON->Controllers;
        $this->ViewsPath = Config::$MainDir."/$envJSON->Views";
        $this->PublicPath = Config::$MainDir."/$envJSON->Public";
    }

    public static function setPath($path) {
        Config::$MainDir = $path;
    }

    private static $MainDir;
    public $AppURL;
    public $ControllersPath;
    public $ViewsPath;
    public $PublicPath;
    public $RoutesPath;
}