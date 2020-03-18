jQuery(function(){

	jQuery(".wdbutton_preview_box").draggable({});	
	var Button_URL=php_vars.Button_URL;
	var myInterval;
	var button_layout;
	var button_layout_checked=jQuery('input[name=button_layout]:checked').val();
	

	myIntervalstart(button_layout_checked);

	jQuery('input[name=button_layout]').click(function(){
		button_layout=jQuery(this).val();
		myIntervalstop();
		if(button_layout!= undefined){
			myIntervalstart(button_layout);
		}		
	});	

	function myIntervalstart(button_layout) {
		myInterval=setInterval(function(){
			button_preview(button_layout);
		},500);
	}
	
	function myIntervalstop() {
		clearInterval(myInterval);
	}

	function button_preview(button_layout){
		var button_data=preview_data_store();
		button_effect(button_layout);

		if(button_layout=='simple_button'){				
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='2d_transitions'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);			
			
			
		}else if(button_layout=='border_transitions'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);	
			
		}else if(button_layout=='curl'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);	
		}else if(button_layout=='speech_bubbles'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);	
		}else if(button_layout=='background_transitions'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);	
		}else if(button_layout=='icon_simple'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='icon_with_text'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='hexagons'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='social_button_set_with_icon'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='social_model_1'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='social_model_2'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='social_model_3'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='social_model_4'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='social_model_5'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}else if(button_layout=='social_model_6'){
			preview_button(button_data,button_layout);			
			hover_preview_button(button_data,button_layout);
		}		
	}

	
	function preview_button(button_data,button_layout){
		var button_html;
		
		if(button_layout=='hexagons'){

			var hexagons_style;
			hexagons_style='<style>';
			hexagons_style+='.hexagons_inline{display: inline-block;position:relative;text-align: center;z-index: 0;}';
			hexagons_style+='.hexagons_inline:before,.hexagons_inline:after{position: absolute;content: "";left: -1px;top: 0;z-index: -1;}';
			hexagons_style+='.hexagons_inline:before{-webkit-transform: rotate(60deg);-moz-transform: rotate(60deg);-ms-transform: rotate(60deg);-o-transform: rotate(60deg);transform: rotate(60deg);}';
			hexagons_style+='.hexagons_inline:after{-webkit-transform: rotate(-60deg);-moz-transform: rotate(-60deg);-ms-transform: rotate(-60deg);-o-transform: rotate(-60deg);transform: rotate(-60deg);}';
			hexagons_style+='.hexagons_inline i{z-index: 9;-webkit-transition: all .25s ease;-moz-transition: all .25s ease;-ms-transition: all .25s ease;-o-transition: all .25s ease;transition: all .25s ease;}';
			hexagons_style+='.hexagons_inline,.hexagons_inline:before,.hexagons_inline:after{box-sizing: border-box; -webkit-transition: all .25s ease;-moz-transition: all .25s ease; -ms-transition: all .25s ease;-o-transition: all .25s ease;transition: all .25s ease;}';
			hexagons_style+='.hexagons_inline,.hexagons_inline:before,.hexagons_inline:after{background: '+button_data['button_bg_color_start']+';border-left:1px '+button_data['border_style']+';border-right:1px '+button_data['border_style']+';border-color:'+button_data['border_color']+';color:'+button_data['button_text_color']+';}';
			hexagons_style+='.hexagons_inline,.hexagons_inline:before,.hexagons_inline:after{height:21.5px;width:38px; }';
			hexagons_style+='.hexagons_inline{line-height: 21.5px;font-size: 21.5px;margin: 9.5px 0px;}';
			hexagons_style+='</style>';

			jQuery('.wdbutton_preview_style').html(hexagons_style);

			button_html='<a><span class="hexagons_inline"><i class="fa '+button_data['icon_hexagons']+'"></i></span></a>';

			jQuery(".wdbutton_preview_box .wdbutton_live").html(button_html);
			hover_box_status();
		}else{
			var style=wdbutton_data(button_data,button_layout);
			button_html=abc(button_layout,style,button_data);		
			jQuery(".wdbutton_preview_box .wdbutton_live").html(button_html);
		 //myIntervalstop()
		}
	}

	function hover_preview_button(button_data,button_layout){		
		var button_hover_html;

		if(button_layout=='hexagons'){
			var hexagons_hover_style;
			hexagons_hover_style='<style>';			
			hexagons_hover_style+='.hexagons_inline_hover,.hexagons_inline_hover:before,.hexagons_inline_hover:after{background: '+button_data['button_bg_hover_color_start']+';border-left:1px '+button_data['border_style']+';border-right:1px '+button_data['border_style']+';border-color:'+button_data['border_hover_color']+';color:'+button_data['button_text_hover_color']+';}';
			hexagons_style+='</style>';

			jQuery('.wdbutton_preview_style').append(hexagons_hover_style);

			button_hover_html='<a><span class="hexagons_inline hexagons_inline_hover"><i class="fa '+button_data['icon_hexagons']+'"></i></span></a>';

			jQuery(".wdbutton_preview_box .wdbutton_live_hover").html(button_hover_html);

		}else{
			var hover_style=hover_data(button_data,button_layout);		
			button_hover_html=abc(button_layout,hover_style,button_data);
			jQuery(".wdbutton_preview_box .wdbutton_live_hover").html(button_hover_html);
			//myIntervalstop()
		}
	}

	function abc(button_layout,style,button_data){
		var button_html;
		hover_box_status();
		if(button_layout=='simple_button' || button_layout=='2d_transitions' || button_layout=='border_transitions' || button_layout=='curl' || button_layout=='speech_bubbles' || button_layout=='background_transitions' ){
			button_html='<a style="'+style+'">'+button_data['button_text']+'</a>';

		}else if(button_layout=='icon_simple'){			
			button_html='<a style="'+style+'"><i class="fa '+button_data['icon_simple']+'"></i></a>';
		}else if(button_layout=='icon_with_text'){				
			var span_style='width: 45px;';
			span_style+='display: inline-block;';
			span_style+='text-align: center;';
			span_style+='float: left;';
			span_style+='margin: 3px 0px 0px 2px;';

			var label_style='padding: 10px;';
			label_style+='float: left;';
			label_style+='border-left: 1px solid rgba(0,0,0,0.1);';
			label_style+='line-height: 1.75;';
			label_style+='font-size: 17px;';
			label_style+='width: auto;';
			label_style+='margin: 0;';
			label_style+='background:none;';
			if(button_data['text_bold']=='bold' ){label_style+='font-weight:'+ button_data['text_bold'] +';';}
			if(button_data['text_italic']=='italic'){label_style+='font-style:'+ button_data['text_italic'] +';';}

			button_html='<a style="'+style+'"><span style="'+span_style+'"><i style="line-height: 45px;text-shadow:none;" class="fa '+button_data['icon_text']+' '+button_data['button_icon_size']+'"></i></span><label style="'+label_style+'">'+button_data['button_text']+'</label></a>';
			
		}else if(button_layout=='social_button_set_with_icon'){
			button_html='<img src="'+Button_URL+'images/button_sets/button_sets.png"><h3 class="wd_btnset_use">Button Set</h3>';
			hover_box_status('hide');
		}else if(button_layout=='social_model_1'){
			button_html='<img src="'+Button_URL+'images/button_sets/social_model_1.png"><h3 class="wd_btnset_use">Button Set</h3>';
			hover_box_status('hide');
		}else if(button_layout=='social_model_2'){
			button_html='<img src="'+Button_URL+'images/button_sets/social_model_2.png"><h3 class="wd_btnset_use">Button Set</h3>';
			hover_box_status('hide');
		}else if(button_layout=='social_model_3'){
			button_html='<img src="'+Button_URL+'images/button_sets/social_model_3.png"><h3 class="wd_btnset_use">Button Set</h3>';
			hover_box_status('hide');
		}else if(button_layout=='social_model_4'){
			button_html='<img src="'+Button_URL+'images/button_sets/social_model_4.png"><h3 class="wd_btnset_use">Button Set</h3>';
			hover_box_status('hide');
		}else if(button_layout=='social_model_5'){
			button_html='<img src="'+Button_URL+'images/button_sets/social_model_5.png"><h3 class="wd_btnset_use">Button Set</h3>';
			hover_box_status('hide');
		}else if(button_layout=='social_model_6'){
			button_html='<img src="'+Button_URL+'images/button_sets/social_model_6.png"><h3 class="wd_btnset_use">Button Set</h3>';
			hover_box_status('hide');
		}

		return button_html;
	}

	function hover_box_status(val){
		if(val=='hide'){jQuery('.hover_box').hide();}else{jQuery('.hover_box').show();}
		
	}

	function wdbutton_data(button_data,button_layout){
		var style;
		style='line-height: 1.42857143;';
		style+='box-sizing: border-box;';
		if(button_layout=='icon_simple'){
			style+='width:'+ button_data['button_width'] +'px;';
			style+='height:'+ button_data['button_width'] +'px;';
			style+='padding-top:'+button_data['padding_top'] +'px;';
			style+='padding-right:'+button_data['padding_right'] +'px;';
			style+='padding-bottom:'+ button_data['padding_bottom'] +'px;';
			style+='padding-left:'+ button_data['padding_left'] +'px;';			
			style+='font-size:'+ button_data['font_size'] +'px;';
			style+='text-align: center;';
		}else if(button_layout=='icon_with_text'){

			style+='width:'+ (button_data['button_width']-2) +'px;';

		}else{

			style+='width:'+ button_data['button_width'] +'px;';
			style+='height:'+ button_data['button_height'] +'px;';
			style+='padding-top:'+button_data['padding_top'] +'px;';
			style+='padding-right:'+button_data['padding_right'] +'px;';
			style+='padding-bottom:'+ button_data['padding_bottom'] +'px;';
			style+='padding-left:'+ button_data['padding_left'] +'px;';
			style+='font-size:'+ button_data['font_size'] +'px;';
			style+='text-align:'+ button_data['text_align'] +';';
			style+='font-family:'+ button_data['font_family'] +';';


		}


		if(button_data['button_circle']==1 || button_layout=='icon_simple' && button_data['button_circle']==1){ 			
			style+='border-radius:50%;';

		}else{
			button_data['button_circle']="";
			style+='border-top-left-radius:'+ button_data['border_top_left'] +'px;';
			style+='border-top-right-radius:'+ button_data['border_top_right'] +'px;';
			style+='border-bottom-left-radius:'+ button_data['border_bottom_left'] +'px;';
			style+='border-bottom-right-radius:'+ button_data['border_bottom_right'] +'px;';
		}

		style+='color:'+ button_data['button_text_color'] +';';			

		if(button_data['text_bold']=='bold' || !(button_layout=='icon_with_text')){style+='font-weight:'+ button_data['text_bold'] +';';}
		if(button_data['text_italic']=='italic' || !(button_layout=='icon_with_text')){style+='font-style:'+ button_data['text_italic'] +';';}

		style+='text-shadow:'+ button_data['shadow_offset_left']+'px '+ button_data['shadow_offset_top']+'px '+ button_data['shadow_blur']+'px '+ button_data['shadow_color']+';';
		
		if(!((button_layout=='border_transitions' && button_data['border_transitions']=='wd_hvr_border_fade') || button_layout=='border_transitions' && button_data['border_transitions']=='wd_hvr_hollow')){
			style+='box-shadow:'+ button_data['border_shadow_offset_left']+'px '+ button_data['border_shadow_offset_top']+'px '+ button_data['border_shadow_blur']+'px '+ button_data['border_shadow_color']+';';
		}
		

		if(!(button_layout=='border_transitions' || (button_layout=='curl')) ){

			style+='border-style:'+ button_data['border_style'] +';';
			style+='border-width:'+ button_data['border_width'] +'px;';
			style+='border-color:'+ button_data['border_color'] +';';
		}

		/*opacity color*/
		button_data['button_bg_color_start']=colorconvertHex(button_data['button_bg_color_start'],button_data['opacity_start']);
		button_data['button_bg_color_end']=colorconvertHex(button_data['button_bg_color_end'],button_data['opacity_end']);


		style+='background:-webkit-gradient(linear, left top, left bottom, color-stop('+ button_data['gradient_stop']+'%,'+button_data['button_bg_color_start']+'),color-stop(1,'+button_data['button_bg_color_end']+'));';
		style+='background:-moz-linear-gradient('+ button_data['button_bg_color_start']+' '+button_data['gradient_stop']+'%, '+button_data['button_bg_color_end']+');';
		style+='background:-o-linear-gradient('+ button_data['button_bg_color_start']+' '+button_data['gradient_stop']+'%, '+button_data['button_bg_color_end']+');';
		style+='background:linear-gradient('+ button_data['button_bg_color_start']+' '+button_data['gradient_stop']+'%, '+button_data['button_bg_color_end']+');';
		return style;
	}

	function hover_data(button_data,button_layout){

		button_data['button_text_color']=button_data['button_text_hover_color'];
		button_data['shadow_color']=button_data['shadow_hover_color'];
		button_data['border_color']=button_data['border_hover_color'];
		button_data['border_shadow_color']=button_data['border_shadow_hover_color'];
		button_data['button_bg_color_start']=button_data['button_bg_hover_color_start'];
		button_data['button_bg_color_end']=button_data['button_bg_hover_color_end'];
		button_data['opacity_start']=button_data['hover_opacity_start'];
		button_data['opacity_end']=button_data['hover_opacity_end'];

		//console.log(button_data)
		
		var hover_style=wdbutton_data(button_data,button_layout);
		if(button_layout=='border_transitions'){
			
			if(button_data['border_transitions']=='wd_hvr_underline_from_left' || button_data['border_transitions']=='wd_hvr_underline_from_center' || button_data['border_transitions']=='wd_hvr_underline_from_right' || button_data['border_transitions']=='wd_hvr_underline_reveal'){
				hover_style+='border-bottom:'+ button_data['border_transitions_width'] +'px solid '+button_data['border_transitions_color']+';';
			}else if(button_data['border_transitions']=='wd_hvr_overline_from_left' || button_data['border_transitions']=='wd_hvr_overline_from_center' || button_data['border_transitions']=='wd_hvr_overline_from_right' || button_data['border_transitions']=='wd_hvr_overline_reveal'){
				hover_style+='border-top:'+ button_data['border_transitions_width'] +'px solid '+button_data['border_transitions_color']+';';
			}else {
				hover_style+='border-style:solid;';
				hover_style+='border-width:'+ button_data['border_transitions_width'] +'px;';
				hover_style+='border-color:'+ button_data['border_transitions_color'] +';';

			}
		}
		return hover_style;
	}

	function hexagons_style(){
		var style;
	}

	function hexagons_hover_style(){
		var style;
	}

	function preview_data_store(){
		var myarr={};
		jQuery( ".wdbutton_settings_box input[type=text]" ).map(function(n,i) {		
			myarr[ this.name ] = jQuery(this).val(); 
		});

		jQuery( ".wdbutton_settings_box input[type=number]" ).map(function(n,i) {		
			myarr[ this.name ] = jQuery(this).val(); 
		});

		jQuery( ".wdbutton_settings_box input[type=checkbox]:checked" ).map(function(n,i) {		
			myarr[ this.name ] = jQuery(this).val(); 
		});

		jQuery( ".wdbutton_settings_box input[type=radio]:checked" ).map(function(n,i) {		
			myarr[ this.name ] = jQuery(this).val(); 
		});
		
		jQuery( ".wdbutton_settings_box select" ).map(function(n,i) {		
			myarr[ this.name ] = jQuery(this).val();
		});

		jQuery( ".button_effect_section input[type=number]" ).map(function(n,i) {		
			myarr[ this.name ] = jQuery(this).val(); 
		});

		jQuery( ".button_effect_section input[type=text]" ).map(function(n,i) {		
			myarr[ this.id ] = jQuery(this).val(); 
		});

		jQuery( ".button_effect_section input[type=radio]:checked" ).map(function(n,i) {		
			myarr[ this.name ] = jQuery(this).val(); 
		});

		jQuery( ".button_effect_section select" ).map(function(n,i) {		
			myarr[ this.name ] = jQuery(this).val();
		});

		return myarr;		
	}

	function colorconvertHex(hex,opacity){
		hex = hex.replace('#','');
		r = parseInt(hex.substring(0,2), 16);
		g = parseInt(hex.substring(2,4), 16);
		b = parseInt(hex.substring(4,6), 16);

		result = 'rgba('+r+','+g+','+b+','+opacity/100+')';
		return result;
	}

	function button_effect(button_layout){
		jQuery('.'+button_layout).change(function(){
			//myIntervalstart(button_layout);
			//button_preview(button_layout);
		});
	}

	function my_ajax(button_data,button_layout){

		jQuery.ajax({

			url:Button_URL+'user_view/button_layouts/'+button_layout+'/css/'+button_layout+'_hover.php',
			type:'POST',
			data:{'hover_class':button_data[button_layout]},
			beforeSend:function(){
				jQuery('.wdbutton_preview_style').html('');
			},
			success:function(res){
				//console.log();
				
				jQuery('.wdbutton_preview_style').html(res);
				//alert(res)				
				//myIntervalstop();
				
			},complete:function(){
				//alert('hello');
				//myIntervalstop();
			}
		});
	}	


});