<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SecureRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isGuest = Auth::guest() || Auth::guard(null)->guest();

        //Log::info("user is $isGuest");
        if($isGuest) {
            if($request->ajax() || $request->wantsJson()) {
                return response()->json(["message" => "User Unauthorized. access denied"]);
            } else {
                return redirect("/");
            }
        }
        return $next($request);
    }
}
