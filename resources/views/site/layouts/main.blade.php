<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta property="fb:app_id"        content="{{ $_ENV['FACEBOOK_APP_ID'] }}" />
    <meta property="og:type"          content="website" />
    <meta property="og:url"           content="{{ $_ENV['APP_URL'] }}" />
    @if ($title ?? '')
        <meta property="og:title"     content="{{ strip_tags(trans('info.title')) }}" />
    @endif
    <meta property="og:description"   content="{{ strip_tags(trans('index.carousel-block_text-1')) }}" />
    <meta property="og:image"         content="{{ $_ENV['APP_URL'] }}/src/img/logo-blue.png" />
    <meta name="description"          content="{{ strip_tags(trans('index.carousel-block_text-1')) }}" />

    <title>@if (isset($title) && $title) {{ $title }} @endif</title>

    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/fonts.css" rel="stylesheet">
    {{--<link href="/css/app.css" rel="stylesheet">--}}
    {{--<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>--}}

    <script src="/js/app.js"></script>
</head>
<body @if (isset($blue_style) && $blue_style )class="blue-style" @endif itemscope itemtype="http://schema.org/WebSite">

<div class="hidden" itemprop="name">{{ strip_tags(trans('info.title')) }}</div>
<img class="hidden" itemprop="image" src="{{ $_ENV['APP_URL'] }}/src/img/logo-blue.png" />
<div class="hidden" itemprop="description">{{ strip_tags(trans('index.carousel-block_text-1')) }}</div>
<div class="hidden" itemprop="url">{{ $_ENV['APP_URL'] }}</div>

<div class="wrapper">
    <div id="header" class="header" style="padding-left: 22px;">
        <ul class="menu">
            <li>
                <a ajax="true" href="/">{!!  trans('main.menu.zero') !!}</a>
            </li>
            <li>
                <a ajax="true" href="/meditation">{!!  trans('main.menu.meditation') !!}</a>
            </li>
            <li>
                <a ajax="true" href="/library" >{!!  trans('main.menu.library') !!}</a>
            </li>
            <li><a ajax="true" id="main_logo"  href="/" class="logo"></a></li>
            <li>
                <a ajax="true" href="/membre">{!!  trans('main.menu.profile') !!}</a>
            </li>
            <li>
                <a ajax="true" href="@if(Auth::check() && !Auth::user()->isAdmin() && Auth::user()->isSubscriber()) /abonne/subscription @else /abonne @endif">{!!  trans('main.menu.subscription') !!}</a>
            </li>
            <li>
                <a ajax="true" href="/contacts">{!!  trans('main.menu.contacts') !!}</a>
            </li>
            <li>
                <div class="footer_soc-net">
                    <a href="https://plus.google.com/share?url={{$_ENV['APP_URL']}}?hl={{App::getLocale()}}">
                        <img src="/src/img/gog+.png" alt="Share on Google+"/>
                    </a>
                    <a href="http://www.facebook.com/sharer/sharer.php?u={{$_ENV['APP_URL']}}">
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
                        <a ajax="true" href="/faq">{!!  trans('main.footer.faq') !!}</a>
                    </li>
                    <li>
                        <a ajax="true" href="/presse">{!!  trans('main.footer.presse') !!}</a>
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

</body>
</html>