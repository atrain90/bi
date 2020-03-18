(function() {

    tinymce.PluginManager.add('rtb_button', function( editor, url ) {
        editor.addButton( 'rtb_button', {
            text: '',
            icon: ' dashicons-chart-line',
            onclick: function() {


                jQuery.post(ajaxurl, {action:"load_poll_list"}, function(response){

                    var tables = jQuery.parseJSON(response);

                    editor.windowManager.open( {
                        title: 'Insert Poll',
                        body: [{
                            type: 'listbox',
                            name: 'table',
                            label: 'Select a Poll',
                            'values': tables
                        }],
                        onsubmit: function( e ) {
                            editor.insertContent( '[poll id='+ e.data.table +']');
                        }
                    });
                });

            }
        });
    });
})();




