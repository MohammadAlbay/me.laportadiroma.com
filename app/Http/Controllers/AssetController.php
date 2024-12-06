<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    //

    public function dispayAssetNewTypeView() {
        return view("dashboard.laportadiroma.asset.add-type");
    }
}
