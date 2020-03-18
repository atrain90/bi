<?php
/*
* Plugin Name: Remove Footer Credit
* Version: 1.0.3
* Description: A simple plugin to remove footer credits
* Author: Macho Themes
* Author URI: https://www.machothemes.com/
* License: GPLv3 or later
* Text Domain: remove-footer-credit
*/

/*
Copyright 2018 Macho Themes - office [at] machothemes.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RFC_Plugin {

	private $tabs;
	private $options;
	private $assets_path;
	
	function __construct() {

		$this->tabs = array(
			'settings' => array(
				'label' => esc_html__( 'Settings', 'remove-footer-credit' ),
				'path' => 'settings.php',
			),
			'plugins' => array(
				'label' => esc_html__( 'Other Great Plugins', 'remove-footer-credit' ),
				'path' => 'plugins.php',
			),
			'help' => array(
				'label' => esc_html__( 'Get Help', 'remove-footer-credit' ),
				'path' => 'plugin-info.php',
			),
		);

		$this->options = get_option( 'jabrfc_text' );
		$this->assets_path = plugin_dir_url( __FILE__ ) . 'assets/';

		$this->public_hooks();
		$this->admin_hooks();

	}

	private function public_hooks() {

		add_filter( 'the_content', array( $this, 'jabrfc_the_content' ) );

		//Handles find and replace for public pages
		add_action( 'template_redirect', array( $this, 'jabrfc_template_redirect' ) );

	}

	private function admin_hooks() {

		if ( ! is_admin() ) {
			return;
		}

		// Add style
		add_action( 'admin_enqueue_scripts', array( $this, 'jabrfc_admin_enqueue_scripts' ) );

		//Add left menu item in admin
		add_action( 'admin_menu', array( $this, 'jabrfc_admin_menu' ) );

	}

	private function generate_url( $tab = 'settings' ){
		return admin_url( 'tools.php?page=remove-footer-credit&tab=' . $tab );
	}

	public function jabrfc_the_content( $content ) {
		global $post;
		$data = get_option( 'jabrfc_text' );
		if ( $data['willLinkback'] == 'yes' && is_singular() && $data['linkbackPostId'] == $post->ID ) {
			$content = $content . 'Get WordPress help, plugins, themes and tips at <a href="https://www.machothemes.com?utm_source=remove-footer-credit&utm_medium=front&utm_campaign=credit-link">MachoThemes.com</a>';
		}
		return $content;
	}

	/*
	* Add a submenu under Tools
	*/
	public function jabrfc_admin_menu() {
		add_submenu_page( 'tools.php', esc_html__('Remove Footer Credit', 'remove-footer-credit'), esc_html__('Remove Footer Credit', 'remove-footer-credit'), 'activate_plugins', 'remove-footer-credit', array( $this, 'jabrfc_options_page' ) );
	}

	public function jabrfc_admin_enqueue_scripts( $hook ) {

		if ( 'tools_page_remove-footer-credit' != $hook ) {
			return;
		}

		wp_enqueue_style( 'jabrfc-styles', $this->assets_path . 'css/admin.css' );
		wp_enqueue_script( 'jabrfc-plugin-install', $this->assets_path . 'js/plugin-install.js', array( 'jquery', 'updates' ), '1.0.0', 'all' );

	}

	public function jabrfc_template_redirect() {
		ob_start();
		ob_start( 'jabrfc_ob_call' );
	}

	public function jabrfc_options_page() {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$_POST = stripslashes_deep( $_POST );

			$data = array(
				'find'  => explode("\n", str_replace("\r", "", $_POST['find'])),
				'replace'  => explode("\n", str_replace("\r", "", $_POST['replace'])),
				'willLinkback' => $_POST['willLinkback'],
				'linkbackPostId' => $_POST['linkbackPostId']
			);

			update_option( 'jabrfc_text', $data );
			$this->options = $data;

			echo '<div id="message" class="updated fade">';
				echo '<p><strong>' . esc_html__( 'Settings Saved', 'remove-footer-credit' ) . '</strong></p>';
			echo '</div>';
		}

		$current_tab = 'settings';
		if ( isset($_GET['tab']) && isset( $this->tabs[ $_GET['tab'] ] ) ) {
			$current_tab = $_GET['tab'];
		}

		echo '<div class="wrap about-wrap epsilon-wrap">';
		echo '<h1>' . esc_html__( 'Remove Footer Credit', 'remove-footer-credit' ) . '</h1>';
		echo '<div class="about-text">';
				/* Translators: Welcome Screen Description. */
				echo esc_html__( 'Remove or replace footer credits (or any text or HTML in page) before page is rendered. With this plugin there is no need to modify code such as footer.php which if done incorrectly can cause your site to break or new theme updates will stomp over your changes requiring you to remove footer credits on each update.', 'remove-footer-credit' );
			echo '</div>';
		echo '<div class="wp-badge remove-footer-credit"><span class="dashicons dashicons-editor-unlink"></span></div>';
		echo '<h2 class="nav-tab-wrapper wp-clearfix">';
		foreach ( $this->tabs as $tab_id => $tab ) {
			echo '<a class="nav-tab ' . ( $tab_id === $current_tab ? 'nav-tab-active' : '' ) . '" href="' . esc_url( $this->generate_url( $tab_id ) ) . '">' . esc_html( $tab['label'] ) . '</a>';
		}
		echo '</h2>';
		include 'sections/' . $this->tabs[ $current_tab ]['path'];
		echo '</div>';

	}

}

new RFC_Plugin();




/*
* Apply find and replace rules
*/
function jabrfc_ob_call( $buffer ) { // $buffer contains entire page

	$data = get_option( 'jabrfc_text' );

	if ( is_array( $data['find']) ) {
		$i = 0;
		foreach ( $data['find'] as &$value ) {
			$buffer = str_replace( $value, (array_key_exists($i, $data['replace']) ? $data['replace'][$i] : ''), $buffer );
			$i++;
		}
	}
	return $buffer;
}







