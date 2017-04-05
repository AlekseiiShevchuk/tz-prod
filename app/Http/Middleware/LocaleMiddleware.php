<?php
/**
 * Developer: Andrew Karpich
 * Date: 07.02.2017 14:30
 */

namespace App\Http\Middleware;


use Closure;
use App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware {

    public function handle($request, Closure $next, $guard = null){

        $language = Session::get('locale', App::getLocale());
        App::setLocale($language);

        return $next($request);

    }

}