<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class LaPortaDiRomaDashboardController extends Controller {
    public function index(){
        return view("dashboard.laportadiroma.index");
    }
    
    public function displaySettings() {
        return view("dashboard.laportadiroma.settings");
    }

    public function createNewAssetType(Request $request) {
        $validator = Validator::make($request->all(), [], []);
    }
}