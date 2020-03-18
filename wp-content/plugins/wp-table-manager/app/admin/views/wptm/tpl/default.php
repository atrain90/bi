<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0W
 */

use Joomunited\WPFramework\v1_0_5\Factory;
use Joomunited\WPFramework\v1_0_5\Utilities;
use Joomunited\WPFramework\v1_0_5\Model;

// No direct access.
defined('ABSPATH') || die();

if (!current_user_can('wptm_access_category')) {
    wp_die(esc_attr__("You don't have permission to view this page", 'wptm'));
}

$userRoles = ( array )wp_get_current_user()->roles;
$userRoles = array_values($userRoles);
$userRoles = $userRoles[0];

$wp_roles = new WP_Roles();
// list role users
$roles = $wp_roles->role_objects;

if (Utilities::getInput('caninsert', 'GET', 'bool')) {
    global $hook_suffix;
    _wp_admin_html_begin();
    do_action('admin_enqueue_scripts', $hook_suffix);
    do_action('admin_print_scripts-$hook_suffix');
    do_action('admin_print_scripts');
}

$alone = '';

$editor_id = 'wptmditor';
$editor_args = array(
    'tabfocus_elements' => 'content-html,save-post',
    'quicktags' => true,
    'media_buttons' => false,
    'editor_height' => 400,
    'tinymce' => array(
        'resize' => true,
        'wp_autoresize_on' => true,
        'add_unload_trigger' => false
    )
);
wp_editor('<p></p><p></p>', $editor_id, $editor_args);


