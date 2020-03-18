<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
wp_enqueue_style('wd_font_awesome');
require(Button_PATH.'user_view/button_layouts/social_button_sets/social_button_set_with_icon/css/social_button_set_with_icon_style.php');
?>

<div id="social_platforms">
	<?php if($custom_data['wd_fb_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_facebook" href="<?php echo esc_url($custom_data['wd_fb_url']); ?>" target="_blank"><i class="fa fa-facebook"></i><span>Facebook</span></a><?php } ?>
	
	<?php if($custom_data['wd_twitter_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_twitter" href="<?php echo esc_url($custom_data['wd_twitter_url']); ?>" target="_blank"><i class="fa fa-twitter"></i><span>Twitter</span></a><?php } ?>
	
	<?php if($custom_data['wd_google_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_googleplus" href="<?php echo esc_url($custom_data['wd_google_url']); ?>" target="_blank"><i class="fa fa-google-plus"></i><span>Google+</span></a><?php } ?>
	
	<?php if($custom_data['wd_pinterest_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_pinterest" href="<?php echo esc_url($custom_data['wd_pinterest_url']); ?>" target="_blank"><i class="fa fa-pinterest"></i><span>Pinterest</span></a><?php } ?>
	
	<?php if($custom_data['wd_linkedin_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_linkedin" href="<?php echo esc_url($custom_data['wd_linkedin_url']); ?>" target="_blank"><i class="fa fa-linkedin"></i><span>LinkedIn</span></a><?php } ?>
	
	<?php if($custom_data['wd_instagram_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_instagram" href="<?php echo esc_url($custom_data['wd_instagram_url']); ?>" target="_blank"><i class="fa fa-instagram"></i><span>Instagram</span></a><?php } ?>
	
	<?php if($custom_data['wd_vimeo_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_vimeo" href="<?php echo esc_url($custom_data['wd_vimeo_url']); ?>" target="_blank"><i class="fa fa-vimeo-square"></i><span>Vimeo</span></a><?php } ?>
	
	<?php if($custom_data['wd_skype_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_skype" href="<?php echo esc_url($custom_data['wd_skype_url']); ?>" target="_blank"><i class="fa fa-skype"></i><span>Skype</span></a><?php } ?>
	
	<?php if($custom_data['wd_youtube_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_youtube" href="<?php echo esc_url($custom_data['wd_youtube_url']); ?>" target="_blank"><i class="fa fa-youtube"></i><span>Youtube</span></a><?php } ?>
	
	<?php if($custom_data['wd_tumblr_btn']==1){ ?><a class="wd_btn wd_btn_icon wd_btn_tumblr" href="<?php echo esc_url($custom_data['wd_tumblr_url']); ?>" target="_blank"><i class="fa fa-tumblr"></i><span>Tumblr</span></a><?php } ?>
	
</div>