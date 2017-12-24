<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthCheckMiddleware
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
        if (! Auth::check()) {
            Session::flash('alert-type', 'alert-danger');
            Session::flash('alert-message', 'Debe iniciar sesión para acceder a esta zona');

            return redirect()->route('index.index');
        }

        return $next($request);
    }
}
