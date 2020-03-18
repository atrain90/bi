/**
 * WP File Download
 *
 * @package WP File Download
 * @author Joomunited
 * @version 1.0
 */

function wpfdTrackDownload() {
    
    if(typeof wpfdparams != "undefined" && wpfdparams.ga_download_tracking == '1') {
        jQuery('a.wpfd_downloadlink').click(function(e) {

            var href = jQuery(this).attr('href');
            var extLink = href.replace(/^https?\:\/\//i, '');
                  
            if(typeof _gaq != 'undefined') {
                    _gaq.push(['_trackEvent', 'WPFD', 'Download', extLink]);
              
                    setTimeout(function() { location.href = href; }, 400);
                    return false;
              
            }

        })
        
        //run below code when open preview on new tab
        jQuery('a.wpfd_previewlink').click(function(e) {
            
            var href = jQuery(this).attr('href');
            var extLink = href.replace(/^https?\:\/\//i, '');            
           
            if(typeof _gaq != 'undefined') {
                
                    _gaq.push(['_trackEvent', 'WPFD', 'Preview', extLink]);
                    
                    setTimeout(function() { window.open(href,'_blank');}, 400);
                    return false;              
            }
        })
        
    }
}

jQuery(document).ready(function($) {
    wpfdTrackDownload();
})

function wpfd_remove_loading(el) {
    jQuery('.wpfd-loading', el).remove();
}