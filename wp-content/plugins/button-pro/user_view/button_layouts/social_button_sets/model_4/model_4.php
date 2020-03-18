<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
wp_enqueue_style('wd_font_awesome');
wp_enqueue_style('social_model_default');
wp_enqueue_style('model_4_style');
?>
<div class="btn_model_container">
  <ul class="wd_social_nav btn_model_4">
    <?php if($custom_data['wd_fb_btn']==1){ ?> <li><a href="<?php echo esc_url($custom_data['wd_fb_url']); ?>" target="_blank" class="facebook"> <i class="fa fa-facebook"></i></a></li><?php } ?>
    <?php if($custom_data['wd_twitter_btn']==1){ ?> <li><a href="<?php echo esc_url($custom_data['wd_twitter_url']); ?>" target="_blank" class="twitter"><i class="fa fa-twitter"></i></a></li>  <?php } ?>
    <?php if($custom_data['wd_google_btn']==1){ ?><li><a href="<?php echo esc_url($custom_data['wd_google_url']); ?>" target="_blank" class="google-plus"><i class="fa fa-google-plus"></i></a></li><?php } ?>
    <?php if($custom_data['wd_pinterest_btn']==1){ ?> <li><a href="<?php echo esc_url($custom_data['wd_pinterest_url']); ?>" target="_blank" class="pinterest"><i class="fa fa-pinterest"></i></a></li><?php } ?>
    <?php if($custom_data['wd_linkedin_btn']==1){ ?><li><a href="<?php echo esc_url($custom_data['wd_linkedin_url']); ?>" target="_blank" class="linkedin"><i class="fa fa-linkedin"></i></a></li><?php } ?>
    <?php if($custom_data['wd_instagram_btn']==1){ ?> <li><a href="<?php echo esc_url($custom_data['wd_instagram_url']); ?>" target="_blank" class="instagram"><i class="fa fa-instagram"></i></a></li><?php } ?>
    <?php if($custom_data['wd_vimeo_btn']==1){ ?><li><a href="<?php echo esc_url($custom_data['wd_vimeo_url']); ?>" target="_blank" class="vimeo"><i class="fa fa-vimeo-square"></i></a></li><?php } ?>
    <?php if($custom_data['wd_skype_btn']==1){ ?><li><a href="<?php echo esc_url($custom_data['wd_skype_url']); ?>" target="_blank" class="skype"><i class="fa fa-skype"></i></a></li><?php } ?>
    <?php if($custom_data['wd_youtube_btn']==1){ ?>  <li><a href="<?php echo esc_url($custom_data['wd_youtube_url']); ?>" target="_blank" class="youtube"><i class="fa fa-youtube"></i></a></li><?php } ?>
    <?php if($custom_data['wd_tumblr_btn']==1){ ?><li><a href="<?php echo esc_url($custom_data['wd_tumblr_url']); ?>" target="_blank" class="tumblr"><i class="fa fa-tumblr"></i></a></li><?php } ?>

  </ul>
</div>

