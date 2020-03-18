jQuery(document).ready(function() {
    jQuery(".various").fancybox({
        maxWidth	: 1200,
        maxHeight	: 1200,
        fitToView	: false,
        width		: "80%",
        height		: "90%",
        autoSize	: false,
        closeClick	: false,
        openEffect	: "none",
        closeEffect	: "none",
        beforeShow: function () {
            tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'description');
            tinymce.EditorManager.execCommand('mceAddEditor',true, 'description');

            var jsonData = [
                {image:teamchart_fancyboxup_vars.defaul_url_image, value:'1', text:teamchart_fancyboxup_vars.default_name},
                {image:teamchart_fancyboxup_vars.circle_url_image, value:'2', text:teamchart_fancyboxup_vars.circle_name},
                {image:teamchart_fancyboxup_vars.nature_url_image,  value:'3', text:teamchart_fancyboxup_vars.nature_name},
                {image:teamchart_fancyboxup_vars.defaul_url_image, value:'4', text:teamchart_fancyboxup_vars.flat_name}
        ];

            oDropdown = jQuery("#choose-theme").msDropdown({enableAutoFilter:false,byJson:{data:jsonData, name:'Template',selectedIndex: 0}}).data("dd");
            if (typeof oDropdown !== 'undefined') {
                oDropdown.set('disabled', true);
            }
        }
    });


    jQuery('input#disableTextEditor').on('click' ,function() {
        if(jQuery("input#disableTextEditor").is(':checked')) {
            tinymce.EditorManager.execCommand('mceToggleEditor',false,'description');
        } else {
            tinymce.EditorManager.remove();
            tinymce.EditorManager.execCommand('mceToggleEditor',true,'description');
        }
    });

    init ();
});