$editor_args1 = $editor_args;
// $editor_args1['editor_height'] = '300' ;
$editor_args1['quicktags'] = false;
$editor_args1['tinymce']   = array(
    'setup' => 'function (ed) {                               
                               ed.on("keyup", function (e) {
                                  // ed.save();
                                   //wptm_tooltipChange();
                                
                                });
                                ed.on("change", function(e) {
                                   // ed.save();
                                    //wptm_tooltipChange();
                                });
                            }',
);
wp_editor('', 'wptm_tooltip', $editor_args1);
$date_formats = !isset($this->params['date_formats']) ? 'Y-m-d' : $this->params['date_formats'];
$date_formats = str_replace('\\', '\\\\', $date_formats);
$symbol_position = !isset($this->params['symbol_position']) ? 0 : $this->params['symbol_position'];
$currency_sym = !isset($this->params['currency_sym']) ? '$' : $this->params['currency_sym'];
$decimal_sym = !isset($this->params['decimal_sym']) ? '.' : $this->params['decimal_sym'];
$decimal_count = !isset($this->params['decimal_count']) ? '0' : $this->params['decimal_count'];
$thousand_sym = !isset($this->params['thousand_sym']) ? ',' : $this->params['thousand_sym'];
?>
    <style>
        #wpwrap, body.sticky-menu {
            background-color: #ffffff;
        }
        #wp-wptmditor-wrap, #wp-wptm_tooltip-wrap {
            display: none
        }
    </style>
    <script type="text/javascript">
        ajaxurl = '<?php echo esc_url_raw(admin_url('admin-ajax.php')); ?>';
        adminurl = '<?php echo esc_url_raw(admin_url()); ?>';
        wptm_ajaxurl = "<?php echo esc_url_raw(Factory::getApplication('wptm')->getAjaxUrl()); ?>";
        wptm_dir = "<?php echo esc_url_raw(Factory::getApplication('wptm')->getBaseUrl()); ?>";
        <?php
        if (Utilities::getInput('caninsert', 'GET', 'bool')) :
            $alone = 'wptmalone wp-core-ui ';
            ?>
            gcaninsert = true;
        <?php else : ?>
            gcaninsert = false;
        <?php endif; ?>

        var Wptm = {};
        if (typeof(addLoadEvent) === 'undefined') {
            addLoadEvent = function (func) {
                if (typeof jQuery != "undefined") jQuery(document).ready(func); else if (typeof wpOnload != 'function') {
                    wpOnload = func;
                } else {
                    var oldonload = wpOnload;
                    wpOnload = function () {
                        oldonload();
                        func();
                    }
                }
            };
        }
        ;
    </script>
    <style>
        <?php if (Utilities::getInput('caninsert', 'GET', 'bool')) : ?>
        html.wp-toolbar {
            padding-top: 0 !important
        }

        <?php endif; ?>
    </style>
    <div id="mybootstrap" class="<?php echo esc_html($alone); ?>">
        <div id="wptm_create_new">
            <a id="newtable" class="wptm_button button-big tooltip" title=""
               href="">
                <?php echo esc_attr__('Create table', 'wptm'); ?></a>
            <a id="newchart" class="wptm_button btn_addGraph nephritis-flat-button" href="#">
                <?php esc_attr_e('Add chart', 'wptm'); ?></a>
            <a id="newcategory" class="wptm_button button-big tooltip" title=""
               href="">
                <?php esc_attr_e('New table category', 'wptm'); ?></a>
        </div>

        <div id="mycategories">
            <div class="categories-toggle" id="cats-toggle">
                <span class="dashicons dashicons-arrow-left-alt2"></span>
            </div>
            <div class="nested dd">
                <ol id="categorieslist" class="dd-list nav bs-docs-sidenav2" style="left: 10px;">
                    <?php
                    if (!empty($this->categories)) {
                        $content                                                   = '';
                        $previouslevel                                             = 1;
                        $categorys_role                                            = new stdClass();
                        $user_role                                                 = new stdClass();
                        $active                                                    = - 1;
                        $roles[$userRoles]->capabilities['wptm_edit_category']     =
                            isset($roles[$userRoles]->capabilities['wptm_edit_category'])
                                ? $roles[$userRoles]->capabilities['wptm_edit_category']
                                : false;
                        $roles[$userRoles]->capabilities['wptm_edit_own_category'] =
                            isset($roles[$userRoles]->capabilities['wptm_edit_own_category'])
                                ? $roles[$userRoles]->capabilities['wptm_edit_own_category']
                                : false;
                        $roles[$userRoles]->capabilities['wptm_edit_own_category'] =
                            ($roles[$userRoles]->capabilities['wptm_edit_category'] === true)
                                ? true
                                : $roles[$userRoles]->capabilities['wptm_edit_own_category'];
                        $countCategory                                             = count($this->categories);
                        for ($index = 0; $index < $countCategory; $index ++) {
                            $check         = false;
                            $role_category = array();
                            $category_role = json_decode($this->categories[$index]->params);
                            $category_role = $category_role->role;
                            if ($roles[$userRoles]->capabilities['wptm_edit_category']
                                || ($roles[$userRoles]->capabilities['wptm_edit_own_category']
                                    && (int) $category_role->{0} === $this->idUser)
                            ) {
                                $check                                           = true;
                                $categorys_role->{$this->categories[$index]->id} = $category_role !== null
                                    ? $category_role
                                    : new stdClass();
                            }
                            if ($index + 1 !== $countCategory) {
                                $nextlevel = (int) $this->categories[$index + 1]->level;
                            } else {
                                $nextlevel = 0;
                            }
                            $active  = $active === - 1 ? $check ? 1 : $active : 0;
                            $content .= openItem($this->categories[$index], $active, $check);
                            $content .= '<ul class="wptm-tables-list">';

                            if ((int) $this->categories[$index]->id === $this->dbtable_cat) {
                                $tableType = 'mysql';
                            } else {
                                $tableType = '';
                            }

                            if (isset($this->tables[$this->categories[$index]->id]) && $roles[$userRoles]->capabilities['wptm_edit_own_category']) {
                                $wptm_edit_tables = isset($roles[$userRoles]->capabilities['wptm_edit_tables']) ? true : false;
                                foreach ($this->tables[$this->categories[$index]->id] as $table) {
                                    $idUser    = isset($roles[$userRoles]->capabilities['wptm_edit_own_tables']) ? $this->idUser : - 2;
                                    $roleTable = $wptm_edit_tables ? true : (int) $table->author === $idUser ? true : false;
                                    if ($roleTable) {
                                        $content .= '<li class="wptmtable" data-id-table="' . esc_attr($table->id) . '" data-table-type="' . esc_attr($tableType) . '">';
                                        $content .= '<a href="#"><i class="icon-database"></i> <span class="title">' . esc_attr($table->title) . '</span></a>';
                                        $content .= ' <a class="edit"><i class="icon-edit"></i></a>';
                                        $content .= ' <a class="copy"><i class="icon-copy"></i></a>';
                                        $content .= ' <a class="trash"><i class="icon-trash"></i></a>';
                                        $content .= '</li>';
                                    }
                                }
                            }
                            if ($tableType !== 'mysql') {
                                $content .= '<li><a class="newTable" href="#"><i class="dashicons dashicons-plus-alt"></i> ' . esc_attr__('New table', 'wptm') . '</a></li>';
                            }
                            $content .= '</ul>';

                            if ($nextlevel > $this->categories[$index]->level) {
                                $content .= openlist($this->categories[$index]);
                            } elseif ($nextlevel === (int) $this->categories[$index]->level) {
                                $content .= closeItem($this->categories[$index]);
                            } else {
                                $c       = '';
                                $c       .= closeItem($this->categories[$index]);
                                $c       .= closeList($this->categories[$index]);
                                $content .= str_repeat($c, $this->categories[$index]->level - $nextlevel);
                            }
                            $previouslevel = $this->categories[$index]->level;
                        }
                    }

                    if (!isset($content)) {
                        $content = '';
                    }
                    //phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- The inner variables were esc
                    echo $content;
                    ?>
                </ol>
                <input type="hidden" id="categoryToken" name=""/>
            </div>
        </div>

        <div id="rightcol" class="">
            <?php if (Utilities::getInput('caninsert', 'GET', 'bool')) : ?>
                <a id="inserttable" class="button button-primary button-big" href="javascript:void(0)"
                   onclick="if (window.parent) insertTable();"><?php esc_attr_e('Insert this table', 'wptm'); ?></a>
            <?php endif; ?>
            <?php if (isset($this->params['enable_autosave']) && (string)$this->params['enable_autosave'] === '0') : ?>
                <div class="control-group">
                    <label id="jform_saveTable">
                        <a id="saveTable" class="button button-primary button-big"
                           title="<?php esc_attr_e('Save modifications', 'wptm'); ?>"><?php esc_attr_e('Save modifications', 'wptm'); ?></a>
                    </label>
                </div>
            <?php endif; ?>

            <div id="parent_configTable">
                <ul class="nav nav-tabs" id="configTable">
                    <li class="referTable active"><a data-toggle="tab" href="#table"><?php esc_attr_e('Table', 'wptm'); ?></a>
                    </li>
                    <li class="referCell"><a data-toggle="tab" href="#cell"><?php esc_attr_e('Format', 'wptm'); ?></a></li>
                    <li class="tableMore"><a data-toggle="tab" href="#css"><?php esc_attr_e('More', 'wptm'); ?></a></li>
                </ul>
                <div class="tab-content" id="tableTabContent">
                    <div id="table" class="tab-pane active">
                        <div class="control-group">
                            <div class="table-styles">
                                <ul>
                                    <?php foreach ($this->styles as $style) : ?>
                                        <li><a href="#" data-id="<?php echo esc_attr($style->id); ?>"><img
                                                        src="<?php echo esc_url_raw(WP_TABLE_MANAGER_PLUGIN_URL . 'app/admin/assets/images/styles/' . $style->image); ?>"/></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <div class="control-group" id="select_alternating_color">
                                <div class="control-label">
                                    <label>
                                        <?php esc_attr_e('Automatic styling', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <input type="button" value="<?php esc_attr_e('Select', 'wptm'); ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <label id="jform_use_sortable-lbl" for="jform_use_sortable">
                                        <?php esc_attr_e('Use sortable table', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select class="chzn-select observeChanges" name="jform[jform_use_sortable]"
                                            id="jform_use_sortable">
                                        <option value="0"><?php esc_attr_e('No', 'wptm'); ?></option>
                                        <option value="1"><?php esc_attr_e('Yes', 'wptm'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <label id="jform_default_sortable-lbl" for="jform_default_sortable">
                                        <?php esc_attr_e('Default sorting', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select class="chzn-select observeChanges" name="jform[jform_default_sortable]"
                                            id="jform_default_sortable">
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <label id="jform_default_order_sortable-lbl" for="jform_default_order_sortable">
                                        <?php esc_attr_e('Order sort a column by default', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select class="chzn-select observeChanges"
                                            name="jform[jform_default_order_sortable]"
                                            id="jform_default_order_sortable">
                                        <option value="0"><?php esc_attr_e('ASC', 'wptm'); ?></option>
                                        <option value="1"><?php esc_attr_e('DESC', 'wptm'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <label id="jform_table_align-lbl" for="jform_table_align">
                                        <?php esc_attr_e('Align table', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select class="chzn-select observeChanges" name="jform[jform_table_align]"
                                            id="jform_table_align">
                                        <option value="center"><?php esc_attr_e('Center', 'wptm'); ?></option>
                                        <option value="right"><?php esc_attr_e('Right', 'wptm'); ?></option>
                                        <option value="left"><?php esc_attr_e('Left', 'wptm'); ?></option>
                                        <option value="none"><?php esc_attr_e('None', 'wptm'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="control-label">
                                    <label id="jform_responsive_type-lbl" for="jform_responsive_type">
                                        <?php esc_attr_e('Responsive Type', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select class="chzn-select observeChanges" name="jform[jform_responsive_type]"
                                            id="jform_responsive_type">
                                        <option value="scroll"><?php esc_attr_e('Scrolling', 'wptm'); ?></option>
                                        <option value="hideCols"><?php esc_attr_e('Hiding Cols', 'wptm'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group" style="clear:left;">
                                <div class="control-label">
                                    <label id="jform_responsive_col-lbl" for="jform_responsive_col">
                                        <?php esc_attr_e('Column', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select class="chzn-select observeChangesCol" name="jform[jform_responsive_col]"
                                            id="jform_responsive_col">
                                    </select>
                                </div>
                            </div>

                            <div class="control-group" style="clear:left;">
                                <div class="control-label">
                                    <label id="jform_responsive_priority-lbl" for="jform_responsive_priority">
                                        <?php esc_attr_e('Responsive Priority', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select class="chzn-select observeChanges" name="jform[jform_responsive_priority]"
                                            id="jform_responsive_priority">
                                    </select>
                                </div>
                            </div>

                            <div id="freeze_options">
                                <div class="control-group">
                                    <div class="control-label">
                                        <label id="jform_freeze_row-lbl" for="jform_freeze_row">
                                            <?php esc_attr_e('Freeze first ', 'wptm'); ?>
                                            <select class="chzn-select observeChanges" name="freeze_row"
                                                    id="jform_freeze_row" style="width:auto">
                                                <?php for ($i = 0; $i < 6; $i++) { ?>
                                                    <option value="<?php echo esc_attr($i); ?>"><?php echo esc_attr($i); ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php esc_attr_e('rows', 'wptm'); ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="control-group" id="table_height_container">
                                    <div class="control-label">
                                        <label id="jform_table_height-lbl" for="jform_table_height">
                                            <?php esc_attr_e('Table height', 'wptm'); ?>
                                            <div class="controls inline">
                                                <input class="observeChanges input-mini" type="text"
                                                       name="jform[table_height]" id="jform_table_height" value=""
                                                       size="7"/>
                                            </div>
                                            <?php esc_attr_e('px', 'wptm'); ?>
                                        </label>
                                    </div>

                                </div>

                                <div class="control-group">
                                    <div class="control-label">
                                        <label id="jform_freeze_col-lbl" for="jform_freeze_col">
                                            <?php esc_attr_e('Freeze first ', 'wptm'); ?>
                                            <select class="chzn-select observeChanges" name="freeze_col"
                                                    id="jform_freeze_col" style="width:auto">
                                                <?php for ($i = 0; $i < 6; $i++) { ?>
                                                    <option value="<?php echo esc_attr($i); ?>"><?php echo esc_attr($i); ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php esc_attr_e('cols', 'wptm'); ?>
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="control-group" style="margin-bottom: 10px">
                                <div class="control-label">
                                    <label id="jform_enable_filters-lbl" for="jform_enable_filters">
                                        <?php esc_attr_e('Enable filters', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select class="chzn-select observeChanges" name="jform[jform_enable_filters]"
                                            id="jform_enable_filters">
                                        <option value="0"><?php esc_attr_e('No', 'wptm'); ?></option>
                                        <option value="1"><?php esc_attr_e('Yes', 'wptm'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div style="display:none" class="dbtable_params">
                                <div class="control-group" style="margin-bottom: 10px">
                                    <div class="control-label">
                                        <label id="jform_enable_pagination-lbl" for="jform_enable_pagination">
                                            <?php esc_attr_e('Enable Pagination', 'wptm'); ?> :
                                        </label>
                                    </div>
                                    <div class="controls">
                                        <select class="chzn-select observeChanges" name="jform[enable_pagination]"
                                                id="jform_enable_pagination">
                                            <option value="1"><?php esc_attr_e('Yes', 'wptm'); ?></option>
                                            <option value="0"><?php esc_attr_e('No', 'wptm'); ?></option>
                                        </select>
                                    </div>
                                </div>


                                <div class="control-group" style="margin-bottom: 10px">
                                    <div class="control-label">
                                        <label id="jform_limit_rows-lbl" for="jform_limit_rows">
                                            <?php esc_attr_e('Number rows per page', 'wptm'); ?> :
                                        </label>
                                    </div>
                                    <div class="controls">
                                        <select class="chzn-select observeChanges" name="jform[limit_rows]"
                                                id="jform_limit_rows">
                                            <option value="0"><?php esc_attr_e('Show All', 'wptm'); ?></option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="40">40</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group spreadsheet_sync">
                                <div class="control-label">
                                    <label id="spreadsheet_url-lbl" for="jform_spreadsheet_url">
                                        <?php esc_attr_e('Spreadsheet link', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <input type="text" class="observeChanges" name="jform[jform_spreadsheet_url]"
                                           id="jform_spreadsheet_url" value=""/>
                                    <a class="button button-primary" id="fetch_spreadsheet"
                                       href=""><?php esc_attr_e('Fetch data', 'wptm'); ?></a>
                                    <a href="index.php?page=wptm-foldertree&TB_iframe=true&width=600&height=550"
                                       class="thickbox button button-primary"><?php esc_attr_e('Browse', 'wptm'); ?></a>
                                </div>
                            </div>

                            <div class="control-group spreadsheet_sync">
                                <div class="control-label">
                                    <label id="auto_sync-lbl" for="jform_auto_sync">
                                        <?php esc_attr_e('Auto Sync', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select name="auto_sync" id="jform_auto_sync" class="chzn-select observeChanges">
                                        <option value="1"><?php esc_attr_e('Yes', 'wptm'); ?></option>
                                        <option value="0"><?php esc_attr_e('No', 'wptm'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group spreadsheet_sync">
                                <div class="control-label">
                                    <label id="spreadsheet_style-lbl" for="jform_spreadsheet_style">
                                        <?php esc_attr_e('Fecth style', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select name="spreadsheet_style" id="jform_spreadsheet_style" class="chzn-select observeChanges">
                                        <option value="0"><?php esc_attr_e('No', 'wptm'); ?></option>
                                        <option value="1"><?php esc_attr_e('Yes', 'wptm'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group download_button">
                                <div class="control-label">
                                    <label id="download_button-lbl" for="jform_download_button"
                                           title="<?php esc_attr_e('Add a button to download the whole table as xls file', 'wptm'); ?>">
                                        <?php esc_attr_e('Download button', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <select name="download_button" id="jform_download_button" class="chzn-select observeChanges">
                                        <option value="0"><?php esc_attr_e('No', 'wptm'); ?></option>
                                        <option value="1"><?php esc_attr_e('Yes', 'wptm'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group" style="margin-bottom: 50px"></div>
                        </div>
                    </div>

                    <!-- Cell  -->
                    <div id="cell" class="tab-pane ">

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_cell_type-lbl" for="jform_cell_type">
                                    <?php esc_attr_e('Cell type', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <select class="chzn-select observeChanges" name="jform[jform_cell_type]"
                                        id="jform_cell_type">
                                    <option value=""><?php esc_attr_e('Default', 'wptm'); ?></option>
                                    <option value="html"><?php esc_attr_e('Html', 'wptm'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_cell_background_color-lbl" for="jform_cell_background_color">
                                    <?php esc_attr_e('Cell background color', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <input class="minicolors minicolors-input observeChanges" data-position="left"
                                       data-control="hue" type="text" name="jform[jform_cell_background_color]"
                                       id="jform_cell_background_color" value="" size="7"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_cell_border_type-lbl" for="jform_cell_border_type">
                                    <?php esc_attr_e('Borders', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="clr"></div>
                            <div class="controls">
                                <div>
                                    <select class="chzn-select" name="jform[jform_cell_border_type]"
                                            id="jform_cell_border_type">
                                        <option value="solid"><?php esc_attr_e('Solid', 'wptm'); ?></option>
                                        <option value="dashed"><?php esc_attr_e('Dashed', 'wptm'); ?></option>
                                        <option value="dotted"><?php esc_attr_e('Dotted', 'wptm'); ?></option>
                                        <option value="none"><?php esc_attr_e('No border', 'wptm'); ?></option>
                                    </select>

                                    <div class="form-inline">
                                        <div class="input-append">
                                            <input type="text" name="jform[jform_cell_border_width]"
                                                   id="jform_cell_border_width" value="1"/>
                                            <button class="btn" type="button" id="cell_border_width_incr">+</button>
                                            <button class="btn" type="button" id="cell_border_width_decr">-</button>
                                        </div>
                                    </div>
                                    <input class="minicolors minicolors-input observeChanges" data-position="left"
                                           data-control="hue" type="text" name="jform[jform_cell_border_color]"
                                           id="jform_cell_border_color" value="#CCCCCC" size="7"/>
                                </div>
                                <div class="aglobuttons">
                                    <button class="btn observeChanges" name="jform[jform_cell_border_top]"
                                            type="button"><span class="sprite sprite_border_top"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_border_right]"
                                            type="button"><span class="sprite sprite_border_right"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_border_bottom]"
                                            type="button"><span class="sprite sprite_border_bottom"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_border_left]"
                                            type="button"><span class="sprite sprite_border_left"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_border_all]"
                                            type="button"><span class="sprite sprite_border_all"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_border_inside]"
                                            type="button"><span class="sprite sprite_border_inside"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_border_outline]"
                                            type="button"><span class="sprite sprite_border_outline"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_border_horizontal]"
                                            type="button"><span class="sprite sprite_border_horizontal"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_border_vertical]"
                                            type="button"><span class="sprite sprite_border_vertical"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_border_remove]"
                                            type="button"><span class="sprite sprite_border_remove"></span></button>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_cell_font_family-lbl" for="jform_cell_font_family">
                                    <?php esc_attr_e('Font', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">

                                <select class="chzn-select observeChanges" name="jform[jform_cell_font_family]"
                                        id="jform_cell_font_family">
                                    <option value="Arial">Arial</option>
                                    <option value="Arial Black">Arial Black</option>
                                    <option value="Comic Sans MS">Comic Sans MS</option>
                                    <option value="Courier New">Courier New</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Impact">Impact</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Trebuchet MS">Trebuchet MS</option>
                                    <option value="Verdana">Verdana</option>
                                </select>

                                <div class="form-inline">
                                    <div class="input-append">
                                        <input class="observeChanges" type="text" name="jform[jform_cell_font_size]"
                                               id="jform_cell_font_size" value="13"/>
                                        <button class="btn" type="button" id="cell_font_size_incr">+</button>
                                        <button class="btn" type="button" id="cell_font_size_decr">-</button>
                                    </div>
                                </div>
                                <input class="minicolors minicolors-input observeChanges" data-position="left"
                                       data-control="hue" type="text" name="jform[jform_cell_font_color]"
                                       id="jform_cell_font_color" value="#000000" size="7"/>
                                <div class="aglobuttons">
                                    <button class="btn observeChanges" name="jform[jform_cell_font_bold]" type="button">
                                        <span class="sprite sprite_text_bold"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_font_underline]"
                                            type="button"><span class="sprite sprite_text_underline"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_font_italic]"
                                            type="button"><span class="sprite sprite_text_italic"></span></button>
                                    <br/>
                                    <button class="btn observeChanges" name="jform[jform_cell_align_left]"
                                            type="button"><span class="sprite sprite_text_align_left"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_align_center]"
                                            type="button"><span class="sprite sprite_text_align_center"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_align_right]"
                                            type="button"><span class="sprite sprite_text_align_right"></span></button>
                                    <button class="btn observeChanges" name="jform[jform_cell_align_justify]"
                                            type="button"><span class="sprite sprite_text_align_justify"></span>
                                    </button>
                                    <br/>
                                    <button class="btn observeChanges" name="jform[jform_cell_vertical_align_top]"
                                            type="button"><span class="sprite sprite_vertical_align_top"></span>
                                    </button>
                                    <button class="btn observeChanges" name="jform[jform_cell_vertical_align_middle]"
                                            type="button"><span class="sprite sprite_vertical_align_middle"></span>
                                    </button>
                                    <button class="btn observeChanges" name="jform[jform_cell_vertical_align_bottom]"
                                            type="button"><span class="sprite sprite_vertical_align_bottom"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_row_height-lbl" for="jform_row_height">
                                    <?php esc_attr_e('Row height', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <input class="observeChanges input-mini" type="text" name="jform[jform_row_height]"
                                       id="jform_row_height" value="" size="7"/>
                            </div>
                            <div class="control-label">
                                <label id="jform_col_width-lbl" for="jform_col_width">
                                    <?php esc_attr_e('Column width', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <input class="observeChanges input-mini" type="text" name="jform[jform_col_width]"
                                       id="jform_col_width" value="" size="7"/>
                            </div>
                        </div>

                        <?php if (isset($this->params['enable_tooltip']) && (string)$this->params['enable_tooltip'] === '1') : ?>
                            <div class="control-group">
                                <label id="jform_tooltip_content-lbl" for="jform_tooltip_content">
                                    <a id="editToolTip" class="button button-primary button-big"
                                       title="<?php esc_attr_e('Edit', 'wptm'); ?>"
                                       href="#wptm_editToolTip"><?php esc_attr_e('Edit Tooltip', 'wptm'); ?></a>
                                </label>

                                <div id="wptm_editToolTip" style="display:none">
                                    <div id="tooltip_editor">
                                        <textarea id="tooltip_content" name="tooltip_content"
                                                  class="observeChanges"></textarea>
                                        <a id="saveToolTipbtn" class="button button-primary button-large"
                                           title="<?php esc_attr_e('Save', 'wptm'); ?>"
                                           href="javascript:void(0)"><?php esc_attr_e('Save', 'wptm'); ?></a>
                                        <a id="cancelToolTipbtn" class="button button-large"
                                           title="<?php esc_attr_e('Cancel', 'wptm'); ?>"
                                           href="javascript:void(0)"><?php esc_attr_e('Cancel', 'wptm'); ?></a>
                                    </div>
                                </div>

                                <div class="control-label">
                                    <label id="jform_tooltip_width-lbl" for="jform_tooltip_width">
                                        <?php esc_attr_e('Tooltip width (in px)', 'wptm'); ?> :
                                    </label>
                                </div>
                                <div class="controls">
                                    <input class="observeChanges input-mini" type="text"
                                           name="jform[jform_tooltip_width]" id="jform_tooltip_width" value=""
                                           size="7"/>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                    <!-- More tab -->
                    <div id="css" class="tab-pane ">

                        <div class="control-group spreadsheet_sync">
                            <?php if ((string)$this->params['enable_import_excel'] === '1') : ?>
                                <div class="progress progress-striped active" role="progressbar" style="display: none;"
                                     aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                    <div class="bar progress-bar-success data-dz-uploadprogress" style="width:0%;"
                                         data-dz-uploadprogress></div>
                                </div>
                                <div class="controls">
                                    <div class="control-label">
                                        <label id="import_style-lbl" for="import_style">
                                            <?php esc_attr_e('Import/Export xls', 'wptm'); ?> :
                                        </label>
                                    </div>
                                    <div class="controls" style="margin-bottom: 10px;">
                                        <select class="chzn-select" name="jform[import_style]" id="jform_import_style">
                                            <option value="1"><?php esc_attr_e('Data + styles', 'wptm'); ?></option>
                                            <option value=""><?php esc_attr_e('Data only', 'wptm'); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="controls pull-left">
                                    <form action="admin-ajax.php?action=Wptm&task=excel.import" id="proc-excel"
                                          class="dropzone pull-left" accept-charset="utf-8">
                                        <a title="Import Excel sheet, import just the data or sheet data + style" href="javascript:void(0);"
                                           class="dz-btn nephritis-flat-button"><?php esc_attr_e('Import Excel', 'wptm') ?></a>
                                    </form>
                                </div>
                                <div class="controls pull-right" style="margin-right:10px">
                                    <a title="Export Excel sheet, import just the data or sheet data + style"
                                       href="javascript:void(0);" class="carrot-flat-button" id="export-excel"
                                       data-format-excel="<?php echo esc_attr($this->params['export_excel_format']); ?>">
                                        <?php esc_attr_e('Export Excel', 'wptm') ?></a>
                                </div>
                            <?php endif ?>
                        </div>


                        <div class="control-group" style="clear:left;">
                            <div class="control-label">
                                <label id="jform_date_format_col-lbl" for="jform_date_format">
                                    <?php esc_attr_e('Date formats ', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <input class="observeChanges" id="jform_date_format" name="jform[jform_date_format]" value="<?php echo esc_attr($date_formats); ?>" type="text">
                                <a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="__blank">Date format</a>
                            </div>
                        </div>

                        <div class="control-group" style="clear:left;">
                            <div class="control-label">
                                <label id="jform_symbol_position_col-lbl" for="jform_symbol_position">
                                    <?php esc_attr_e('Symbol position', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls" style="margin-bottom: 10px;">
                                <select class="chzn-select observeChanges" name="jform[jform_symbol_position]" id="jform_symbol_position">
                                    <option value="0"><?php echo esc_attr_e('Before', 'wptm'); ?></option>
                                    <option value="1"><?php echo esc_attr_e('After', 'wptm'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group" style="clear:left;">
                            <div class="control-label">
                                <label id="jform_currency_sym_col-lbl" for="jform_currency_sym">
                                    <?php esc_attr_e('Currency symbol(s) ', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <input id="jform_currency_sym" class="observeChanges" name="jform[jform_currency_sym]" value="<?php echo esc_attr($currency_sym); ?>" type="text">
                            </div>
                        </div>

                        <div class="control-group" style="clear:left;">
                            <div class="control-label">
                                <label id="jform_decimal_sym_col-lbl" for="jform_decimal_sym">
                                    <?php esc_attr_e('Decimal symbol ', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <input id="jform_decimal_sym" class="observeChanges" name="jform[jform_decimal_sym]" value="<?php echo esc_attr($decimal_sym); ?>" type="text">
                            </div>
                        </div>

                        <div class="control-group" style="clear:left;">
                            <div class="control-label">
                                <label id="jform_decimal_count_col-lbl" for="jform_decimal_count">
                                    <?php esc_attr_e('Decimal count ', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <input id="jform_decimal_count" class="observeChanges" name="jform[jform_decimal_count]" value="<?php echo esc_attr($decimal_count); ?>" type="text">
                            </div>
                        </div>

                        <div class="control-group" style="clear:left;">
                            <div class="control-label">
                                <label id="jform_thousand_sym_col-lbl" for="jform_thousand_sym">
                                    <?php esc_attr_e('Thousand symbol ', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <input id="jform_thousand_sym" class="observeChanges" name="jform[jform_thousand_sym]" value="<?php echo esc_attr($thousand_sym); ?>" type="text">
                            </div>
                        </div>


<!--                        select list user have role own category-->
                        <div class="control-group" style="clear:left;">
                            <div class="control-label">
                                <label id="jform_category_owner_col-lbl" for="jform_category_owner">
                                    <?php esc_attr_e('User category owner', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="input-append category-own-select">
                                <?php
                                $security = wp_create_nonce('wptm_user');
                                $url_selectuser = 'admin.php?page=wptm&amp;task=user.display&amp;check=1&amp;noheader=true&amp;';
                                $url_selectuser .= 'fieldtype=field-user-category-own&amp;cataction=false&amp;&amp;security='.$security.'&amp;TB_iframe=true&amp;height=400&amp;width=800;';
                                ?>

                                <input type="text" id="category-own-select" value="" placeholder="Select a User" readonly="" class="field-user-category-own-name inputbox input-block-level">

                                <a href="<?php echo esc_url_raw(admin_url() . $url_selectuser); ?>"
                                   role="button" class="thickbox btn button-select" title="Select User">
                                    <span class="icon-user"></span>
                                </a>
                                <a class="btn user-clear-category"><span class="icon-remove"></span></a>
                            </div>
                        </div>

<!--                        select list user have role own table-->
                        <div class="control-group" style="clear:left;">
                            <div class="control-label">
                                <label id="jform_table_owner_col-lbl" for="jform_table_owner">
                                    <?php esc_attr_e('User table owner', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="input-append category-own-select">
                                <?php
                                $url_selectuser = 'admin.php?page=wptm&amp;task=user.display&amp;check=0&amp;noheader=true&amp;';
                                $url_selectuser .= 'fieldtype=field-user-category-own&amp;cataction=true&amp;security='.$security.'&amp;TB_iframe=true&amp;height=400&amp;width=800';
                                ?>

                                <input type="text" id="table-own-select" value="" placeholder="Select a User" readonly="" class="field-user-table-own-name inputbox input-block-level">

                                <a href="<?php echo esc_url_raw(admin_url() . $url_selectuser); ?>"
                                   role="button" class="thickbox btn button-select" title="Select User">
                                    <span class="icon-user"></span>
                                </a>
                                <a class="btn user-clear-table"><span class="icon-remove"></span></a>
                            </div>
                        </div>

                        <div class="control-group" style="clear:left;">
                            <div class="control-label">
                                <label id="jform_responsive_col-lbl" for="jform_shortcode">
                                    <?php esc_attr_e('Shortcode', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <p><?php esc_attr_e('Table', 'wptm'); ?> <input readonly="readonly" id="shortcode_table"
                                                                        value="" type="text"></p>
                                <div id="shortcode_charts"></div>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_cell_padding-lbl" for="jform_cell_padding">
                                    <?php esc_attr_e('Paddings', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <div style="height: 170px; width: 210px; border: 1px solid #ffffff; margin: 0 auto; position: relative; opacity: 0.8;">
                                    <div style="height: 80px; width: 80px; border: 1px dashed #ffffff; margin: 45px auto; text-align: center; line-height: 80px;font-size:12px;">
                                        Lorem Ipsum
                                    </div>
                                    <div style="position: absolute; top: 70px; left: 3px;">
                                        <input style="width: 30px;" type="text" name="jform[jform_cell_padding_left]"
                                               id="jform_cell_padding_left" class="observeChanges" value="0">px
                                    </div>
                                    <div style="position: absolute; top: 9px; left: 85px;">
                                        <input style="width: 30px;" type="text" name="jform[jform_cell_padding_top]"
                                               id="jform_cell_padding_top" class="observeChanges" value="0">px
                                    </div>
                                    <div style="position: absolute; top: 70px; right: 3px;">
                                        <input style="width: 30px;" type="text" name="jform[jform_cell_padding_right]"
                                               id="jform_cell_padding_right" class="observeChanges" value="0">px
                                    </div>
                                    <div style="position: absolute; bottom: 0px; left: 85px;">
                                        <input style="width: 30px;" type="text" name="jform[jform_cell_padding_bottom]"
                                               id="jform_cell_padding_bottom" class="observeChanges" value="0">px
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_cell_background_radius-lbl" for="jform_cell_background_radius">
                                    <?php esc_attr_e('Cell background radius', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <div style="height: 170px; width: 210px; border: 1px solid #FFF; margin: 0 auto; position: relative; opacity: 0.8;">
                                    <div style="height: 80px; width: 80px; margin: 45px auto; text-align: center; line-height: 80px; border-radius: 5px; background-color: #ffffff;font-size:12px;">
                                        Lorem Ipsum
                                    </div>
                                    <div style="position: absolute; top: 15px; left: 15px;">
                                        <input style="width: 30px;" type="text"
                                               name="jform[jform_cell_background_radius_left_top]"
                                               id="jform_cell_background_radius_left_top" class="observeChanges"
                                               value="0">px
                                    </div>
                                    <div style="position: absolute; top: 15px; right: 3px;">
                                        <input style="width: 30px;" type="text"
                                               name="jform[jform_cell_background_radius_right_top]"
                                               id="jform_cell_background_radius_right_top" class="observeChanges"
                                               value="0">px
                                    </div>
                                    <div style="position: absolute; bottom: 15px; right: 3px;">
                                        <input style="width: 30px;" type="text"
                                               name="jform[jform_cell_background_radius_right_bottom]"
                                               id="jform_cell_background_radius_right_bottom" class="observeChanges"
                                               value="0">px
                                    </div>
                                    <div style="position: absolute; bottom: 15px; left: 15px;">
                                        <input style="width: 30px;" type="text"
                                               name="jform[jform_cell_background_radius_left_bottom]"
                                               id="jform_cell_background_radius_left_bottom" class="observeChanges"
                                               value="0">px
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_css-lbl" for="jform_css">
                                    <?php esc_attr_e('Custom css for this table', 'wptm'); ?> :
                                </label>
                                <a id="customCssbtn" class="button button-primary button-big"
                                   title="<?php esc_attr_e('Custom Css', 'wptm'); ?>"
                                   href="#wptm_customCSS"><?php esc_attr_e('Edit css', 'wptm'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="alternating_color" class="">
                <div class="alternating_color_top">
                    <span><?php esc_attr_e('Automatic styling', 'wptm'); ?></span>
                    <span class="cancel dashicons dashicons-no-alt"></span>
                </div>
                <div>
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_alternate_row_even_color-lbl" for="jform_alternate_row_even_color">
                                <?php esc_attr_e('Apply to range', 'wptm'); ?> :
                            </label>
                        </div>
                        <div class="controls">
                            <input id="cellRangeLabelAlternate" class="input-mini" style="width: 100px;">
                        </div>
                    </div>
                    <hr/>
                    <div class="control-group">
                        <div class="banding-header-footer-checkbox-wrapper">
                            <div class="banding-checkbox-wrapper">
                                <input class="banding-header-checkbox" type="checkbox" value="">
                                <span class="banding-header-checkbox-label" >Header styling</span></div>
                            <div class="banding-checkbox-wrapper">
                                <input class="banding-footer-checkbox" type="checkbox" value="">
                                <span class="banding-footer-checkbox-label" >Footer styling</span></div>
                        </div>
                    </div>
                    <hr/>
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_alternate_color" for="jform_alternate_color">
                                <?php esc_attr_e('Automatic styling', 'wptm'); ?> :
                            </label>
                        </div>
                        <div class="controls formatting_style">
                            <?php
                            if (isset($this->params['alternate_color'])) {
                                $arrayValue = explode('|', $this->params['alternate_color']);
                            } else {
                                $defaultAlternateColor = '#bdbdbd|#ffffff|#f3f3f3|#ffffff';
                                $defaultAlternateColor .= '|#4dd0e1|#ffffff|#e0f7fa|#a2e8f1';
                                $defaultAlternateColor .= '|#63d297|#ffffff|#e7f9ef|#afe9ca';
                                $defaultAlternateColor .= '|#f7cb4d|#ffffff|#fef8e3|#fce8b2';
                                $defaultAlternateColor .= '|#f46524|#ffffff|#ffe6dd|#ffccbc';
                                $defaultAlternateColor .= '|#5b95f9|#ffffff|#e8f0fe|#acc9fe';
                                $defaultAlternateColor .= '|#26a69a|#ffffff|#ddf2f0|#8cd3cd';
                                $defaultAlternateColor .= '|#78909c|#ffffff|#ebeff1|#bbc8ce';
                                $arrayValue            = explode('|', $defaultAlternateColor);
                            }

                            $count = count($arrayValue);
                            $html  = '';
                            for ($i = 0; $i < $count / 4; $i ++) {
                                $i4    = $i % 4;
                                $i16   = $i * 4;
                                $value = array(
                                    $arrayValue[$i16],
                                    $arrayValue[$i16 + 1],
                                    $arrayValue[$i16 + 2],
                                    $arrayValue[$i16 + 3]
                                );

                                $html .= renderListStyle($value, $i4);
                            }
                            ?>

                            <?php
                            //phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- The inner variables were esc
                            echo $html;
                            ?>
                        </div>
                    </div>
                    <input id="alternate_color_value" type="text" style="display: none;" value="">
                    <a class="button button-primary" id="alternate_color_done" href="">Done</a>
                    <a class="button button-primary" id="alternate_color_cancel" href="">Cancel</a>
                </div>
            </div>
        </div>
        <div id="wptm_customCSS" style="display:none">
            <textarea rows="10" cols="50" style="width:400px" name="jform[jform_css]" id="jform_css"></textarea>
            <a id="saveCssbtn" class="button button-primary button-large" title="<?php esc_attr_e('Save', 'wptm'); ?>"
               href="javascript:void(0)"><?php esc_attr_e('Save', 'wptm'); ?></a>
            <a id="cancelCssbtn" class="button button-large" title="<?php esc_attr_e('Cancel', 'wptm'); ?>"
               href="javascript:void(0)"><?php esc_attr_e('Cancel', 'wptm'); ?></a>
        </div>
        <!-- Chart parameter -->
        <div id="rightcol2" style="display: none">
            <?php if (Utilities::getInput('caninsert', 'GET', 'bool')) : ?>
                <a id="insertChart" class="button button-primary button-big" href="javascript:void(0);"
                   onclick="if (window.parent) {insertChart();}"><?php esc_attr_e('Insert this chart', 'wptm'); ?></a>
            <?php endif; ?>

            <div class="">

                <ul class="nav nav-tabs" id="configChart">
                    <li class="active"><a data-toggle="tab" href="#chart"><?php esc_attr_e('Chart', 'wptm'); ?></a></li>
                </ul>

                <div class="tab-content" id="chartTabContent">
                    <div id="chart" class="tab-pane active">
                        <div class="control-group">
                            <div class="chart-styles">
                                <ul>
                                    <?php foreach ($this->chartTypes as $chartType) : ?>
                                        <li><a href="#" title="<?php echo esc_attr($chartType->name); ?>"
                                               data-id="<?php echo esc_attr($chartType->id); ?>"><img
                                                        alt="<?php echo esc_attr($chartType->name); ?>"
                                                        src="<?php echo esc_url_raw(WP_TABLE_MANAGER_PLUGIN_URL . 'app/admin/assets/images/charts/' . $chartType->image); ?>"/></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_dataSelected-lbl" for="jform_dataSelected">
                                    <?php esc_attr_e('Selected Range', 'wptm'); ?> : <input class="cellRangeLabel input-mini" style="width: 100px;">
                                    <a id="changerChart" class="button button-primary button-big" style="   margin: 5px 10px;" href="#" title="Edit"><?php esc_attr_e('Change', 'wptm'); ?></a>
                                </label>
                            </div>
                            <div class="controls">

                            </div>

                            <div class="control-label">
                                <label id="jform_dataUsing-lbl" for="jform_dataUsing">
                                    <?php esc_attr_e('Switch Row/Column', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <select class="chzn-select observeChanges2" name="jform[dataUsing]"
                                        id="jform_dataUsing">
                                    <option value="row"><?php esc_attr_e('Row', 'wptm'); ?></option>
                                    <option value="column"><?php esc_attr_e('Column', 'wptm'); ?></option>
                                </select>
                            </div>

                            <div class="control-label">
                                <label id="jform_useFirstRowAsLabels-lbl" for="jform_useFirstRowAsLabels">
                                    <?php esc_attr_e('Use first row/column as labels', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <select class="chzn-select observeChanges2" name="jform[useFirstRowAsLabels]"
                                        id="jform_useFirstRowAsLabels">
                                    <option value="yes"><?php esc_attr_e('Yes', 'wptm'); ?></option>
                                    <option value="no"><?php esc_attr_e('No', 'wptm'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_chart_width-lbl" for="jform_chart_width">
                                    <?php esc_attr_e('Chart width', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <div class="form-inline">
                                    <input class="observeChanges2 input-mini" type="text" name="jform[chart_width]"
                                           id="jform_chart_width" value="" size="7"/>
                                </div>
                            </div>

                            <div class="control-label">
                                <label id="jform_chart_height-lbl" for="jform_chart_height">
                                    <?php esc_attr_e('Chart height', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <div class="form-inline">
                                    <input class="observeChanges2 input-mini" type="text" name="jform[chart_height]"
                                           id="jform_chart_height" value="" size="7"/>
                                </div>
                            </div>

                        </div>

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_table_align-lbl" for="jform_chart_align">
                                    <?php esc_attr_e('Align Chart', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <select class="chzn-select observeChanges2" name="jform[chart_align]"
                                        id="jform_chart_align">
                                    <option value="center"><?php esc_attr_e('Center', 'wptm'); ?></option>
                                    <option value="right"><?php esc_attr_e('Right', 'wptm'); ?></option>
                                    <option value="left"><?php esc_attr_e('Left', 'wptm'); ?></option>
                                    <option value="none"><?php esc_attr_e('None', 'wptm'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_table_align-lbl" for="jform_dataset_select">
                                    <?php esc_attr_e('Dataset', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <select class="chzn-select observeChanges3" name="jform[dataset_select]"
                                        id="jform_dataset_select">
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_dataset_color-lbl" for="jform_dataset_color">
                                    <?php esc_attr_e('Color', 'wptm'); ?> :
                                </label>
                            </div>
                            <div class="controls">
                                <input class="minicolors minicolors-input observeChanges2" data-position="left"
                                       data-control="hue" type="text" name="jform[dataset_color]"
                                       id="jform_dataset_color" value="" size="7"/>
                            </div>
                        </div>

                        <br/><br/>
                    </div>
                </div>
            </div>
        </div>

        <div id="pwrapper">
            <div id="wpreview">
                <div id="preview">
                    <span id="savedInfo"
                          style="display:none;"><?php esc_attr_e('All modifications were saved', 'wptm'); ?></span>
                    <span id="saveError"
                          style="display:none;"><?php esc_attr_e('Error! You have an error in the date calculation.', 'wptm'); ?></span>
                    <ul class="nav nav-tabs" id="mainTable">
                        <li class="active"><a id="tableTitle" data-toggle="tab" href="#dataTable"><?php esc_attr_e('Table', 'wptm'); ?></a></li>
                        <li class="add_chart">
                        </li>
                        <div id="search_term">
                            <input name="filter[search_drp]" id="dp-form-search" type="search">
                            <i class="dashicons dashicons-search"></i>
                        </div>
                    </ul>
                    <div id="mainTabContent" class="tab-content">
                        <div id="dataTable" class="tab-pane active">
                            <div>
                                <h3 id="titleError"></h3>
                                <div class="clearfix"></div>
                                <div id="tableContainer" style="overflow:hidden;"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <input type="hidden" name="id_category" value=""/>
        </div>
    </div>

    <script>
        var listRoleCategory = <?php echo json_encode($categorys_role); ?>;
        var idUser = <?php echo json_encode($this->idUser); ?>;
        var wptm_isAdmin = <?php echo (int)current_user_can('manage_options'); ?>;
        jQuery(document).ready(function ($) {
            $('#mainTable #newchart').appendTo('#wptm_create_new');

            var myOptions = {
                width: 220,
                // a callback to fire whenever the color changes to a valid color
                change: function (event, ui) {

                    var hexcolor = $(this).wpColorPicker('color');
                    $(event.target).val(hexcolor);
                    $(event.target).trigger('change');
                }
            }

            $('.minicolors').wpColorPicker(myOptions);

        })

        var wptmChangeWait;
        function wptm_tooltipChange() {
            clearTimeout(wptmChangeWait);
            wptmChangeWait = setTimeout(function () {
                jQuery("#tooltip_content").trigger("change");
            }, 1000);
        }
        var enable_autosave = true;
        var default_value = {};
        default_value.date_formats = '<?php echo esc_attr($date_formats);?>';
        default_value.symbol_position = '<?php echo esc_attr($symbol_position);?>';
        default_value.currency_symbol = '<?php echo esc_attr($currency_sym);?>';
        default_value.decimal_symbol = '<?php echo esc_attr($decimal_sym);?>';
        default_value.decimal_count = '<?php echo esc_attr($decimal_count);?>';
        default_value.thousand_symbol = '<?php echo esc_attr($thousand_sym);?>';
        <?php if (isset($this->params['enable_autosave']) && (string)$this->params['enable_autosave'] === '0') : ?>
        enable_autosave = false;
        <?php endif;?>

        <?php
        $id_table = Utilities::getInt('id_table'); ?>
        var idTable = <?php echo esc_attr($id_table);?> ;
    </script>

<?php
/**
 * OpenItem
 *
 * @param object  $category Category
 * @param integer $key      Key
 * @param boolean $check2   Check
 *
 * @return string
 */
function openItem($category, $key, $check2)
{
    return '<li class="dd-item ' . ($check2 ? 'hasRole' : '') . ' dd3-item ' . ($key === 1 ? 'active' : '') . '" data-id-category="' . $category->id . '">
        <div class="dd-handle dd3-handle"></div>
        <div class="dd-content dd3-content ui-droppable ' . ($check2 ? 'hasRole' : '') . '">
            <div class="content_list_options">
                <a class="trash"><i class="icon-trash"></i>Delete category</a>
                <a class="edit"><i class="icon-edit-category"></i>Edit title</a>
            </div>
            <a href="" class="t">
                <span class="title dd-handle">' . $category->title . '</span>
            </a>
            <a href="" class="list_options">
                <i class="dashicons dashicons-editor-ul"></i>
            </a>
        </div>';
}

/**
 * CloseItem
 *
 * @param integer $category Categoer
 *
 * @return string
 */
function closeItem($category)
{
    return '</li>';
}

/**
 * Get title category
 *
 * @param object $category Title category
 *
 * @return string
 */
function itemContent($category)
{
    return '<div class="dd-handle dd3-handle"></div>
    <div class="dd-content dd3-content"
        <i class="icon-chevron-right"></i>
        <a class="edit"><i class="icon-edit"></i></a>
        <a href="" class="t">
            <span class="title">' . $category->title . '</span>
        </a>
    </div>';
}

/**
 * Open list category
 *
 * @param integer $category Category
 *
 * @return string
 */
function openlist($category)
{
    return '<ol class="dd-list">';
}

/**
 * Close list category
 *
 * @param integer $category Category
 *
 * @return string
 */
function closelist($category)
{
    return '</ol>';
}

/**
 * Render list style color
 *
 * @param array   $value Style value
 * @param integer $order Order number
 *
 * @return string
 */
function renderListStyle($value, $order)
{
    $html = '';
    $html .= '<td class="td_' . $order . '">';
    $html .= '<div class="pane-color-tile">';
    $html .= '<div class="pane-color-tile-header pane-color-tile-band" data-value="' . $value[0] . '" style="background-color:' . $value[0] . ';"></div>';
    $html .= '<div class="pane-color-tile-1 pane-color-tile-band" data-value="' . $value[1] . '" style="background-color:' . $value[1] . ';"></div>';
    $html .= '<div class="pane-color-tile-2 pane-color-tile-band" data-value="' . $value[2] . '" style="background-color:' . $value[2] . ';"></div>';
    $html .= '<div class="pane-color-tile-footer pane-color-tile-band" data-value="' . $value[3] . '" style="background-color:' . $value[3] . ';"></div>';
    $html .= '</div>';
    $html .= '</td>';
    return $html;
}
?>
