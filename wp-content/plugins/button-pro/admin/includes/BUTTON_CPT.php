<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
register_post_type( 'wd_button',array('labels' => array(
	'name' => __('Button Pro','button-pro'),
	'add_new' => __('Add New Button', 'button-pro'),
	'add_new_item' => __('Add New Button','button-pro'),
	'edit_item' => __('Add New Button','button-pro'),
	'new_item' => __('New Link','button-pro'),
	'all_items' => __('All Buttons','button-pro'),
	'view_item' => __('View Link','button-pro'),
	'search_items' => __('Search Links','button-pro'),
	'not_found' =>  __('No Links found','button-pro'),
	'not_found_in_trash' => __('No Links found in Trash','button-pro')),
'supports' => array('title'),
'hierarchical' => false,
'show_in' => true,
'show_ui' => true,
'show_in_nav_menus' => false,
'show_in_menu' => true,
'publicly_queryable' => true,
'exclude_from_search' => true,
'has_archive' => true,
'query_var' => true,
'can_export' => true,
'rewrite' =>true,
'menu_position' => 67,		
'public' => true,
'capability_type' => 'post',
'menu_icon' =>Button_URL.'images/button_cpt_icon.png'));

add_filter( 'manage_edit-wd_button_columns', array(&$this, 'wd_button_columns' )) ;
add_action( 'manage_wd_button_posts_custom_column', array(&$this, 'wd_button_manage_columns' ), 10, 2 );

?>