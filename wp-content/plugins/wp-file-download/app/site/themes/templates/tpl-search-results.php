<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0.3
 */
defined('ABSPATH') || die();

if ($files !== null && is_array($files) && count($files) > 0) : ?>
    <table class="table">
        <thead>
        <th>
            <a href="#"
               class="orderingCol <?php echo esc_attr(($ordering === 'type') ? 'curentOrderingCol' : ''); ?>"
               data-ordering="type"
               data-direction="<?php echo esc_attr(($ordering === 'type' && $dir === 'asc') ? 'desc' : 'asc'); ?>">
                <?php esc_html_e('File type', 'wpfd'); ?>
            </a>/
            <a href="#"
               class="orderingCol <?php echo esc_attr(($ordering === 'title') ? 'curentOrderingCol' : ''); ?>"
               data-ordering="title"
               data-direction="<?php echo esc_attr(($ordering === 'title' && $dir === 'asc') ? 'desc' : 'asc'); ?>">
                <?php esc_html_e('File name', 'wpfd'); ?>
            </a>
        </th>
        <?php if ($viewer !== 'no') : ?>
            <th><?php esc_html_e('Open', 'wpfd'); ?></th>
        <?php endif; ?>
        <th>
            <a href="#"
               class="orderingCol <?php echo esc_attr(($ordering === 'created') ? 'curentOrderingCol' : ''); ?>"
               data-ordering="created"
               data-direction="<?php echo esc_attr(($ordering === 'created' && $dir === 'asc') ? 'desc' : 'asc'); ?>">
                <?php esc_html_e('Creation date', 'wpfd'); ?>
            </a>
        </th>
        <th>
            <a href="#"
               class="orderingCol <?php echo esc_attr(($ordering === 'cat') ? 'curentOrderingCol' : ''); ?>"
               data-ordering="cat"
               data-direction="<?php echo esc_attr(($ordering === 'cat' && $dir === 'asc') ? 'desc' : 'asc'); ?>">
                <?php esc_html_e('Category', 'wpfd'); ?>
            </a>
        </th>
        </thead>
        <tbody>
        <?php foreach ($files as $key => $file) : ?>
            <tr>
                <td class="title">
                    <span class="file-icon">
                    <?php if ($config['custom_icon'] && $file->file_custom_icon) : ?>
                        <img class="icon-custom" src="<?php echo esc_url(get_site_url() . $file->file_custom_icon); ?>">
                    <?php else : ?>
                        <i class="<?php echo esc_attr($file->ext); ?>"></i>
                    <?php endif; ?>
                    </span>
                    <a class="file-item wpfd-file-link" data-id="<?php echo esc_attr($file->ID); ?>"
                       href="<?php echo esc_url($file->linkdownload); ?>" id="file-<?php echo esc_attr($file->ID); ?>"
                       title="<?php echo esc_attr($file->title); ?>">
                        <?php
                        if (isset($file->crop_title)) {
                            echo esc_html($file->crop_title);
                        } else {
                            echo esc_html($file->title);
                        }
                        ?>
                    </a>
                </td>
                <?php if ($viewer !== 'no') : ?>
                    <td class="viewer">
                        <?php
                        $previewImage = $baseurl . '/app/site/assets/images/open_242.png';
                        if (isset($file->openpdflink)) { ?>
                            <a href="<?php echo esc_url($file->openpdflink); ?>" class="openlink" target="_blank">
                                <img src="<?php echo esc_url($previewImage); ?>"
                                     title="<?php esc_html_e('Open', 'wpfd'); ?>"/></a>
                        <?php } elseif ($file->viewerlink) { ?>
                            <a data-id="<?php echo esc_attr($file->ID); ?>"
                               data-catid="<?php echo esc_attr($file->catid); ?>"
                               data-file-type="<?php echo esc_attr($file->ext); ?>"
                               class="openlink <?php echo esc_attr(($viewer === 'lightbox') ? 'wpfdlightbox' : ''); ?>"
                                <?php echo esc_attr(($viewer === 'tab') ? 'target="_blank"' : ''); ?>
                               href='<?php echo esc_url($file->viewerlink); ?>'>
                                <img src="<?php echo esc_url($previewImage); ?>"
                                     title="<?php esc_html_e('Open', 'wpfd'); ?>"/>
                            </a>
                        <?php } ?>
                    </td>
                <?php endif; ?>
                <td class="created"><?php echo esc_html($file->created); ?></td>
                <td class="catname"><?php echo esc_html($file->cattitle); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php wpfd_num($limit); ?>
<?php else : ?>
    <h5 class="text-center">
        <?php esc_html_e("Sorry, we haven't found anything that matches this search query", 'wpfd'); ?>
    </h5>
<?php endif; ?>
