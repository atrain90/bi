<?php

use Joomunited\WPFramework\v1_0_5\Application;
use Joomunited\WPFramework\v1_0_5\Model;
use Joomunited\WPFramework\v1_0_5\Utilities;

/**
 * Class WPFDWidgetSearch
 */
class WPFDWidgetSearch extends WP_Widget
{

    /**
     * WPFDWidgetSearch constructor.
     */
    public function __construct()
    {
        $widget_ops = array('classname' => 'widget_wpfd_search', 'description' => esc_html__('A search form.', 'wpfd'));
        parent::__construct('wpfd_search', esc_html__('WP File Download Search', 'wpfd'), $widget_ops);
    }

    /**
     * Method display search files
     *
     * @param array $args     Options
     * @param array $instance Instance
     *
     * @return void
     */
    public function widget($args, $instance)
    {
        WpfdHelperFile::wpfdAssets();
        WpfdHelperFile::wpfdAssetsSearch();
        $widget_title = empty($instance['title']) ? esc_html__('Search', 'wpfd') : $instance['title'];
        $title = apply_filters('widget_title', $widget_title, $instance, $this->id_base);

        $filters = array();
        $q = Utilities::getInput('q', 'GET', 'string');

        if (!empty($q)) {
            $filters['q'] = $q;
        }
        $catid = Utilities::getInput('catid', 'GET', 'string');
        if (!empty($catid)) {
            $filters['catid'] = $catid;
        }

        $ftags = Utilities::getInput('ftags', 'GET', 'none');
        if (is_array($ftags)) {
            $ftags = array_unique($ftags);
            $ftags = implode(',', $ftags);
        } else {
            $ftags = Utilities::getInput('ftags', 'GET', 'string');
        }

        if (!empty($ftags)) {
            $filters['ftags'] = $ftags;
        }
        $cfrom = Utilities::getInput('cfrom', 'GET', 'string');
        if (!empty($cfrom)) {
            $filters['cfrom'] = $cfrom;
        }
        $cto = Utilities::getInput('cto', 'GET', 'string');
        if (!empty($cto)) {
            $filters['cto'] = $cto;
        }
        $ufrom = Utilities::getInput('ufrom', 'GET', 'string');
        if (!empty($ufrom)) {
            $filters['ufrom'] = $ufrom;
        }
        $uto = Utilities::getInput('uto', 'GET', 'string');
        if (!empty($uto)) {
            $filters['uto'] = $uto;
        }

        $ordering = Utilities::getInput('ordering', 'GET', 'string');
        $dir = Utilities::getInput('dir', 'GET', 'string');
        $dir = $dir === null ? 'asc' : 'desc';

        $modelCategories = Model::getInstance('categories');
        $model = Model::getInstance('search');
        $modelConfig = Model::getInstance('config');
        if (method_exists($modelConfig, 'getGlobalConfig')) {
            $config = $modelConfig->getGlobalConfig();
        } elseif (method_exists($modelConfig, 'getConfig')) {
            $config = $modelConfig->getConfig();
        } else {
            return;
        }
        $categories = $modelCategories->getLevelCategories();

        $tags = get_terms('wpfd-tag', array(
            'orderby' => 'count',
            'hide_empty' => 0,
        ));

        $allTagsFiles = '';
        if ($tags) {
            $TagsFiles = array();
            foreach ($tags as $tag) {
                $TagsFiles[] = '' . esc_html($tag->slug);
            }
            $allTagsFiles = '["' . implode('","', $TagsFiles) . '"]';
        }

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Allow html
        echo $args['before_widget'];
        if ($title) {
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Allow html
            echo $args['before_title'] . $title . $args['after_title'];
        }
        ?>

        <script>
            wpfdajaxurl = "<?php echo wpfd_sanitize_ajax_url(Application::getInstance('Wpfd')->getAjaxUrl()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- keep this, if not it error ?>";
            var filterData = null;
            var defaultAllTags = <?php echo ($allTagsFiles !== '' ? $allTagsFiles : '[]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allready esc above?>;
            jQuery(document).ready(function () {

                <?php if ((int)$instance['tag_filter'] === 1 && (string)$instance['display_tag'] === 'searchbox') { ?>
                var availTags = [];
                <?php if (!empty($allTagsFiles)) { ?>
                availTags = <?php echo $allTagsFiles; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allready esc above ?>;
                <?php } ?>
                jQuery("#filter_tags").tagit({
                    availableTags: availTags,
                    allowSpaces: true
                });
                <?php } ?>

                <?php if (!empty($filters)) { ?>
                filterData = <?php echo json_encode($filters);?>;
                <?php } ?>

                window.history.pushState(filterData, "", window.location);
            });
        </script>

        <form action="<?php echo wpfd_sanitize_ajax_url(Application::getInstance('Wpfd')->getAjaxUrl()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- keep this, if not it error ?>task=search.query"
              name="widget_search" id="widget_search" method="post">

            <div class="box-search-filter">
                <div class="only-file input-group clearfix">
                    <input type="text" class="pull-left required" name="q" id="txtfilename"
                           placeholder="<?php esc_html_e('Search ...', 'wpfd'); ?>"
                           value="<?php echo isset($filters['q']) ? esc_attr($filters['q']) : '' ?>"
                    />
                    <button type="submit" id="widget_btnsearch" class="pull-left"><i class="wpfd-icon-search"></i>
                    </button>
                </div>

                <div class="by-feature feature-border">
                    <div class="top clearfix">
                        <div class="pull-left"><strong><?php esc_html_e('Filter', 'wpfd') ?></strong></div>
                        <div class="pull-right"><i class="feature-toggle" class="feature-toggle-up"></i></div>
                    </div>
                    <div class="feature clearfix row-fluid">
                        <?php if ((int)$instance['cat_filter'] === 1) { ?>
                            <div class="categories-filtering">
                                <h4><?php esc_html_e('Categories', 'wpfd'); ?></h4>
                                <div class="ui-widget">
                                    <select title="" id="filter_catid" class="chzn-select" name="catid">
                                        <option value=""><?php echo ' ' . esc_html__('Select one', 'wpfd'); ?></option>
                                        <?php
                                        if (count($categories) > 0) {
                                            foreach ($categories as $key => $category) {
                                                if (isset($filters['catid']) && (int)$filters['catid'] === $category->term_id) {
                                                    $html = '<option selected="selected"  value="';
                                                    $html .= esc_attr($category->term_id) . '">';
                                                    $html .= str_repeat('-', $category->level - 1);
                                                    $html .= ' ' . esc_html($category->name) . '</option>';
                                                    echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Allow html
                                                } else {
                                                    $html = '<option  value="' . esc_attr($category->term_id) . '">';
                                                    $html .= str_repeat('-', $category->level - 1);
                                                    $html .= ' ' . esc_html($category->name) . '</option>';
                                                    echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Allow html
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ((int)$instance['tag_filter'] === 1 && (string)$instance['display_tag'] === 'searchbox') { ?>
                            <div class="tags-filtering">
                                <h4><?php esc_html_e('Tags', 'wpfd'); ?></h4>
                                <input title="" type="text" id="filter_tags" name="ftags" class="tagit"
                                       value="<?php echo isset($filters['ftags']) ? esc_attr($filters['ftags']) : ''; ?>"/>
                            </div>
                        <?php } ?>
                        <?php if ((int)$instance['creation_date'] === 1) { ?>
                            <div class="creation-date">
                                <h4><?php esc_html_e('Creation date', 'wpfd'); ?></h4>
                                <div><span class="lbl-date"><?php esc_html_e('From', 'wpfd'); ?> </span>
                                    <input title="" class="input-date" type="text" data-min="cfrom" name="cfrom"
                                           value="<?php echo isset($filters['cfrom']) ? esc_attr($filters['cfrom']) : ''; ?>"
                                           id="widget_cfrom"/>
                                    <i data-id="widget_cfrom" class="icon-date icon-calendar"></i>
                                </div>
                                <div><span class="lbl-date"><?php esc_html_e('To', 'wpfd'); ?></span>
                                    <input title="" class="input-date" data-min="cfrom" type="text"
                                           name="cto" id="widget_cto"
                                           value="<?php echo isset($filters['cto']) ? esc_attr($filters['cto']) : ''; ?>"/>
                                    <i data-id="widget_cto" data-min="cfrom" class="icon-date icon-calendar"></i>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ((int)$instance['update_date'] === 1) { ?>
                            <div class="update-date">
                                <h4><?php esc_html_e('Update date', 'wpfd'); ?></h4>
                                <div><span class="lbl-date"><?php esc_html_e('From', 'wpfd'); ?> </span>
                                    <input title="" class="input-date" type="text" data-min="ufrom"
                                           value="<?php echo isset($filters['ufrom']) ? esc_attr($filters['ufrom']) : ''; ?>"
                                           name="ufrom" id="widget_ufrom"/>
                                    <i data-id="widget_ufrom" class="icon-date icon-calendar"></i>
                                </div>
                                <div><span class="lbl-date"><?php esc_html_e('To', 'wpfd'); ?> </span>
                                    <input title="" class="input-date" type="text" data-min="ufrom"
                                           value="<?php echo isset($filters['uto']) ? esc_attr($filters['uto']) : ''; ?>"
                                           name="uto" id="widget_uto"/>
                                    <i data-id="widget_uto" data-min="ufrom" class="icon-date icon-calendar"></i>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ((int)$instance['tag_filter'] === 1 && (string)$instance['display_tag'] === 'checkbox') { ?>
                            <div class="clearfix row-fluid">
                                <div class="chk-tags-filtering">
                                    <h4 style="text-align:left"><?php esc_html_e('Tags', 'wpfd'); ?></h4>
                                    <input type="hidden" id="input_tags" name="ftags"
                                           value="<?php echo isset($filters['ftags']) ? esc_attr($filters['ftags']) : ''; ?>"/>
                                    <?php
                                    $allTags = str_replace(array('[', ']', '"'), '', $allTagsFiles);
                                    if ((string)$allTags !== '') {
                                        $arrTags = explode(',', $allTags);
                                        echo '<ul>';
                                        foreach ($arrTags as $key => $tag) { ?>
                                            <li>
                                                <input title="" type="checkbox" name="chk_ftags[]" class="chk_ftags"
                                                       id="ftags<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($tag); ?>"/>
                                                <span><?php echo esc_html($tag); ?></span>
                                            </li>
                                        <?php }
                                        echo '</ul>';
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <div class="box-btngroup-below">
                            <a href="#" class="btnsearchbelow" type="reset" id="widget_btnReset">
                                <?php esc_html_e('Reset', 'wpfd'); ?>
                            </a>
                            <button id="witget_btnsearchbelow" class="btnsearchbelow" type="submit">
                                <i class="wpfd-icon-search"></i><?php esc_html_e('Search', 'wpfd'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="limit" value="<?php echo esc_attr($instance['files_per_page']); ?>">
        </form>
        <?php
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Allow html
        echo $args['after_widget'];
    }

    /**
     * Method update instance
     *
     * @param array $new_instance Instance to replace
     * @param array $old_instance Old Instance
     *
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['tag_filter'] = $new_instance['tag_filter'];
        $instance['cat_filter'] = $new_instance['cat_filter'];
        $instance['display_tag'] = $new_instance['display_tag'];
        $instance['creation_date'] = $new_instance['creation_date'];
        $instance['update_date'] = $new_instance['update_date'];
        $instance['files_per_page'] = $new_instance['files_per_page'];
        return $instance;
    }

    /**
     * Method form instance
     *
     * @param array $instance Instance
     *
     * @return string|void
     */
    public function form($instance)
    {
        $instance = wp_parse_args(
            (array)$instance,
            array(
                'title' => '',
                'tag_filter' => 1,
                'display_tag' => 'searchbox',
                'cat_filter' => 1,
                'creation_date' => 1,
                'update_date' => 1,
                'files_per_page' => 15
            )
        );
        $title = esc_attr($instance['title']);

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'wpfd'); ?></label>
            <input class="widefat" id="<?php esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('cat_filter')); ?>">
                <?php esc_html_e('Filter by category', 'wpfd'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('cat_filter')); ?>"
                    id="<?php echo esc_attr($this->get_field_id('cat_filter')); ?>" class="widefat">
                <option value="1"<?php selected($instance['cat_filter'], '1'); ?>><?php esc_html_e('Yes', 'wpfd'); ?></option>
                <option value="0"<?php selected($instance['cat_filter'], '0'); ?>><?php esc_html_e('No', 'wpfd'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('tag_filter')); ?>"><?php esc_html_e('Filter by tag', 'wpfd'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('tag_filter')); ?>"
                    id="<?php echo esc_attr($this->get_field_id('tag_filter')); ?>" class="widefat">
                <option value="1"<?php selected($instance['tag_filter'], '1'); ?>><?php esc_html_e('Yes', 'wpfd'); ?></option>
                <option value="0"<?php selected($instance['tag_filter'], '0'); ?>><?php esc_html_e('No', 'wpfd'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('display_tag')); ?>">
                <?php esc_html_e('Display tag as', 'wpfd'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('display_tag')); ?>"
                    id="<?php echo esc_attr($this->get_field_id('display_tag')); ?>" class="widefat">
                <option value="searchbox"<?php selected($instance['display_tag'], 'searchbox'); ?>>
                    <?php esc_html_e('Search box', 'wpfd'); ?></option>
                <option value="checkbox"<?php selected($instance['display_tag'], 'checkbox'); ?>>
                    <?php esc_html_e('Checkbox', 'wpfd'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('creation_date')); ?>">
                <?php esc_html_e('Filter by creation date', 'wpfd'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('creation_date')); ?>"
                    id="<?php echo esc_attr($this->get_field_id('creation_date')); ?>" class="widefat">
                <option value="1"<?php selected($instance['creation_date'], '1'); ?>>
                    <?php esc_html_e('Yes', 'wpfd'); ?></option>
                <option value="0"<?php selected($instance['creation_date'], '0'); ?>><?php esc_html_e('No', 'wpfd'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('update_date')); ?>">
                <?php esc_html_e('Filter by update date', 'wpfd'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('update_date')); ?>"
                    id="<?php echo esc_attr($this->get_field_id('update_date')); ?>" class="widefat">
                <option value="1"<?php selected($instance['update_date'], '1'); ?>><?php esc_html_e('Yes', 'wpfd'); ?></option>
                <option value="0"<?php selected($instance['update_date'], '0'); ?>><?php esc_html_e('No', 'wpfd'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('files_per_page')); ?>">
                <?php esc_html_e('# Files per page', 'wpfd'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('files_per_page')); ?>"
                    id="<?php echo esc_attr($this->get_field_id('files_per_page')); ?>" class="widefat">
                <option value="5"<?php selected($instance['files_per_page'], '5'); ?>>5</option>
                <option value="10"<?php selected($instance['files_per_page'], '10'); ?>>10</option>
                <option value="15"<?php selected($instance['files_per_page'], '15'); ?>>15</option>
                <option value="20"<?php selected($instance['files_per_page'], '20'); ?>>20</option>
                <option value="25"<?php selected($instance['files_per_page'], '25'); ?>>25</option>
                <option value="30"<?php selected($instance['files_per_page'], '30'); ?>>30</option>
                <option value="50"<?php selected($instance['files_per_page'], '50'); ?>>50</option>
                <option value="100"<?php selected($instance['files_per_page'], '100'); ?>>100</option>
                <option value="-1"<?php selected($instance['files_per_page'], '-1'); ?>>All</option>
            </select>
        </p>
        <?php
    }
}

/**
 * Method widgets load
 *
 * @return void
 */
function wpfd_widgets_init()
{
    register_widget('WPFDWidgetSearch');
}

add_action('widgets_init', 'wpfd_widgets_init', 1);
