/**
 * Created by Олеся on 11.03.2017.
 */

module.exports = {
    init: function(){

        let $categoryCheckboxes = D('.category input.group_name-down.category-name[type="checkbox"]');
        let $groupCheckboxes = D('.category input.group_name-down:not(.category-name)[type="checkbox"]');

        $categoryCheckboxes.on('change', function(){

            let clickedEl = this;

            $categoryCheckboxes.forEach(function(el){
                if(clickedEl != el && el.checked) el.checked = false;
            });

            window.Scroller(clickedEl.parentNode.offsetTop - 83 , 500);

        });

        $groupCheckboxes.on('change', function(){

            let clickedEl = this;

            $groupCheckboxes.forEach(function(el){
                if(clickedEl != el && el.checked) el.checked = false;
            });

            window.Scroller(clickedEl.parentNode.offsetTop - 83 , 500);

        });

    }
};