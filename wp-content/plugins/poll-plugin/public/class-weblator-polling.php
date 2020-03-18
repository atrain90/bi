<?php
/**
 * Weblator Polling.
 *
 * @package   Weblator_Polling
 * @author    Weblator <daniel.prior@weblator.com>
 * @license   GPL-2.0+
 * @link      http://weblator.com
 * @copyright 2015 Weblator
 */


class Weblator_Polling {

	const VERSION = '1.7.8';

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'weblator_polling';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

    add_shortcode("poll", array( $this, 'display_poll'));
		add_shortcode("weblator_poll", array( $this, 'display_poll'));
		add_shortcode("poll_results", array( $this, 'display_poll_results'));

    add_action('wp_head', array($this, 'browser_stylesheets'));


	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	public static function single_deactivate(){

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {

        global $wpdb;

        $wpdb->query(
            "
                CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}weblator_chart_type` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `chart_type` varchar(255) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

        $wpdb->query(
            "
        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}weblator_poll_options` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `poll_id` int(11) DEFAULT NULL,
          `option_value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
          `option_order` int(11) DEFAULT NULL,
          `option_colour` varchar(25) CHARACTER SET latin1 DEFAULT 'rgba(151,187,205,0.5)',
          `option_deleted_date` datetime DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

        $wpdb->query(
            "
        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}weblator_poll_votes` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `poll_id` int(11) DEFAULT NULL,
          `option_id` int(11) DEFAULT NULL,
          `user_id` int(11) DEFAULT NULL,
          `ipv4` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
          `date_voted` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

        $wpdb->query(
            "
        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}weblator_polls` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `poll_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `poll_chart_type` int(11) DEFAULT NULL,
          `poll_allow_view_results` tinyint(1) DEFAULT NULL,
          `poll_one_vote` tinyint(1) DEFAULT NULL,
          `poll_is_live` tinyint(1) DEFAULT NULL,
          `poll_chart_width` int(4) DEFAULT '400',
          `poll_chart_height` int(4) DEFAULT '400',
          `poll_created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `poll_deleted_date` datetime DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

        $wpdb->query(
            "
        CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}weblator_style_chart_link` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `chart_id` int(11) DEFAULT NULL,
          `style_id` int(11) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");


        $wpdb->query(
            "
                    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}weblator_style_options` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `style_option` varchar(255) DEFAULT NULL,
              `style_key` varchar(25) DEFAULT NULL,
              `style_label` varchar(255) DEFAULT NULL,
              `style_description` varchar(255) DEFAULT '',
              `style_colorpicker` tinyint(1) DEFAULT '0',
              `style_bool` tinyint(1) DEFAULT '0',
              `style_default` varchar(255) DEFAULT NULL,
              `style_order` int(3) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

        $wpdb->query(
            "
                CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}weblator_style_values` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `poll_id` int(11) DEFAULT NULL,
                  `style_id` int(11) DEFAULT NULL,
                  `style_value` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
            ");

			$wpdb->query(
	            "
	                CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}weblator_text_options` (
	                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	                  `poll_id` int(11) DEFAULT NULL,
	                  `text_field` varchar(255) DEFAULT NULL,
					  `text_value` varchar(255) DEFAULT NULL,
					  `deleted_date` datetime DEFAULT NULL,
	                  PRIMARY KEY (`id`)
	                ) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	            ");


        $id = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}weblator_style_options WHERE id = 1");

        if (!$id){
            $wpdb->query("INSERT INTO `{$wpdb->prefix}weblator_style_options` (`id`, `style_option`, `style_key`, `style_label`, `style_description`, `style_colorpicker`, `style_bool`, `style_default`, `style_order`)
            VALUES
                (1, 'poll_fill_colour', NULL, 'Fill Colour', '', 1, 0, 'rgba(151,187,205,0.5)', 1),
                (2, 'poll_stroke_color', NULL, 'Stroke Colour', '', 1, 0, 'rgba(151,187,205,1)', 2),
                (3, 'poll_point_color', NULL, 'Point Colour', '', 1, 0, 'rgba(151,187,205,1)', 3),
                (4, 'poll_point_stroke_color', NULL, 'Point Stroke Colour', '', 1, 0, 'rgba(255,255,255,1)', 4),
                (5, 'poll_scale_line_color', 'scaleLineColor', 'Scale Line Colour', '', 1, 0, 'rgba(0,0,0, .1)', 5),
                (6, 'poll_scale_line_width', 'scaleLineWidth', 'Scale Line Width', 'Width of the scale line in pixels', 0, 0, '1', 6),
                (7, 'poll_scale_show_labels', 'scaleShowLabels', 'Show Scale Labels', '', 0, 1, '1', 7),
                (8, 'poll_scale_font_size', 'scaleFontSize', 'Scale Label Font Size', '', 0, 0, '12', 8),
                (9, 'poll_scale_font_style', 'scaleFontStyle', 'Scale Label Font Style', '', 0, 0, 'normal', 9),
                (10, 'poll_scale_font_colour', 'scaleFontColor', 'Scale Label Font Colour', '', 1, 0, 'rgb(102, 102, 102)', 10),
                (11, 'poll_scale_show_gridlines', 'scaleShowGridLines', 'Show Scale Grid Lines', 'Whether grid lines are shown across the chart', 0, 1, '1', 11),
                (12, 'poll_scale_gridline_color', 'scaleGridLineColor', 'Scale Grid Line Colour', '', 1, 0, 'rgba(0,0,0, 0.1)', 12),
                (13, 'poll_scale_gridline_width', 'scaleGridLineWidth', 'Scale Grid Line Width', 'Width of the grid lines in pixels', 0, 0, '1', 13),
                (14, 'poll_bar_show_stroke', 'barShowStroke', 'Show Bar Stroke', '', 0, 1, '1', 18),
                (15, 'poll_bar_stroke_width', 'barStrokeWidth', 'Bar Stroke Width', 'The width of the bar stroke in pixels', 0, 0, '2', 19),
                (16, 'poll_bar_value_spacing', 'barValueSpacing', 'Bar Value Spacing', 'Spacing between each of the X value sets in pixels', 0, 0, '5', 20),
                (17, 'poll_bar_dataset_spacing', 'barDatasetSpacing', 'Bar Dataset Spacing', 'Spacing between data sets within X values in pixels', 0, 0, '1', 21),
                (18, 'poll_scale_show_label_backdrop', 'scaleShowLabelBackdrop', 'Show Scale Label Backdrop', '', 0, 1, '1', 14),
                (19, 'poll_scale_backdrop_color', 'scaleBackdropColor', 'Scale Backdrop Colour', '', 1, 0, 'rgba(255,255,255,0.75)', 15),
                (20, 'poll_scale_backdrop_padding_y', 'scaleBackdropPaddingY', 'Scale Backdrop Padding Y', 'The backdrop padding above and below the label in pixels', 0, 0, '2', 16),
                (21, 'poll_scale_backdrop_padding_x', 'scaleBackdropPaddingX', 'Scale Backdrop Padding X', 'The backdrop padding to the side of the label in pixels', 0, 0, '2', 17),
                (23, 'poll_angle_show_line_out', 'angleShowLineOut', 'Show Angle Line Out', 'Whether we show the angle lines out of the radar', 0, 1, '1', 22),
                (24, 'poll_angle_line_color', 'angleLineColor', 'Angle Line Colour', 'Colour of the angle lines', 1, 0, 'rgba(0,0,0,.1)', 23),
                (25, 'poll_angle_line_width', 'angleLineWidth', 'Angle Line Width', 'Angle line width in pixels', 0, 0, '1', 24),
                (26, 'poll_point_label_font_style', 'pointLabelFontStyle', 'Point Label Font Style', '', 0, 0, 'normal', 25),
                (27, 'poll_point_font_size', 'pointLabelFontSize', 'Point Font Size', '', 0, 0, '12', 26),
                (28, 'poll_point_font_color', 'pointLabelFontColor', 'Point Font Colour', '', 1, 0, 'rgb(102, 102, 102)', 27),
                (29, 'poll_point_dot', 'pointDot', 'Point Dot', 'Whether to show a dot for each point', 0, 1, '1', 28),
                (30, 'poll_point_dot_radius', 'pointDotRadius', 'Point Dot Radius', 'Point dot radius in pixels', 0, 0, '3', 29),
                (31, 'poll_point_dot_stroke_width', 'pointDotStrokeWidth', 'Point Dot Stroke Width', 'Point dot stroke width in pixels', 0, 0, '1', 30),
                (32, 'poll_segment_show_state', 'segmentShowStroke', 'Segment Show Stroke', 'Stroke a line around each segment in the chart', 0, 1, '1', 31),
                (33, 'poll_segment_stroke_color', 'segmentStrokeColor', 'Segment Stroke Colour', 'The colour of the stroke around each segment', 1, 0, 'rgba(255,255,255,1)', 32),
                (34, 'poll_segment_stroke_width', 'segmentStrokeWidth', 'Segment Stroke Width', 'The width of the stroke in pixels', 0, 0, '2', 33),
                (35, 'bs_bar_striped', 'bsBarStriped', 'Striped Gradient', 'Apply striped pattern to progress bar', 0, 1, '0', 34);
            ");

            $wpdb->query("INSERT INTO `{$wpdb->prefix}weblator_chart_type` (`id`, `chart_type`)
                VALUES
                    (1, 'Bar Chart'),
                    (2, 'Pie Chart'),
                    (3, 'Doughnut Chart'),
                    (4, 'Line Chart'),
                    (5, 'Radar Chart'),
                    (6, 'Polar Chart'),
                    (7, 'Bootstrap Progress Bars');
                ");

            $wpdb->query("INSERT INTO `{$wpdb->prefix}weblator_style_chart_link` (`id`, `chart_id`, `style_id`)
                    VALUES
						(1, 0, 0),
						(2, 6, 5),
						(3, 5, 5),
						(4, 4, 5),
						(5, 1, 5),
						(6, 6, 6),
						(7, 5, 6),
						(8, 4, 6),
						(9, 1, 6),
						(10, 6, 7),
						(11, 5, 7),
						(12, 4, 7),
						(13, 1, 7),
						(14, 6, 8),
						(15, 5, 8),
						(16, 4, 8),
						(17, 1, 8),
						(18, 6, 9),
						(19, 5, 9),
						(20, 4, 9),
						(21, 1, 10),
						(22, 6, 10),
						(23, 5, 10),
						(24, 4, 10),
						(26, 6, 11),
						(28, 4, 11),
						(29, 1, 11),
						(32, 4, 12),
						(33, 1, 12),
						(36, 4, 13),
						(37, 1, 13),
						(38, 1, 14),
						(39, 1, 15),
						(40, 1, 16),
						(41, 1, 17),
						(42, 6, 18),
						(43, 6, 19),
						(44, 6, 20),
						(45, 6, 21),
						(46, 5, 18),
						(47, 5, 19),
						(48, 5, 20),
						(49, 5, 21),
						(50, 5, 23),
						(51, 5, 24),
						(52, 5, 25),
						(53, 5, 26),
						(54, 5, 27),
						(55, 5, 28),
						(56, 5, 29),
						(57, 5, 30),
						(58, 5, 31),
						(59, 3, 32),
						(60, 3, 33),
						(61, 3, 34),
						(62, 2, 32),
						(63, 2, 33),
						(64, 2, 34),
						(65, 6, 32),
						(66, 6, 33),
						(67, 6, 34),
						(68, 1, 1),
						(69, 1, 2),
						(70, 4, 1),
						(71, 4, 2),
						(72, 4, 3),
						(73, 4, 4),
						(74, 5, 1),
						(75, 5, 2),
						(76, 5, 3),
						(77, 5, 4),
						(78, 7, 35),
						(79, 1, 9);
						");

        }

        self::update110();
        self::update120();
        self::update130();
        self::update140();
		self::update150();
		self::update160();
		self::update162();
		self::update170();

	}

    /**
     * Update to Version 1.1.0
     *
     * Alters polls table column poll_chart_width to poll_max_width
     * column poll_chart_height to poll_min_width
     *
     * @since 1.1.0
     */
    public static function update110 () {
        global $wpdb;

        $maxWidth = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}weblator_polls LIKE 'poll_max_width'", ARRAY_A);

        if (!count($maxWidth))
            $wpdb->query("ALTER TABLE `{$wpdb->prefix}weblator_polls` CHANGE poll_chart_width poll_max_width INT");

        $minWidth = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}weblator_polls LIKE 'poll_min_width'", ARRAY_A);

        if (!count($minWidth))
            $wpdb->query("ALTER TABLE `{$wpdb->prefix}weblator_polls` CHANGE poll_chart_height poll_min_width INT");


    }

    /**
     * Update to Version 1.2.0
     *
     * Alters polls table column poll_name charset to utf8
     *
     * @since 1.2.0
     */
    public static function update120 () {

        global $wpdb;

        $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls MODIFY poll_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_poll_options MODIFY option_value VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci");

    }

    /**
     * Update to Version 1.3.0
     *
     * Alters polls table column to add legend and dropdown support
     *
     * @since 1.3.0
     */
    public static function update130 () {

        global $wpdb;

        //Check if tables have been altered already
        $check = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}weblator_polls LIKE 'chart_legend_font_colour'");
        if (!count($check)){

            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls ADD COLUMN `chart_legend_font_colour` varchar(50) DEFAULT 'rgb(102, 102, 102)' AFTER `poll_min_width`");
            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls ADD COLUMN `chart_legend_font_style` varchar(50) DEFAULT 'normal' AFTER `poll_min_width`");
            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls ADD COLUMN `chart_legend_font_size` int(11) DEFAULT '12' AFTER `poll_min_width`");
            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls ADD COLUMN `chart_legend_position` varchar(2) DEFAULT 'tl' AFTER `poll_min_width`");
            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls ADD COLUMN `chart_legend` tinyint(1) DEFAULT '0' AFTER `poll_min_width`");

            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_style_options ADD COLUMN `style_dropdown` tinyint(1) DEFAULT '0' AFTER `style_bool`");

            $wpdb->query("UPDATE {$wpdb->prefix}weblator_style_options SET style_dropdown = 1 WHERE style_option = 'poll_scale_font_size'");
            $wpdb->query("UPDATE {$wpdb->prefix}weblator_style_options SET style_dropdown = 1 WHERE style_option = 'poll_scale_font_style'");
            $wpdb->query("UPDATE {$wpdb->prefix}weblator_style_options SET style_dropdown = 1 WHERE style_option = 'poll_point_label_font_style'");
            $wpdb->query("UPDATE {$wpdb->prefix}weblator_style_options SET style_dropdown = 1 WHERE style_option = 'poll_point_font_size'");

            $wpdb->query("
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}weblator_poll_style_dropdowns` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `style_id` int(11) DEFAULT NULL,
              `dropdown_value` varchar(250) DEFAULT NULL,
              `dropdown_nicename` varchar(250) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) DEFAULT CHARSET=utf8;");

            $wpdb->query("
                INSERT INTO `{$wpdb->prefix}weblator_poll_style_dropdowns` (`id`, `style_id`, `dropdown_value`, `dropdown_nicename`)
                VALUES
                    (1, 9, 'normal', 'normal'),
                    (2, 9, 'italic', 'italic'),
                    (3, 9, 'oblique', 'oblique'),
                    (4, 26, 'oblique', 'oblique'),
                    (5, 26, 'italic', 'italic'),
                    (6, 26, 'normal', 'normal'),
                    (7, 8, '8', '8px'),
                    (8, 8, '9', '9px'),
                    (9, 8, '10', '10px'),
                    (10, 8, '11', '11px'),
                    (11, 8, '12', '12px'),
                    (12, 8, '13', '13px'),
                    (13, 8, '14', '14px'),
                    (14, 8, '15', '15px'),
                    (15, 8, '16', '16px'),
                    (16, 8, '17', '17px'),
                    (17, 8, '18', '18px'),
                    (18, 8, '19', '19px'),
                    (19, 8, '20', '20px'),
                    (20, 8, '21', '21px'),
                    (21, 8, '22', '22px'),
                    (22, 27, '8', '8px'),
                    (23, 27, '9', '9px'),
                    (24, 27, '10', '10px'),
                    (25, 27, '11', '11px'),
                    (26, 27, '12', '12px'),
                    (27, 27, '13', '13px'),
                    (28, 27, '14', '14px'),
                    (29, 27, '15', '15px'),
                    (30, 27, '16', '16px'),
                    (31, 27, '17', '17px'),
                    (32, 27, '18', '18px'),
                    (33, 27, '19', '19px'),
                    (34, 27, '20', '20px'),
                    (35, 27, '21', '21px'),
                    (36, 27, '22', '22px');
            ");

        }

    }


