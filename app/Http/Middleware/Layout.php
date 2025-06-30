<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class Layout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (Auth::user()->roles[0]->layout == "public" && Auth::user()->roles[0]->layout == $layout) {
        if (Auth::user()->roles[0]->layout == "public") {
            if (explode("/",request()->route()->getPrefix())[0] == "admin") {
                return redirect()->route(RouteServiceProvider::PUBLIC);
            }
        }
        else{
            // if (explode("/",request()->route()->getPrefix())[0] == "public") {
            //     return redirect()->route(RouteServiceProvider::ADMIN);
            // }
        }

        return $next($request);
    }
}
