<?php
/**
 * Developer: Andrew Karpich
 * Date: 01.02.2017 15:18
 */

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use League\Flysystem\Exception;
use ReflectionFunction;

class AjaxMiddleware {

    public function handle($request, Closure $next, $guard = null){

        /**
         * @var Request $request
         * @var Response $response
         * @var ResponseFactory $responseFactory
         */

        $response = $next($request);

        $responseFactory = response();

        $blueStyle = false;
        $title = 'Turbulence Zéro';

        if(isset($responseFactory->otherViewData)){
            if(isset($responseFactory->otherViewData['blue_style']) && $responseFactory->otherViewData['blue_style']) $blueStyle = true;
            if(isset($responseFactory->otherViewData['title'])) $title = $responseFactory->otherViewData['title'];
        }

        $responseData = [
            'title' => $title,
            'content' => $response->getContent(),
            'blue_style' => $blueStyle,
            'location' => $response->headers->get('location')
        ];

        if( !$request->ajax() ){

            $response->setContent(view('site.layouts.main', $responseData));

        }else{

            $response = $responseFactory->json($responseData);

        }

        return $response;
    }

}