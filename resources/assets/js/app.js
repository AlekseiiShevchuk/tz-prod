
require("babel-polyfill");

import 'whatwg-fetch';

const D = require('./D');

const popupS = window.popupS = require('popups');

const Opentip = window.Opentip = require("./../../../node_modules/opentip/lib/opentip.js");
require("./../../../node_modules/opentip/lib/adapter-native.js");

Opentip.styles.deny = {
    showOn: 'click',
    target: true,
    tipJoint: "bottom",
    group: "deny",
    background: '#f9f9f9',
    borderColor: '#f9f9f9',
    shadowBlur: 20,
    className: 'deny'
};

const Scroller = window.Scroller = require('scroll-to-js');

const SoundManager = require('SoundManager2').soundManager;

const InlinePlayer = require('./InlinePlayer');

const Router = require('./Router');

let inlinePlayer;

SoundManager.setup({
    debugMode: true,
    preferFlash: false,
    useFlashBlock: true,
    url: '../../swf/',
    flashVersion: 9
});
SoundManager.config = {
    playNext: false,
    autoPlay: false,
    allowMultiple: false
};

SoundManager.onready(function(){

    inlinePlayer = new InlinePlayer(SoundManager);

    inlinePlayer.detectAudio();

});

window.onload = function(){

    window.Scroller = window.Scroller.bind(null, navigator.userAgent.toLowerCase().indexOf('firefox') > -1 ? document.documentElement : document.body);

    window.move = require('move-js');

    Router.init();

    Router.afterChange = function(){

        if(inlinePlayer){
            if(inlinePlayer.currentSound) inlinePlayer.currentSound.stop();
            inlinePlayer.detectAudio();
        }

        D('[onlynumbers="true"]').on('keydown', function(e){
            if(e.code != 'Backspace' && e.key != 'Backspace'){
                if(!/^[+()\d-]+$/.test(e.key)){
                    e.preventDefault();
                }
            }
        });

        D('.only_for_subscribers').forEach(function(el){
            new Opentip(el, "Uniquement pour les abonnés", { style: "deny" });
        });

        D('.verif_email_sound').forEach(function(el){
            new Opentip(el, "Confirmer votre eMail", { style: "deny" });
        });

    };

    Router.afterChange();

    D(window).on('scroll',function(){
        let left = (window.pageXOffset || document.documentElement.scrollLeft) - (document.documentElement.clientLeft || 0);
        move(D('#footer-player')[0]).x(-left).duration(0).end();
        move(D('#footer')[0]).x(-left).duration(0).end();
        move(D('#header')[0]).x(-left).duration(0).end();
    });

    D('[popup]').on('click',function(event){
        event.preventDefault();

        popupS.modal({
            content: D('#' + D(this).attr('popup') + '-popup')[0].innerHTML,
            onOpen: function(){
                document.body.style.overflow='hidden';
            },
            onClose: function(){
                document.body.style.overflow='auto';
            }
        });
        document.getElementsByClassName("popupS-layer")[0].parentElement.style.height = (window.innerHeight - 20) + "px";
    });

    D('.footer_soc-net a').on('click', function(event){
        event.preventDefault();
        window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600')
    });
};

SoundManager.onerror = function(){
    console.error('SoundManager2 does not load');
};

