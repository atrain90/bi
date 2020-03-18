<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */
//-- No direct access
defined('ABSPATH') || die();

/**
 * Class WptmHelper
 */
class WptmHelper
{
    /**
     * Result Calculator
     *
     * @var string
     */
    protected static $resultCalc;
    /**
     * Date formats
     *
     * @var string
     */
    protected static $date_formats;
    /**
     * Date var
     *
     * @var array
     */
    protected static $date;
    /**
     * Number cells in calculator
     *
     * @var integer
     */
    protected static $n;

    /**
     * Compile style table/chart
     *
     * @param object $table     Data table
     * @param array  $styles    Style table
     * @param string $customCss Custom css
     *
     * @return boolean
     */
    public static function compileStyle($table, $styles, $customCss)
    {
        $folder = wp_upload_dir();
        $folder = $folder['basedir'] . DIRECTORY_SEPARATOR . 'wptm' . DIRECTORY_SEPARATOR;
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $file = $folder . $table->id . '_' . $table->hash . '.css';
        if (!file_exists($file)) {
            $files = glob($folder . $table->id . '_*.css');
            foreach ($files as $f) {
                unlink($f);
            }
        } else {
            return true;
        }

        $folder_admin = dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'admin';
        require_once $folder_admin . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'lessphp.php';
        if (!class_exists('csstidy')) {
            require_once $folder_admin . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'csstidy' . DIRECTORY_SEPARATOR . 'class.csstidy.php';
        }

        $countStyles = count($styles);
        $contents    = array();
        $contents[0] = '';

        for ($i = 0; $i < $countStyles; $i ++) {
            $style = $styles[$i];
            $less  = new lessc;

            try {
                $contents[$i] = $less->compile('#wptmtable' . $table->id . '.wptmtable {' . $style . '}');
            } catch (Exception $exc) {
                return false;
            }

            try {
                $customContent = $less->compile('#wptmtable' . $table->id . '.wptmtable table tbody {' . $customCss . '}');
            } catch (Exception $exc) {
                $customContent = '';
            }

            $contents[$i] .= $customContent;
            $csstidy      = new csstidy();
            $csstidy->parse($contents[$i]);

            $less->setFormatter('compressed');

            try {
                //phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- The inner variables were esc
                $contents[$i] = $less->compile($csstidy->print->plain());
            } catch (Exception $exc) {
                return false;
            }
        }

        if (isset($contents[1])) {
            $content = implode($contents);
        } else {
            $content = $contents[0];
        }

        if (!file_put_contents($file, $content)) {
            echo 'error saving file!';
            return false;
        }
        return true;
    }

