<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $username = $request ->getUser();
        $password = $request ->getPassword();

        $BASIC_AUTH_USER = "admin";
        $BASIC_AUTH_PASSWORD = "admin";

       // $basic_auth_user = env('BASIC_AUTH_USER');
       // $basic_auth_password = env('BASIC_AUTH_PASSWORD');

       if ($username != $BASIC_AUTH_USER || $password != $BASIC_AUTH_PASSWORD)

       return response()->json([
        "message" => "No Autorizado",
        "estado" => 403
       ]);
        return $next($request);
    }
}
