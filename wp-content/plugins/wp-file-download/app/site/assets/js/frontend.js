/**
 * WP File Download
 *
 * @package WP File Download
 * @author Joomunited
 * @version 1.0
 */

jQuery(document).ready(function($) {

    function preloader() {
        $('.wpfd-loading').css('background',"url("+wpfdfrontend.pluginurl + "/app/site/assets/images/balls.gif) no-repeat center center");
    }
    function addLoadEvent(func) {
        var oldonload = window.onload;
        if (typeof window.onload != 'function') {
            window.onload = func;
        } else {
            window.onload = function() {
                if (oldonload) {
                    oldonload();
                }
                func();
            }
        }
    }

    addLoadEvent(preloader);
});

