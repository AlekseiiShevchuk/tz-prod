(function(window){

    let $title, $body, $content;

    let methods = {
        beforeChange: null,
        afterChange: null,
        init: function(){

            $title = D('title');
            $body = D('body');
            $content = D('#content-wrapper');

            window.addEventListener('popstate', function(event) {
                console.log(event.state);

                console.log(event);
                if(event.state) changeContent(event.state.href);
                else{
                    history.go(-1);
                }
            });

            loadJs();

            findAjaxLinks();

            markCurrentLink(location.href);

            history.replaceState({href: location.href}, null, location.href);
        }
    };


    function changeContent(href){
        if(methods.beforeChange) methods.beforeChange();

        fetch(href,{
            credentials: 'include',
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then(function(response){
            return response.json();
        }).then(function(data){

            console.log(href, data);

            if(data.location){

                history.replaceState({href: data.location}, null, data.location);
                changeContent(data.location);

            }else{

                window.Scroller(0 , 0);

                $content.html(data.content);

                if(data.blue_style){
                    $body.addClass('blue-style');
                } else{
                    $body.removeClass('blue-style');
                }

                if(data.title){
                    $title.text(data.title);
                }

                loadJs();

                findAjaxLinks();

                markCurrentLink(href);

                if(methods.afterChange) methods.afterChange();
            }

        }).catch(function(ex){
            console.log(ex);
            location.href = href;
        });
    }

    function findAjaxLinks(){
        D('a[ajax="true"]').forEach(function(el, i){

            let href = el.href;
            let path = D(el).attr('href');

            if(el.target != '_blank' && href != '' && path != '' && path != '#'){

                D(el).on('click',function(event){

                    event.preventDefault();

                    if(!event.ctrlKey){
                        history.pushState({href: href}, null, href);

                        changeContent(href);
                    }else{
                        window.open(href);
                    }

                }).attr('ajax','inited');
            }
        });
    }

    function loadJs(){
        let controllerName = location.pathname.slice(1);

        try{
            require('./pages/' + (controllerName || 'index')).init();
        }catch(e){
            if(!e.message.match('Cannot find module')){
                console.log(e);
            }
        }
    }

    function markCurrentLink(href){
        D('a[ajax="inited"]').forEach(function(el, i){
            if(el.href == href) D(el).addClass('current');
            else D(el).removeClass('current');
        });
    }

    module.exports = methods;

}(window));