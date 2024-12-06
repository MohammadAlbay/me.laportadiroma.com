<?php

namespace App;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Common
{
    /**
     * Create a new class instance.
     */
    public static function isGuest() {
        return Auth::guest() || Auth::guard(null)->guest();
    }
    public static function respons(Request $request, $routeResponse, $jsonResponse)  {
        if($request->wantsJson() || $request->ajax())
            return response()->json(["State" => 0, "Code" => 0, "Message" => $jsonResponse]);
        else
            return $routeResponse;
    }

    public static function responseError(Request $request, RedirectResponse|string|null $route, $errors) {
        if($request->wantsJson() || $request->ajax())
            return response()->json(["State" => 1, "Code" => 1, "Message" => $errors]);
        else {
            if($route instanceof RedirectResponse)
                return $route->with("error", $errors);
            else if(is_string($route))
                redirect($route)->with("error", $errors);
            else
                redirect()->back()->with("error", $errors);
        }
    }
}
