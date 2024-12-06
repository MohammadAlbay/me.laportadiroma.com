<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class HolasaciController extends Controller {
    public function index(){
        return view("dashboard.holasaci.index");
    }
    
}