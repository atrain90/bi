<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Application;
use Joomunited\WPFramework\v1_0_5\Utilities;
use Joomunited\WPFramework\v1_0_5\Model;

defined('ABSPATH') || die();

$app = Application::getInstance('Wptm');
require_once $app->getPath() . DIRECTORY_SEPARATOR . $app->getType() . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'wptmBase.php';

add_action('admin_menu', 'wptm_menu');
add_action('wp_ajax_Wptm', 'wptm_ajax');
add_action('media_buttons_context', 'wptm_button');
add_action('load-dashboard_page_wptm-foldertree', 'wptm_foldertree_thickbox');
add_action('wp_ajax_wptm_getFolders', 'wptm_getFolders');
add_action('init', 'wptm_register_post_type');
add_action('admin_init', 'wptm_update_version');

if (!function_exists('wptm_update_version')) {
    /**
     * Update data params category when update plugin
     *
     * @return void
     */
    function wptm_update_version()
    {
        $version = get_option('wptm_version');
        if (version_compare($version, '2.3.0', '<')) {
            $id_user = get_current_user_id();
            $app = Application::getInstance('Wptm');
            $modelCat = Model::getInstance('categories');
            $categories = $modelCat->getCategories();
            $count_categories = count($categories);
            for ($index = 0; $index < $count_categories; $index++) {
                $id = $categories[$index]->id;
                if ($categories[$index]->params !== '') {
                    $dataCategory = json_decode($categories[$index]->params);
                } else {
                    $dataCategory = new stdClass();
                }
                if (empty($dataCategory->role)) {
                    $dataCategory = new stdClass();
                    $dataCategory->role = new stdClass();
                    $dataCategory->role->{0} = (string)$id_user;
                    $dataCategory = json_encode($dataCategory);
                    $modelUser = Model::getInstance('user');
                    $modelUser->save($id, $dataCategory, 0);
                }
            }
            // Set permissions for editors and admins so they can do stuff with wptm
            $wptm_roles = array('editor', 'administrator');
            foreach ($wptm_roles as $role_name) {
                $role = get_role($role_name);
                if ($role) {
                    $role->add_cap('wptm_create_category');
                    $role->add_cap('wptm_edit_category');
                    $role->add_cap('wptm_edit_own_category');
                    $role->add_cap('wptm_delete_category');
                    $role->add_cap('wptm_create_tables');
                    $role->add_cap('wptm_edit_tables');
                    $role->add_cap('wptm_edit_own_tables');
                    $role->add_cap('wptm_delete_tables');
                    $role->add_cap('wptm_access_category');
                }
            }
        }
    }
}

/**
 * Register post type
 *
 * @return void
 */
function wptm_register_post_type()
{
    register_taxonomy('wptm-tag', 'wptm_file');

    register_post_type(
        'wptm_file',
        array(
            'labels' => array(
                'name' => __('Files', 'wptm'),
                'singular_name' => __('File', 'wptm')
            ),
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_in_nav_menus' => false,
            'show_ui' => false,
            'taxonomies' => array('wptm-category', 'wptm-tag'),
            'has_archive' => false,
            'show_in_menu' => false,
            'capability_type' => 'wptm_file',
            'map_meta_cap' => false,
            'capabilities' => array(
                'wptm_create_category' => __('Create category', 'wptm'),
                'wptm_edit_category' => __('Edit category', 'wptm'),
                'wptm_edit_own_category' => __('Edit own category', 'wptm'),
                'wptm_delete_category' => __('Delete category', 'wptm'),
                'wptm_create_tables' => __('Create tables', 'wptm'),
                'wptm_edit_tables' => __('Edit tables', 'wptm'),
                'wptm_edit_own_tables' => __('Edit own tables', 'wptm'),
                'wptm_delete_tables' => __('Delete tables', 'wptm'),
                'wptm_access_category' => __('Access WP Table Manager', 'wptm')
            ),
        )
    );
}

