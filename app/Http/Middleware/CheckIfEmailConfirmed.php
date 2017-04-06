<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfEmailConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            request()->route()->getPath() == 'contacts'
            || request()->route()->getPath() == 'membre'
            || request()->route()->getPath() == 'logout'
            || request()->route()->getName() == 'emailVerify'
        ) {
            return $next($request);
        }
        if (
            auth()->check() &&
            auth()->user()->role == 'client' &&
            (auth()->user()->is_email_valid == 0 || auth()->user()->country_id == 1 || empty(auth()->user()->name))
        ) {

            return redirect('/membre');
        }
        return $next($request);
    }
}
