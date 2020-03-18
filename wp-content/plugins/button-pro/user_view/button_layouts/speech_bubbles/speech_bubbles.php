<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
require(Button_PATH.'user_view/coman_css/custom_css.php');
require(Button_PATH.'user_view/button_layouts/speech_bubbles/css/speech_bubbles_hover.php');
if($custom_data['button_target']==1){$button_target="_blank";}else{$button_target="_self";}

$attribute = '';

if(!empty($custom_data['attribute_id']) && !empty($custom_data['attribute_value'])){
	
	$attribute = $custom_data['attribute_id']."=".$custom_data['attribute_value'];
}
?>
<div class="button_wrapper_id_<?php echo $id; ?>">
	<div class="button_container button_container_id_<?php echo $id; ?>">			
		<a href="<?php echo esc_url($custom_data['button_link']); ?>" target="<?php echo $button_target;?>" id="wd_button" class="<?php echo $custom_data['speech_bubbles'].'_'.$id;?> wd_button_<?php echo $id;?>" <?php echo esc_attr($attribute); ?>><?php printf( __( '%s', 'button-pro' ),$custom_data['button_text']); ?></a>	
		<div class="clear_fix"></div>
	</div>
</div>