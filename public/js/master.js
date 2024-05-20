(function ($) {
    "use strict";

    function themeSwitch(){
        const isDark = localStorage.getItem('isDark') == 'true' ? true : false;
        if(isDark){
            $('body').addClass('dark-mode'); 
        }else{
            $('body').removeClass('dark-mode');
        } 
        $('input[name=theme-switch').prop('checked', isDark)
    }

    // set_menu(url,url_segment1,url_segment2,url_segment3);

    // $('.select').select2();

    //theme switch function
    $('input[name=theme-switch]').on('change', function(){
        const isChecked = $(this).is(':checked');
        localStorage.setItem('isDark', isChecked);
        themeSwitch();
    });

    setTimeout(() => {
        themeSwitch();
    }, 200);
})(jQuery); 