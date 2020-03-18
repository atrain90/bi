<?php 

/**
* 
*/
if ( ! defined( 'ABSPATH' ) ) exit;
class WDButton_sets_style
{
	
	function __construct()
	{
		add_action('admin_enqueue_scripts', array(&$this,'wd_default_style'));
		add_action('wp_enqueue_scripts', array(&$this,'wd_default_style'));
	}

	function wd_default_style(){		
		//wp_enqueue_style('wd_default_style',Button_URL.'user_view/coman_css/wd_default_style.css');
		wp_register_style('social_model_default',Button_URL.'user_view/coman_css/social_model_default.css');
		wp_register_style('model_1_style',Button_URL.'user_view/button_layouts/social_button_sets/model_1/css/model_1_style.css');
		wp_register_style('model_2_style',Button_URL.'user_view/button_layouts/social_button_sets/model_2/css/model_2_style.css');
		wp_register_style('model_3_style',Button_URL.'user_view/button_layouts/social_button_sets/model_3/css/model_3_style.css');
		wp_register_style('model_4_style',Button_URL.'user_view/button_layouts/social_button_sets/model_4/css/model_4_style.css');
		wp_register_style('model_5_style',Button_URL.'user_view/button_layouts/social_button_sets/model_5/css/model_5_style.css');
		wp_register_style('model_6_style',Button_URL.'user_view/button_layouts/social_button_sets/model_6/css/model_6_style.css');
	}
}
?>