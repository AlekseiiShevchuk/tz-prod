/**
 * Created by Andrew Karpich on 08.02.2017.
 */

(function(window, D){

    function InlinePlayer(SoundManager){

        let self = this;
        let dPlayer = D('.footer-player');
        let dPlayerActionBtn = dPlayer.find('.footer-player_action-btn');
        let dPlayerTime = dPlayer.find('.footer-player_time');

        this.soundsByURL = {};
        this.soundLinks = [];
        this.soundCount = 0;
        this.currentSound = null;

        this.config = {
            playNext: false,
            autoPlay: false
        };

        this.css = {
            sDefault: 'ms-play', // default state
            sLoading: 'ms-play',
            sPlaying: 'ms-pause',
            sPaused: 'ms-play'
        };

        this.Player = {
            intervalId: null,
            show: function(){
                dPlayer.removeClass('footer-player_hide');
            },
            hide: function(){
                dPlayer.addClass('footer-player_hide');
            },
            setName: function(text){
                dPlayer.find('.footer-player_name').text(text);
            },
            setTime: function(time){
                let seconds = time / 1000;
                let minutes = parseInt(seconds / 60);
                let sec = parseInt(seconds - (minutes * 60));
                dPlayerTime.text((minutes < 10 ? '0' + minutes : minutes) + ':' + (sec < 10 ? '0' + sec : sec));
            },
            play: function(){
                let sound = this;
                self.Player.setName(sound._data.name);
                dPlayerActionBtn.removeClass('footer-player_play').addClass('footer-player_pause');
                self.Player.setTime(sound.position);
                self.Player.intervalId = setInterval(function(){
                    self.Player.setTime(sound.position);
                }, 1000);
            },
            stop: function(){
                self.Player.hide();
                clearInterval(self.Player.intervalId);
            },
            pause: function(){
                dPlayerActionBtn.removeClass('footer-player_pause').addClass('footer-player_play');
                clearInterval(self.Player.intervalId);
            }
        };

        dPlayerActionBtn.on('click', function(event){
            event.preventDefault();
            self.currentSound.togglePause();
        });
        dPlayer.find('.footer-player_right').on('click', function(event){
            event.preventDefault();

            let currentSoundIndex = null;

            self.soundLinks.forEach(function(el, i){
                if(el == self.currentSound._data.object) currentSoundIndex = i + 1;
                else if(currentSoundIndex == i) D(el).trigger('click');
            });
        });
        dPlayer.find('.footer-player_left').on('click', function(event){
            event.preventDefault();

            let currentSoundIndex = null;

            self.soundLinks.forEach(function(el, i){
                if(el == self.currentSound._data.object) currentSoundIndex = i - 1;
            });

            if(currentSoundIndex >= 0) self.soundLinks.forEach(function(el, i){
                if(i == currentSoundIndex) D(el).trigger('click');
            });
        });

        let soundManagerIntervalId = 0;

        let showSoundPosition = function(){
            let seconds = self.currentSound.position / 1000;
            let minutes = parseInt(seconds / 60);
            let sec = parseInt(seconds - (minutes * 60));
            D(self.currentSound._data.object.parentElement.parentElement).find('.group_name-time').text((minutes < 10 ? '0' + minutes : minutes) + ':' + (sec < 10 ? '0' + sec : sec));
        };

        this.soundManagerEvents = {
            play: function(){
                // self.Player.play.call(this);
                let dEl = D(this._data.object);
                dEl.removeClass(this._data.className).addClass(this._data.className = self.css.sPlaying);
                if(self.currentSound) showSoundPosition();
                soundManagerIntervalId = setInterval(showSoundPosition, 100);
            },
            stop: function(){
                let dEl = D(this._data.object);
                dEl.removeClass(this._data.className);
                this._data.className = '';
                // self.Player.stop.call(this);
                clearInterval(soundManagerIntervalId);
            },
            pause: function(){
                let dEl = D(this._data.object);
                dEl.removeClass(this._data.className).addClass(this._data.className = self.css.sPaused);
                // self.Player.pause.call(this);
                clearInterval(soundManagerIntervalId);
            },
            resume: function(){
                self.soundManagerEvents.play.call(this);
            },
            finish: function(){
                self.soundManagerEvents.stop.call(this);

                if(self.config.playNext){
                    dPlayer.find('.footer-player_right').trigger('click');
                }
            }
        };


        this.getSoundByUrl = function(url){
            return (typeof self.soundsByURL[ url ] != 'undefined' ? self.soundsByURL[ url ] : null);
        };

        this.stopSound = function(sound){
            SoundManager.stop(sound.id);
            SoundManager.unload(sound.id);
        };

        let lastClickTime = new Date();

        let clickHandler = function(event){
            event.preventDefault();

            let now = new Date();

            if(now.getTime() > lastClickTime.getTime() + 200){

                let url = this.getAttribute('a');

                let sound = self.getSoundByUrl(url);

                if(!sound){

                    let soundIndex = ++self.soundCount;

                    sound = SoundManager.createSound({
                        id: 'InlinePlayerSound' + soundIndex,
                        url: url,
                        onplay: self.soundManagerEvents.play,
                        onstop: self.soundManagerEvents.stop,
                        onpause: self.soundManagerEvents.pause,
                        onresume: self.soundManagerEvents.resume,
                        onfinish: self.soundManagerEvents.finish,
                        type: (this.type || null)
                    });

                    sound._data = {
                        object: this,
                        className: self.css.sPlaying,
                        index: soundIndex,
                        name: this.getAttribute('audio-name')
                    };

                    self.soundsByURL[ url ] = sound;

                    if(self.currentSound){
                        // self.stopSound(self.currentSound);
                        self.currentSound.pause();
                    }

                    sound.play();
                } else{
                    if(sound != self.currentSound && self.currentSound){
                        // self.stopSound(self.currentSound);
                        self.currentSound.pause();
                    }

                    sound.togglePause();
                }

                self.currentSound = sound;

                // self.Player.show();

                lastClickTime = now;
            }

            return false;
        };

        this.detectAudio = function(){

            let setReplay = function(el){
                D(el.parentElement).find('.group_name-replay').on('click', function(event){
                    event.preventDefault();

                    let a = el.getAttribute('a');

                    if(self.soundsByURL[ a ] && self.soundsByURL[ a ] == self.currentSound){

                        self.stopSound(self.currentSound);
                        self.currentSound.play();

                    } else{

                        D(el).trigger('click');

                        setTimeout(function(){
                            self.stopSound(self.currentSound);
                            self.currentSound.play();
                        }, 50)

                    }
                });
            };

            let links = D('a[audio="true"]');

            let newLinks = [];

            let openEl = null;

            links.forEach(function(el, i){

                let issetAudio = false;

                for(let i = 0; i < self.soundLinks.length; i++){

                    let a = el.getAttribute('a');

                    if(a == self.soundLinks[ i ].getAttribute('a')){
                        issetAudio = true;
                        D(el).on('click', clickHandler);
                        setReplay(el);
                        self.soundLinks[ i ] = el;
                        if(self.soundsByURL[ a ]){
                            self.soundsByURL[ a ]._data.object = el;
                            D(el).removeClass(self.css.sDefault)
                                .removeClass(self.css.sLoading)
                                .removeClass(self.css.sPaused)
                                .removeClass(self.css.sPlaying)
                                .addClass(self.soundsByURL[ a ]._data.className);
                            openEl = self.soundsByURL[ a ]._data.object;
                        }
                        break;
                    }
                }

                if(!issetAudio){
                    newLinks.push(el);
                }
            });

            if(newLinks.length > 0){
                self.soundLinks = self.soundLinks.concat(newLinks);

                newLinks.on('click', clickHandler);

                newLinks.forEach(setReplay);

                if(self.config.autoPlay) D(newLinks[ 0 ]).trigger('click');
            }

            if(openEl){

                let li = D(openEl.parentNode.parentNode.parentNode.parentNode.parentNode);
                li.find('input.group_name-down')[0].checked = true;

                let li2 = D(li[0].parentNode.parentNode.parentNode);
                li2.find('input.group_name-down')[0].checked = true;

                window.Scroller( li2[0].parentNode.offsetTop - 83 , 500);

                // showSoundPosition();

                // console.log(openEl);
                // console.log(li);
                // console.log(li2);
            }
        };
    }

    module.exports = InlinePlayer;

}(window, D));
