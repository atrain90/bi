<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="container btn_sets_settings">
	<h2 class="home_title"><?php _e( 'Button Sets Settings', 'button-pro' ); ?></h2>
	<div class="input_group">
		<div class="input_label">
			<label><?php _e( 'Facebook', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_fb_btn" value="1" <?php if($custom_data['wd_fb_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show Facebook Button.', 'button-pro' ); ?></label>						
		</div>
		<div class="input_field">
			<input type="text" name="wd_fb_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_fb_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group">
		<div class="input_label">
			<label><?php _e( 'Twitter', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_twitter_btn" value="1" <?php if($custom_data['wd_twitter_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show Twitter Button.', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="text" name="wd_twitter_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_twitter_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group">
		<div class="input_label">
			<label><?php _e( 'Google Plus', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_google_btn" value="1" <?php if($custom_data['wd_google_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show Goople Plus Button.', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="text" name="wd_google_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_google_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group">
		<div class="input_label">
			<label><?php _e( 'Pinterest', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_pinterest_btn" value="1" <?php if($custom_data['wd_pinterest_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show Pinterest Button.', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="text" name="wd_pinterest_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_pinterest_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group">
		<div class="input_label">
			<label><?php _e( 'LinkedIn', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_linkedin_btn" value="1" <?php if($custom_data['wd_linkedin_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show LinkedIn Button.', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="text" name="wd_linkedin_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_linkedin_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group">
		<div class="input_label">
			<label><?php _e( 'Instagram', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_instagram_btn" value="1" <?php if($custom_data['wd_instagram_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show Instagram Button.', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="text" name="wd_instagram_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_instagram_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group">
		<div class="input_label">
			<label><?php _e( 'Vimeo', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_vimeo_btn" value="1" <?php if($custom_data['wd_vimeo_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show Vimeo Button.', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="text" name="wd_vimeo_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_vimeo_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group">
		<div class="input_label">
			<label><?php _e( 'Skype', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_skype_btn" value="1" <?php if($custom_data['wd_skype_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show Skype Button.', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="text" name="wd_skype_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_skype_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group">
		<div class="input_label">
			<label><?php _e( 'Youtube', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_youtube_btn" value="1" <?php if($custom_data['wd_youtube_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show Youtube Button.', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="text" name="wd_youtube_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_youtube_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group border_none">
		<div class="input_label">
			<label><?php _e( 'Tumblr', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="wd_tumblr_btn" value="1" <?php if($custom_data['wd_tumblr_btn']==1){echo "checked";} ?>>
			<label><?php _e( 'Show Tumblr Button.', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="text" name="wd_tumblr_url" class="input_box social_url" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['wd_tumblr_url']); ?>" placeholder="http://">
		</div>
		<div class="clear_fix"></div> 
	</div>

</div>