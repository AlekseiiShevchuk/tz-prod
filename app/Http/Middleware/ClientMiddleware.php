<?php
/**
 * Developer: Andrew Karpich
 * Date: 16.02.2017 15:20
 */

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ClientMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            return $next($request);
        }else{
            Session::set('from_url', $request->getUri());
            return redirect('/login');
        }
    }
}