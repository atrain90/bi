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
use Joomunited\WPFramework\v1_0_5\Factory;

defined('ABSPATH') || die();

/**
 * Class Input2
 */
class Input2 extends Field
{
    /**
     * Render <input> tag
     *
     * @param array $field Fields
     * @param array $data  Data
     *
     * @return string
     */
    public function getfield($field, $data)
    {
        $attributes = $field['@attributes'];
        $html       = '';
        $tooltip    = isset($attributes['tooltip']) ? $attributes['tooltip'] : '';
        if (!empty($attributes['type']) || (!empty($attributes['hidden']) && $attributes['hidden'] !== 'true')) {
            $html .= '<div class="control-group">';
            if (!empty($attributes['label']) && $attributes['label'] !== '' &&
                !empty($attributes['name']) && $attributes['name'] !== '') {
                $html .= '<label title="' . $tooltip . '" class="control-label" for="' . $attributes['name'] . '">';
                // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- Dynamic translate
                $html .= esc_html__($attributes['label'], 'wpfd') . '</label>';
            }
            $html .= '<div class="controls">';
        }
        $html .= '<input type="password" ';
        if (!empty($attributes)) {
            $attributes_array = array('id', 'class', 'placeholder', 'name', 'value', 'placeholder');
            foreach ($attributes as $attribute => $value) {
                if (in_array($attribute, $attributes_array) && isset($value)) {
                    $html .= ' ' . $attribute . '="' . $value . '"';
                }
            }
        }
        $html .= ' />';
        if (!empty($attributes['help']) && $attributes['help'] !== '') {
            $html .= '<p class="help-block">' . $attributes['help'] . '</p>';
        }
        if (!empty($attributes['type']) || (!empty($attributes['hidden']) && $attributes['hidden'] !== 'true')) {
            $html .= '</div></div>';
        }

        return $html;
    }
}
