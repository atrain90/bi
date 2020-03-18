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
        $html    = '';
        $tooltip = isset($attributes['tooltip']) ? $attributes['tooltip'] : '';
        $html    .= '<div class="control-group">';
        if (!empty($attributes['label']) && $attributes['label'] !== '' &&
            !empty($attributes['name']) && $attributes['name'] !== '') {
            $html .= '<label title="' . $tooltip . '" class="control-label" for="' . $attributes['name'] . '">';
            // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- Dynamic translate
            $html .= esc_html__($attributes['label'], 'wpfd') . '</label>';
        }
        $html .= '<div class="controls" style="width: 100%">';
        $html .= '<input type="text" name="' . $attributes['name'] . '" id="' . $attributes['id'];
        $html .= '"  readonly="true" value="' . $attributes['value'] . '" />';

        if (!empty($attributes['help']) && $attributes['help'] !== '') {
            $html .= '<p class="help-block">' . $attributes['help'] . '</p>';
        }
        $html .= '</div></div>';

        return $html;
    }
}
