<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class StoreLastPageMiddleware
{
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
        $slugs = ['login', 'logout', 'facebook', 'google', 'abonne', 'email'];

        if (Auth::check()
            && !in_array($request->route()->getPrefix(), ['/ap', 'ap/', '/AP', 'AP/'])
            && !preg_match("/(".implode('|', $slugs).")/is", $request->route()->getUri())
        ) {
            $currentUri = Route::current()->uri();
            $prevUri    = $request->cookie('lastUri');

            if ((!isset($_SERVER['HTTP_REFERER']) || parse_url($_SERVER['HTTP_REFERER'])['host'] != $_SERVER['HTTP_HOST']) && $request->cookie('redirectedFlag') === '0') {
                try {
                    $redirect = redirect($prevUri);
                    if ($redirect && $redirect instanceof \Illuminate\Http\RedirectResponse) return redirect($prevUri)->withCookie(cookie()->forever('redirectedFlag', '1'));
                }catch (\Exception $e){}
            }

            if ($currentUri != $prevUri) {
                return $next($request)->withCookie(cookie()->forever('lastUri', $currentUri))->withCookie(cookie()->forever('redirectedFlag', '0'));
            }
        }

        return $next($request)->withCookie(cookie()->forever('redirectedFlag', '0'));

//        return $next($request);
    }
}
