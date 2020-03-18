<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

//-- No direct access
defined('ABSPATH') || die();

if (!empty($file)) : ?>
    <div class="wpfd-file wpfd-single-file" data-file="<?php echo esc_attr($file->ID); ?>">
        <div class="wpfd-file-link wpfd_downloadlink">
            <a class="noLightbox"
               href="<?php echo esc_url($file->linkdownload) ?>"
               data-id="<?php echo esc_attr($file->ID); ?>"
                <?php
                $fileTitle = isset($file->description) ? wp_strip_all_tags($file->description) : $file->title;
                ?>
               title="<?php echo esc_html($fileTitle); ?>">
                <span class="droptitle">
                    <?php echo esc_html($nameDisplay); ?>
                </span>
                <br/>
                <?php
                /**
                 * Action fire before file info in single file display
                 *
                 * @param object File object
                 */
                do_action('wpfd_before_single_file_info', $file);
                ?>
                <span class="dropinfos">
                    <?php if ($showsize) : ?>
                        <b><?php esc_html_e('Size', 'wpfd'); ?>: </b>
                        <?php echo esc_html(WpfdHelperFile::bytesToSize($file->size)); ?>
                    <?php endif; ?>
                    <b><?php esc_html_e('Format', 'wpfd'); ?> : </b>
                    <?php echo esc_html(strtoupper($file->ext)); ?>
                </span>
                <?php
                /**
                 * Action fire after file info in single file display
                 *
                 * @param object File object
                 */
                do_action('wpfd_after_single_file_info', $file);
                ?>
            </a><br>
            <?php if (isset($file->openpdflink)) { ?>
                <a href="<?php echo esc_url($file->openpdflink); ?>" class="noLightbox" target="_blank">
                    <?php esc_html_e('Preview', 'wpfd'); ?> </a>
            <?php } elseif (isset($file->viewerlink)) { ?>
                <a data-id="<?php echo esc_attr($file->ID); ?>" data-catid="<?php echo esc_attr($file->catid); ?>"
                   data-file-type="<?php echo esc_attr($file->ext); ?>"
                   class="openlink wpfdlightbox wpfd_previewlink noLightbox"
                   href="<?php echo esc_url($file->viewerlink); ?>">
                    <?php esc_html_e('Preview', 'wpfd'); ?></a>
            <?php } ?>
        </div>
    </div>
<?php endif; ?>
