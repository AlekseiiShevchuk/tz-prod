/**
 * Created by Andrew Karpich on 09.02.2017.
 */
module.exports = {
    init: function(){

        D('[go-prev]').on('click', function(event){
            event.preventDefault();
            window.history.go(-1);
        });

        D('#loader').on('change', this.previewImg);

        D('#country_id').on('change', function(e){

            let code = D(this.selectedOptions[0]).attr('phonecode'), value = '';

            if(code != '0') value = '+' + code;

            D('.membre-form input[name="phone_country_code"]')[0].value = value;
        });

        let $date = D('#date'),
            $month = D('#month'),
            $year = D('#year');

        let dateUser = parseInt($date.attr('value')),
            monthUser = parseInt($month.attr('value')),
            yearUser = parseInt($year.attr('value'));

        let day = new Date,
            md = (new Date(day.getFullYear(), day.getMonth() + 1, 0, 0, 0, 0, 0)).getDate(),
            $month_name = $month.attr('months').split(" ");

        function set_select(el, c, d, e){
            el.options.length = 0;
            el.options[0] = new Option('', 0);
            for(let b = 0; b < c; b++){
                el.options[ b + 1 ] = new Option(el.id == 'month' ? $month_name[ b ] : b + d, b + d);
            }
            el.options[ e + 1 ] && (el.options[ e + 1 ].selected = !0)
        }

        function check_date(){

            let newMonth = $month[0].selectedIndex + 1;
            let newYear = $year[0].selectedIndex + 1947;

            $date.attr('value', $date[0].selectedIndex + 1);
            $month.attr('value', newMonth);
            $year.attr('value', newYear);

            md = (new Date(newYear || 0, newMonth || 0, 0, 0, 0, 0, 0)).getDate();

            set_select($date[0], md, 1, $date[0].selectedIndex);
        }

        if(dateUser == day.getDate() && monthUser == day.getMonth() + 1 && yearUser == day.getFullYear()){
            dateUser = 0;
            monthUser = 0;
        }

        set_select($date[0], md, 1, dateUser - 1);
        set_select($month[0], 12, 1, monthUser - 1);
        set_select($year[0], 71, yearUser - 70, 70);

        $year.on('change', check_date);
        $month.on('change', check_date);

    },
    previewImg: function(){
        let input = D('#loader')[ 0 ];
        if(input.files && input.files[ 0 ]){
            if(input.files[ 0 ].type.match('image.*')){
                let reader = new FileReader();
                reader.onload = function(e){
                    let avatar = D('#avatar');
                    avatar.attr('src', e.target.result);
                    avatar[0].parentNode.style.height = avatar[0].clientHeight + 'px';
                };
                reader.readAsDataURL(input.files[ 0 ]);
            } else console.log('is not image mime type');
        } else console.log('not isset files data or files API not supordet');
    }
};