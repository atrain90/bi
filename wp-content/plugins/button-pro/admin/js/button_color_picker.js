jQuery(document).ready(function($){
    jQuery('.button_color_field').wpColorPicker();
});

var myOptions = {   
    defaultColor: false,    
    change: function(event, ui){},  
    clear: function() {},    
    hide: true,   
    palettes: true
}; 
jQuery('.button_color_field').wpColorPicker(myOptions);