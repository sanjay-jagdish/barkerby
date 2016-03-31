/* Swedish initialisation for the timepicker plugin */
/* Written by BjÃ¶rn Westlin (bjorn.westlin@su.se). */
jQuery(function($){
    $.timepicker.regional['sv'] = {
                hourText: 'Tim',
                minuteText: 'Min',
                amPmText: ['', ''] ,
                closeButtonText: 'StÃ¤ng',
                nowButtonText: 'Nu',
                deselectButtonText: 'Rensa' }
    $.timepicker.setDefaults($.timepicker.regional['sv']);
});