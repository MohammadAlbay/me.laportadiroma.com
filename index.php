<?php
include_once __DIR__.'/config/Config.php';
use Config\Config;
Config::setPath(__DIR__);
require_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__.'/app/routes/web.php';
//echo "La Porta Di Roma AAAA";

