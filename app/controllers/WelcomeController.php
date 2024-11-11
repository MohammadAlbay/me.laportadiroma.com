<?php
namespace App\Controllers;

use Vendor\Controller\Controller;

class WelcomeController extends Controller {

    /**
     * @route(GET, '/')
     * @route(POST, '/submit')
     */
    public function index() {
        return $this->render(['name' => "La Porta Di Roma", 'myName' => "Mohammad Albay"], "index.php");
    }

    /**
     * @route(GET, '/info')
     */
    public function info() {
        return "INFO page here";
    }
}