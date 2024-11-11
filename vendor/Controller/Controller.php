<?php
namespace Vendor\Controller;

use Config\Config;

abstract class Controller {
    public function __construct() {
        
    }

    protected final function render($data, $view) {
        extract($data);

        // Include the view file
        include(Config::instance()->ViewsPath. $view);
    }
} 