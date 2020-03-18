(function ($) {

    var PollOptions = function(){

        function init() {

            $(".save-options-button").click(function(){
                saveData();
            });

        }

        function saveData() {

            $(".delete-action").css("visibility", "visible");
            $(".delete-action span.spinner").css({"display" : "inline-block", "visibility" : "visible"});

            var arr = [];
            $("input[id^=weblator_]").each(function(){

                arr.push({
                    "key" : $(this).attr("id"),
                    "value" : $(this).val()
                });

            });

            $.post(ajaxurl, {action:"update_global_options", options:arr, '_wpnonce': jQuery('#_wpnonce').val()}, function(data){

                $(".delete-action").css("visibility", "hidden");
                $(".delete-action span.spinner").css({"display" : "none", "visibility" : "hidden"});

                $("#message").slideDown();

                setTimeout(function(){
                    $("#message").slideUp();
                }, 3000);
            });

        }

        init();
    }

    if ( $(".weblator-poll-options").length ){
        var c = new PollOptions();
    }


})(jQuery);
