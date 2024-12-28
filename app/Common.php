<?php

namespace App;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Common
{
    /**
     * Create a new class instance.
     */
    public static function isGuest() {
        return Auth::guest() || Auth::guard(null)->guest();
    }
    public static function respons(Request $request, $routeResponse, $jsonResponse)  {
        if($request->wantsJson() || $request->ajax() || $request->expectsJson())
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


    /**
     * Recursively delete a directory and all its contents
     *
     * @param string $dir
     * @return bool
     */
    public static function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public static function moveFileTo($path, $file)
    {
        try {
            $extension = $file->getClientOriginalExtension();
            $shortFileName = Str::random(40) . '.' . $extension;

            $file->move(public_path().$path, $shortFileName);

            return $path . '/' . $shortFileName;
        } catch (\Exception $e) {
            return null;
        }
    }

}
