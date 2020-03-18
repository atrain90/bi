<?php 
/*
Plugin Name: Button Pro
Plugin URI: http://webdzier.com
Description: WordPress button generator plugin.You can create any type of button.
Version: 1.0.7
Author: webdzier
Author URI: http://webdzier.com
Text Domain: button-pro 
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit;
define("Button_URL", plugin_dir_url(__FILE__));
define('Button_PATH',plugin_dir_path(__FILE__));


add_action('plugins_loaded', 'WDButton_GetReadyTranslation');
function WDButton_GetReadyTranslation() {
	load_plugin_textdomain('button-pro', FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

register_activation_hook(__FILE__, 'WDButton_activation_setting');	
function WDButton_activation_setting(){
	require_once(Button_PATH.'admin/includes/button_default_setting.php');
}

register_deactivation_hook(__FILE__,'WDButton_deactivation_setting');
function WDButton_deactivation_setting(){
	delete_option('button_Default_Setting');
}

require_once(Button_PATH.'admin/classes/WDButton_sets_style.class.php');

function WDbutton_current_user(){
	if ( current_user_can( 'administrator' ) ) {
		if(is_admin()){	
			require_once(Button_PATH.'admin/classes/WDButton_CPT_class.class.php');	
			require_once(Button_PATH.'admin/classes/WDButton_admin_style_script.class.php');	
			require_once(Button_PATH.'admin/classes/WDButton_metaboxes_class.class.php');		
			require_once(Button_PATH.'admin/classes/WDButton_duplicate_class.php');		
			
			new WDButton_CPT_class();
			new WDButton_admin_style_script();
			new WDButton_sets_style();
			new WDButton_metaboxes_class();	
		}	
	}	
}
add_action( 'plugins_loaded', 'WDbutton_current_user');



require_once(Button_PATH.'user_view/classes/WDButton_shortcode_class.class.php');
new WDButton_sets_style();
new WDButton_shortcode_class();


require_once(Button_PATH.'admin/classes/widget/button_widget.php');


?>