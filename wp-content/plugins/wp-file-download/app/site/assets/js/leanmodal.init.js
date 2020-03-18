function wpfd_frameload() {    
       jQuery("#wpfd_loader").hide();
       jQuery("#wpfdmodalframe").show();
}
jQuery(document).ready(function($){
    $('.wpfdlaunch').leanModal({ top : 20, beforeShow: function(){ $("#wpfdmodal").css("height","90%"); $("#wpfdmodalframe").hide(); $("#wpfdmodalframe").attr('src', $("#wpfdmodalframe").attr('src')); $("#wpfd_loader").show(); } });
    $('body').append('<div id="wpfdmodal"><img src="images/spinner-2x.gif" width="32" id="wpfd_loader" /><iframe id="wpfdmodalframe" onload="wpfd_frameload()"  width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto" src="'+wpfdmodalvars.adminurl+'admin.php?page=wpfd&noheader=1&caninsert=1" /><button id="wpfd-close-modal" onclick="jQuery(\'#lean_overlay\',window.parent.document).fadeOut(300);jQuery(\'#wpfdmodal\',window.parent.document).fadeOut(300);" style="position: absolute; right: -23px;">x</button></div>');
   return false;
});
