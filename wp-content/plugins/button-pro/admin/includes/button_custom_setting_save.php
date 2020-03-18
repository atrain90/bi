<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
if(isset($post) && isset($_POST['button_layout']))
{
	$_array =array();
	$key_array=array(
		'button_layout',
		'button_text',
		'button_link',
		'attribute_id',
		'attribute_value',
		'button_icon',
		'button_icon_size',
		'2d_transitions',

		/**border transitions***/
		'border_transitions',		
		'border_transitions_width',
		'border_transitions_color',
		/*****/
		'curl',
		
		'speech_bubbles',
		'speech_bubbles_color',

		'background_transitions',

		'icon_simple',
		'icon_simple_effect',

		'icon_with_text',

		'icon_hexagons',
		'hexagons_effect',

		
		/*button sets*/
		
		'wd_fb_btn',
		'wd_twitter_btn',
		'wd_google_btn',
		'wd_pinterest_btn',
		'wd_linkedin_btn',
		'wd_instagram_btn',
		'wd_vimeo_btn',
		'wd_skype_btn',
		'wd_youtube_btn',
		'wd_tumblr_btn',

		'wd_fb_url',
		'wd_twitter_url',
		'wd_google_url',
		'wd_pinterest_url',
		'wd_linkedin_url',
		'wd_instagram_url',
		'wd_vimeo_url',
		'wd_skype_url',
		'wd_youtube_url',
		'wd_tumblr_url',

		'button_target',
		'padding_top',
		'padding_right',
		'padding_bottom',
		'padding_left',
		'button_width',
		'button_height',
		'button_text_color',
		'button_text_hover_color',
		'font_family',
		'font_size',
		'text_bold',
		'text_italic',
		'text_align',
		'button_circle',
		'border_top_left',
		'border_top_right',
		'border_bottom_left',
		'border_bottom_right',
		'border_style',
		'border_width',
		'border_color',
		'border_hover_color',
		'border_shadow_color',
		'border_shadow_hover_color',
		'border_shadow_offset_left',
		'border_shadow_offset_top',
		'border_shadow_blur',
		'button_bg_color_start',
		'button_bg_color_end',
		'button_bg_hover_color_start',
		'button_bg_hover_color_end',
		'opacity_start',
		'opacity_end',
		'hover_opacity_start',
		'hover_opacity_end',
		'gradient_stop',
		'container_use',
		'container_center',
		'container_width',
		'button_align',
		'margin_top',
		'margin_right',
		'margin_bottom',
		'margin_left',
		'shadow_offset_left',
		'shadow_offset_top',
		'shadow_blur',
		'shadow_color',
		'shadow_hover_color',
		'custom_css');
	foreach ($key_array as $value) 
	{
		$_array[$value] =$_POST[$value];
	}
	$_array = serialize($_array);			
	update_post_meta($post,'button_custom_setting_'.$post,$_array);
}
?>