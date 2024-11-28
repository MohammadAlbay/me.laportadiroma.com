<?php

namespace App\Http\Controllers;

use App\Models\BusinessBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class BranchController  extends Controller {
    public function index(){
        $user = Auth::user();
        $businessBrand = BusinessBrand::find($user->business_brand_id);
        if($businessBrand == null) {
            return redirect("/auth")->with("error","This user is not part of any business brand!");
        }
        else
            return redirect($businessBrand->base_route);
        
    }
    
}