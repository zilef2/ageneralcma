<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IPWhitelistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response{
//        $allowedIPs = ['127.0.0.1',
////            '192.168.1.101',
//        ];
//
//        if (!in_array($request->ip(), $allowedIPs)) {
//            return response()->json(['error' => 'Acceso no autorizado.'], 403);
//        }

        return $next($request);
    }
}
