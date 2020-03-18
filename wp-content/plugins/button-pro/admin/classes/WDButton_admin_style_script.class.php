<?php 

/**
* 
*/
if ( ! defined( 'ABSPATH' ) ) exit;
class WDButton_admin_style_script
{
	
	function __construct()
	{
		add_action('admin_enqueue_scripts', array(&$this,'WDButton_metaboxes_scripts'));
	}

	function WDButton_metaboxes_scripts(){
		global $typenow; 
		if ($typenow=='page' || $typenow=='post'){
			wp_register_style('wd_font_awesome',Button_URL.'css/wd_font_awesome/css/wd_font_awesome.css');					
		}
		if ($typenow=='wd_button'){
			$dataToBePassed = array('Button_URL'  => Button_URL);
			wp_enqueue_script( 'jquery');
			wp_enqueue_script('jquery-ui-draggable');

			/*button hide show script*/
			wp_enqueue_script( 'button_effect_script', Button_URL.'admin/js/button_effect_script.js', array(), false, true );
			
			/**********/
			/*color picker*/
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'Button_colorpicker_script', Button_URL.'admin/js/button_color_picker.js', array( 'wp-color-picker' ), false, true );
			/**************/

			/** metabox style**/
			wp_enqueue_style('Button_metaboxes_style',Button_URL.'admin/css/metaboxes_style.css');

			/********/

			/*****icons****/
			wp_enqueue_style('wd_font_awesome',Button_URL.'css/wd_font_awesome/css/wd_font_awesome.css');					
			wp_enqueue_style('icon-picker-css',Button_URL.'admin/css/fontawesome-iconpicker.css');

			wp_enqueue_script( 'icons-js', Button_URL.'admin/js/fontawesome-iconpicker.js', array(), false, true );
			wp_enqueue_script( 'call-icons', Button_URL.'admin/js/call-icon-picker.js', array(), false, true );

			/***live preview******/

			wp_enqueue_script( 'button_preview', Button_URL.'admin/js/button_preview.js', array(), false, true );
			
			$localize_array=array('button_preview'=>$dataToBePassed);

			foreach ($localize_array as $key => $value) {
				wp_localize_script( $key, 'php_vars', $value );
			}

			/** linedtextarea***/
			wp_enqueue_style('jquery-linedtextarea-css',Button_URL.'admin/css/jquery.numberedtextarea.css');

			wp_enqueue_script( 'jquery-linedtextarea-js', Button_URL.'admin/js/jquery.numberedtextarea.js', array(), false, true);
			wp_enqueue_script( 'my_admin_custom', Button_URL.'admin/js/my_admin_custom.js', array(), false, true );
			/*****/
		}	

	}
}
?>