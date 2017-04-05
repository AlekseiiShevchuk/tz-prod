/**
 * Created by andrewkarpich on 18.02.2017.
 */

module.exports = {
    init: function(){
        D('#conditions-popup-opener').on('click',function(event){

            event.preventDefault();

            popupS.modal({
                content: D('#conditions-popup')[0].innerHTML
            });

        });
    }
};