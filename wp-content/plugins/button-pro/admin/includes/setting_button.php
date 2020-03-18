<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
require(Button_PATH.'admin/includes/custom_setting_get.php');
?>

<div class="wdbutton_preview_box">
	<h2 class="header">Live Preview</h2>
	<div class="wdbutton_preview_style"></div>	
	<div class="wdbutton_live">
		
	</div>
	<div class="hover_box">
		<h3 class="header_hover">Hover</h3>
		<div class="wdbutton_live_hover"></div>
	</div>
	<p style="margin-left:20px;"><b>Note</b>  :-  Button Hover Effect is not show.</p>
</div>
<div class="wdbutton_settings_box">
	<div class="wrapper buttons"><?php  
	require_once(Button_PATH.'admin/setting_UI/button_setting_basic.php');
	require_once(Button_PATH.'admin/setting_UI/button_setting_text_shadow.php');
	require_once(Button_PATH.'admin/setting_UI/button_setting_border.php'); 
	require_once(Button_PATH.'admin/setting_UI/button_setting_background.php'); 
	require_once(Button_PATH.'admin/setting_UI/button_setting_container.php'); 
	?></div>
	<div class="wrapper btn_set">
		<?php require_once(Button_PATH.'admin/setting_UI/btn_sets_setting/btn_sets_settings.php');  ?>
	</div>
</div>