<?php

namespace App\Http\Middleware;

use Closure;

class loginsession
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
    	if (!$request->session()->has('customer.id')) {
    		// user value cannot be found in session
    		return redirect('/login')->with("return",$request->fullUrl());
    	}

        return $next($request);
    }
}
