<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
wp_enqueue_style('wd_font_awesome');
require(Button_PATH.'user_view/coman_css/custom_css.php');
require(Button_PATH.'user_view/button_layouts/hexagons/css/wd_hexagons.php');
require(Button_PATH.'user_view/button_layouts/hexagons/css/wd_hexagons_hover.php');
if($custom_data['button_target']==1){$button_target="_blank";}else{$button_target="_self";}


$attribute = '';

if(!empty($custom_data['attribute_id']) && !empty($custom_data['attribute_value'])){
	
	$attribute = $custom_data['attribute_id']."=".$custom_data['attribute_value'];
}
?>

<div class="button_wrapper_id_<?php echo $id; ?>">
	<div class="button_container button_container_id_<?php echo $id; ?>">		

		<a href="<?php echo esc_url($custom_data['button_link']); ?>" target="<?php echo $button_target; ?>" <?php echo esc_attr($attribute); ?>><span class="wd_hb_<?php echo $id;?> wd_hb_xs_<?php echo $id;?> <?php echo $custom_data['hexagons_effect'].'_'.$id;?>"><i class="fa <?php echo $custom_data['button_icon']; ?>"></i></span></a>

		<div class="clear_fix"></div>
	</div>
</div>





