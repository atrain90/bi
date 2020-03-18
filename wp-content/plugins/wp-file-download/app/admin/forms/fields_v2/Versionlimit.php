<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

namespace Joomunited\WP_File_Download\Admin\Fields;

use Joomunited\WPFramework\v1_0_5\Fields\Typeint as IntType;
use Joomunited\WPFramework\v1_0_5\Application;

defined('ABSPATH') || die();

/**
 * Class Maxinputfile
 */
class Versionlimit extends IntType
{
    /**
     * Display field max input type
     *
     * @param array $field Fields
     * @param array $data  Data
     *
     * @return string
     */
    public function getfield($field, $data)
    {
        $attributes          = $field['@attributes'];
        $attributes['value'] = ($attributes['value'] !== '') ? $attributes['value'] : 10;
        $attributes['value'] = (int) $attributes['value'];
        $attributes['type']  = 'text';
        if ((int) $attributes['value'] < 0) {
            $attributes['value'] = 0;
        } elseif ((int) $attributes['value'] > 100) {
            $attributes['value'] = 100;
        }
        $html       = '<div class="ju-settings-option">';
        $tooltip = isset($attributes['tooltip']) ? $attributes['tooltip'] : '';
        if (!empty($attributes['type']) || (!empty($attributes['hidden']) && $attributes['hidden'] !== 'true')) {
            if (!empty($attributes['label']) && $attributes['label'] !== '' &&
                !empty($attributes['name']) && $attributes['name'] !== '') {
                $html .= '<label title="' . $tooltip . '" class="ju-setting-label" for="' . $attributes['name'] . '">';
                // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- Dynamic translate
                $html .= esc_html__($attributes['label'], 'wpfd') . '</label>';
            }
        }
        $html .= '<div class="ju-settings-toolbox">';
        if (!empty($attributes['help']) && $attributes['help'] !== '') {
            $html .= '<p class="help-block">' . $attributes['help'] . '</p>';
        }
        $html .= '<button type="button" class="ju-button" id="versionspurge">' .
                 esc_html__('Cleanup File History', 'wpfd') .
                 '</button>&nbsp;<span id="versionpurgemessage"></span>';
        $html .= '</div>';
        if (empty($attributes['hidden']) || (!empty($attributes['hidden']) && $attributes['hidden'] !== 'true')) {
            $html .= '<input';
        } else {
            $html .= '<hidden';
        }

        if (!empty($attributes)) {
            $attribute_array = array('type', 'id', 'class', 'placeholder', 'name', 'value');
            foreach ($attributes as $attribute => $value) {
                if (in_array($attribute, $attribute_array) && isset($value)) {
                    $html .= ' ' . $attribute . '="' . $value . '"';
                }
            }
        }
        $html .= ' />';


//        if (!empty($attributes['type']) || (!empty($attributes['hidden']) && $attributes['hidden'] !== 'true')) {
//            $html .= '</div></div>';
//        }
        $html .= '</div>';

        return $html;
    }
}
