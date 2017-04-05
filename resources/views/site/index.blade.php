<div class="abonne-title">
    {{ trans('index.carousel-block_title') }}
</div>
<br><br>
<div class="content">
    <div class="container-home">
        {{--<div class="carousel-block_title">--}}
            {{--{{ trans('index.carousel-block_title') }}--}}
        {{--</div>--}}
        <ul id="slider">
            <li class="carousel-block">
                <div class="carousel-block_text">
                    {!!  trans('index.carousel-block_text-1') !!}
                </div>
            </li>
            <li class="carousel-block">
                <div class="carousel-block_text">
                    {!!  trans('index.carousel-block_text-2') !!}
                </div>
            </li>
            <li class="carousel-block">
                <div class="carousel-block_text">
                    {!!  trans('index.carousel-block_text-3') !!}
                </div>
            </li>
        </ul>

        <div class="carousel-block_nav">
            <ul>
                <li id="1" class="carousel-block_nav-active"></li>
                <li id="2"></li>
                <li id="3"></li>
            </ul>
        </div>
        @if(!Auth::check())
            <a ajax="true" href="login" class="home-buttom">{!!  trans('index.enter') !!}</a>
        @endif

        {{--<div class="line"></div>
        @if(!Auth::check())
            <audio id="player" src="src/audio/audio.mp3"></audio>
            <div class="player-home">
                <a href="#" class="play-left"  onclick="restart()"></a>
                <a href="#" class="play-home" id="play"></a>
                <a href="#" class="play-right"  onclick="end()"></a>
            </div>
        @endif--}}
    </div>
</div>