    /**
     * Render style table
     *
     * @param object $table Data table
     *
     * @return boolean
     */
    public function styleRender($table)
    {
        $folder = wp_upload_dir();
        $folder = $folder['basedir'] . DIRECTORY_SEPARATOR . 'wptm' . DIRECTORY_SEPARATOR;
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $file = $folder . $table->id . '_' . $table->hash . '.css';
        if (!file_exists($file)) {
            $files = glob($folder . $table->id . '_*.css');
            foreach ($files as $f) {
                unlink($f);
            }
        } else {
            return true;
        }
        $style = json_decode($table->style);
        $contents = array();

        if ($style === null) {
            $contents[0] = '';
        } else {
            $content1 = 'table {';

            //render global table params
            if (isset($style->table->alternate_row_odd_color) && $style->table->alternate_row_odd_color) {
                $content1 .= 'tr:nth-child(even) td {background-color: ' . $style->table->alternate_row_odd_color . ';}';
            }
            if (isset($style->table->alternate_row_even_color) && $style->table->alternate_row_even_color) {
                $content1 .= 'tr:nth-child(odd) td {background-color: ' . $style->table->alternate_row_even_color . ';}';
            }
            if (isset($style->table->row_border) && $style->table->row_border) {
                $content1 .= 'tr td {border-bottom: ' . $style->table->row_border . ';}';
            }

            //render global rows
            $count_Row = 0;
            foreach ($style->rows as $row) {
                if (isset($row[1]->height)) {
                    $content1 .= '.dtr' . (int) ($row[0]) . ' {height: ' . (int) $row[1]->height . 'px;}';
                }
                $count_Row++;
            }

            //render global cols
            $count_Col = 0;
            foreach ($style->cols as $col) {
                if (isset($col[1]->width)) {
                    $content1 .= '.dtc' . (int) ($col[0]) . ' {width: ' . (int) $col[1]->width . 'px; min-width: ' . (int) $col[1]->width . 'px;}';
                }
                $count_Col++;
            }

            $content2 = '';
            if (isset($style->table->width) && $style->table->width > 0) {
                $content2 .= '& {width : ' . $style->table->width . 'px;}';
            }

            if (!isset($style->table->table_align)) {
                $style->table->table_align = 'center';
            }

            switch ($style->table->table_align) {
                case 'left':
                    $content2 .= '& {margin : 0 auto 0 0 }';
                    break;
                case 'right':
                    $content2 .= '& {margin : 0 0 0 auto }';
                    break;
                case 'none':
                    break;
                case 'center':
                default:
                    $content2 .= '& {margin : 0 auto 0 auto }';
                    break;
            }
            $content2 .= '}';

            $i = 0;
            $content = '';
            foreach ($style->cells as $cell) {
                $cell_style = '';

                //render global table params
//                $content .= '.dtr'.(int)($cell[0]).'.dtc'.(int)($cell[1]).' {'. $cell_style . '}';
                if (isset($cell[2]->AlternateColor) && isset($style->table->alternateColorValue->{$cell[2]->AlternateColor})) {
                    $AlternateColor = $style->table->alternateColorValue->{$cell[2]->AlternateColor};
                    $numberRow = 0;
                    if ($AlternateColor->header === '') {
                        $numberRow = -1;
                    }
                    switch ($cell[0]) {
                        case $AlternateColor->selection[0]:
                            if ($AlternateColor->header === '') {
                                $cell_style .= 'background-color: '.$AlternateColor->even. '; ';
                            } else {
                                $cell_style .= 'background-color: '.$AlternateColor->header. '; ';
                            }
                            break;
                        case $AlternateColor->selection[2]:
                            if ($AlternateColor->footer === '') {
                                if (($cell[0] - (int) ($AlternateColor->selection[0] + $numberRow)) % 2) {
                                    $cell_style .= 'background-color: '.$AlternateColor->even. '; ';
                                } else {
                                    $cell_style .= 'background-color: '.$AlternateColor->old. '; ';
                                }
                            } else {
                                $cell_style .= 'background-color: '.$AlternateColor->footer. '; ';
                            }
                            break;
                        default:
                            if (($cell[0] - (int) ($AlternateColor->selection[0] + $numberRow)) % 2) {
                                $cell_style .= 'background-color: '.$AlternateColor->even. '; ';
                            } else {
                                $cell_style .= 'background-color: '.$AlternateColor->old. '; ';
                            }
                            break;
                    }
                }

                if (isset($cell[2]->cell_background_color) && !empty($cell[2]->cell_background_color)) {
                    $cell_style .= 'background-color: '.$cell[2]->cell_background_color. '; ';
                }
                if (isset($cell[2]->cell_border_top)) {
                    $cell_style .= ' border-top: '.$cell[2]->cell_border_top . ';';
                }
                if (isset($cell[2]->cell_border_right)) {
                    $cell_style .= ' border-right: ' . $cell[2]->cell_border_right . ';';
                }
                if (isset($cell[2]->cell_border_bottom)) {
                    $cell_style .= ' border-bottom: ' . $cell[2]->cell_border_bottom . ';';
                }
                if (isset($cell[2]->cell_border_left)) {
                    $cell_style .= ' border-left: ' . $cell[2]->cell_border_left . ';';
                }
                if (isset($cell[2]->cell_font_family)) {
                    $cell_style .= ' font-family: '.$cell[2]->cell_font_family.';';
                }
                if (isset($cell[2]->cell_font_size)) {
                    $cell_style .= ' font-size: '.$cell[2]->cell_font_size.'px;';
                }
                if (isset($cell[2]->cell_font_color) && $cell[2]->cell_font_color !== '') {
                    $cell_style .= ' color: '.$cell[2]->cell_font_color.';';
                }
                if (isset($cell[2]->cell_font_bold) && $cell[2]->cell_font_bold === true) {
                    $cell_style .= ' font-weight: bold;';
                }
                if (isset($cell[2]->cell_font_italic) && $cell[2]->cell_font_italic === true) {
                    $cell_style .= ' font-style: italic;';
                }
                if (isset($cell[2]->cell_font_underline) && $cell[2]->cell_font_underline === true) {
                    $cell_style .= ' text-decoration: underline;';
                }
                if (isset($cell[2]->cell_text_align)) {
                    $cell_style .= ' text-align: '.$cell[2]->cell_text_align.';';
                }
                if (isset($cell[2]->cell_vertical_align)) {
                    $cell_style .= ' vertical-align: '.$cell[2]->cell_vertical_align.';';
                }
                if (isset($cell[2]->cell_padding_left)) {
                    $cell_style .= ' padding-left: '.$cell[2]->cell_padding_left.'px;';
                }
                if (isset($cell[2]->cell_padding_top)) {
                    $cell_style .= ' padding-top: '.$cell[2]->cell_padding_top.'px;';
                }
                if (isset($cell[2]->cell_padding_right)) {
                    $cell_style .= ' padding-right: '.$cell[2]->cell_padding_right.'px;';
                }
                if (isset($cell[2]->cell_padding_bottom)) {
                    $cell_style .= ' padding-bottom: '.$cell[2]->cell_padding_bottom.'px;';
                }
                if (isset($cell[2]->cell_background_radius_left_top)) {
                    $cell_style .= ' border-top-left-radius: '.$cell[2]->cell_background_radius_left_top.'px;';
                }
                if (isset($cell[2]->cell_background_radius_right_top)) {
                    $cell_style .= ' border-top-right-radius: '.$cell[2]->cell_background_radius_right_top.'px;';
                }
                if (isset($cell[2]->cell_background_radius_right_bottom)) {
                    $cell_style .= ' border-bottom-right-radius: '.$cell[2]->cell_background_radius_right_bottom.'px;';
                }
                if (isset($cell[2]->cell_background_radius_left_bottom)) {
                    $cell_style .= ' border-bottom-left-radius: '.$cell[2]->cell_background_radius_left_bottom.'px;';
                }

                $content .= '.dtr'.(int)($cell[0]).'.dtc'.(int)($cell[1]).' {'. $cell_style . '}';
                if (isset($cell[2]->tooltip_width) && !empty($cell[2]->tooltip_width)) {
                    $content .= '.dtr' . (int) ($cell[0]) . '.dtc' . (int) ($cell[1]) . ' .wptm_tooltipcontent_show {width: ' . $cell[2]->tooltip_width . 'px; }';
                }
                $i++;
                if ($i > 0 && $i % 1000 === 0) {
                    $contents[$i / 1000 - 1] = $content1 . $content . $content2;
                    $content = '';
                }
                //else {
                //$content .= ".dtr" . (int) ($cell[0]) . ".dtc" . (int) ($cell[1]) . " .wptm_tooltipcontent_show
                // {width: 200px; }";
                //}
            }
            $count_content = count($contents);
            $contents[$count_content] = $content1 . $content . $content2;
        }
        require_once dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'wptmHelper.php';

        self::compileStyle($table, $contents, $table->css);
    }

