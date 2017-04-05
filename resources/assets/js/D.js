/**
 * Created by andre on 08.02.2017.
 */

(function(window){

    let context = document;

    let D = function(selectorOrObject){

        let nodeList = [];

        if(typeof selectorOrObject == 'object'){
            if(Array.isArray(selectorOrObject)) nodeList = selectorOrObject;
            else nodeList.push(selectorOrObject);
        } else if(typeof selectorOrObject == 'string'){

            let m = selectorOrObject.match(/#\d+/g);
            if(m) m.forEach(function(el){selectorOrObject = selectorOrObject.replace(el, '#\\3' + el.slice(1));});

            nodeList = context.querySelectorAll(selectorOrObject)
        } else if(typeof selectorOrObject === "function"){
            selectorOrObject();return;
        }

        /**
         * @var Array array
         */
        let array = Array.prototype.slice.call(nodeList);

        array.__proto__.find = function(selector){
            let arrays = [];
            this.forEach(function(el, i){
                context = el;
                arrays.push(D(selector));
                context = document;
            });

            return [].concat.apply([], arrays);
        };

        array.__proto__.hasClass = function(className){
            let object = this[ 0 ];
            return (typeof(object.className) != 'undefined' ? !!object.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)')) : false);
        };

        array.__proto__.addClass = function(className){
            this.forEach(function(el, i){
                if(!(!el || !className || D(el).hasClass(className))){
                    el.className = (el.className ? el.className + ' ' : '') + className;
                }
            });
            return this;
        };

        array.__proto__.removeClass = function(className){
            this.forEach(function(el, i){
                let classes = el.className.split(' ');
                for(let i = 0; i < classes.length; i++){
                    if(classes[ i ] == className){
                        classes.splice(i, 1);
                        i--;
                    }
                }
                el.className = classes.join(' ');
            });
            return this;
        };

        array.__proto__.on = function(eventName, eventHandler, useCapture){
            let isListener = (typeof window.addEventListener !== 'undefined');
            this.forEach(function(el, i){
                if(isListener) el.addEventListener(eventName, eventHandler, useCapture || false);
                else o.attachEvent('on' + eventName, eventHandler);
            });
            return this;
        };

        array.__proto__.off = function(eventName, eventHandler, useCapture){
            let isListener = (typeof window.removeEventListener !== 'undefined');
            this.forEach(function(el, i){
                if(isListener) el.removeEventListener(eventName, eventHandler, useCapture || false);
                else o.detachEvent('on' + eventName, eventHandler);
            });
            return this;
        };

        array.__proto__.trigger = function(eventName, options){
            let event;
            if(!window.CustomEvent){
                event = document.createEvent('CustomEvent');
                event.initCustomEvent(eventName, true, true, options);
            } else event = new CustomEvent(eventName, options);
            this.forEach(function(el, i){
                if(document.createEvent){
                    el.dispatchEvent(event);
                } else{
                    el.fireEvent("on" + event.eventType, event);
                }
            });
        };

        array.__proto__.attr = function(name, value){
            if(!value) return this[ 0 ] ? this[ 0 ].getAttribute(name) : null;
            else{
                this.forEach(function(el, i){
                    el.setAttribute(name, value);
                });
                return this;
            }
        };

        array.__proto__.text = function(text){
            let textEl = document.createTextNode(text);
            this.forEach(function(el, i){
                el.innerText = textEl.textContent;
            });
        };

        array.__proto__.html = function(html){

            let elem = window.document.createElement('div');
            elem.innerHTML = html;

            D(elem).find('script').forEach(function(el){
                if(el.innerHTML){
                    window.eval(el.innerHTML);
                }
                if(el.src){
                    let script = window.document.createElement('script');
                    script.type = 'text/javascript';
                    script.async = true;
                    script.src = el.src;
                    window.document.getElementsByTagName('head')[0].appendChild(script);
                }
            });

            this.forEach(function(el, i){
                while (el.firstChild) {
                    el.removeChild(el.firstChild);
                }

                while (elem.childNodes[0]) {
                    el.appendChild(elem.childNodes[0]);
                }
            });
        };

        return array;
    };

    module.exports = D;

    window.D = D;

}(window));