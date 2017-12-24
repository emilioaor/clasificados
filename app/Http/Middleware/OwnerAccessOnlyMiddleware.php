<?php

namespace App\Http\Middleware;

use App\Publication;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OwnerAccessOnlyMiddleware
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
        $publication = Publication::where('public_id', $request->publication)->first();

        if ($publication && $publication->user_id !== Auth::user()->id) {
            Session::flash('alert-type', 'alert-danger');
            Session::flash('alert-message', 'No posee permisos para acceder a esta zona');

            return redirect()->route('publication.index');
        }

        return $next($request);
    }
}
