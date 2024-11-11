<?php
namespace App\Controllers;

use Vendor\Controller\Controller;

class UserController extends Controller {

    /**
     * @route(GET, '/user/')
     */
    public function index() {
        return $this->render(['name' => "La Porta Di Roma", 'myName' => "Mohammad Albay"], "index.php");
    }

    /**
     * @route(POST, '/user/add')
     */
    public function create() {
        return "THis route is working..";
    }

    /**
     * @route(GET, '/info')
     */
    public function info() {
        return "INFO page here";
    }
}