<?php


/**
 * Weblator Polling
 *
 * @package   Weblator_Polling_Admin
 * @author    Weblator <daniel.prior@webaltor.com>
 * @license   GPL-2.0+
 * @link      http://weblator.com
 * @copyright 2014 Weblator
 */

class Weblator_Polling_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = "toplevel_page_polls";

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$plugin = Weblator_Polling::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action('admin_head', array( $this, 'add_editor_button') );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );



	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

        wp_enqueue_style( $this->plugin_slug .'-editor', plugins_url( 'assets/css/editor.css', __FILE__ ), array(), Weblator_Polling::VERSION );

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}


		$screen = get_current_screen();

		if ( $this->plugin_screen_hook_suffix == $screen->id || (isset($_GET["page"]) && $_GET["page"] == "poll-options") ) {

			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Weblator_Polling::VERSION );
            wp_enqueue_style( $this->plugin_slug .'-admin-styles-icons', plugins_url( 'assets/css/weblator.css', __FILE__ ), array(), Weblator_Polling::VERSION );
            wp_enqueue_style( $this->plugin_slug .'-colourpicker', plugins_url( 'assets/css/spectrum.css', __FILE__ ), array(), Weblator_Polling::VERSION );

            if (get_bloginfo("version") < 3.8){

                wp_enqueue_style( $this->plugin_slug .'-less-than-38', plugins_url( 'assets/css/less_38.css', __FILE__ ), array(), Weblator_Polling::VERSION );
            }

        }

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id || (isset($_GET["page"]) && $_GET["page"] == "poll-options") ) {

            wp_enqueue_script( 'handlebars', plugins_url( 'assets/js/handlebars-v1.3.0.js', __FILE__ ), array( 'jquery' ), Weblator_Polling::VERSION );
            wp_enqueue_script( 'spectrum', plugins_url( 'assets/js/spectrum.js', __FILE__ ), array( 'jquery' ), Weblator_Polling::VERSION );
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery', 'handlebars', "jquery-ui-sortable" ), Weblator_Polling::VERSION, 1 );
            wp_enqueue_script( $this->plugin_slug . '-admin-options-script', plugins_url( 'assets/js/poll-options.js', __FILE__ ), array( 'jquery' ), Weblator_Polling::VERSION, 1 );

        }

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

        if (get_bloginfo("version") < 3.8){

            add_menu_page( 'Polls', 'Polls', 'manage_options', 'polls', array($this, "display_plugin_admin_page"), plugins_url("../assets/poll.png", __FILE__) );

			add_submenu_page( "polls", "Polls", "Polls", "manage_options", "polls", array($this, "display_plugin_admin_page") );
			add_submenu_page( "polls", "Poll Messages", "Poll Messages", "manage_options", "poll-options", array($this, "display_plugin_settings_page") );
        }else{

			add_menu_page( 'Polls', 'Polls', 'manage_options', 'polls', array($this, "display_plugin_admin_page"), "dashicons-chart-line" );
			add_submenu_page( "polls", "Polls", "Polls", "manage_options", "polls", array($this, "display_plugin_admin_page") );
			add_submenu_page( "polls", "Poll Messages", "Poll Messages", "manage_options", "poll-options", array($this, "display_plugin_settings_page") );
		}



	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {

        global $wpdb;

        if(!class_exists('WP_List_Table')){
            require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
        }

        if (! isset($_GET["action"])){

            require_once("class-weblator-tables.php");
            include_once( 'views/admin.php' );
            return;
        }




        if ($_GET["page"] == "polls"){



            switch($_GET["action"]){

				case "delete":
					$this->delete_poll();
                case "edit":
                    $this->display_plugin_add_page();
                break;
                case "add":
                    require_once("views/new.php");
                break;


            }

        }

	}

	private function delete_poll()
	{
		if(intval($_GET["delete_poll"])>0 && isset($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], "weblator_poll_delete_".intval($_GET["delete_poll"])))
		{
			global $wpdb;

	        if (!is_numeric($_GET["delete_poll"]))
			{
	            echo 0;
	            die();
	        }

	        $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}weblator_polls SET `poll_deleted_date` = NOW() WHERE `id` = %d", intval($_GET["delete_poll"])) );
		}

		require_once("class-weblator-tables.php");
		include_once( 'views/admin.php' );
		return;
	}

    public function display_plugin_add_page(){

        global $wpdb;

        if (!is_numeric($_GET["edit_poll"] )){
            wp_redirect( admin_url() . 'admin.php?page=polls', 301 );
            exit();
        }

        $poll_id = $_GET["edit_poll"];

        $poll = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}weblator_polls WHERE id = %d", $poll_id ));

        if (!is_null($poll->poll_deleted_date)){
            wp_redirect( admin_url() . 'admin.php?page=polls', 301 );
            exit();
        }

        $options = $wpdb->get_results($wpdb->prepare( "SELECT *, (SELECT count(*) FROM {$wpdb->prefix}weblator_poll_votes WHERE option_id = {$wpdb->prefix}weblator_poll_options.id) as votes FROM {$wpdb->prefix}weblator_poll_options WHERE poll_id = %d AND option_deleted_date IS NULL ORDER BY option_order ASC", $poll_id ), ARRAY_A);
        $total_votes = array_sum(array_column($options, 'votes'));
        require_once("views/edit.php");

    }

	public function display_plugin_settings_page() {

		require_once("views/settings.php");
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

    public function add_editor_button() {

        global $typenow;
            // check user permissions
        if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
            return;
        }

        // check if WYSIWYG is enabled
        if ( get_user_option('rich_editing') == 'true') {
            add_filter("mce_external_plugins", array( $this, "rtb_add_tinymce_plugin"));
            add_filter('mce_buttons', array( $this, 'rtb_register_my_tc_button') );
        }
    }

    public function rtb_add_tinymce_plugin($plugin_array) {

        $plugin_array['rtb_button'] = plugins_url( 'assets/js/editor-button.js', __FILE__ );
        return $plugin_array;

    }

    public function rtb_register_my_tc_button($buttons) {
        array_push($buttons, "rtb_button");
        return $buttons;
    }

	public function get_global_text_option($label) {

		global $wpdb;

		return $this->prepare_data_for_output($wpdb->get_var("SELECT text_value FROM {$wpdb->prefix}weblator_text_options WHERE poll_id = 0 AND deleted_date IS NULL AND text_field = '{$label}'"));

	}

	public function get_single_text_option($poll_id, $label) {

		global $wpdb;

		return $this->prepare_data_for_output($wpdb->get_var($wpdb->prepare("SELECT text_value FROM {$wpdb->prefix}weblator_text_options WHERE poll_id = %d AND deleted_date IS NULL AND text_field = '%s'", $poll_id, $label)));


	}

	public function prepare_data_for_output($string = "")
	{
		return htmlentities(stripslashes(strip_tags($string)));
	}
}
