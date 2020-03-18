<?php 

/*
 * Function for post duplication. Dups appear as drafts. User is redirected to the edit screen
 */
function WDButton_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'WDButton_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}

	/*
	 * Nonce verification
	 */
	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
		return;

	/*
	 * get the original post id
	 */
	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	
	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );

	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;

	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $post ) && $post != null) { 		

		/*
		 * new post data array
		 */
		$args = array(
			
			'post_type'      => 'wd_button',
			'post_status'    => 'draft',
			'post_title'     => ' Copy of - '.$post->post_title,
			
			); 

		/*
		 * insert the post by wp_insert_post() function
		 */
		$new_post_id = wp_insert_post( $args );

		

		$button_data = unserialize(get_post_meta($post_id,'button_custom_setting_'.$post_id, true));
			
		$field_names = WDButton_setting_field_name();			

		foreach($field_names as $name){

			$btn_array[$name] = $button_data[$name];

		}		

		$btn_array = serialize($btn_array);			
		
		update_post_meta($new_post_id, 'button_custom_setting_'.$new_post_id, $btn_array);

		/*
		 * finally, redirect to the edit post screen for the new draft
		 */
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		
		exit;

	} else {
		
		wp_die('Post creation failed, could not find original post: ' . $post_id);

	}
}
add_action( 'admin_action_WDButton_duplicate_post_as_draft', 'WDButton_duplicate_post_as_draft' );

/*
 * Add the duplicate link to action list for post_row_actions
 */
function WDButton_duplicate_post_link( $actions, $post ) {
	if (current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=WDButton_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
	}
	return $actions;
}

add_filter( 'post_row_actions', 'WDButton_duplicate_post_link', 10, 2 );


function WDButton_setting_field_name(){
	return array(
		'button_layout',
		'button_text',
		'button_link',
		'attribute_id',
		'attribute_value',
		'button_icon',
		'button_icon_size',
		'2d_transitions',

		/**border transitions***/
		'border_transitions',		
		'border_transitions_width',
		'border_transitions_color',
		/*****/
		'curl',
		
		'speech_bubbles',
		'speech_bubbles_color',

		'background_transitions',

		'icon_simple',
		'icon_simple_effect',

		'icon_with_text',

		'icon_hexagons',
		'hexagons_effect',

		
		/*button sets*/
		
		'wd_fb_btn',
		'wd_twitter_btn',
		'wd_google_btn',
		'wd_pinterest_btn',
		'wd_linkedin_btn',
		'wd_instagram_btn',
		'wd_vimeo_btn',
		'wd_skype_btn',
		'wd_youtube_btn',
		'wd_tumblr_btn',

		'wd_fb_url',
		'wd_twitter_url',
		'wd_google_url',
		'wd_pinterest_url',
		'wd_linkedin_url',
		'wd_instagram_url',
		'wd_vimeo_url',
		'wd_skype_url',
		'wd_youtube_url',
		'wd_tumblr_url',

		'button_target',
		'padding_top',
		'padding_right',
		'padding_bottom',
		'padding_left',
		'button_width',
		'button_height',
		'button_text_color',
		'button_text_hover_color',
		'font_family',
		'font_size',
		'text_bold',
		'text_italic',
		'text_align',
		'button_circle',
		'border_top_left',
		'border_top_right',
		'border_bottom_left',
		'border_bottom_right',
		'border_style',
		'border_width',
		'border_color',
		'border_hover_color',
		'border_shadow_color',
		'border_shadow_hover_color',
		'border_shadow_offset_left',
		'border_shadow_offset_top',
		'border_shadow_blur',
		'button_bg_color_start',
		'button_bg_color_end',
		'button_bg_hover_color_start',
		'button_bg_hover_color_end',
		'opacity_start',
		'opacity_end',
		'hover_opacity_start',
		'hover_opacity_end',
		'gradient_stop',
		'container_use',
		'container_center',
		'container_width',
		'button_align',
		'margin_top',
		'margin_right',
		'margin_bottom',
		'margin_left',
		'shadow_offset_left',
		'shadow_offset_top',
		'shadow_blur',
		'shadow_color',
		'shadow_hover_color',
		'custom_css'
		);
}
?>