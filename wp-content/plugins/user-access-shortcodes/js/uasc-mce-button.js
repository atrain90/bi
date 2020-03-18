(function() {
	tinymce.PluginManager.add('uasc_mce_button', function( editor, url ) {
		editor.addButton( 'uasc_mce_button', {
			icon: 'uasc-mce-icon',
            tooltip: 'User Access Shortcodes',
			type: 'menubutton',
			menu: [
				{
				    text: 'Guests only',
				    onclick: function() {
                        editor.windowManager.open( {
							title: 'Guests only content',
							body: [
                                {
									type: 'textbox',
									name: 'gonote',
									label: 'Hint (for future reference)',
								},
								{
									type: 'textbox',
									name: 'included',
									label: 'Include users by ID (comma separated)',
								},
								{
									type: 'listbox',
									name: 'guests_admin_can',
									label: 'Should the admin be able to see this content?',
									'values': [
										{text: 'Yes', value: '1'},
										{text: 'No', value: '0'}
									]
								}
							],
							onsubmit: function( e ) {
                                editor.insertContent('[UAS_guest hint="' + e.data.gonote + '" in="' + e.data.included + '" admin="' + e.data.guests_admin_can + '"]<br/>This content can only be seen by guests.<br/>[/UAS_guest]');
							}
				        });	
				    },
                },
                {
				    text: 'Logged in users only',
				    onclick: function() {
				        editor.windowManager.open( {
							title: 'Logged in users only content',
							body: [
                                {
									type: 'textbox',
									name: 'linote',
									label: 'Hint (for future references)',
								},
								{
									type: 'textbox',
									name: 'excluded',
									label: 'Exclude users by ID (comma separated)',
								},
							],
							onsubmit: function( e ) {
                                editor.insertContent('[UAS_loggedin hint="' + e.data.linote + '" ex="' + e.data.excluded + '"]<br/>This content can only be seen by logged in users.<br/>[/UAS_loggedin]');
							}
				        });	
				    },
                },
                {
				    text: 'Specific users only',
				    onclick: function() {
				        editor.windowManager.open( {
							title: 'Specific users only content',
							body: [
                                {
									type: 'textbox',
									name: 'spnote',
									label: 'Hint (for future reference)',
								},
								{
									type: 'textbox',
									name: 'specific',
									label: 'Select users by ID (comma separated)',
								},
                                {
									type: 'listbox',
									name: 'specific_admin_can',
									label: 'Should the admin be able to see this content?',
									'values': [
										{text: 'Yes', value: '1'},
										{text: 'No', value: '0'}
									]
								}
							],
							onsubmit: function( e ) {
                                editor.insertContent('[UAS_specific hint="' + e.data.spnote + '" ids="' + e.data.specific + '" admin="' + e.data.specific_admin_can + '"]<br/>This content can only be seen by some selected users.<br/>[/UAS_specific]');
							}
				        });	
				    },
                },
			]
		});
	});
})();