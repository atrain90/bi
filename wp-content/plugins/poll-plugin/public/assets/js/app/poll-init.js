jQuery(function($){

    setTimeout(function(){
        $("canvas[data-weblator-poll-id]").each(function(){
            new Poll($(this).data("weblator-poll-id"), $(this).closest('[data-random-id]').attr('data-random-id'));
        });
    }, 1000);

});
