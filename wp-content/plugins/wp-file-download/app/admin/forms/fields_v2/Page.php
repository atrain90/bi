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
 * Class Page
 */
class Page extends Field
{
    /**
     * Display page
     *
     * @param array $field Fields
     * @param array $data  Data
     *
     * @return string
     */
    public function getfield($field, $data)
    {
        $attributes = $field['@attributes'];
        $attributes['value'] = (int)$attributes['value'];
        $html = '<div class="ju-settings-option">';
        $tooltip = isset($attributes['tooltip']) ? $attributes['tooltip'] : '';
        if (!empty($attributes['label']) && $attributes['label'] !== '' &&
            !empty($attributes['name']) && $attributes['name'] !== '') {
            $html .= '<label title="' . $tooltip . '" class="ju-setting-label" for="' . $attributes['name'] . '">';
            // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- Dynamic translate
            $html .= esc_html__($attributes['label'], 'wpfd') . '</label>';
        }
        $r = array(
            'name' => $attributes['name'],
            'id' => esc_attr($attributes['id']),
            'echo' => 0,
            'class' => 'ju-input',
            'show_option_none' => esc_html__('&mdash; Select &mdash;', 'wpfd'),
            'option_none_value' => '0',
            'selected' => $attributes['value']
        );
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Nothing need to escape
        $html .= wp_dropdown_pages($r);
        if (!empty($attributes['help']) && $attributes['help'] !== '') {
            $html .= '<p class="help-block">' . $attributes['help'] . '</p>';
        }
        $html .= '</div>';
        return $html;
    }
}
