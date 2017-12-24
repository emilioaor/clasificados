<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminCheckMiddleware
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
        if (Auth::user()->level !== User::LEVEL_ADMIN) {
            Session::flash('alert-type', 'alert-danger');
            Session::flash('alert-message', 'No posee permisos para acceder a esta zona');

            return redirect()->route('publication.index');
        }

        return $next($request);
    }
}
