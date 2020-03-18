<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
wp_enqueue_style('wd_font_awesome');
require(Button_PATH.'user_view/coman_css/custom_css.php');
require(Button_PATH.'user_view/button_layouts/icon_simple/css/icon_simple_style.php');
require(Button_PATH.'user_view/button_layouts/icon_simple/css/icon_simple_hover.php');
if($custom_data['button_target']==1){$button_target="_blank";}else{$button_target="_self";}

$attribute = '';

if(!empty($custom_data['attribute_id']) && !empty($custom_data['attribute_value'])){
	
	$attribute = $custom_data['attribute_id']."=".$custom_data['attribute_value'];
}
?>
<div class="button_wrapper_id_<?php echo $id; ?>">
	<div class="button_container_id_<?php echo $id; ?>">		
		<a href="<?php echo esc_url($custom_data['button_link']); ?>" target="<?php echo $button_target; ?>" class="wd_simple_icon_<?php echo $id;?>" id="<?php echo $custom_data['icon_simple_effect'].'_'.$id;?>" <?php echo esc_attr($attribute); ?>><i class="fa <?php echo $custom_data['button_icon']; ?>"></i></a>
		<div class="clear_fix"></div>
	</div>
</div>