        /**
     * Update to Version 1.4.0
     *
     * Alters polls table column to add ip, user and cookie filter
     *
     * @since 1.4.0
     */
    public static function update140 () {

        global $wpdb;

        //Check if tables have been altered already
        $check = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}weblator_polls LIKE 'poll_one_vote_ip'");
        if (!count($check)){

            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls ADD COLUMN `poll_one_vote_ip` int(50) DEFAULT '1' AFTER `poll_one_vote`");
            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls ADD COLUMN `poll_one_vote_cookie` int(1) DEFAULT '1' AFTER `poll_one_vote`");
            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls ADD COLUMN `poll_one_vote_userid` int(1) DEFAULT '1' AFTER `poll_one_vote`");

            $wpdb->query("UPDATE {$wpdb->prefix}weblator_polls SET poll_one_vote_ip = 1");
            $wpdb->query("UPDATE {$wpdb->prefix}weblator_polls SET poll_one_vote_cookie = 1");
            $wpdb->query("UPDATE {$wpdb->prefix}weblator_polls SET poll_one_vote_userid = 1");

        }

    }


	public static function update150() {

		global $wpdb;

		$check = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}weblator_style_options WHERE style_option = 'tooltip_fill_color'");

		if ( !count($check) ) {

			$fonts = array(
				'Lucida Console, Monaco, monospace',
				'Courier New, Courier, monospace',
				'Verdana, Geneva, sans-serif',
				'Trebuchet MS, Helvetica, sans-serif',
				'Tahoma, Geneva, sans-serif',
				'Lucida Sans Unicode, Lucida Grande, sans-serif',
				'Impact, Charcoal, sans-serif',
				'Comic Sans MS, cursive, sans-serif',
				'Arial Black, Gadget, sans-serif',
				'Arial, Helvetica, sans-serif',
				'Palatino Linotype, Book Antiqua, Palatino, serif',
				'Times New Roman, Times, serif',
				'Helvetica Neue, Helvetica, Arial, sans-serif'
			);

			$font_styles = array(
				"normal" => "normal",
				"italic" => "italic",
				"oblique" => "oblique"
			);


			$new_styles = array(

				array( "style_description" => NULL, "style_option" => "tooltip_fill_color", "style_key" => "tooltipFillColor", "style_label" => "Tooltip Background Colour", "style_colorpicker" => 1, "style_bool" => 0, "style_dropdown" => 0, "style_default" => "rgba(0,0,0,0.8)", "style_order" => "35"),
				array( "style_description" => NULL, "style_option" => "tooltip_font_family", "style_key" => "tooltipFontFamily", "style_label" => "Tooltip Font Family", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 1, "style_default" => "Helvetica Neue, Helvetica, Arial, sans-serif", "style_order" => "36"),
				array( "style_description" => NULL, "style_option" => "tooltip_font_size", "style_key" => "tooltipFontSize", "style_label" => "Tooltip Font Size", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 1, "style_default" => "14", "style_order" => "37"),
				array( "style_description" => NULL, "style_option" => "tooltip_font_style", "style_key" => "tooltipFontStyle", "style_label" => "Tooltip Font Style", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 1, "style_default" => "normal", "style_order" => "38"),
				array( "style_description" => NULL, "style_option" => "tooltip_font_colour", "style_key" => "tooltipFontColor", "style_label" => "Tooltip Font Colour", "style_colorpicker" => 1, "style_bool" => 0, "style_dropdown" => 0, "style_default" => "rgba(255,255,255,1)", "style_order" => "39"),
				array( "style_description" => "In Pixels", "style_option" => "tooltip_y_padding", "style_key" => "tooltipYPadding", "style_label" => "Tooltip Padding Left/Right", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 0, "style_default" => "6", "style_order" => "40"),
				array( "style_description" => "In Pixels", "style_option" => "tooltip_x_padding", "style_key" => "tooltipXPadding", "style_label" => "Tooltip Padding Top/Bottom", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 0, "style_default" => "6", "style_order" => "41"),
				array( "style_description" => "The size of the tooltip arrow in pixels", "style_option" => "tooltip_caret_size", "style_key" => "tooltipCaretSize", "style_label" => "Tooltip Caret Size", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 0, "style_default" => "8", "style_order" => "42"),
				array( "style_description" => "In Pixels", "style_option" => "tooltip_corner_radius", "style_key" => "tooltipCornerRadius", "style_label" => "Tooltip Corner Radius", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 0, "style_default" => "6", "style_order" => "43")

 			);


			foreach ( $new_styles as $style ) {

				//Inset the new styles
				$wpdb->query(
					$wpdb->prepare(
						"INSERT INTO {$wpdb->prefix}weblator_style_options (style_option, style_key, style_label, style_description, style_colorpicker, style_bool, style_dropdown, style_default, style_order)
						VALUES ('%s', '%s', '%s', '%s', %d, %d, %d, '%s', %d)", $style["style_option"], $style["style_key"], $style["style_label"], $style["style_description"], $style["style_colorpicker"], $style["style_bool"], $style["style_dropdown"], $style["style_default"], $style["style_order"]
					)
				);

				$last_id = $wpdb->insert_id;

				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}weblator_style_chart_link (chart_id, style_id) VALUES (1, %d)",  $last_id));
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}weblator_style_chart_link (chart_id, style_id) VALUES (2, %d)",  $last_id));
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}weblator_style_chart_link (chart_id, style_id) VALUES (3, %d)",  $last_id));
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}weblator_style_chart_link (chart_id, style_id) VALUES (4, %d)",  $last_id));
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}weblator_style_chart_link (chart_id, style_id) VALUES (5, %d)",  $last_id));
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}weblator_style_chart_link (chart_id, style_id) VALUES (6, %d)",  $last_id));

				if ( $style["style_option"] == "tooltip_font_family" ) {

					foreach ( $fonts as $font ) {

						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$wpdb->prefix}weblator_poll_style_dropdowns (style_id, dropdown_value, dropdown_nicename)
								VALUES (%d, '%s', '%s')", $last_id, $font, $font
							)
						);

					}
				}

				if ( $style["style_option"] == "tooltip_font_style" ) {

					foreach ( $font_styles as $font ) {

						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$wpdb->prefix}weblator_poll_style_dropdowns (style_id, dropdown_value, dropdown_nicename)
								VALUES (%d, '%s', '%s')", $last_id, $font, $font
							)
						);

					}
				}

				if ( $style["style_option"] == "tooltip_font_size" ) {

					for ( $i = 8; $i < 23; $i++ ){

						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$wpdb->prefix}weblator_poll_style_dropdowns (style_id, dropdown_value, dropdown_nicename)
								VALUES (%d, '%s', '%s')", $last_id, $i, $i . "px"
							)
						);

					}

				}

				$charts = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}weblator_polls WHERE poll_chart_type IN (1, 2, 3, 4, 5, 6)");

				if ( count($charts) ){

					foreach ( $charts as $chart ) {

						$wpdb->query(
							$wpdb->prepare("INSERT INTO {$wpdb->prefix}weblator_style_values (poll_id, style_id, style_value)
							 VALUES (%d, %d, '%s')", $chart->id, $last_id, $style["style_default"])
						);

					}

				}

			}

		}
	}

	public static function update160() {

		global $wpdb;

		$check = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}weblator_style_options WHERE style_option = 'bs_label_font_family'");

		if ( !count($check) ) {
			$fonts = array(
				'Lucida Console, Monaco, monospace',
				'Courier New, Courier, monospace',
				'Verdana, Geneva, sans-serif',
				'Trebuchet MS, Helvetica, sans-serif',
				'Tahoma, Geneva, sans-serif',
				'Lucida Sans Unicode, Lucida Grande, sans-serif',
				'Impact, Charcoal, sans-serif',
				'Comic Sans MS, cursive, sans-serif',
				'Arial Black, Gadget, sans-serif',
				'Arial, Helvetica, sans-serif',
				'Palatino Linotype, Book Antiqua, Palatino, serif',
				'Times New Roman, Times, serif',
				'Helvetica Neue, Helvetica, Arial, sans-serif'
			);

			$font_styles = array(
				"normal" => "normal",
				"italic" => "italic",
				"oblique" => "oblique"
			);


			$new_styles = array(

				array( "style_description" => NULL, "style_option" => "bs_label_font_family", "style_key" => "bsLabelFontFamily", "style_label" => "Label Font Family", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 1, "style_default" => "Helvetica Neue, Helvetica, Arial, sans-serif", "style_order" => "44"),
				array( "style_description" => NULL, "style_option" => "bs_label_font_size", "style_key" => "bsLabelFontSize", "style_label" => "Label Font Size", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 1, "style_default" => "12", "style_order" => "45"),
				array( "style_description" => NULL, "style_option" => "bs_label_font_style", "style_key" => "bsLabelFontStyle", "style_label" => "Label Font Style", "style_colorpicker" => 0, "style_bool" => 0, "style_dropdown" => 1, "style_default" => "normal", "style_order" => "46"),
				array( "style_description" => NULL, "style_option" => "bs_label_font_colour", "style_key" => "bsLabelFontColor", "style_label" => "Label Font Colour", "style_colorpicker" => 1, "style_bool" => 0, "style_dropdown" => 0, "style_default" => "rgba(255,255,255,1)", "style_order" => "47"),

			);

			foreach ( $new_styles as $style ) {

				//Inset the new styles
				$wpdb->query(
					$wpdb->prepare(
						"INSERT INTO {$wpdb->prefix}weblator_style_options (style_option, style_key, style_label, style_description, style_colorpicker, style_bool, style_dropdown, style_default, style_order)
						VALUES ('%s', '%s', '%s', '%s', %d, %d, %d, '%s', %d)", $style["style_option"], $style["style_key"], $style["style_label"], $style["style_description"], $style["style_colorpicker"], $style["style_bool"], $style["style_dropdown"], $style["style_default"], $style["style_order"]
					)
				);

				$last_id = $wpdb->insert_id;

				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}weblator_style_chart_link (chart_id, style_id) VALUES (7, %d)",  $last_id));



				if ( $style["style_option"] == "bs_label_font_family" ) {

					foreach ( $fonts as $font ) {

						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$wpdb->prefix}weblator_poll_style_dropdowns (style_id, dropdown_value, dropdown_nicename)
								VALUES (%d, '%s', '%s')", $last_id, $font, $font
							)
						);

					}
				}

				if ( $style["style_option"] == "bs_label_font_style" ) {

					foreach ( $font_styles as $font ) {

						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$wpdb->prefix}weblator_poll_style_dropdowns (style_id, dropdown_value, dropdown_nicename)
								VALUES (%d, '%s', '%s')", $last_id, $font, $font
							)
						);

					}
				}

				if ( $style["style_option"] == "bs_label_font_size" ) {

					for ( $i = 8; $i < 23; $i++ ){

						$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO {$wpdb->prefix}weblator_poll_style_dropdowns (style_id, dropdown_value, dropdown_nicename)
								VALUES (%d, '%s', '%s')", $last_id, $i, $i . "px"
							)
						);

					}

				}

				$charts = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}weblator_polls WHERE poll_chart_type = 7");

				if ( count($charts) ){

					foreach ( $charts as $chart ) {

						$wpdb->query(
							$wpdb->prepare("INSERT INTO {$wpdb->prefix}weblator_style_values (poll_id, style_id, style_value)
							VALUES (%d, %d, '%s')", $chart->id, $last_id, $style["style_default"])
						);

					}

				}

			}



		}

	}

	public static function update162() {

		global $wpdb;

		$check = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}weblator_polls LIKE 'poll_percentage_values'");
        if (!count($check)){

            $wpdb->query("ALTER TABLE {$wpdb->prefix}weblator_polls ADD COLUMN `poll_percentage_values` int(50) DEFAULT '0' AFTER `poll_one_vote_ip`");


		}
	}

	public static function update170() {

		global $wpdb;

		$check = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}weblator_text_options WHERE poll_id = 0");
        if (!count($check)){

			$arr = array(
				array("poll_id" => 0, "text_field" => "weblator_already_voted", "text_value" => "You have already voted on this poll!"),
				array("poll_id" => 0, "text_field" => "weblator_please_select", "text_value" => "Please select an option!"),
				array("poll_id" => 0, "text_field" => "weblator_thank_you", "text_value" => "Thank you for voting"),
				array("poll_id" => 0, "text_field" => "weblator_vote", "text_value" => "Vote"),
				array("poll_id" => 0, "text_field" => "weblator_hide", "text_value" => "Hide"),
				array("poll_id" => 0, "text_field" => "weblator_show", "text_value" => "Show"),
				array("poll_id" => 0, "text_field" => "weblator_load", "text_value" => "Loading")
			);


			foreach($arr as $item) {

				$wpdb->query(
					$wpdb->prepare(
					"INSERT INTO {$wpdb->prefix}weblator_text_options (poll_id, text_field, text_value) VALUES (%d, '%s', '%s')",
					$item["poll_id"], $item["text_field"], $item["text_value"]
					)
				);

			}

		}
	}
	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
        wp_enqueue_style( $this->plugin_slug . '-fa', plugins_url( 'assets/css/font-awesome.min.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        wp_enqueue_script( $this->plugin_slug . '-canvas', plugins_url( 'assets/js/vendors/excanvas.js', __FILE__ ), array( 'jquery' ), self::VERSION );

        //if (get_bloginfo("version") < 3.8)
        //	wp_enqueue_script( $this->plugin_slug . '-charts', plugins_url( 'assets/js/vendors/Chart.min.js', __FILE__ ), array( 'jquery', $this->plugin_slug . '-canvas' ), self::VERSION, true );
        //else
        	wp_enqueue_script( $this->plugin_slug . '-charts', plugins_url( 'assets/js/vendors/chartjs_new.js', __FILE__ ), array( 'jquery', $this->plugin_slug . '-canvas' ), self::VERSION, true );

        wp_enqueue_script( $this->plugin_slug . '-legend', plugins_url( 'assets/js/app/legend.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
        wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/app/poll.js', __FILE__ ), array( 'jquery', $this->plugin_slug . '-charts', $this->plugin_slug . '-canvas', $this->plugin_slug . '-legend' ), self::VERSION, true );
        wp_localize_script($this->plugin_slug . '-plugin-script', 'ajaxurl', admin_url('admin-ajax.php') );

        wp_enqueue_script( $this->plugin_slug . '-poll-init', plugins_url( 'assets/js/app/poll-init.js', __FILE__ ), array( 'jquery', $this->plugin_slug . '-plugin-script' ), self::VERSION, true );
    }

    /**
     * Displays an instance of a poll.x
     *
     * @since    1.0.0
     *
     * @param    array    $atts   array container ID of poll.
     *
     * @return null
     */
    public function display_poll($atts){

        global $wpdb;

        $var = null;

        if (isset($atts[0]) && $atts[0] == "random"){

            $random = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}weblator_polls WHERE poll_deleted_date IS NULL ORDER BY RAND() LIMIT 1;");
            $poll    = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}weblator_polls WHERE id = %d ", $random), ARRAY_A);
            $options = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}weblator_poll_options WHERE poll_id = %d AND option_deleted_date IS NULL ORDER BY option_order ASC", $random), ARRAY_A);

        }else {

            $poll    = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}weblator_polls WHERE id = %d ", $atts["id"]), ARRAY_A);
            $options = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}weblator_poll_options WHERE poll_id = %d AND option_deleted_date IS NULL ORDER BY option_order ASC", $atts["id"]), ARRAY_A);

        }

        $total_votes = $wpdb->get_var(
            $wpdb->prepare("SELECT count(*) AS total FROM {$wpdb->prefix}weblator_poll_votes WHERE poll_id = %d", $atts["id"])
        );

        $legend = null;
        $legendStyle = null;

        if ($poll["poll_max_width"] > 0){

            $width = $poll["poll_max_width"];

            if ($poll["poll_chart_type"] != 5 || $poll["poll_chart_type"] != 6)
                $height = $poll["poll_max_width"] / 2;
        }
        else {

            $width = "100%";
            $height = "";

        }

				$results = 0;
        require("views/poll.php");
        return $var;

    }

		/**
		 * Displays a polls results
		 *
		 * @since    1.7.0
		 *
		 * @param    array    $atts   array container ID of poll.
		 *
		 * @return null
		 */
		public function display_poll_results($atts){

				global $wpdb;

				$var = null;

				if (isset($atts[0]) && $atts[0] == "random"){

						$random = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}weblator_polls WHERE poll_deleted_date IS NULL ORDER BY RAND() LIMIT 1;");
						$poll    = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}weblator_polls WHERE id = %d ", $random), ARRAY_A);
						$options = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}weblator_poll_options WHERE poll_id = %d AND option_deleted_date IS NULL ORDER BY option_order ASC", $random), ARRAY_A);

				}else {

						$poll    = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}weblator_polls WHERE id = %d ", $atts["id"]), ARRAY_A);
						$options = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}weblator_poll_options WHERE poll_id = %d AND option_deleted_date IS NULL ORDER BY option_order ASC", $atts["id"]), ARRAY_A);

				}

				$total_votes = $wpdb->get_var(
						$wpdb->prepare("SELECT count(*) AS total FROM {$wpdb->prefix}weblator_poll_votes WHERE poll_id = %d", $atts["id"])
				);

				$legend = null;
				$legendStyle = null;

				if ($poll["poll_max_width"] > 0){

						$width = $poll["poll_max_width"];

						if ($poll["poll_chart_type"] != 5 || $poll["poll_chart_type"] != 6)
								$height = $poll["poll_max_width"] / 2;
				}
				else {

						$width = "100%";
						$height = "";

				}

				$results = true;

				require("views/poll.php");
				return $var;

		}

	public function get_single_message( $poll_id, $label ) {

		global $wpdb;

		$message = $wpdb->get_var(
			$wpdb->prepare("SELECT text_value FROM {$wpdb->prefix}weblator_text_options WHERE poll_id = %d AND text_field = '%s' AND deleted_date IS NULL", $poll_id, $label)
		);

		if ( !$message ) {

			$message = $wpdb->get_var(
				$wpdb->prepare("SELECT text_value FROM {$wpdb->prefix}weblator_text_options WHERE poll_id = %d AND text_field = '%s' AND deleted_date IS NULL", 0, $label)
			);

			return $this->prepare_data_for_output($message);

		} else {

			return $this->prepare_data_for_output($message);

		}


	}

    public function browser_stylesheets(){

      if(!(isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))){

          echo "<link rel=\"stylesheet\" href=\"" . plugins_url( 'assets/css/notie.css', __FILE__ ) . "\">";

      }else {

          echo "<link rel=\"stylesheet\" href=\"" . plugins_url( 'assets/css/ie.css', __FILE__ ) . "\">";

      }



    }

	public function prepare_data_for_output($string = "")
	{
		return htmlentities(stripslashes(strip_tags($string)));
	}

}
