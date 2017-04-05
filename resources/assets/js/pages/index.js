/**
 * Created by Andrew Karpich on 09.02.2017.
 */

module.exports = {
    init: function(){

        let play = D('#play');
        let player = D('#player');
        let playLeft = D('.player-home .play-left');
        let playRight = D('.player-home .play-right');

        play.on('click', function togglePlay(){
            if(play.hasClass('play-home')){
                player[0].play();
                play.removeClass('play-home');
                play.addClass('pause-home');
            } else{
                player[0].pause();
                play.removeClass('pause-home');
                play.addClass('play-home');
            }
        });

        playLeft.on('click', function restart(){
            player[0].currentTime = 0;
        });

        playRight.on('click', function end(){
            player[0].currentTime = 0;
            player[0].pause();
            play.removeClass('pause-home');
            play.addClass('play-home');
        });


        D(function(){
            window.clearTimeout(window.timer);
            let elWrap = D('#slider'),
                el = elWrap.find('li'),
                indexImg = 1,
                indexMax = el.length,
                phase = 11000;

            function change(v){

                let next = D(el[indexImg - 1]);

                el.forEach(function(item){
                    if(item != next[0]){
                        move(item).x(1000).set('opacity', 0).duration(800).end(function(){
                            move(item).x(-1000).duration(10).end();
                        });
                    }
                });

                if(next[0]) move(next[0]).x(20).set('opacity', 1).duration(800).end();
            }

            function autoChange(){
                indexImg++;
                if(indexImg > indexMax){
                    indexImg = 1;
                }
                D('.carousel-block_nav>ul>li').removeClass("carousel-block_nav-active");
                D('li#' + indexImg).addClass("carousel-block_nav-active");
                change();
                window.timer = window.setTimeout(autoChange, phase);
            }

            let but1 = D('li#1');
            let but2 = D('li#2');
            let but3 = D('li#3');

            but1.on('click', function(){
                window.clearTimeout(window.timer);
                indexImg = 1;
                D('.carousel-block_nav>ul>li').removeClass("carousel-block_nav-active");
                D('li#' + indexImg).addClass("carousel-block_nav-active");
                change();
                window.timer = window.setTimeout(autoChange, phase);
            });
            but2.on('click', function(){
                window.clearTimeout(window.timer);
                indexImg = 2;
                D('.carousel-block_nav>ul>li').removeClass("carousel-block_nav-active");
                D('li#' + indexImg).addClass("carousel-block_nav-active");
                change();
                window.timer = window.setTimeout(autoChange, phase);
            });
            but3.on('click', function(){
                window.clearTimeout(window.timer);
                indexImg = 3;
                D('.carousel-block_nav>ul>li').removeClass("carousel-block_nav-active");
                D('li#' + indexImg).addClass("carousel-block_nav-active");
                change();
                window.timer = window.setTimeout(autoChange, phase);
            });
            if(indexMax !== 1){
                //window.timer = window.setTimeout(autoChange, phase);
            }
        });
    }
};