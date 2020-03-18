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
 * Class Text2
 */
class Seourl extends Field
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
        if (empty($attributes['hidden']) || (!empty($attributes['hidden']) && $attributes['hidden'] !== 'true')) {
            $html .= '<input';
            $html .= ' type="text"';
        } else {
            $html .= '<hidden';
        }

        if (!empty($attributes)) {
            $attribute_array = array('id', 'class', 'placeholder', 'name', 'value', 'placeholder');
            foreach ($attributes as $attribute => $value) {
                if (in_array($attribute, $attribute_array) && isset($value)) {
                    $html .= ' ' . $attribute . '="' . $value . '"';
                }
            }
        }
        $html .= ' />';
        // Force remove file extension check box
        $html .= '&nbsp;<input type="checkbox" rel="rmdownloadext" onChange="jQuery(\'input[name=rmdownloadext]\').val(jQuery(this).is(\':checked\') ? 1 : 0)" />&nbsp;' . esc_html__('Remove download file link extension', 'wpfd');
        $html .= '<script>jQuery(document).ready(function() {jQuery(\'input[rel=rmdownloadext]\').prop(\'checked\', jQuery(\'input[name=rmdownloadext]\').val() === \'1\' ? true : false);})</script>';
        if (!empty($attributes['help']) && $attributes['help'] !== '') {
            $html .= '<p class="help-block">' . $attributes['help'] . '</p>';
        }
        if (!empty($attributes['type']) || (!empty($attributes['hidden']) && $attributes['hidden'] !== 'true')) {
            $html .= '</div></div>';
        }

        return $html;
    }
}
