<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

namespace Joomunited\WP_File_Download\Admin\Fields;

use Joomunited\WPFramework\v1_0_5\Field;
use Joomunited\WPFramework\v1_0_5\Application;

defined('ABSPATH') || die();

/**
 * Class Shortcode
 */
class Shortcode extends Field
{

    /**
     * Display field config shortcode
     *
     * @param array $field Fields
     * @param array $data  Data
     *
     * @return string
     */
    public function getfield($field, $data)
    {
        $attributes = $field['@attributes'];
        if (!empty($attributes['value'])) {
            $attributes['value'] = str_replace('\\', '', $attributes['value']);
        } else {
            $attributes['value'] = '[wpfd_search]';
        }
        $html = '<div class="ju-settings-option">';
        if (isset($attributes['fullwidth']) && !empty($attributes['fullwidth'])) {
            $html = '<div class="ju-settings-option full-width">';
        }
        $html .= '<div class="ju-settings-toolbox">';
        if (!empty($attributes['help']) && $attributes['help'] !== '') {
            $html .= '<p class="help-block">' . $attributes['help'] . '</p>';
        }
        // Copy shortcode to clipboard
        $shortcodeName = (isset($attributes['name']) && !empty($attributes['name'])) ? $attributes['name'] : '';
        $html .= '<button type="button" class="ju-button orange-outline-button shortcode-copy" data-ref="';
        $html .= $shortcodeName;
        $html .= '"><i class="material-icons" data-ref="' . $shortcodeName . '">file_copy</i>';
        $html .= esc_html__('Copy', 'wpfd');
        $html .= '</button>';
        $html .= '</div>';
        $tooltip = isset($attributes['tooltip']) ? $attributes['tooltip'] : '';
        if (!empty($attributes['label']) && $attributes['label'] !== '' &&
            !empty($attributes['name']) && $attributes['name'] !== '') {
            $html .= '<label title="' . $tooltip . '" class="ju-setting-label" for="' . $attributes['name'] . '">';
            // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- Dynamic translate
            $html .= esc_html__($attributes['label'], 'wpfd') . '</label>';
        }
        $class = 'ju-input ju-large-text ' . esc_html($attributes['class']);
        $html .= '<input class="' . $class . '" type="text" name="' . $attributes['name'] . '" id="' . $attributes['id'];
        $html .= '"  readonly="true" value=\'' . $attributes['value'] . '\' />';


        $html .= '</div>';

        return $html;
    }
}
