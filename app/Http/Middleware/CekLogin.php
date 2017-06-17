<?php

namespace App\Http\Middleware;

use Closure;

class CekLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( !$request->session()->has('userlogin') )
        {
            return redirect('oldapp')->withErrors(['failed' => 'Anda belum login. Mohon login dulu.']);
        }

        return $next($request);
    }
}
