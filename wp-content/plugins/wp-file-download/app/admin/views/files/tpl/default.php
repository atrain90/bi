<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

// No direct access.
defined('ABSPATH') || die();

$items_thead = array(
    'ext'           => esc_html__('Ext', 'wpfd'),
    'title'         => esc_html__('Title', 'wpfd'),
    'size'          => esc_html__('File size', 'wpfd'),
    'created_time'  => esc_html__('Date added', 'wpfd'),
    'modified_time' => esc_html__('Date modified', 'wpfd'),
    'version'       => esc_html__('Version', 'wpfd'),
    'hits'          => esc_html__('Hits', 'wpfd')
);
?>
<?php if ($this->files) : ?>
    <table class="restable">
        <thead>
        <tr>
            <?php
            foreach ($items_thead as $thead_key => $thead_text) {
                $icon = '';
                if ($thead_key === $this->ordering) {
                    $icon = '<span 
                    class="dashicons dashicons-arrow-' . ($this->orderingdir === 'asc' ? 'up' : 'down') . '"></span>';
                }
                ?>
                <th class="<?php echo esc_attr($thead_key); ?>">
                    <?php if ($thead_key === 'actions') { ?>
                        <?php echo esc_html($thead_text); ?>
                    <?php } else { ?>
                        <a href="#" class="<?php echo($this->ordering === $thead_key ? 'currentOrderingCol' : ''); ?>"
                           data-ordering="<?php echo esc_attr($thead_key); ?>"
                           data-direction="<?php echo esc_attr($this->orderingdir); ?>">
                            <?php echo esc_html($thead_text); ?><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nothing need to escape ?>
                        </a>
                    <?php } ?>
                </th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php if (is_array($this->files) || is_object($this->files)) : ?>
            <?php foreach ($this->files as $file) :
                $httpcheck = isset($file->guid) ? $file->guid : '';
                $remote_file = preg_match('(http://|https://)', $httpcheck) ? 'is-remote-url' : '';
                ?>
                <tr class="file <?php echo esc_attr($remote_file); ?>" data-id-file="<?php echo esc_attr($file->ID); ?>"
                    data-catid-file="<?php echo esc_attr($file->catid); ?>" data-linkdownload="<?php echo esc_url($file->linkdownload); ?>">
                    <td class=""><div class="ext <?php echo esc_attr($file->ext); ?>"><span class="txt"><?php echo esc_html($file->ext); ?></span></div></td>
                    <td class="title"><?php echo esc_html($file->post_title); ?></td>
                    <td class="size">
                        <?php echo esc_html(($file->size === 'n/a') ? $file->size : WpfdHelperFiles::bytesToSize($file->size)); ?>
                    </td>
                    <td class="created">
                        <?php echo esc_html($file->created); ?>
                    </td>
                    <td class="modified">
                        <?php echo esc_html($file->modified); ?>
                    </td>
                    <td class="version"><?php echo esc_html((isset($file->versionNumber)) ? $file->versionNumber : ''); ?></td>
                    <td class="hits"><?php echo esc_html($file->hits) . ' ' . esc_html__('hits', 'wpfd'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>
