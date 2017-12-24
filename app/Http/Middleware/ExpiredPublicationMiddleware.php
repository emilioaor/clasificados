<?php

namespace App\Http\Middleware;

use App\Publication;
use Closure;
use Illuminate\Support\Facades\Session;

class ExpiredPublicationMiddleware
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

        if ($publication && $publication->status === Publication::STATUS_EXPIRED) {
            Session::flash('alert-type', 'alert-danger');
            Session::flash('alert-message', 'La publicación ya expiro');

            return redirect()->route('publication.index');
        }

        return $next($request);
    }
}