/**
 * Load the heartbeat JS
 *
 * @return void
 */
function wptm_heartbeat_enqueue()
{
    // Make sure the JS part of the Heartbeat API is loaded.
    wp_enqueue_script('heartbeat');
    add_action('admin_print_footer_scripts', 'wptm_heartbeat_footer_js', 20);
}

add_action('admin_enqueue_scripts', 'wptm_heartbeat_enqueue');

/**
 * Inject our JS into the admin footer
 *
 * @return void
 */
function wptm_heartbeat_footer_js()
{
    global $pagenow;

    ?>
    <script>
        (function ($) {

            // Hook into the heartbeat-send
            $(document).on('heartbeat-send', function (e, data) {
                data['wptm_heartbeat'] = 'rendering';
            });

            // Listen for the custom event "heartbeat-tick" on $(document).
            $(document).on('heartbeat-tick', function (e, data) {
                // Only proceed if our EDD data is present
                if (!data['wptm-result'])
                    return;

            });
        }(jQuery));
    </script>
    <?php
}

/**
 * Modify the data that goes back with the heartbeat-tick
 *
 * @param array $response Response
 * @param array $data     Data
 *
 * @return mixed
 */
function wptm_heartbeat_received($response, $data)
{

    // Make sure we only run our query if the edd_heartbeat key is present
    if ((string)$data['wptm_heartbeat'] === 'rendering') {
        $app = Application::getInstance('Wptm');
        require_once dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'wptmHelper.php';
        $wptmHelper = new WptmHelper();
        $modelTables = Model::getInstance('tables');
        $tables = $modelTables->getItems();
        if (count($tables)) {
            foreach ($tables as $table) {
                $wptmHelper->styleRender($table);
                $wptmHelper->htmlRender($table, array());
            }
        }

        $modelConfig = Model::getInstance('config');
        $params = $modelConfig->getConfig();
        if (isset($params['sync_periodicity']) && (string) $params['sync_periodicity'] !== '0') :
            if (isset($params['last_sync']) && (string) $params['last_sync'] !== '0') {
                $last_sync = (int) $params['last_sync'];
            } else {
                $last_sync = 0;
            }
            $time_now = (int) strtotime(date('Y-m-d H:i:s'));
            if (($time_now - $last_sync) / 3600 >= (int)$params['sync_periodicity']) {
                //do sync
                $app->execute('excel.syncSpreadsheet');

                $params['last_sync'] = $time_now;
                $modelConfig->save($params);
            }
        endif;
        // Send back the number of complete payments
        $response['wptm-result'] = time();
    }
    return $response;
}

add_filter('heartbeat_received', 'wptm_heartbeat_received', 10, 2);
/**
 * Create menu
 *
 * @return void
 */
function wptm_menu()
{
    $app = Application::getInstance('Wptm');
    add_menu_page('WP Table Manager', 'Table Manager', 'edit_posts', 'wptm', 'wptm_call', 'dashicons-screenoptions');
    add_submenu_page('wptm', 'Database tables', 'Database tables', 'manage_options', 'wptm-db-table', 'wptm_call_dbtable');
    add_submenu_page('wptm', 'WP Table Manager config', 'Configuration', 'manage_options', 'wptm-config', 'wptm_call_config');
    add_submenu_page('wptm', 'User Roles', __('User Roles', 'wptm'), 'manage_options', 'wptm-role', 'wptm_call_roles');
    add_submenu_page(null, 'Folder tree', 'Folder tree', 'manage_options', 'wptm-foldertree', 'wptm_folderTree');
}

/**
 * Function ajax
 *
 * @return void
 */
function wptm_ajax()
{
    define('WPTM_AJAX', true);
    wptm_call();
}

/**
 * Function call
 *
 * @param null   $ref          Ref
 * @param string $default_task Default task
 *
 * @return void
 */
