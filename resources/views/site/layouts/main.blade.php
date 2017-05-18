<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta property="fb:app_id"        content="{{ env('FACEBOOK_APP_ID') }}" />
    <meta property="og:type"          content="website" />
    <meta property="og:url"           content="{{ env('APP_URL') }}" />
    @if ($title ?? '')
        <meta property="og:title"     content="{{ strip_tags(trans('info.title')) }}" />
    @endif
    <meta property="og:description"   content="{{ strip_tags(trans('index.carousel-block_text-1')) }}" />
    <meta property="og:image"         content="{{ env('APP_URL') }}/src/img/logo-blue.png" />
    <meta name="description"          content="{{ strip_tags(trans('index.carousel-block_text-1')) }}" />

    <title>@if (isset($title) && $title) {{ $title }} @endif</title>

     <link rel="shortcut icon" href="/src/img/favicon.ico" type="image/x-icon">

    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/fonts.css" rel="stylesheet">
    {{--<link href="/css/app.css" rel="stylesheet">--}}
    {{--<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>--}}
    <script src="/js/app.js"></script>
</head>
<body @if (isset($blue_style) && $blue_style )class="blue-style" @endif itemscope itemtype="http://schema.org/WebSite">

<div class="hidden" itemprop="name">{{ strip_tags(trans('info.title')) }}</div>
<img class="hidden" itemprop="image" src="{{ env('APP_URL') }}/src/img/logo-blue.png" />
<div class="hidden" itemprop="description">{{ strip_tags(trans('index.carousel-block_text-1')) }}</div>
<div class="hidden" itemprop="url">{{ env('APP_URL') }}</div>

<div class="wrapper">
    <div id="header" class="header" style="padding-left: 22px;">
        <ul class="menu">
            <li>
                {{--<a ajax="true" href="/">{!!  trans('main.menu.zero') !!}</a>--}}
                <a href="{{ env('APP_URL') }}/">{!!  trans('main.menu.zero') !!}</a>
            </li>
            <li>
                {{--<a ajax="true" href="/meditation">{!!  trans('main.menu.meditation') !!}</a>--}}
                <a href="{{ env('APP_URL') }}/meditation">{!!  trans('main.menu.meditation') !!}</a>
            </li>
            <li>
                {{--<a ajax="true" href="/library" >{!!  trans('main.menu.library') !!}</a>--}}
                <a href="{{ env('APP_URL') }}/library" >{!!  trans('main.menu.library') !!}</a>
            </li>
            {{--<li><a ajax="true" id="main_logo"  href="/" class="logo"></a></li>--}}
            <li><a id="main_logo"  href="/" class="logo"></a></li>
            <li>
                {{--<a ajax="true" href="/membre">{!!  trans('main.menu.profile') !!}</a>--}}
                <a href="{{ env('APP_URL') }}/membre">{!!  trans('main.menu.profile') !!}</a>
            </li>
            <li>
                {{--<a ajax="true" href="@if(Auth::check() && !Auth::user()->isAdmin() && Auth::user()->isSubscriber()) /abonne/subscription @else /abonne @endif">{!!  trans('main.menu.subscription') !!}</a>--}}
                <a href="@if(Auth::check() && !Auth::user()->isAdmin() && Auth::user()->isSubscriber()) /abonne/subscription @else /abonne @endif">{!!  trans('main.menu.subscription') !!}</a>
            </li>
            <li>
                {{--<a ajax="true" href="/contacts">{!!  trans('main.menu.contacts') !!}</a>--}}
                <a href="{{ env('APP_URL') }}/contacts">{!!  trans('main.menu.contacts') !!}</a>
            </li>
            <li>
                <a class="footer_soc-net">
                    <a href="https://plus.google.com/share?url={{env('APP_URL')}}?hl={{App::getLocale()}}&text=La méditation est un exercice, rien d’autre.
Venez découvrir gratuitement la façon la plus facile d’appréhender la méditation, sans gourou ni cours, tout simplement avec Turbulence Zéro.  Nous vous proposons des séries d’exercices pratiques et de partager avec nous votre expérience.
">
                        <img src="/src/img/gog.png" alt="Share on Google+"/>
                    </a>
                    <a href="http://www.facebook.com/sharer/sharer.php?u={{env('APP_URL')}}&text=La méditation est un exercice, rien d’autre.
Venez découvrir gratuitement la façon la plus facile d’appréhender la méditation, sans gourou ni cours, tout simplement avec Turbulence Zéro.  Nous vous proposons des séries d’exercices pratiques et de partager avec nous votre expérience.
">
                        <img src="/src/img/facebook2.png">
                    </a>
                    {{--<a href="#"><img src="src/img/twit.png"></a>--}}
                </div>
            </li>
        </ul>
    </div>
    <div id="content-wrapper">
        {!! $content ?? '' !!}

        @yield('content')

    </div>

    <div  id="footer-player"  class="footer-player footer-player_hide">
        <div class="footer-player_block">
            <div class="footer-player_name"></div>
            <div class="footer-player_time"></div>
            <div class="footer-player_nav">
                <div class="footer-player_left"></div>
                <div class="footer-player_action-btn footer-player_play"></div>
                <div class="footer-player_right"></div>
            </div>
        </div>
    </div>

    <div id="footer" class="footer" >
        <div class="footer_wrap">
            <div class="copywrite">
                {!!  trans('main.copyright') !!}
            </div>
            <div class="footer_menu">
                <ul>
                    <li>
                        <a href="#" popup="privee">{!!  trans('main.footer.preview') !!}</a>
                    </li>
                    <li>
                        <a href="#" popup="conditions">{!!  trans('main.footer.conditions') !!}</a>
                    </li>
                    <li>
                        <a href="#" popup="bio">{!!  trans('main.footer.bio') !!}</a>
                    </li>
                    <li>
                        <a href="#" popup="tanks">{!!  trans('main.footer.tanks') !!}</a>
                    </li>
                    <li>
                        {{--<a ajax="true" href="/faq">{!!  trans('main.footer.faq') !!}</a>--}}
                        <a href="/faq">{!!  trans('main.footer.faq') !!}</a>
                    </li>
                    <li>
                        {{--<a ajax="true" href="/presse">{!!  trans('main.footer.presse') !!}</a>--}}
                        <a href="/presse">{!!  trans('main.footer.presse') !!}</a>
                    </li>
                    <li class="hidden">
                        <div class="footer_soc-net">
                            <a href="/lang/en"><img @if (App::getLocale() == 'en')class="active-lang" @endif src="/src/img/engl-flag.jpg"></a>
                            <a href="/lang/fr"><img @if (App::getLocale() == 'fr')class="active-lang" @endif src="/src/img/franc-flag.jpg"></a>
                            <a href="/lang/nl"><img @if (App::getLocale() == 'nl')class="active-lang" @endif src="/src/img/gol-flag.jpg"></a>
                        </div>
                    </li>
                    @if(Auth::check())
                    <li>
                        <a href="/logout">{!!  trans('app.exit') !!}</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@include('site.popups.conditions')

@include('site.popups.bio')

@include('site.popups.privee')

@include('site.popups.tanks')
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
    var sc_project=11319960;
    var sc_invisible=1;
    var sc_security="2645de8a";
    var scJsHost = (("https:" == document.location.protocol) ?
        "https://secure." : "http://www.");
    document.write("<sc"+"ript type='text/javascript' src='" +
        scJsHost+
        "statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="web stats"
                                      href="http://statcounter.com/" target="_blank"><img
                    class="statcounter"
                    src="//c.statcounter.com/11319960/0/2645de8a/1/" alt="web
stats"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
</body>
</html>
