<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
wp_enqueue_style('wd_font_awesome');
require(Button_PATH.'user_view/coman_css/custom_css.php');
require(Button_PATH.'user_view/button_layouts/icon_with_text/css/icon_with_text_style.php');
if($custom_data['button_target']==1){$button_target="_blank";}else{$button_target="_self";}

$attribute = '';

if(!empty($custom_data['attribute_id']) && !empty($custom_data['attribute_value'])){
	
	$attribute = $custom_data['attribute_id']."=".$custom_data['attribute_value'];
}
?>
<div class="button_wrapper_id_<?php echo $id; ?>">
	<div class="button_container button_container_id_<?php echo $id; ?>">
		<a href="<?php echo esc_url($custom_data['button_link']); ?>" target="<?php echo $button_target; ?>" class="wd_flat_button_<?php echo $id;?>" <?php echo esc_attr($attribute); ?>>
			<span>
				<i class="fa <?php echo $custom_data['button_icon']; ?> <?php echo $custom_data['button_icon_size']; ?>"></i> 
			</span> 
			<label><?php printf( __( '%s', 'button-pro' ),$custom_data['button_text']); ?></label>

		</a>
	</div>
</div>