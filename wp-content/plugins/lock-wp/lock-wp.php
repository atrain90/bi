<?php
/*
Plugin Name: Lock Wordpress
Version: 1.0.0
Plugin URI: http://redblink.com/
Author: Redblink
Author URI: http://redblink.com/
Description: Lock and Unlock the wordpress site
Text Domain: lock-wp
Domain Path: /languages
*/

if ( ! function_exists( 'lockwp_admin_default_setup' ) ) {
	function lockwp_admin_default_setup() {		
                add_options_page(__('Lock WP', 'lock-wp'), __('Lock WP', 'lock-wp'), 'manage_options', 'lockwp_settings', 'lockwp_settings');
	}
}

if ( ! function_exists( 'lockwp_settings' ) ) {
	function lockwp_settings() { 

			if ( isset( $_POST['lockwp_submit'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'lockwp_nonce_name' ) ) {	
				if(isset($_POST['lockwp']) && !empty($_POST['lockwp'])){
					$action = $_POST['lockwp'];
					$response = lock_unlock_wordpress($action);
					 if($response){
					 	update_option( 'wp_status', $action );
					 	$message .= __( "Website ".$response." successfully.", 'lock-wp' );
					 }
					
				}else{
					$error .= " " . __( "Please select an option.", 'lock-wp' );
				}
			}else{
				// Check if the website is locked or not
				$site_status = substr(sprintf('%o', fileperms(get_home_path().'wp-config.php')), -4);
				if ($site_status == '0444'){
					$message .= __( "Website is already locked ", 'lock-wp' );
				}
				else{
					$error .= " " . __( "Website is unlocked. Lock it once you have done your changes on the website", 'lock-wp' );
				}
			}



		?>

    <h2><?php _e( "Lock WP setting", 'easy-wp-smtp' ); ?></h2>

    	<div class="updated fade" <?php if( empty( $message ) ) echo "style=\"display:none\""; ?>>
				<p><strong><?php echo $message; ?></strong></p>
		</div>

        <div class="error" <?php if ( empty( $error ) ) echo "style=\"display:none\""; ?>>
				<p><strong><?php echo $error; ?></strong></p>
		</div>
		<br/>
		<form id="lockwp_settings_form" method="post" action="">					
				
				<label class="radio-inline">
					<input type="radio" name="lockwp" value="lock">Lock
				</label>
				<label class="radio-inline">
					<input type="radio" name="lockwp" value="unlock">Unlock
				</label>
				<p class="submit">
					<input type="submit" id="settings-form-submit" class="button-primary" value="<?php _e( 'Submit', '' ) ?>" />
					<input type="hidden" name="lockwp_submit" value="submit" />
					<?php wp_nonce_field( plugin_basename( __FILE__ ), 'lockwp_nonce_name' ); ?>
				</p>				
		</form> 
		<?php
	}
}

if ( ! function_exists( 'lock_unlock_wordpress' ) ) {
	function lock_unlock_wordpress($action){
	   
	   	$filereadpermission  = 'find '.get_home_path().' -type f -exec chmod 444 {} \; && find '.get_home_path().' -type d -exec chmod 555 {} \;';
		$filewritepermission  = 'find '.get_home_path().' -type f -exec chmod 644 {} \; && find '.get_home_path().' -type d -exec chmod 755 {} \;';
		$uploadpermission = 'find '.get_home_path().'wp-content/uploads -type f -exec chmod 644 {} \; && find '.get_home_path().'wp-content/uploads -type d -exec chmod 775 {} \;';

		if($action == 'lock'){
			exec($filereadpermission);
			exec($uploadpermission);
            return 'locked';
		}
		else if($action == 'unlock'){
			exec($filewritepermission);
			exec($uploadpermission);
			return 'unlocked';

		}
	}
}

add_action( 'admin_menu', 'lockwp_admin_default_setup' );