function wptm_call($ref = null, $default_task = 'wptm.display')
{

    if (!current_user_can('wptm_access_category')) {
        wp_die(esc_attr__('You do not have sufficient permissions to access this page.', 'wptm'));
    }

    if (!defined('WPTM_AJAX')) {
        wptm_init();
    }

    $application = Application::getInstance('Wptm');
    $application->execute($default_task);
}

/**
 * Call roles
 *
 * @return void
 */
function wptm_call_roles()
{

    wptm_call(null, 'role.display');
}

/**
 * Call config
 *
 * @return void
 */
function wptm_call_config()
{

    wptm_call(null, 'config.display');
}

/**
 * Call db table
 *
 * @return void
 */
function wptm_call_dbtable()
{

    wptm_call(null, 'dbtable.display');
}

/**
 * Function init
 *
 * @return void
 */
function wptm_init()
{
    $page = Utilities::getInput('page', 'GET', 'string');
    $application = Application::getInstance('Wptm');
    load_plugin_textdomain('wptm', null, $application->getPath(true) . DIRECTORY_SEPARATOR . 'languages');
    load_plugin_textdomain('wptm', null, dirname(plugin_basename(WPTM_PLUGIN_FILE)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'languages');

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-migrate');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-droppable');
    wp_enqueue_script('jquery-ui-sortable');

    wp_enqueue_script('wptm-iris', plugins_url('assets/js/iris.min.js', __FILE__), array('jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch'), false, 1);
    wp_enqueue_script('wptm-color-picker', admin_url('js/color-picker.min.js'), array('wptm-iris'), false, 1);
    wp_localize_script('wptm-color-picker', 'wpColorPickerL10n', array(
        'clear' => __('Clear', 'wptm'),
        'defaultString' => __('Default', 'wptm'),
        'pick' => __('Select Color', 'wptm'),
        'current' => __('Current Color', 'wptm'),
    ));
    wp_enqueue_style('wp-color-picker');

    wp_enqueue_script('wptm-bootstrap', plugins_url('assets/js/bootstrap.min.js', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_style('wptm-bootstrap', plugins_url('assets/css/bootstrap.min.css', __FILE__), array(), WPTM_VERSION);

    wp_enqueue_script('wptm-touch-punch', plugins_url('assets/js/jquery.ui.touch-punch.min.js', __FILE__), array(), WPTM_VERSION);

    wp_enqueue_style('buttons');
    wp_enqueue_style('wp-admin');
    wp_enqueue_style('colors-fresh');

    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');

    wp_enqueue_style('wptm-style', plugins_url('assets/css/style.css', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_style('wptm-table-sprites', plugins_url('assets/css/table-sprites.css', __FILE__, array(), WPTM_VERSION));
    wp_enqueue_style('wptm-handsontable', plugins_url('assets/css/jquery.handsontable.full.css', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_style('wptm-codemirror', plugins_url('assets/codemirror/codemirror.css', __FILE__));
    wp_enqueue_style('wptm-codemirror3024-night', plugins_url('assets/codemirror/3024-night.css', __FILE__));
    wp_enqueue_style('wptm-modal', plugins_url('assets/css/leanmodal.css', __FILE__));
    wp_enqueue_script('wptm-modal', plugins_url('assets/js/jquery.leanModal.min.js', __FILE__));
    wp_enqueue_script('less', plugins_url('assets/js/less.js', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_script('handsontable', plugins_url('assets/js/jquery.handsontable.full.js', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_script('jquery-textselect', plugins_url('assets/js/jquery.textselect.min.js', __FILE__), array(), WPTM_VERSION);

    wp_enqueue_script('jquery-nestable', plugins_url('assets/js/jquery.nestable.js', __FILE__), array(), WPTM_VERSION);

    wp_enqueue_script('wptm-codemirror', plugins_url('assets/codemirror/codemirror.js', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_script('wptm-codemirror-css', plugins_url('assets/codemirror/mode/css/css.js', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_script('wptm-bootbox', plugins_url('assets/js/bootbox.js', __FILE__), array(), WPTM_VERSION);
    if ($page === 'wptm') {
        wp_enqueue_script('wptm-main', plugins_url('assets/js/wptm.js', __FILE__), array(), WPTM_VERSION);
        wp_enqueue_script('wptm-scroll', plugins_url('assets/js/wp_ju_scroll.js', __FILE__), array(), WPTM_VERSION);
        wp_enqueue_script('chart', plugins_url('assets/js/chart.min.js', __FILE__), array(), WPTM_VERSION);
    }
    if ($page === 'wptm-config') {
        wp_enqueue_script('wptm-main', plugins_url('assets/js/wptmconfig.js', __FILE__), array(), WPTM_VERSION);
    }
    //if($page=='wptm-db-table') {
    wp_enqueue_script('wptm-handlebars', plugins_url('assets/js/handlebars-1.0.0-rc.3.js', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_script('wptm-db-table', plugins_url('assets/js/wptm_dbtable.js', __FILE__), array(), WPTM_VERSION);
    // }

    wp_enqueue_script('dropzone', plugins_url('assets/js/dropzone.min.js', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_script('jquery-fileDownload', plugins_url('assets/js/jquery.fileDownload.js', __FILE__), array(), WPTM_VERSION);
    wp_localize_script('wptm-main', 'wptm_permissions', array(
        'can_create_category' => current_user_can('wptm_create_category'),
        'can_edit_category' => current_user_can('wptm_edit_category'),
        'can_edit_own_category' => current_user_can('wptm_edit_own_category'),
        'can_delete_category' => current_user_can('wptm_delete_category'),
        'can_create_tables' => current_user_can('wptm_create_tables'),
        'can_edit_tables' => current_user_can('wptm_edit_tables'),
        'can_edit_own_tables' => current_user_can('wptm_edit_own_tables'),
        'can_delete_tables' => current_user_can('wptm_delete_tables'),
        'can_access_category' => current_user_can('wptm_access_category'),
        'translate' => array(
            'wptm_create_category' => __('You don\'t have permission to create new category', 'wptm'),
            'wptm_edit_category' => __('You don\'t have permission to edit category', 'wptm'),
            'wptm_delete_category' => __('You don\'t have permission to delete category', 'wptm'),
            'wptm_create_tables' => __('You don\'t have permission to create new tables', 'wptm'),
            'wptm_edit_tables' => __('You don\'t have permission to edit tables', 'wptm'),
            'wptm_delete_tables' => __('You don\'t have permission to delete tables', 'wptm')
        ),
    ));

    wp_localize_script('wptm-main', 'wptmText', array(
        'Delete' => __('Delete', 'wptm'),
        'Edit' => __('Edit', 'wptm'),
        'Cancel' => __('Cancel', 'wptm'),
        'Ok' => __('Ok', 'wptm'),
        'Confirm' => __('Confirm', 'wptm'),
        'Save' => __('Save', 'wptm'),
        'GOT_IT' => __('Got it!', 'wptm'),
        'LAYOUT_WPTM_SELECT_ONE' => __('Please select a table a create a new one', 'wptm'),
        'VIEW_WPTM_TABLE_ADD' => __('Add new table', 'wptm'),
        'JS_WANT_DELETE' => __('Do you really want to delete ', 'wptm'),
        'CHANGE_INVALID_CHART_DATA' => __('Invalid chart data', 'wptm'),
        'CHANGE_ERROR_ROLE_OWN_CATEGORY' => __('Only one user has the right to own', 'wptm'),
        'CHANGE_ROLE_OWN_CATEGORY' => __('Successful ownership change', 'wptm'),
        'CHART_INVALID_DATA' => __('Invalid data, please make a new data range selection with at least one row or column with only numeric data, thanks!', 'wptm'),
        'CHOOSE_EXCEL_FIE_TYPE' => __('Please choose a file with type of xls or xlsx.', 'wptm'),
        'WARNING_CHANGE_THEME' => __('Warning - all data and styles will be removed & replaced on theme switch', 'wptm'),
        'Your browser does not support HTML5 file uploads' => __('Your browser does not support HTML5 file uploads', 'wptm'),
        'Too many files' => __('Too many files', 'wptm'),
        'is too large' => __('is too large', 'wptm'),
        'Only images are allowed' => __('Only images are allowed', 'wptm'),
        'Do you want to delete &quot;' => __('Do you want to delete &quot;', 'wptm'),
        'Select files' => __('Select files', 'wptm'),
        'Image parameters' => __('Image parameters', 'wptm'),
        'notice_msg_table_syncable' => __('This spreadsheet is currently sync with an external file, you may lose content in case of modification', 'wptm'),
        'notice_msg_table_database' => __('Table data are from database, only the 50 first rows are displayed for performance reason.', 'wptm'),

    ));
    wp_localize_script('wptm-bootbox', 'wptmCmd', array(
        'Delete' => __('Delete', 'wptm'),
        'Edit' => __('Edit', 'wptm'),
        'CANCEL' => __('Cancel', 'wptm'),
        'OK' => __('Ok', 'wptm'),
        'CONFIRM' => __('Confirm', 'wptm'),
        'Save' => __('Save', 'wptm'),
    ));

    if (Utilities::getInput('noheader', 'GET', 'bool')) {
        //remove script loaded in bottom of page
        wp_dequeue_script('sitepress-scripts');
        wp_dequeue_script('wpml-tm-scripts');
    }

    wp_enqueue_media();
    add_filter('tiny_mce_before_init', 'wptm_tiny_mce_before_init');  // Before tinymce initialization
    // Build extra plugins array
    add_filter('mce_external_plugins', 'wptm_mce_external_plugins');
    add_editor_style(WP_TABLE_MANAGER_PLUGIN_URL . 'app/admin/assets/css/wptm-editor-style.css');
}

/**
 * Get button
 *
 * @param string $context Context
 *
 * @return string
 */
function wptm_button($context)
{
    wp_enqueue_style('wptm-modal', plugins_url('assets/css/leanmodal.css', __FILE__));
    wp_enqueue_script('wptm-modal', plugins_url('assets/js/jquery.leanModal.min.js', __FILE__));
    wp_enqueue_script('wptm-modal-init', plugins_url('assets/js/leanmodal.init.js', __FILE__));

    $context .= "<a href='#wptmmodal' class='button wptmlaunch' id='wptmlaunch' title='WP Table Manager'>"
        . " <span class='dashicons dashicons-screenoptions' style='line-height: inherit;'></span>WP Table Manager</a>";

    return $context;
}

/**
 * Get plugin.min.js
 *
 * @param array $plugins Plugins
 *
 * @return mixed
 */
function wptm_mce_external_plugins($plugins)
{
    $plugins['code'] = WP_TABLE_MANAGER_PLUGIN_URL . 'app/admin/assets/plugins/code/plugin.min.js';
    $plugins['wpmedia'] = WP_TABLE_MANAGER_PLUGIN_URL . 'app/admin/assets/plugins/wpmedia/plugin.js';
    return $plugins;
}

/**
 * Initialize table ability
 *
 * @param array $init Init
 *
 * @return mixed
 */
function wptm_tiny_mce_before_init($init)
{
    if (isset($init['tools'])) {
        $init['tools'] = $init['tools'] . ',inserttable';
    } else {
        $init['tools'] = 'inserttable';
    }

    if (isset($init['toolbar2'])) {
        $init['toolbar2'] = $init['toolbar2'] . ',code,wpmedia';
    } else {
        $init['toolbar1'] = $init['toolbar1'] . ',code,wpmedia';
    }
    $init['height'] = '500';
    return $init;
}

/**
 * Folder Tree
 *
 * @return void
 */
function wptm_folderTree()
{
    /* Do nothing */
}

/**
 * Get folder tree
 *
 * @return void
 */
function wptm_foldertree_thickbox()
{
    if (!defined('IFRAME_REQUEST')) {
        define('IFRAME_REQUEST', true);
    }
    iframe_header();
    global $wp_scripts, $wp_styles;

    wp_enqueue_script('wptm-jaofiletree', plugins_url('assets/js/jaofiletree.js', __FILE__), array(), WPTM_VERSION);
    wp_enqueue_style('wptm-jaofiletree', plugins_url('assets/css/jaofiletree.css', __FILE__), array(), WPTM_VERSION);
    ?>
    <div style="padding-top: 10px;">
        <div class="pull-left" style="float: left">
            <div id="wptm_foldertree"></div>
        </div>
        <div class="pull-right" style="float: right;margin-right: 10px;">
            <button class="button button-primary" type="button"
                    onclick="selectFile()"><?php echo esc_attr__('OK', 'wptm') ?></button>
            <button class="button" type="button"
                    onclick="window.parent.tb_remove();"><?php echo esc_attr__('Cancel', 'wptm') ?></button>
        </div>
    </div>
    <style>

        #wptm_foldertree input[type="checkbox"] {
            width: 16px;
            height: 16px;
        }

        #wptm_foldertree input[type="checkbox"]:checked:before {
            font-size: 20px;
            line-height: 20px;
        }
    </style>
    <script>

        jQuery(document).ready(function ($) {
            wptm_site_url = '<?php echo esc_url_raw(get_site_url());?>';
            selectFile = function () {
                selected_file = "";
                $('#wptm_foldertree').find('input:checked + a').each(function () {
                    selected_file = $(this).attr('data-file');
                })

                window.parent.document.getElementById('jform_spreadsheet_url').value = wptm_site_url + selected_file;
                window.parent.jQuery("#jform_spreadsheet_url").change();
                window.parent.tb_remove();
            }


            $('#wptm_foldertree').jaofiletreewptm({
                script: ajaxurl,
                usecheckboxes: 'files',
                showroot: '/',
                oncheck: function (elem, checked, type, file) {

                }
            });

        })
    </script>
    <?php
    iframe_footer();
    exit; //Die to prevent the page continueing loading and adding the admin menu's etc.
}

/**
 * Get Folders
 *
 * @return void
 */
function wptm_getFolders()
{
    $path = ABSPATH . DIRECTORY_SEPARATOR;
    $dir = Utilities::getInput('dir', 'GET', 'string');
    $allowed_ext = array('xls', 'xlsx');
    $return = array();
    $dirs   = array();
    $fi     = array();
    if (file_exists($path . $dir)) {
        $files = scandir($path . $dir);

        natcasesort($files);
        if (count($files) > 2) { // The 2 counts for . and ..
            // All dirs
            $baseDir = ltrim(rtrim(str_replace(DIRECTORY_SEPARATOR, '/', $dir), '/'), '/');
            if ((string)$baseDir !== '') {
                $baseDir .= '/';
            }
            foreach ($files as $file) {
                if (file_exists($path . $dir . DIRECTORY_SEPARATOR . $file) && $file !== '.' && $file !== '..' && is_dir($path . $dir . DIRECTORY_SEPARATOR . $file)) {
                    $dirs[] = array('type' => 'dir', 'dir' => $dir, 'file' => $file);
                } elseif (file_exists($path . $dir . DIRECTORY_SEPARATOR . $file) && $file !== '.' && $file !== '..' && !is_dir($path . $dir . DIRECTORY_SEPARATOR . $file)) {
                    $dot      = strrpos($file, '.') + 1;
                    $file_ext = strtolower(substr($file, $dot));
                    if (in_array($file_ext, $allowed_ext)) {
                        $fi[] = array('type' => 'file', 'dir' => $dir, 'file' => $file, 'ext' => $file_ext);
                    }
                }
            }
            $return = array_merge($dirs, $fi);
        }
    }
    echo json_encode($return);
    die();
}
