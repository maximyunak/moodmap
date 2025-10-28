<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(data:["message" => "No rights? :("], status: 403);
        }
        $user = User::where("token", $token)->first();
        if (!$user ) {
            return response()->json(data:["message" => "No rights? :("], status: 403);
        }
        auth()->login($user);
        return $next($request);
    }
}
