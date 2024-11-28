<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SimplePasswordAuth
{
    /**
     * The valid credentials for accessing the route.
     *
     * @var array
     */
    protected $validCredentials = [
        'username' => 'admin',  // The expected username
        'password' => 'admin', // The expected password
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->getUser() != env('BASIC_AUTH_USERNAME') || $request->getPassword() != env('BASIC_AUTH_PASSWORD')) {
            // Send the WWW-Authenticate header to trigger the browser's Basic Auth prompt
            return response('Unauthorized', Response::HTTP_UNAUTHORIZED)
                ->header('WWW-Authenticate', 'Basic realm="Protected"');
        }

        return $next($request);
        // // Check if the 'Authorization' header exists
        // if ($request->headers->has('Authorization')) {
        //     // Extract the 'Authorization' header value
        //     $authHeader = $request->header('Authorization');

        //     // Check if it's a basic auth header
        //     if (strpos($authHeader, 'Basic ') === 0) {
        //         // Decode the base64-encoded credentials
        //         $credentials = base64_decode(substr($authHeader, 6));
        //         list($username, $password) = explode(':', $credentials);

        //         // Compare the extracted credentials with the valid ones
        //         if ($username === $this->validCredentials['username'] && $password === $this->validCredentials['password']) {
        //             return $next($request); // Allow the request to proceed if valid
        //         }
        //     }
        // }

        // // If credentials are missing or invalid, return a 401 Unauthorized response
        // return response('Unauthorized', 401);
    }
}