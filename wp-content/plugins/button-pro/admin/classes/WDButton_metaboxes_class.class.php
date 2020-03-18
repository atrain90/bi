<?php 

/**
* 
*/
if ( ! defined( 'ABSPATH' ) ) exit;
class WDButton_metaboxes_class
{
	
	function __construct()
	{
		add_action('add_meta_boxes',array(&$this, 'WDButton_meta_box_function'));
		add_action('save_post',array(&$this,'WDButton_custom_setting_save'));		
	}

	function WDButton_meta_box_function(){

		add_meta_box('layout_metabox',  __('Button Layouts', 'button-pro'),array(&$this, 'Button_layout_select'),
			'wd_button','normal','low');
		
		add_meta_box('button_setting_metabox', __('Button Settings', 'button-pro'),array(&$this, 'button_setting_metabox'),
			'wd_button','normal','low');

		
		add_meta_box('shortcode_meta_box', __('Shortcode', 'button-pro'),array(&$this, 'shortcode_meta_box'),
			'wd_button','side','low');

		add_meta_box('button_rateus', __('Rate Us If You Like This Plugin', 'button-pro'), array(&$this, 'button_rateus_function'), 'wd_button', 'side', 'low');
	}	

	function Button_layout_select($post){ 
		require_once(Button_PATH.'admin/setting_UI/layouts.php');
	}

	function button_setting_metabox($post){ 
		require(Button_PATH.'admin/includes/setting_button.php');		
	}	

	function WDButton_custom_setting_save($post){
		require_once(Button_PATH.'admin/includes/button_custom_setting_save.php');
	}

	function shortcode_meta_box($post){
		?>	
		<div class="shortcode_meta_box">
			<input type="text" value="<?php echo "[WD_Button id=".get_the_ID()."]"; ?>" onclick='jQuery(this).select()' readonly/>
		</div>
		<?php 
	}

	function button_rateus_function(){
		require_once(Button_PATH.'admin/includes/rate_us.php');		
	}


}
?>