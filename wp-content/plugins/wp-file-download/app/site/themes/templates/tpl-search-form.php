<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0.3
 */
defined('ABSPATH') || die();

?>
<script>
    wpfdajaxurl = "<?php echo $ajaxUrl; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- keep this, if not it error ?>";
    var filterData = null;
    var defaultAllTags = <?php echo ($allTagsFiles !== '' ? $allTagsFiles : '[]'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allready esc in view.php?>;
    jQuery(document).ready(function () {
        jQuery('#filter_catid_chzn').removeAttr('style');
        jQuery('.chzn-search input').removeAttr('readonly');

        <?php if ((int) $args['tag_filter'] === 1 && $args['display_tag'] === 'searchbox') : ?>
        var defaultTags = [];
        var availTags = [];
        <?php if (isset($filters) && isset($filters['ftags'])) : ?>
        var ftags = '<?php echo esc_html($filters['ftags']);?>';
        defaultTags = ftags.split(',');
        <?php endif; ?>
        <?php if (!empty($allTagsFiles)) : ?>
        availTags = <?php echo $allTagsFiles; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allready esc in view.php?>;
        <?php endif; ?>
        jQuery("#input_tags").tagit({
            availableTags: availTags,
            allowSpaces: true,
            initialTags: defaultTags
        });
        <?php endif; ?>
        <?php if (!empty($filters)) : ?>
        filterData = <?php echo json_encode($filters);?>;
        <?php endif; ?>
        window.history.pushState(filterData, '', window.location);
    });
</script>

<form action="" id="adminForm" name="adminForm" method="post">
    <div id="loader" style="display:none">
        <img src="<?php echo esc_url($baseUrl. '/app/site/assets/images/spinner.gif'); ?>"/>
    </div>
    <div class="box-search-filter">
        <div class="only-file input-group clearfix">
            <input type="text" class="pull-left required" name="q" id="txtfilename"
                   placeholder="<?php esc_html_e('Search ...', 'wpfd'); ?>"
                   value="<?php echo esc_html(isset($filters['q']) ? $filters['q'] : ''); ?>"
            />
            <button type="submit" id="btnsearch" class="pull-left"><i class="wpfd-icon-search"></i></button>
        </div>
        <?php if ((int) $args['cat_filter'] === 1 || $args['tag_filter'] || (int) $args['create_filter'] === 1 ||
                  (int) $args['update_filter'] === 1) : ?>
            <div class="by-feature feature-border">
                <div class="top clearfix">
                    <div class="pull-left"><strong><?php esc_html_e('Filter', 'wpfd') ?></strong></div>
                    <div class="pull-right"><i class="feature-toggle feature-toggle-up"></i></div>
                </div>
                <?php
                $span = 'span3';
                if ((int) $args['tag_filter'] === 1 && (int) $args['display_tag'] === 'checkbox') {
                    $span = 'span4';
                }
                ?>
                <div class="feature clearfix row-fluid">
                    <?php if ((int) $args['cat_filter'] === 1) : ?>
                        <div class="<?php echo esc_attr($span); ?> categories-filtering">
                            <h4><?php esc_html_e('Categories', 'wpfd'); ?></h4>
                            <div class="ui-widget">
                                <select title="" id="filter_catid" class="chzn-select" name="catid">
                                    <option value=""><?php echo esc_html(' ' . esc_html__('Select one', 'wpfd')); ?></option>
                                    <?php
                                    if (count($categories) > 0) {
                                        foreach ($categories as $key => $category) {
                                            if (isset($filters['catid']) && (int) $filters['catid'] === $category->term_id) {
                                                $echo = '<option selected="selected"  value="';
                                                $echo .= esc_attr($category->term_id) . '">';
                                                $echo .= esc_html(str_repeat('-', $category->level - 1));
                                                $echo .= ' ' . esc_html($category->name) . '</option>';
                                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc above
                                                echo $echo;
                                            } else {
                                                $echo = '<option  value="' . esc_attr($category->term_id) . '">'
                                                     . esc_html(str_repeat('-', $category->level - 1))
                                                     . ' ' . esc_html($category->name) . '</option>';
                                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc above
                                                echo $echo;
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ((int) $args['tag_filter'] === 1 && $args['display_tag'] === 'searchbox') : ?>
                        <div class="<?php echo esc_attr($span); ?> tags-filtering">
                            <h4><?php esc_html_e('Tags', 'wpfd'); ?></h4>
                            <input title="" type="text" id="input_tags" name="ftags" class="tagit"
                                   value="<?php echo esc_attr(isset($filters['ftags']) ? $filters['ftags'] : ''); ?>"/>
                        </div>
                    <?php endif; ?>
                    <?php if ((int) $args['create_filter'] === 1) : ?>
                        <div class="<?php echo esc_attr($span); ?> creation-date">
                            <h4><?php esc_html_e('Creation date', 'wpfd'); ?></h4>
                            <div>
                                <span class="lbl-date"><?php esc_html_e('From', 'wpfd'); ?> </span>
                                <input title="" class="input-date" type="text" data-min="cfrom" name="cfrom"
                                       value="<?php echo esc_attr(isset($filters['cfrom']) ? $filters['cfrom'] : ''); ?>"
                                       id="cfrom"/>
                                <i data-id="cfrom" class="icon-date icon-calendar"></i>
                            </div>
                            <div>
                                <span class="lbl-date"><?php esc_html_e('To', 'wpfd'); ?></span>
                                <input title="" class="input-date" data-min="cfrom" type="text" name="cto" id="cto"
                                       value="<?php echo esc_attr(isset($filters['cto']) ? $filters['cto'] : ''); ?>"/>
                                <i data-id="cto" data-min="cfrom" class="icon-date icon-calendar"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ((int) $args['update_filter'] === 1) : ?>
                        <div class="<?php echo esc_attr($span); ?> update-date">
                            <h4><?php esc_html_e('Update date', 'wpfd'); ?></h4>
                            <div><span class="lbl-date"><?php esc_html_e('From', 'wpfd'); ?> </span>
                                <input title="" class="input-date" type="text" data-min="ufrom"
                                       value="<?php echo esc_attr(isset($filters['ufrom']) ? $filters['ufrom'] : ''); ?>"
                                       name="ufrom" id="ufrom"/>
                                <i data-id="ufrom" class="icon-date icon-calendar"></i>
                            </div>
                            <div><span class="lbl-date"><?php esc_html_e('To', 'wpfd'); ?> </span>
                                <input title="" class="input-date" type="text" data-min="ufrom"
                                       value="<?php echo esc_attr(isset($filters['uto']) ? $filters['uto'] : ''); ?>"
                                       name="uto" id="uto"/>
                                <i data-id="uto" data-min="ufrom" class="icon-date icon-calendar"></i>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((int) $args['tag_filter'] === 1 && $args['display_tag'] === 'checkbox') : ?>
                        <div class="clearfix row-fluid">
                            <div class="span11 chk-tags-filtering">
                                <h4 style="text-align:left;"><?php esc_html_e('Tags', 'wpfd'); ?></h4>
                                <input type="hidden" id="input_tags" name="ftags"
                                       value="<?php echo esc_attr(isset($filters['ftags']) ? $filters['ftags'] : ''); ?>"/>
                                <?php
                                if (isset($filters['ftags'])) {
                                    $selectedTags = explode(',', $filters['ftags']);
                                } else {
                                    $selectedTags = array();
                                }
                                $allTags = str_replace(array('[', ']', '"'), '', $allTagsFiles);
                                if ($allTags !== '') {
                                    $arrTags = explode(',', $allTags);
                                    asort($arrTags);
                                    echo '<ul>';
                                    foreach ($arrTags as $key => $fileTag) {
                                        ?>
                                        <li>
                                            <input title="" type="checkbox" name="chk_ftags[]" class="chk_ftags"
                                                   id="ftags<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($fileTag);?>" <?php echo esc_attr(in_array($fileTag, $selectedTags) ? 'checked' : ''); ?>/>
                                            <span><?php echo esc_html($TagLabels[$fileTag]); ?></span>
                                        </li>
                                    <?php }
                                    echo '</ul>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                    <div class="box-btngroup-below">
                        <a href="#" class="btnsearchbelow" type="reset" id="btnReset">
                            <?php esc_html_e('Reset', 'wpfd'); ?>
                        </a>
                        <button id="btnsearchbelow" class="btnsearchbelow" type="button">
                            <i class="wpfd-icon-search"></i>
                            <?php esc_html_e('Search', 'wpfd'); ?>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div id="wpfd-results" class="list-results"></div>
    </div>
</form>

