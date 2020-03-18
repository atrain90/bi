<?php
/*
Plugin Name: User Access Shortcodes
Plugin URI: https://wpdarko.zendesk.com/hc/en-us/articles/206303637-Get-started-with-the-User-Access-Shortcodes-plugin
Description: "The most simple way of controlling who sees what in your posts/pages". This plugin adds a button to your post editor, allowing you to restrict content to logged in users only (or guests) with a simple shortcode. Find help and information on our <a href="http://wpdarko.com/support/">support site</a>.
Version: 2.1.1
Author: WP Darko
Author URI: http://wpdarko.com
License: GPL2
 */

add_action( 'admin_head', 'uasc_css' );

function uasc_css()
{
    $uasc = plugins_url( 'img/uasc-icon.png', __FILE__ );
    echo '
    <style>
        i.mce-i-uasc-mce-icon {
	       background-image: url("'.$uasc.'");
        }
    </style>
    ';
}

// Hooks your functions into the correct filters
function uasc_add_mce_button() {
	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'uasc_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'uasc_register_mce_button' );
	}
}
add_action('admin_head', 'uasc_add_mce_button');

// Declare script for new button
function uasc_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['uasc_mce_button'] = plugins_url('/js/uasc-mce-button.js', __FILE__);
	return $plugin_array;
}

// Register new button in the editor
function uasc_register_mce_button( $buttons ) {
	array_push( $buttons, 'uasc_mce_button' );
	return $buttons;
}

add_shortcode( 'UAS_guest', 'uasc_guest_sc' );

function uasc_guest_sc( $atts, $content = null ) {
    extract( shortcode_atts( array(
               'in' => '',
               'admin' => '',
    ), $atts ) );

    $in = str_replace(' ', '', $in);
    $includeds = explode(",", $in);

    $user_id = get_current_user_id();

    //check if user is logged in
    if ( is_user_logged_in() ) {
        //check if admin is allowed
        if ($admin == '1') {
            //loop through included user ids
            foreach ($includeds as $included) {
                //check if user is included
                if ($user_id == $included) {
                    return do_shortcode($content);
                }
            }
            //check if user is admin
            if ( current_user_can('administrator') ) {
                return do_shortcode($content);
            } else {
                return '';
            }
        } else {
            //loop though included user ids
            foreach ($includeds as $included) {
                //check if user is included
                if ($user_id == $included) {
                    return do_shortcode($content);
                }
            }
            return '';
        }
    //show content to guests
    } else {
        return do_shortcode($content);
    }
}

add_shortcode( 'UAS_loggedin', 'uasc_loggedin_sc' );

function uasc_loggedin_sc( $atts, $content = null ) {
    extract( shortcode_atts( array(
               'ex' => '',
    ), $atts ) );

    $ex = str_replace(' ', '', $ex);
    $excludeds = explode(",", $ex);

    $user_id = get_current_user_id();

    //check if user is logged in
    if ( is_user_logged_in() ) {
        //loop through excluded user ids
        foreach ($excludeds as $excluded) {
            //check if user is excluded
            if ($user_id == $excluded) {
                //show nothing
                return '';
            }
        }
        //show content to logged in users
        return do_shortcode($content);
    //hide content to guests
    } else {
        return '';
    }
}

add_shortcode( 'UAS_specific', 'uasc_specific_sc' );

function uasc_specific_sc( $atts, $content = null ) {
    extract( shortcode_atts( array(
            'admin' => '',
            'ids' => '',
    ), $atts ) );

    $ids = str_replace(' ', '', $ids);
    $selecteds = explode(",", $ids);

    $user_id = get_current_user_id();

    //check if user is logged in
    if ( is_user_logged_in() ) {
        if ($admin == '1') {
            //loop through selected user ids
            foreach ($selecteds as $selected) {
                //check if user is selected
                if ($user_id == $selected) {
                    return do_shortcode($content);
                }
            }
            //check if user is admin
            if ( current_user_can('administrator') ) {
                return do_shortcode($content);
            } else {
                return '';
            }
        }
        //loop through selected user ids
        foreach ($selecteds as $selected) {
            //check if user is selected
            if ($user_id == $selected) {
                return do_shortcode($content);
            }
        }
        //hide content to non-selected users
        return '';
    //hide content to guests
    } else {
        return '';
    }
}
?>
