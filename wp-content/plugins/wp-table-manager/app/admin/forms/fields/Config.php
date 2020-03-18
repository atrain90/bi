<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

namespace Joomunited\WP_Table_Manager\Admin\Fields;

use Joomunited\WPFramework\v1_0_5\Field;
use Joomunited\WPFramework\v1_0_5\Application;
use Joomunited\WPFramework\v1_0_5\Model;

defined('ABSPATH') || die();

/**
 * Class Category
 */
class Config extends Field
{
    /**
     * Display all categories
     *
     * @param array $field Data field
     * @param array $datas Full datas
     *
     * @return string
     */
    public function getfield($field, $datas)
    {
        $attributes = $field['@attributes'];
        $html       = '';
        $tooltip    = isset($attributes['tooltip']) ? $attributes['tooltip'] : '';
        $html       .= '<div class="control-group">';
        if (!empty($attributes['label']) && (string) $attributes['label'] !== '' &&
            !empty($attributes['name']) && (string) $attributes['name'] !== ''
        ) {
            $html .= '<label title="' . $tooltip . '" class="control-label" for="' . $attributes['name'] . '">';
            $label = (string) $attributes['label'];
            $html .= esc_attr($label, 'wptm') . '</label>';
        }
        $html .= '<div class="controls">';
        $html .= $this->renderCategory($attributes);
        if (!empty($attributes['help']) && (string) $attributes['help'] !== '') {
            $html .= '<p class="help-block">' . $attributes['help'] . '</p>';
        }
        $html .= '</div></div>';
        return $html;
    }

    /**
     * Render category
     *
     * @param array $att Data format color
     *
     * @return string
     */
    public function renderCategory($att)
    {
        $default = '#bdbdbd|#ffffff|#f3f3f3|#ffffff';
        $default .= '|#4dd0e1|#ffffff|#e0f7fa|#a2e8f1';
        $default .= '|#63d297|#ffffff|#e7f9ef|#afe9ca';
        $default .= '|#f7cb4d|#ffffff|#fef8e3|#fce8b2';
        $default .= '|#f46524|#ffffff|#ffe6dd|#ffccbc';
        $default .= '|#5b95f9|#ffffff|#e8f0fe|#acc9fe';
        $default .= '|#26a69a|#ffffff|#ddf2f0|#8cd3cd';
        $default .= '|#78909c|#ffffff|#ebeff1|#bbc8ce';

        $values  = isset($att['value']) && (string) $att['value'] !== '' ? $att['value'] : $default;

        $html    = '';
        $html    .= '<div id="control_format_style">';
        $html    .= '<div class="label_text">' . __('Automatic styling', 'wptm') . ':</div>';
        $html    .= '<div class="control_value" style="display: none">';
        $html    .= '<input name = "' . $att['name'] . '" id = "' . $att['name'] . '" class="' . $att['class'] . '" value="' . $values . '">';
        $html    .= '</div>';
        $html    .= '<div id="list_format_style">';
        $arrayValue  = explode('|', $values);
        $count   = count($arrayValue);
        for ($i = 0; $i < $count / 4; $i ++) {
            $i16   = $i * 4;
            $value = array($arrayValue[$i16], $arrayValue[$i16 + 1], $arrayValue[$i16 + 2], $arrayValue[$i16 + 3]);
            $html .= $this->renderListStyle($value, $i);
        }
        $html .= '</div>';
        $html .= '<div id="new_format_style">';
        $html .= '<input type="button" class="create_format_style" value="New">';
        $html .= '<input type="button" class="remove_format_style" value="Remove">';
        $html .= '<input type="button" class="hide_set_format_style" value="Show">';
        $html .= '</div>';
        $value = array('#ffffff', '#ffffff', '#ffffff', '#ffffff');
        $html .= $this->renderListStyle($value, 'create');
        $html .= '<div id="save_format_style">';
        $html .= '<input type="button" value="' . __('Done', 'wptm') . '">';
        $html .= '<input type="button" value="' . __('Cancel', 'wptm') . '">';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Render list style color
     *
     * @param array   $value Style value
     * @param integer $order Order number
     *
     * @return string
     */
    public function renderListStyle($value, $order)
    {
        $html = '';
        if ($order !== 'create') {
            $html .= '<div class="pane-color-tile td_' . $order . '">';
            $html .= '<div class="pane-color-tile-header pane-color-tile-band" data-value="' . $value[0] . '" style="background-color:' . $value[0] . ';"></div>';
            $html .= '<div class="pane-color-tile-1 pane-color-tile-band" data-value="' . $value[1] . '" style="background-color:' . $value[1] . ';"></div>';
            $html .= '<div class="pane-color-tile-2 pane-color-tile-band" data-value="' . $value[2] . '" style="background-color:' . $value[2] . ';"></div>';
            $html .= '<div class="pane-color-tile-footer pane-color-tile-band" data-value="' . $value[3] . '" style="background-color:' . $value[3] . ';"></div>';
            $html .= '</div>';
        } else {
            $html .= '<div id="set_color" class="input-pane-set-color">';
            $html .= '<div class="label_text">';
            $html .= '<span>' . __('Header color', 'wptm') . '</span>';
            $html .= '<span>' . __('Alternate color 1', 'wptm') . '</span>';
            $html .= '<span>' . __('Alternate color 2', 'wptm') . '</span>';
            $html .= '<span>' . __('Footer color', 'wptm') . '</span>';
            $html .= '</div>';
            $html .= '<div class="control_value">';
            $html .= '<input title="" value="#ffffff" class="pane-set-color-header inputbox input-block-level wp-color-field" type="text">';
            $html .= '<input title="" value="#ffffff" class="pane-set-color-1 inputbox input-block-level wp-color-field" type="text">';
            $html .= '<input title="" value="#ffffff" class="pane-set-color-2 inputbox input-block-level wp-color-field" type="text">';
            $html .= '<input title="" value="#ffffff" class="pane-set-color-footer inputbox input-block-level wp-color-field" type="text">';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }
}
