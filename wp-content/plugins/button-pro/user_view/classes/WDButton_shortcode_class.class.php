<?php 
/**
* 
*/
if ( ! defined( 'ABSPATH' ) ) exit;
class WDButton_shortcode_class
{
	
	function __construct()
	{
		add_shortcode( 'WD_Button', array(&$this, 'WD_Button_shortcode') );
		
	}
	

	function WD_Button_shortcode($post){
		ob_start();
		$id=$post['id'];
		$ids=explode(",", $id);
			
		foreach ($ids as $id) {
			if(isset($id)){
				$custom_data = unserialize(get_post_meta($id,'button_custom_setting_'.$id, true));

				wp_enqueue_style('wd_font_awesome',Button_URL.'css/wd_font_awesome/css/wd_font_awesome.css');

				require_once(Button_PATH.'user_view/includes/font_family.php');				
				
				require_once(Button_PATH.'user_view/includes/rgba_color.php');	

				/**bg color**/
				$button_bg_color_start=Button_rgba( $custom_data['button_bg_color_start'], $custom_data['opacity_start']);
				$button_bg_color_end=Button_rgba( $custom_data['button_bg_color_end'], $custom_data['opacity_end']);
				$button_bg_hover_color_start=Button_rgba( $custom_data['button_bg_hover_color_start'], $custom_data['hover_opacity_start']);
				$button_bg_hover_color_end=Button_rgba( $custom_data['button_bg_hover_color_end'], $custom_data['hover_opacity_end']);

				?> <style type="text/css"><?php echo $custom_data['custom_css']; ?></style><?php
				
				if($custom_data['button_layout']=="simple_button"){
					require(Button_PATH.'user_view/button_layouts/simple_button/simple_button.php');
				}elseif($custom_data['button_layout']=="2d_transitions"){
					require(Button_PATH.'user_view/button_layouts/2d_transitions/2d_transitions.php');
				}elseif($custom_data['button_layout']=="border_transitions"){
					require(Button_PATH.'user_view/button_layouts/border_transitions/border_transitions.php');
				}elseif($custom_data['button_layout']=="curl"){
					require(Button_PATH.'user_view/button_layouts/curl/curl.php');
				}elseif($custom_data['button_layout']=="speech_bubbles"){
					require(Button_PATH.'user_view/button_layouts/speech_bubbles/speech_bubbles.php');
				}elseif($custom_data['button_layout']=="background_transitions"){
					require(Button_PATH.'user_view/button_layouts/background_transitions/background_transitions.php');
				}elseif($custom_data['button_layout']=="icon_simple"){
					require(Button_PATH.'user_view/button_layouts/icon_simple/icon_simple.php');
				}elseif($custom_data['button_layout']=="icon_with_text"){
					require(Button_PATH.'user_view/button_layouts/icon_with_text/icon_with_text.php');
				}elseif($custom_data['button_layout']=="hexagons"){
					require(Button_PATH.'user_view/button_layouts/hexagons/hexagons.php');
				}elseif($custom_data['button_layout']=="social_button_set_with_icon"){
					require(Button_PATH.'user_view/button_layouts/social_button_sets/social_button_set_with_icon/social_button_set_with_icon.php');
				}elseif($custom_data['button_layout']=="social_model_1"){
					require(Button_PATH.'user_view/button_layouts/social_button_sets/model_1/model_1.php');
				}elseif($custom_data['button_layout']=="social_model_2"){
					require(Button_PATH.'user_view/button_layouts/social_button_sets/model_2/model_2.php');
				}elseif($custom_data['button_layout']=="social_model_3"){
					require(Button_PATH.'user_view/button_layouts/social_button_sets/model_3/model_3.php');
				}elseif($custom_data['button_layout']=="social_model_4"){
					require(Button_PATH.'user_view/button_layouts/social_button_sets/model_4/model_4.php');
				}elseif($custom_data['button_layout']=="social_model_5"){
					require(Button_PATH.'user_view/button_layouts/social_button_sets/model_5/model_5.php');
				}elseif($custom_data['button_layout']=="social_model_6"){
					require(Button_PATH.'user_view/button_layouts/social_button_sets/model_6/model_6.php');
				}

			}		
		}	
		
		return ob_get_clean();
	}
}
?>