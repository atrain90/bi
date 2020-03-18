
jQuery(function(){
	
	jQuery(window).load(function() {
		jQuery('.wdbutton_settings_box').show();
	});

	var button_val=jQuery('input[name=button_layout]:checked').val();
	var btn_set_val=jQuery('input[name=button_layout]:checked').data("wdbutton");

	button_effect_status(button_val,btn_set_val);
	

	jQuery('input[name=button_layout]').click(function(){
		var button_val=jQuery(this).val();
		var btn_set_val=jQuery(this).data("wdbutton");					

		if(button_val=='simple_button'){
			jQuery('.button_effect_section').hide();
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='2d_transitions'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='border_transitions'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='curl'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='speech_bubbles'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='background_transitions'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='icon_simple'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='icon_with_text'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='hexagons'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='social_button_set_with_icon'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='social_model_1'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='social_model_2'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='social_model_3'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='social_model_4'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='social_model_5'){
			button_effect_status(button_val,btn_set_val)
		}else if(button_val=='social_model_6'){
			button_effect_status(button_val,btn_set_val)
		}
	});

function button_effect_status(button_val,btn_set_val){
	jQuery('.button_effect_section').hide();
	jQuery('.wrapper').hide();

	jQuery('.button_effect_section').find('.ip_input').attr('name', '');
	jQuery('.'+button_val).find('.ip_input').attr('name', 'button_icon');

	jQuery('.button_circle').hide();
	jQuery('.icon_with_text_none').show();
	jQuery('.icon_simple_none').show();
	jQuery('.hexagons_none').show();	
	jQuery('.curl_none').show();
	jQuery('.border_transitions_none').show();

	if(button_val=='simple_button'){
		jQuery('.button_circle').show();
	}

	if(button_val=='border_transitions'){
		jQuery('.border_transitions_none').hide();
	}

	if(button_val=='curl'){
		jQuery('.curl_none').hide();
	}

	if(button_val=='icon_simple'){		
		jQuery('.icon_simple_none').hide();
		jQuery('.button_circle').show();
	}

	if(button_val=='icon_with_text'){
		jQuery('.icon_with_text_none').hide();
	}

	if(button_val=='hexagons'){
		jQuery('.hexagons_none').hide();
	}

	jQuery('.'+btn_set_val).show();
	jQuery('.'+button_val).show();
}	

});