    /**
     * Render html
     *
     * @param object $table        Data table
     * @param array  $configParams Config param data
     *
     * @return boolean
     */
    public function htmlRender($table, $configParams)
    {
        $table_hash = md5($table->datas. $table->style. $table->params);
        $folder = wp_upload_dir();
        $folder = $folder['basedir'] . DIRECTORY_SEPARATOR . 'wptm' . DIRECTORY_SEPARATOR;
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $file = $folder . $table->id . '_' . $table_hash . '.html';
        if (!file_exists($file)) {
            $files = glob($folder . $table->id . '_*.html');
            foreach ($files as $f) {
                unlink($f);
            }
        } else {
            return true;
        }
        $datas = json_decode($table->datas);
        $style = json_decode($table->style);
        if (!isset($style->table)) {
            $style->table = new stdClass();
        }
        if (isset($style->table->responsive_type) && (string)$style->table->responsive_type === 'hideCols') {
            $responsive_type = 'hideCols';
        } else {
            $responsive_type = 'scroll';
        }
        if (!isset($style->table->freeze_col)) {
            $style->table->freeze_col = 0;
        }
        if (!isset($style->table->freeze_row)) {
            $style->table->freeze_row = 0;
        }
        if (!isset($style->table->enable_filters)) {
            $style->table->enable_filters = 0;
        }
        $default_order_sortable = isset($style->table->default_order_sortable) ? (int)$style->table->default_order_sortable : 0;
        $default_sort = isset($style->table->default_sortable) ? (int)$style->table->default_sortable : -1;
        $limit = isset($style->table->limit_rows) ? (int)$style->table->limit_rows : 0;
        $enable_pagination = isset($style->table->enable_pagination) ? (int)$style->table->enable_pagination : 0;
        if (isset($style->table->use_sortable) && (int) $style->table->use_sortable === 1) {
            $sortable = true;
            $heads    = 1;
        } else {
            $sortable = false;
            $heads    = 0;
            if ($responsive_type === 'scroll') {
                if (($style->table->freeze_row) || ($style->table->freeze_col)) {
                    $heads = 1;
                }
            }

            if ($style->table->enable_filters || $enable_pagination === 1) {
                $heads = 1;
            }
        }
        if (is_string($table->params)) {
            $table->params = json_decode($table->params);
        }

        if (isset($table->params->mergeSetting)) {
            $mergeSetting = json_decode($table->params->mergeSetting);
        } else {
            $mergeSetting = array();
        }

        if (isset($table->params->hyperlink) && is_string($table->params->hyperlink)) {
            $tableHyperlink = json_decode($table->params->hyperlink);
        } elseif (!isset($table->params->hyperlink)) {
            $tableHyperlink = array();
        } else {
            $tableHyperlink = $table->params->hyperlink;
        }

        if ($responsive_type === 'hideCols') {
            $hideCols      = 1;
            $res_prioritys = array();
            foreach ($style->cols as $col) {
                if (isset($col[1]->res_priority)) {
                    $res_prioritys[(string) $col[0]] = ((string)$col[1]->res_priority === 'persistent') ? 'persistent' : (int) $col[1]->res_priority;
                }
            }
            $priority = json_encode($res_prioritys, JSON_FORCE_OBJECT);
            $priority = str_replace('"', '\'', $priority);
        } else {
            $hideCols = 0;
            $priority = '{}';
        }

        $content = '<div class="wptmresponsive  wptmtable" id="wptmtable' . (int) $table->id . '">';
        $tblCls = ($sortable ? 'sortable' : '');

        if (!$hideCols) {
            if (($style->table->freeze_row) || ($style->table->freeze_col)) {
                $tblCls .= ' fxdHdrCol';
            }
        }

        if (!isset($style->table->table_height)) {
            $style->table->table_height = 0;
        }

        if ($style->table->enable_filters) {
            $tblCls .= ' filterable';
        } elseif ($enable_pagination && $limit) {
            $tblCls .= ' enablePager';
        }

        if (!$enable_pagination || !$limit) {
            $tblCls .= ' disablePager';
        }
        $content .= '<table id="wptmTbl' . (int)$table->id . '" data-id="' . $table->id . '" data-hidecols="' . $hideCols . '" data-default-sort="'
                    . $default_sort . '" data-order="' . $default_order_sortable . '" data-priority="' . (string)$priority . '"'
                    . '  data-freeze-cols="' . (int)$style->table->freeze_col . '"  data-freeze-rows="' . (int)$style->table->freeze_row
                    . '" data-table-height="' . (int)$style->table->table_height . '" class="' . $tblCls . '">';

        $rowNb = 0;
        $limit_rows = 0;

        $date_formats    = (!empty($configParams['date_formats'])) ? $configParams['date_formats'] : 'Y-m-d';
        $date_formats    = (!empty($style->table->date_formats)) ? $style->table->date_formats : $date_formats;
        $symbol_position = (!empty($configParams['symbol_position'])) ? $configParams['symbol_position'] : 0;
        $symbol_position = (!empty($style->table->symbol_position)) ? $style->table->symbol_position : $symbol_position;
        $currency_symbol = (!empty($configParams['currency_sym'])) ? $configParams['currency_sym'] : '$';
        $currency_symbol = (!empty($style->table->currency_symbol)) ? $style->table->currency_symbol : $currency_symbol;
        $decimal_symbol  = (!empty($configParams['decimal_sym'])) ? $configParams['decimal_sym'] : '.';
        $decimal_symbol  = (!empty($style->table->decimal_symbol)) ? $style->table->decimal_symbol : $decimal_symbol;
        $decimal_count   = (!empty($configParams['decimal_count'])) ? $configParams['decimal_count'] : 0;
        $decimal_count   = (!empty($style->table->decimal_count)) ? $style->table->decimal_count : $decimal_count;
        $thousand_symbol = (!empty($configParams['thousand_sym'])) ? $configParams['thousand_sym'] : ',';
        $thousand_symbol = (!empty($style->table->thousand_symbol)) ? $style->table->thousand_symbol : $thousand_symbol;

        $currency_symbol = str_replace(' ', '', $currency_symbol);
        $string_currency_symbol = str_replace(',', '|', $currency_symbol);
        $string_unit = str_replace(',', '|^', $currency_symbol);
        $string_currency_symbol = '/[' . $string_currency_symbol . ']/';
        $string_unit = '/[^a-zA-Z|' . $string_unit . ']/';

        $M_name = array('', 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec');
        $D_name = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');

        // Fix Col width issue for Mac
        $firstRow = $datas[0];
        $content .= '<colgroup>';
        $countFirstRow = count($firstRow);
        for ($colNb = 0; $colNb < $countFirstRow; $colNb ++) {
            $content .= '<col data-dtr="' . $rowNb . '" data-dtc="' . $colNb . '" class="dtc' . $colNb . '">';
        }
        $content .= '</colgroup>';
        foreach ($datas as $row) {
            if (!empty($style->table->limit_rows)) {
                $limit_rows++;
            }
            if ($rowNb < $heads && $rowNb === 0) {
                $content .= '<thead>';
            } else {
                if ($rowNb === $heads) {
                    $content .= '<tbody>';
                }
            }

            // when have pagination---> freeze_row = limit_rows (joomla)
            if ($limit_rows <= $style->table->freeze_row) {
                $content .= '<tr class=" row' . $rowNb . '">';
            } else {
                $content .= '<tr class="droptable_none row' . $rowNb . '">';
            }
            $colNb = 0;
            foreach ($row as $col) {
                $mergeInfo = self::checkMergeInfo($rowNb, $colNb, $mergeSetting);
                if ($rowNb < $heads) {
                    $mergeInfo .= !$sortable ? ' data-sorter="false"' : '';
                    $content .= '<th ' . $mergeInfo . ' data-dtr="' . $rowNb . '" data-dtc="' . $colNb . '" class="dtr' . $rowNb . ' dtc' . $colNb . '">';
                } else {
                    $content .= '<td ' . $mergeInfo . ' data-dtr="' . $rowNb . '" data-dtc="' . $colNb . '" class="dtr' . $rowNb . ' dtc' . $colNb . '">';
                }
                $cellHtml = '';
                if (isset($col[0]) && $col[0] === '=') {
                    $pattern = '@^=(DATE|DAY|DAYS|DAYS360|AND|OR|XOR|SUM|COUNT|MIN|MAX|AVG|CONCAT|date|day|days|days360|and|or|xor|sum|count|min|max|avg|concat)\\((.*?)\\)$@';
                    if (preg_match($pattern, $col, $matches)) {
                        $cells = explode(';', $matches[2]);
                        $values = array();
                        $checkIsDay = (count(explode('DAY', $matches[1])) === 2) ? true : false;
                        foreach ($cells as $cell) {
                            $vals = explode(':', $cell);
                            $pattern2 = '@([a-zA-Z]+)([0-9]+)@';
                            if (count($vals) === 1) { //single cell
                                preg_match_all($pattern2, $cell, $val0);
                                $data = '';
                                if ($val0[0] !== array()) {
                                    $count = count($val0[0]);
                                    for ($i = 0; $i < $count; $i++) {
                                        $d = $datas[$val0[2][$i]-1][self::convertAlpha($val0[1][$i])-1];
                                        $unit = $d;
                                        if ($d !== null) {
                                            if ($i === 0) {
                                                $data = str_replace($val0[0][$i], $d, $cell);
                                            } else {
                                                $data = str_replace($val0[0][$i], $d, $data);
                                            }
                                        }
                                    }
                                } else {
                                    $data = $cell;
                                    $unit = $cell;
                                }
                                if (strtoupper($matches[1]) !== 'CONCAT') {
                                    $d = preg_replace($string_currency_symbol, '', $data);
                                    if ($checkIsDay === false) {
                                        if ($thousand_symbol === ',') {
                                            $d = preg_replace('/,/', '', $d);
                                        } else {
                                            $d = preg_replace('/\./', '', $d);
                                        }

                                        $d = ($decimal_symbol === ',') ? preg_replace('/,/', '.', $d) : $d;
                                    }
                                } else {
                                    $d = $data;
                                }
                                preg_match_all('/<=|>=|!=|>|<|=/', $d, $math1);
                                $math2 = $math1[0];
                                if (!empty($math2)) {
                                    $d = preg_replace('/[ |A-Za-z]+/', '', $d);
                                    switch ($math2[0]) {
                                        case '<=':
                                            $varNumber = explode('<=', $d);
                                            $number    = (int) ($varNumber[0]) <= (int) ($varNumber[1]);
                                            $number    = ($number === true) ? 'true' : 'false';
                                            break;
                                        case '>=':
                                            $varNumber = explode('>=', $d);
                                            $number    = (int) ($varNumber[0]) >= (int) ($varNumber[1]);
                                            $number    = ($number === true) ? 'true' : 'false';
                                            break;
                                        case '=':
                                            $varNumber = explode('=', $d);
                                            $number    = (int) ($varNumber[0]) === (int) ($varNumber[1]);
                                            $number    = ($number === true) ? 'true' : 'false';
                                            break;
                                        case '!=':
                                            $varNumber = explode('!=', $d);
                                            $number    = (int) ($varNumber[0]) !== (int) ($varNumber[1]);
                                            $number    = ($number === true) ? 'true' : 'false';
                                            break;
                                        case '<':
                                            $varNumber = explode('<', $d);
                                            $number    = (int) ($varNumber[0]) < (int) ($varNumber[1]);
                                            $number    = ($number === true) ? 'true' : 'false';

                                            break;
                                        case '>':
                                            $varNumber = explode('>', $d);
                                            $number    = (int) ($varNumber[0]) > (int) ($varNumber[1]);
                                            $number    = ($number === true) ? 'true' : 'false';

                                            break;
                                        default:
                                            $number = $d;
                                            break;
                                    }
                                } else {
                                    $number = $d;
                                }
                                $values[] = $number;
                            } else { //range
                                preg_match($pattern2, $vals[0], $val1);
                                preg_match($pattern2, $vals[1], $val2);
                                if ($val1 !== array() && $val2 !== array()) {
                                    if ($checkIsDay === true && $val1[2] > $val2[2]) {
                                        $val3 = $val1;
                                        $val1 = $val2;
                                        $val2 = $val3;
                                    }

                                    for ($il = $val1[2] - 1; $il <= $val2[2] - 1; $il ++) {
                                        $convertVal1 = self::convertAlpha($val1[1]) -1;
                                        $convertVal2 = self::convertAlpha($val2[1]) -1;
                                        for ($ik = $convertVal1; $ik <= $convertVal2; $ik ++) {
                                            if (strtoupper($matches[1]) !== 'CONCAT') {
                                                $number = preg_replace($string_currency_symbol, '', $datas[$il][$ik]);
                                                if ($checkIsDay === false) {
                                                    $number = ($thousand_symbol === ',') ? preg_replace('/,/', '', $number) : preg_replace('/\./', '', $number);
                                                    $number = ($decimal_symbol === ',') ? preg_replace('/,/', '.', $number) : $number;
                                                }
                                            } else {
                                                $number = $datas[$il][$ik];
                                            }
                                            $values[] = $number;
                                            $unit     = $datas[$il][$ik];
                                        }
                                    }
                                    if (!empty($val3) && $val3 === $val2) {
                                        $values_data = $values[0];
                                        $values[0] = $values[1];
                                        $values[1] = $values_data;
                                    }
                                } else {
                                    $values[] = $cell;
                                }
                            }
                        }

                        $cellHtmls = array();
                        $value_unit = ($unit !== null) ? preg_replace($string_unit, '', $unit) : '';
                        preg_match_all('/[A-Z|\\\|a-z]+/', $date_formats, self::$date_formats);

                        switch (strtoupper($matches[1])) {
                            case 'DATE':
                                self::$resultCalc = '';
                                if (count($values) === 1) {
                                    preg_match_all('/[a-zA-Z0-9|+|-|\\\]+/', $number, $values);
                                    $values = $values[0];
                                }
                                $text_date = self::convertDay($values, self::$date_formats[0], false);
                                if ($text_date !== false) {
                                    $date_string = date_create($text_date);
                                    if ($date_string !== false) {
                                        preg_match_all('/[^A-Z|^\\\|^a-z]+/', $date_formats, $format_resultCalc);
                                        $date_string = getdate($date_string->getTimestamp());
                                        $date = array();
                                        $date['D'] = $D_name[$date_string['wday']];
                                        $date['l'] = $date_string['weekday'];
                                        $date['j'] = $date_string['mday'];
                                        $date['d'] = ((int)$date_string['mday'] < 10) ? '0' . $date_string['mday'] : $date_string['mday'];
                                        $date['F'] = $date_string['month'];
                                        $date['M'] = $M_name[$date_string['mon']];
                                        $date['n'] = $date_string['mon'];
                                        $date['m'] = ((int)$date_string['mon'] < 10) ? '0' . $date_string['mon'] : $date_string['mon'];
                                        $date['Y'] = $date_string['year'];
                                        $date['y'] = (int)$date_string['year'] % 100;
                                        foreach (self::$date_formats[0] as $date_format => $key) {
                                            if (strpos($key, '\\') !== false
                                                || in_array($key, array('a', 'A', 'g', 'G', 'h', 'H', 'i', 's', 'T')) !== false
                                            ) {
                                                $date[$key] = $values[$date_format];
                                            }
                                            self::$resultCalc .= '' . $date[$key] . (!empty($format_resultCalc[0][$date_format]) ? $format_resultCalc[0][$date_format] : '');
                                        }
                                    } else {
                                        self::$resultCalc = 'NaN';
                                    }
                                } else {
                                    self::$resultCalc = 'NaN';
                                }
                                $cellHtml .= self::$resultCalc;
                                break;
                            case 'DAY':
                                self::$resultCalc = 0;
                                array_map(function ($foo) {
                                    preg_match_all('/[a-zA-Z0-9|+|-|\\\]+/', $foo, $number);
                                    $text_date = self::convertDay($number[0], self::$date_formats[0], true);
                                    if ($text_date !== false) {
                                        $date1            = date_create($text_date);
                                        self::$resultCalc = getdate($date1->getTimestamp());
                                        self::$resultCalc = self::$resultCalc['mday'];
                                    } else {
                                        self::$resultCalc = 'NaN';
                                    }
                                }, $values);
                                $cellHtml .= self::$resultCalc;
                                break;
                            case 'DAYS':
                                self::$resultCalc = 0;
                                array_map(function ($foo) {
                                    self::$resultCalc ++;
                                    preg_match_all('/[a-zA-Z0-9|+|-|\\\]+/', $foo, $number);
                                    $text_date                     = self::convertDay($number[0], self::$date_formats[0], true);
                                    self::$date[self::$resultCalc] = new DateTime($text_date);
                                    self::$date[self::$resultCalc] = self::$date[self::$resultCalc]->getTimestamp();
                                }, $values);
                                self::$resultCalc = (self::$date[1] - self::$date[2])/(24*3600);
                                $cellHtml .= (int)self::$resultCalc;
                                break;
                            case 'DAYS360':
                                self::$resultCalc = 1;
                                $countValue = count($values);
                                for ($i = 0; $i < $countValue; $i++) {
                                    preg_match_all('/[a-zA-Z0-9|+|-|\\\]+/', $values[$i], $number);
                                    $text_date = self::convertDay($number[0], self::$date_formats[0], true);
                                    if ($text_date !== false) {
                                        $result[$i] = getdate(date_create($text_date)->getTimestamp());
                                    } else {
                                        self::$resultCalc = 'NaN';
                                        break;
                                    }
                                }
                                if (self::$resultCalc !== 'NaN') {
                                    if ($result[0]['year'] > $result[1]['year']) {
                                        self::$resultCalc = -1;
                                    }
                                    $result[0]['mday'] = ($result[0]['mday'] === 31) ? 30 : $result[0]['mday'];
                                    $result[1]['mday'] = ($result[1]['mday'] === 31) ? 30 : $result[1]['mday'];
                                    self::$resultCalc = (($result[1]['year'] - $result[0]['year'] - 1) * 360) + ((13 - $result[0]['mon']) * 30 - $result[0]['mday']) + (($result[1]['mon'] - 1) * 30 + $result[1]['mday']);
                                }
                                $cellHtml .= (int)self::$resultCalc;
                                break;
                            case 'AND':
                                self::$resultCalc = 1;
                                array_map(function ($foo) {
                                    self::$resultCalc = self::$resultCalc * $foo;
                                }, $values);
                                $cellHtml .= (self::$resultCalc === 1) ? 'true': 'false';
                                break;
                            case 'OR':
                                self::$resultCalc = 0;
                                array_map(function ($foo) {
                                    self::$resultCalc += $foo;
                                }, $values);
                                $cellHtml .= (self::$resultCalc > 0) ? 'true': 'false';
                                break;
                            case 'XOR':
                                self::$resultCalc = 2;
                                array_map(function ($foo) {
                                    self::$resultCalc += $foo;
                                }, $values);
                                $cellHtml .= ((self::$resultCalc % 2) === 1) ? 'true': 'false';
                                break;
                            case 'SUM':
                                self::$resultCalc = 0;
                                array_map(function ($foo) {
                                    $foo = preg_replace('/[ |A-Za-z]+/', '', $foo);
                                    if (is_numeric($foo)) {
                                        self::$resultCalc = self::$resultCalc + $foo;
                                    }
                                }, $values);
                                $cellHtml .= self::formatSymbols(self::$resultCalc, $decimal_count, $thousand_symbol, $decimal_symbol, $symbol_position, $value_unit);
                                break;
                            case 'COUNT':
                                self::$resultCalc = 0;
                                array_map(function ($foo) {
                                    $foo = preg_replace('/[ |A-Za-z]+/', '', $foo);
                                    if (is_numeric($foo)) {
                                        self::$resultCalc ++;
                                    }
                                }, $values);
                                $cellHtml .= self::$resultCalc;
                                break;
                            case 'MIN':
                                self::$resultCalc = null;
                                array_map(function ($foo) {
                                    $foo = preg_replace('/[ |A-Za-z]+/', '', $foo);
                                    if (is_numeric($foo)) {
                                        if (self::$resultCalc === null || self::$resultCalc > $foo) {
                                            self::$resultCalc = $foo;
                                        }
                                    }
                                }, $values);
                                $cellHtml .= self::formatSymbols(self::$resultCalc, $decimal_count, $thousand_symbol, $decimal_symbol, $symbol_position, $value_unit);
                                break;
                            case 'MAX':
                                self::$resultCalc = null;
                                array_map(function ($foo) {
                                    $foo = preg_replace('/[ |A-Za-z]+/', '', $foo);
                                    if (is_numeric($foo)) {
                                        if (self::$resultCalc < $foo) {
                                            self::$resultCalc = $foo;
                                        }
                                    }
                                }, $values);
                                $cellHtml .= self::formatSymbols(self::$resultCalc, $decimal_count, $thousand_symbol, $decimal_symbol, $symbol_position, $value_unit);
                                break;
                            case 'AVG':
                                self::$resultCalc = 0;
                                self::$n = 0;
                                array_map(function ($foo) {
                                    $foo = preg_replace('/[ |A-Za-z]+/', '', $foo);
                                    if (is_numeric($foo)) {
                                        self::$resultCalc += $foo;
                                        self::$n ++;
                                    }
                                }, $values);
                                if (self::$n > 0) {
                                    self::$resultCalc = self::$resultCalc / self::$n;
                                }
                                $cellHtml .= self::formatSymbols(self::$resultCalc, $decimal_count, $thousand_symbol, $decimal_symbol, $symbol_position, $value_unit);
                                break;
                            case 'CONCAT':
                                self::$resultCalc = '';
                                array_map(function ($foo) {
                                    if (isset($foo[0]) && (string) $foo[0] !== '=') {
                                        self::$resultCalc .= (string) $foo;
                                    }
                                }, $values);
                                $cellHtml .= self::$resultCalc;
                                break;
                        }
                    }
                } elseif (isset($style->cells->{$rowNb . '!' . $colNb}) && isset($style->cells->{$rowNb . '!' . $colNb}[2]->cell_type) && (string)$style->cells->{$rowNb . '!' . $colNb}[2]->cell_type === 'html') {
                    if (isset($tableHyperlink->{$rowNb . '!' . $colNb})) {
                        $checkHtml = strpos($col, $tableHyperlink->{$rowNb . '!' . $colNb}->hyperlink);
                    } else {
                        $checkHtml = false;
                    }
                    if (isset($tableHyperlink->{$rowNb . '!' . $colNb}) && $checkHtml === false) {
                        $cellHtml .= '<a target="_blank" href="' . $tableHyperlink->{$rowNb . '!' . $colNb}->hyperlink . '">' . $col . '</a>';
                    } else {
                        $cellHtml .= $col;
                    }
                } else {
                    $cellHtml .= nl2br($col);
                }

                if (isset($style->cells->{$rowNb . '!' . $colNb}) && isset($style->cells->{$rowNb . '!' . $colNb}[2]->tooltip_content) && (string)$style->cells->{$rowNb . '!' . $colNb}[2]->tooltip_content !== '') {
                    $content .= '<span class="wptm_tooltip ">'.$cellHtml.'<span class="wptm_tooltipcontent">'.$style->cells->{$rowNb . '!' . $colNb}[2]->tooltip_content .'</span></span>';
                } else {
                    $content .= $cellHtml;
                }

                if ($rowNb < $heads) {
                    $content .= '</th>';
                } else {
                    $content .= '</td>';
                }
                $colNb++;
            }
            if ($rowNb < $heads) {
                $content .= '</thead>';
            } else {
                $content .= '</tr>';
            }
            $rowNb++;
        }

        $content .= '</tbody>';

        if ($enable_pagination && $limit) {
            //pager
            $content .= '<tfoot>';
            $content .= '<tr>';
            $content .= '<td colspan="' . $colNb . '" class="ts-pager form-horizontal" >';
            $content .= '<button type="button" class="btn first">';
            $content .=     '<i class="icon-step-backward glyphicon glyphicon-step-backward"></i>';
            $content .= '</button>';
            $content .= '<button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i>';
            $content .= '</button>';
            $content .= '<span class="pagedisplay"></span><!-- this can be any element, including an input -->';
            $content .= '<button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i>';
            $content .= '</button>';
            $content .= '<button type="button" class="btn last">';
            $content .=     '<i class="icon-step-forward glyphicon glyphicon-step-forward"></i>';
            $content .= '</button>';
            $content .= '<select class="pagesize input-mini" title="Select page size">';
            $content .= ($limit === 10) ? '<option selected="selected"' : '<option ';
            $content .= ' value="10">10</option>';
            $content .= ($limit === 20) ? '<option selected="selected"' : '<option ';
            $content .= ' value="20">20</option>';
            $content .= ($limit === 40) ? '<option selected="selected"' : '<option ';
            $content .= ' value="40">40</option>';
            $content .= ($limit === 0) ? '<option selected="selected"' : '<option ';
            $content .= ' value="99999">All</option>';
            $content .= '</select>';
            $content .= '<select class="pagenum input-mini" title="Select page number"></select>';
            $content .= '</td>';
            $content .= '</tr>';
            $content .= '</tfoot>';
        }

        $content .= '</table></div>';
        if (!file_put_contents($file, esc_html($content))) {
            echo 'error saving file!';
            return false;
        }
        return true;
    }

    /**
     * Convert string to m/d/Y
     *
     * @param array   $number      Var of date
     * @param array   $date_format Date format
     * @param boolean $timezone    Check used timezone
     *
     * @return boolean|string
     */
    public static function convertDay($number, $date_format, $timezone)
    {
        $F_name     = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
        $M_name     = array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec');
        $date_array = array();
        $date       = array();
        $number     = (!!$number) ? $number : array();
        if (count($date_format) !== count($number)) {
            return false;
        }

        $countFormatDate = count($date_format);
        for ($n = 0; $n < $countFormatDate; $n ++) {
            $number[$n] = (!!$number[$n]) ? $number[$n] : '';
            if ($date_format[$n] === 'd' || $date_format[$n] === 'j') {
                $date_array[2] = ($number[$n] !== '') ? $number[$n] : 0;
            } elseif ($date_format[$n] === 'S' || $date_format[$n] === 'jS' || $date_format[$n] === 'dS') {
                preg_match_all('/[0-9]+/', $number[$n], $date[2]);
                $date_array[2] = $date[2][0][0];
            } elseif ($date_format[$n] === 'm' || $date_format[$n] === 'n') {
                $date_array[1] = $number[$n];
            } elseif ($date_format[$n] === 'F') {
                $date_array[1] = array_search(strtolower($number[$n]), $F_name) + 1;
            } elseif ($date_format[$n] === 'M') {
                $date_array[1] = array_search(strtolower($number[$n]), $M_name) + 1;
            } elseif ($date_format[$n] === 'Y' || $date_format[$n] === 'y') {
                $date_array[3] = $number[$n];
            } elseif (strtolower($date_format[$n]) === 'g' || strtolower($date_format[$n]) === 'h') {
                $date_array[4] = $number[$n];
            } elseif (strtolower($date_format[$n]) === 'ga' || strtolower($date_format[$n]) === 'ha') {
                preg_match_all('/[0-9]+/', $number[$n], $date[4]);
                $number[$n]    = preg_replace('/[0-9]+/', '', $number[$n]);
                $date_array[4] = (strtolower($number[$n]) === 'am') ? (int) $date[4][0][0] : (int) $date[4][0][0] + 12;
            } elseif (strtolower($date_format[$n]) === 'a') {
                $date_array[7] = $number[$n];
            } elseif (strtolower($date_format[$n]) === 'i' || strtolower($date_format[$n]) === 'ia') {
                preg_match_all('/[0-9]+/', $number[$n], $date[5]);
                $date_array[5] = $date[5][0][0];
            } elseif (strtolower($date_format[$n]) === 's' || strtolower($date_format[$n]) === 'sa') {
                preg_match_all('/[0-9]+/', $number[$n], $date[6]);
                $date_array[6] = $date[6][0][0];
            } elseif ($date_format[$n] === 'T') {
                $date_array[8] = $number[$n];
            } elseif ($date_format[$n] === 'r') {
                if (array_search(strtolower($number[$n]), $F_name) + 1 > 0) {
                    $date_array[1] = array_search(strtolower($number[$n]), $F_name) + 1;
                } else {
                    $date_array[1] = array_search(strtolower($number[$n]), $M_name) + 1;
                }
                return $date_array[1] . '/' . $number[1] . '/' . $number[3] . ' ' . $number[4] . ':' . $number[5] . ':' . $number[6] . ' ' . $number[7];
            }
        }
        if ($date_array[3] === '' || $date_array[2] === '' || $date_array[2] > 31 || $date_array[1] > 12) {
            return false;
        }

        $date_array[4] = (!empty($date_array[4])) ? (int) $date_array[4] : '00';
        $date_array[5] = (!empty($date_array[5])) ? (int) $date_array[5] : '00';
        $date_array[6] = (!empty($date_array[6])) ? (int) $date_array[6] : '00';
        $date_array[7] = (!empty($date_array[7])) ? $date_array[7] : '';
        $date_array[8] = (!empty($date_array[8])) ? $date_array[8] : '';
        $date_array[8] = ($timezone === true) ? $date_array[8] : '';
        if (strtolower($date_array[7]) === 'pm') {
            $date_array[4] = $date_array[4] + 12;
        }
        return (int) $date_array[1] . '/' . (int) $date_array[2] . '/' . $date_array[3] . ' ' . $date_array[4] . ':' . $date_array[5] . ':' . $date_array[6] . $date_array[8];
    }

    /**
     * Convert var calculator by format
     *
     * @param integer $resultCalc      Var calculator
     * @param integer $decimal_count   Count decimal
     * @param string  $thousand_symbol Thousand symbol
     * @param string  $decimal_symbol  Decimal symbol
     * @param integer $symbol_position Symbol position
     * @param string  $value_unit      Value unit
     *
     * @return string
     */
    public static function formatSymbols($resultCalc, $decimal_count, $thousand_symbol, $decimal_symbol, $symbol_position, $value_unit)
    {
        $decimal_count    = (int) $decimal_count;
        $array_resultCalc = str_split((string) round($resultCalc, $decimal_count));
        $decimal          = array_search('.', $array_resultCalc);
        $decimal          = ($decimal !== false) ? $decimal : count($array_resultCalc);
        if ($decimal === count($array_resultCalc)) {
            $array_resultCalc[count($array_resultCalc)] = '.';
        }

        $data = '';
        $j    = ($decimal > 3) ? $decimal % 3 : - 1;
        if ($array_resultCalc[0] === '-') {
            $j                   = ($decimal - 1 > 3) ? ($decimal - 1) % 3 + 1 : - 1;
            $array_resultCalc[0] = (int) $symbol_position === 0 ? $array_resultCalc[0] . $value_unit : $array_resultCalc[0];
        } else {
            $array_resultCalc[0] = (int) $symbol_position === 0 ? $value_unit . $array_resultCalc[0] : $array_resultCalc[0];
        }
        $decimal1 = $decimal;
        for ($i = 0; $i < $decimal1 + 1 + $decimal_count; $i ++) {
            if ($i + 1 === $j && $array_resultCalc[$i] !== '-') {
                $data .= is_numeric($array_resultCalc[$i]) ? $array_resultCalc[$i] . $thousand_symbol : $array_resultCalc[$i];
            } elseif ($j !== - 1 && $i + 1 - $j !== 0 && ($i + 1 - $j) % 3 === 0 && $i < $decimal - 1) {
                $data .= $array_resultCalc[$i] . $thousand_symbol;
            } elseif ($i === $decimal && $decimal_count !== 0) {
                $data .= $decimal_symbol;
            } elseif (empty($array_resultCalc[$i])) {
                $data .= '0';
            } elseif ($array_resultCalc[$i] !== $decimal_symbol) {
                $data .= $array_resultCalc[$i];
            }
        }
        return ((int) $symbol_position === 0) ? $data : $data . ' ' . $value_unit;
    }

    /**
     * Check merge cell
     *
     * @param integer $rowNb         Row
     * @param integer $colNb         Col NB
     * @param array   $mergeSettings Setting
     *
     * @return string
     */
    private static function checkMergeInfo($rowNb, $colNb, $mergeSettings)
    {
        $result = '';
        $count = count($mergeSettings);
        if (!is_array($mergeSettings) || $count === 0) {
            return $result;
        }
        foreach ($mergeSettings as $ms) {
            if ((int) $ms->row === (int) $rowNb && (int) $ms->col === (int) $colNb) {
                $result = ' rowspan="' . $ms->rowspan . '" colspan="' . $ms->colspan . '" ';
            } elseif ($ms->row <= $rowNb && $rowNb < $ms->row + $ms->rowspan && $ms->col <= $colNb && $colNb < $ms->col + $ms->colspan) {
                $result = ' style="display:none" ';
            }
        }

        return $result;
    }

    /**
     * Get val cell
     *
     * @param string $col Position of cell
     *
     * @return integer
     */
    private static function convertAlpha($col)
    {
        $col = str_pad($col, 2, '0', STR_PAD_LEFT);
        $i   = ((string) $col{0} === '0') ? 0 : (ord($col{0}) - 64) * 26;
        $i   += ord($col{1}) - 64;

        return $i;
    }
}
