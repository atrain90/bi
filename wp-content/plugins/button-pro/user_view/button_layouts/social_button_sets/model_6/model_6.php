<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
wp_enqueue_style('wd_font_awesome');
wp_enqueue_style('social_model_default');
wp_enqueue_style('model_6_style');
?>
 <div class="btn_model_container">
  <ul class="wd_social_nav btn_model_6">
     <?php if($custom_data['wd_fb_btn']==1){ ?> <li>
      <a href="<?php echo esc_url($custom_data['wd_fb_url']); ?>" class="facebook"> 
        <div class="fornt"><i class="fa fa-facebook"></i></div>
        <div class="back_btn"><i class="fa fa-facebook"></i></div>
      </a>
    </li>
    <?php } ?>
    <?php if($custom_data['wd_twitter_btn']==1){ ?><li>
      <a href="<?php echo esc_url($custom_data['wd_twitter_url']); ?>" class="twitter">
        <div class="front_btn"><i class="fa fa-twitter"></i></div>
        <div class="back_btn"><i class="fa fa-twitter"></i></div>
      </a>
    </li>  
    <?php } ?>  
   <?php if($custom_data['wd_google_btn']==1){ ?> <li>
      <a href="<?php echo esc_url($custom_data['wd_google_url']); ?>" class="pinterest" class="google-plus">
        <div class="front_btn"><i class="fa fa-google-plus"></i></div>
        <div class="back_btn"><i class="fa fa-google-plus"></i></div>
      </a>
    </li>
    <?php } ?>
    <?php if($custom_data['wd_pinterest_btn']==1){ ?><li>
      <a href="<?php echo esc_url($custom_data['wd_pinterest_url']); ?>" class="pinterest">
        <div class="front_btn"><i class="fa fa-pinterest"></i></div>
        <div class="back_btn"><i class="fa fa-pinterest"></i></div>
      </a>
    </li>
    <?php } ?>
   <?php if($custom_data['wd_linkedin_btn']==1){ ?> <li>
      <a href="<?php echo esc_url($custom_data['wd_linkedin_url']); ?>" class="linkedin">
        <div class="front_btn"><i class="fa fa-linkedin"></i></div>
        <div class="back_btn"><i class="fa fa-linkedin"></i></div>
      </a>
    </li>
    <?php } ?>
    <?php if($custom_data['wd_instagram_btn']==1){ ?><li>
      <a href="<?php echo esc_url($custom_data['wd_instagram_url']); ?>" class="instagram">
        <div class="front_btn"><i class="fa fa-instagram"></i></div>
        <div class="back_btn"><i class="fa fa-instagram"></i></div>
      </a>
    </li>
    <?php } ?>
   <?php if($custom_data['wd_vimeo_btn']==1){ ?> <li>
      <a href="<?php echo esc_url($custom_data['wd_vimeo_url']); ?>" class="vimeo">
        <div class="front_btn"><i class="fa fa-vimeo-square"></i></div>
        <div class="back_btn"><i class="fa fa-vimeo-square"></i></div>
      </a>
    </li>
    <?php } ?>
    <?php if($custom_data['wd_skype_btn']==1){ ?><li>
      <a href="<?php echo esc_url($custom_data['wd_skype_url']); ?>" class="skype">
        <div class="front_btn"><i class="fa fa-skype"></i></div>
        <div class="back_btn"><i class="fa fa-skype"></i></div>
      </a>
    </li>
    <?php } ?>
    <?php if($custom_data['wd_youtube_btn']==1){ ?><li>
      <a href="<?php echo esc_url($custom_data['wd_youtube_url']); ?>" class="youtube">
        <div class="front_btn"><i class="fa fa-youtube"></i></div>
        <div class="back_btn"><i class="fa fa-youtube"></i></div>
      </a>
    </li>
    <?php } ?>
   <?php if($custom_data['wd_tumblr_btn']==1){ ?> <li>
      <a href="<?php echo esc_url($custom_data['wd_tumblr_url']); ?>" class="tumblr">
        <div class="front_btn"><i class="fa fa-tumblr"></i></div>
        <div class="back_btn"><i class="fa fa-tumblr"></i></div>
      </a>
    </li>
    <?php } ?>

  </ul>

</div>

