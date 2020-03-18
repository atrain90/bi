<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Filter;
use Joomunited\WPFramework\v1_0_5\Model;
use Joomunited\WPFramework\v1_0_5\Factory;
use Joomunited\WPFramework\v1_0_5\Application;

defined('ABSPATH') || die();

/**
 * Class wptmFilter
 */
class WptmFilter extends Filter
{
    /**
     * Var style highlight cell
     *
     * @var string
     */
    private $hightLightCss = '';

    /**
     * Function load shortcode
     *
     * @return void
     */
    public function load()
    {
        add_filter('the_content', array($this, 'wptmReplaceContent'), 9);
        add_filter('themify_builder_module_content', array($this, 'themifyModuleContent'));
        // Register our shortcode
        add_shortcode('wptm', array($this, 'applyShortcode'));
    }

    /**
     * Return content of our shortcode
     *
     * @param array $args Data of chart/table
     *
     * @return string
     */
    public function applyShortcode($args = array())
    {
        $html = '';
        if (isset($args['id']) && !empty($args['id'])) {
            $id_table = $args['id'];
            $app  = Application::getInstance('Wptm');
            $model    = Model::getInstance('tableSite');
            $table    = $model->getItem($id_table);
            if (!$table) {
                return $html;
            }
            $html = $this->replaceTable($id_table, $table);
        } elseif (isset($args['id-chart']) && !empty($args['id-chart'])) {
            $app  = Application::getInstance('Wptm');
            $id_chart = $args['id-chart'];
            $model    = Model::getInstance('tableSite');
            $table    = $model->getTableFromChartId($id_chart);
            $html     = $this->replaceChart($id_chart, $table);
        }

        return $html;
    }

    /**
     * Get function wptmReplaceContent
     *
     * @param string $content Strings to search and replace
     *
     * @return mixed
     */
    public function themifyModuleContent($content)
    {
        $content = $this->wptmReplaceContent($content);
        return $content;
    }

    /**
     * Function replace
     *
     * @param string $content Strings to search and replace
     *
     * @return mixed
     */
    public function wptmReplaceContent($content)
    {
        $content = preg_replace_callback('@<img[^>]*?data\-wptmtable="([0-9]+)".*?/?>@', array(
            $this,
            'replace'
        ), $content);

        return $content;
    }

    /**
     * Get function replaceChart/replaceTable
     *
     * @param array $match Data table
     *
     * @return string
     */
    private function replace($match)
    {
        $app      = Application::getInstance('Wptm');
        $content  = '';
        $id_table = $match[1];
        $model    = Model::getInstance('tableSite');
        $table    = $model->getItem($id_table);
        if (!$table) {
            return $content;
        }

        $exp = '@<img.*data\-wptm\-chart="([0-9]+)".*?>@';
        preg_match($exp, $match[0], $matches);
        if (count($matches) > 0) { //is a chart
            $id_chart = $matches[1];
            $content  = $this->replaceChart($id_chart, $table);
        } else {  //is a table
            $content = $this->replaceTable($id_table, $table);
        }

        return $content;
    }

    /**
     * Create table(front_end)
     *
     * @param integer $id_table Id of table
     * @param object  $table    Data table
     *
     * @return string
     */
    private function replaceTable($id_table, $table)
    {
        $datas      = json_decode($table->datas);
        $style      = json_decode($table->style);
        $params     = json_decode(stripslashes_deep($table->params));
        $table_type = 'html';
        if (is_object($params) && isset($params->table_type) && (string)$params->table_type === 'mysql') {
            $table_type = 'mysql';
            $datas      = $table->datas;
        }
        $modelConfig                  = $this->getModel('configSite');
        $this->params                 = $modelConfig->getConfig();
        $hightLight                   = !isset($this->params['enable_hightlight']) ? 0 : (int)$this->params['enable_hightlight'];
        $table->hightlight_color      = !isset($this->params['tree_hightlight_color']) ? '#ffffaa' : $this->params['tree_hightlight_color'];
        $table->hightlight_font_color = !isset($this->params['tree_hightlight_font_color']) ? '#ffffff' : $this->params['tree_hightlight_font_color'];
        $table->hightlight_opacity    = !isset($this->params['hightlight_opacity']) ? '0.9' : $this->params['hightlight_opacity'];

        if (!isset($style->table->freeze_col)) {
            $style->table->freeze_col = 0;
        }
        if (!isset($style->table->freeze_row)) {
            $style->table->freeze_row = 0;
        }
        if (!isset($style->table->enable_filters)) {
            $style->table->enable_filters = 0;
        }
        if (isset($style->table->use_sortable) && (int)$style->table->use_sortable === 1) {
            $sortable = true;
        } else {
            $sortable = false;
        }
        $default_order_sortable = isset($style->table->default_order_sortable) ? (int) $style->table->default_order_sortable : 0;
        $default_sort           = isset($style->table->default_sortable) ? (int) $style->table->default_sortable : - 1;
        $enable_pagination      = isset($style->table->enable_pagination) ? (int) $style->table->enable_pagination : 0;
        if (isset($style->table->responsive_type) && (string)$style->table->responsive_type === 'hideCols') {
            $responsive_type = 'hideCols';
        } else {
            $responsive_type = 'scroll';
        }
        if (!isset($style->table->enable_filters)) {
            $style->table->enable_filters = false;
        }
        if ($responsive_type === 'hideCols') {
            $hideCols      = true;
            $res_prioritys = array();
            foreach ($style->cols as $col) {
                if (isset($col[1]->res_priority)) {
                    $res_prioritys[(string) $col[0]] = ((string)$col[1]->res_priority === 'persistent') ? 'persistent' : (int) $col[1]->res_priority;
                }
            }
            $priority = json_encode($res_prioritys, JSON_FORCE_OBJECT);
            $priority = str_replace('"', '\'', $priority);
        } else {
            $hideCols = false;
            $priority = '{}';
        }

        if ($datas !== null && !empty($datas)) {
            wp_enqueue_script('jquery');

            // filters
            if ($sortable && !$style->table->enable_filters) {
                wp_enqueue_script('wptm_sorttable', plugins_url('assets/js/sorttable.js', __FILE__), array(), WPTM_VERSION);
            }

            // hightlight
            if ($hightLight === 1) {
                wp_enqueue_script('wptm_sorttable_wholly', plugins_url('assets/js/wholly.js', __FILE__), array(), WPTM_VERSION);
            } else {
                $table->hightlight_color = 'not hightlight';
            }

            if ($hideCols) {
                wp_enqueue_script('wptm_restable', plugins_url('assets/restable/js/restable.js', __FILE__), array(), WPTM_VERSION);
                wp_enqueue_style('wptm-theme-table', plugins_url('assets/restable/css/restable.css', __FILE__), array(), WPTM_VERSION);
            }
            if ($responsive_type === 'scroll') {
                if ((isset($style->table->freeze_row) && $style->table->freeze_row) || (isset($style->table->freeze_col) && $style->table->freeze_col)) {
                    wp_enqueue_script('wptm_fixedtable', plugins_url('assets/js/fixed_table_rc.js', __FILE__), array(), WPTM_VERSION);
                    wp_enqueue_style('wptm_fixedtable', plugins_url('assets/css/fixed_table_rc.css', __FILE__), array(), WPTM_VERSION);
                }
            }

            wp_enqueue_script('wptm_tablesorter', plugins_url('assets/tablesorter/jquery.tablesorter.js', __FILE__), array(), WPTM_VERSION);
            wp_enqueue_script('jquery-fileDownload', plugins_url('assets/js/jquery.fileDownload.js', __FILE__), array(), WPTM_VERSION);
            if ($style->table->enable_filters || $enable_pagination === 1) {
                wp_enqueue_script('wptm_tablesorter_widgets', plugins_url('assets/tablesorter/jquery.tablesorter.widgets.js', __FILE__), array(), WPTM_VERSION);
                wp_enqueue_script('wptm_tablesorter_pager', plugins_url('assets/tablesorter/jquery.tablesorter.pager.js', __FILE__), array(), WPTM_VERSION);

                wp_enqueue_style('wptm_tablesorter_jui', plugins_url('assets/tablesorter/theme.jui.css', __FILE__), array(), WPTM_VERSION);
                wp_enqueue_style('wptm_tablesorter_bootstrap', plugins_url('assets/tablesorter/theme.bootstrap.css', __FILE__), array(), WPTM_VERSION);
                wp_enqueue_style('wptm_tablesorter_pager', plugins_url('assets/tablesorter/jquery.tablesorter.pager.css', __FILE__), array(), WPTM_VERSION);
            }

            wp_enqueue_script('wptm_table' . $id_table, plugins_url('assets/js/wptm_front.js', __FILE__), array(), WPTM_VERSION);
            $check_sortable = $sortable ? 'use_sortable' : '';
            $content        = '<div class="wptm_table tablesorter-bootstrap ' . $check_sortable . '" data-id="' . (int) $table->id . '" data-hightlight="' . $hightLight . '">';

            /*button download table*/
            if (isset($style->table->download_button) && $style->table->download_button) {
                $app      = Application::getInstance('Wptm');
                $content .= '<input type="button" data-href="' . $app->getAjaxUrl() . '" href="javascript:void(0);" class="download_wptm" value="' . esc_attr('Download Table', 'wptm') . '"/>';
            }

            if ($table_type === 'html') {
                require_once dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'wptmHelper.php';
                $table_hash = md5($table->datas . $table->style . $table->params);
                $wptmHelper = new WptmHelper();
                $wptmHelper->htmlRender($table, $this->params);
                $folder  = wp_upload_dir();
                $folder  = $folder['basedir'] . DIRECTORY_SEPARATOR . 'wptm' . DIRECTORY_SEPARATOR;
                $file    = $folder . $table->id . '_' . $table_hash . '.html';
                $content .= file_get_contents($file);
                $content = html_entity_decode($content);
            } else {
                $limit = isset($style->table->limit_rows) ? (int) $style->table->limit_rows : 0;
                global $wpdb;
                if (!$enable_pagination && $limit) {
                    $query = $table->datas . ' LIMIT ' . $limit;
                } else {
                    $query = $table->datas ;
                }
                //phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- query already escaped
                $result = $wpdb->get_results($query, ARRAY_A);
                if (!empty($result)) {
                    $content = '<div class="wptmresponsive  wptmtable wptm_dbtable" id="wptmtable' . (int) $table->id . '">';
                    $tblCls  = ($sortable ? 'sortable' : '');

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
                    } else {
                        if ($enable_pagination && $limit) {
                            $tblCls .= ' enablePager';
                        }
                    }
                    if (!$enable_pagination || !$limit) {
                        $tblCls .= ' disablePager';
                    }

                    $data_hidecolss = $hideCols === true ? 1 : 0;
                    $content      .= '<table id="wptmTbl' . (int) $table->id . '" data-id="' . $table->id . '" data-hidecols="' . $data_hidecolss . '" data-default-sort="'
                                     . $default_sort . '" data-order="' . $default_order_sortable . '" data-priority="' . (string) $priority . '"'
                                     . '  data-freeze-cols="' . (int) $style->table->freeze_col . '"  data-freeze-rows="' . (int) $style->table->freeze_row
                                     . '" data-table-height="' . (int) $style->table->table_height . '" class="' . $tblCls . '" data-pagesize="' . $limit . '">';
                    $table_params = json_decode(stripslashes_deep($table->params));
                    $headers      = $table_params->custom_titles;
                    ob_start();
                    include(WPTM_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'table_template.inc.php');
                    $ret_val = ob_get_contents();
                    ob_end_clean();

                    $content .= $ret_val;

                    if ($enable_pagination && $limit) {
                        $colNb = count($result[0]);
                        //pager
                        $content .= '<tfoot><tr>';
                        $content .= '<td colspan="' . $colNb . '" class="ts-pager form-horizontal" >';
                        $content .= '<button type="button" class="btn first">';
                        $content .= '<i class="icon-step-backward glyphicon glyphicon-step-backward"></i>';
                        $content .= '</button>';
                        $content .= '<button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i>';
                        $content .= '</button>';
                        $content .= '<span class="pagedisplay"></span><!-- this can be any element, including an input -->';
                        $content .= '<button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i>';
                        $content .= '</button>';
                        $content .= '<button type="button" class="btn last">';
                        $content .= '<i class="icon-step-forward glyphicon glyphicon-step-forward"></i>';
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
                        $content .= '</td></tr></tfoot>';
                    }
                    $content .= '</table></div>';
                } else {
                    $content = __('table is empty', 'wptm');
                }
            }
            $this->styleRender($table);
            $content .= '<script>'
                        . 'wptm_ajaxurl = "' . esc_url_raw(Factory::getApplication('wptm')->getAjaxUrl()) . '";'
                        . '</script>';
            $content .= '</div><style>' . $this->hightLightCss . '</style>';
        }
        return $content;
    }

    /**
     * Create chart(front_end)
     *
     * @param integer $id_chart Id of chart
     * @param object  $table    Data chart
     *
     * @return string
     */
    private function replaceChart($id_chart, $table)
    {
        $content    = '';
        $modelChart = Model::getInstance('chartSite');
        $chart      = $modelChart->getChart($id_chart);

        if ($chart) {
            $modelConfig  = $this->getModel('configSite');
            $configParams = $modelConfig->getConfig();

            $chartData   = $this->getChartData($chart->datas, $chart->tData);
            $chartConfig = json_decode($chart->config);

            $table_config    = json_decode($table->style);
            $symbol_position = (!empty($configParams['symbol_position'])) ? $configParams['symbol_position'] : 0;
            $symbol_position = (!empty($table_config->table->symbol_position)) ? $table_config->table->symbol_position : $symbol_position;
            $currency_symbol = (!empty($configParams['currency_sym'])) ? $configParams['currency_sym'] : '$';
            $currency_symbol = (!empty($table_config->table->currency_symbol)) ? $table_config->table->currency_symbol : $currency_symbol;
            $decimal_symbol  = (!empty($configParams['decimal_sym'])) ? $configParams['decimal_sym'] : '.';
            $decimal_symbol  = (!empty($table_config->table->decimal_symbol)) ? $table_config->table->decimal_symbol : $decimal_symbol;
            $decimal_count   = (!empty($configParams['decimal_count'])) ? $configParams['decimal_count'] : 0;
            $decimal_count   = (!empty($table_config->table->decimal_count)) ? $table_config->table->decimal_count : $decimal_count;
            $thousand_symbol = (!empty($configParams['thousand_sym'])) ? $configParams['thousand_sym'] : ',';
            $thousand_symbol = (!empty($table_config->table->thousand_symbol)) ? $table_config->table->thousand_symbol : $thousand_symbol;

            $jsChartData = $this->buildJsChartData(
                $chartData,
                $chart->type,
                $chartConfig,
                $currency_symbol,
                $decimal_symbol,
                $thousand_symbol
            );

            if (!$chartConfig) {
                $chartConfig = new stdClass();
            }
            $chartConfig->width       = isset($chartConfig->width) ? $chartConfig->width : 500;
            $chartConfig->height      = isset($chartConfig->height) ? $chartConfig->height : 375;
            $chartConfig->chart_align = isset($chartConfig->chart_align) ? $chartConfig->chart_align : 'center';
            $symbol                   = '';
            $countChart = count($chartData);
            for ($i = 0; $i < $countChart; $i ++) {
                $count = count($chartData[$i]);
                for ($j = 0; $j < $count; $j ++) {
                    if (strpos($chartData[$i][$j], $currency_symbol) !== false) {
                        $chartData[$i][$j] = str_replace($thousand_symbol, '', $chartData[$i][$j]);
                        $chartData[$i][$j] = str_replace($decimal_symbol, '.', $chartData[$i][$j]);
                        if (is_numeric(str_replace($currency_symbol, '', $chartData[$i][$j]))) {
                            $symbol = $currency_symbol;
                            break;
                        }
                    }
                }
            }

            $js = 'var DropChart = {};' . "\n";
            $js .= 'DropChart.id = "' . $chart->id . '" ; ' . "\n";
            $js .= 'DropChart.type = "' . $chart->type . '" ; ' . "\n";
            $js .= 'DropChart.data = ' . $jsChartData . '; ' . "\n";
            $js .= 'DropChart.currency_symbols = "' . $symbol . '"; ' . "\n";
            $js .= 'DropChart.places = ' . $decimal_count . '; ' . "\n";
            $js .= 'DropChart.unit_symbols = ' . $symbol_position . '; ' . "\n";
            $js .= 'DropChart.decimal_symbols = "' . $decimal_symbol . '"; ' . "\n";
            $js .= 'DropChart.thousand_symbols = "' . $thousand_symbol . '"; ' . "\n";
            if ($chart->config) {
                $js .= 'DropChart.config = ' . $chart->config . '; ' . "\n";
            } else {
                $js .= 'DropChart.config = {} ; ' . "\n";
            }
            $js .= ' if(typeof DropCharts === "undefined") { var DropCharts = []; } ; ' . "\n";

            $js .= ' DropCharts.push(DropChart) ; ' . "\n";

            wp_enqueue_script('jquery');
            wp_enqueue_script('wptm_chart', plugins_url('assets/js/chart.min.js', __FILE__), array(), '2.4.0');
            wp_enqueue_script('wptm_dropchart', plugins_url('assets/js/dropchart.js', __FILE__), array(), '2.4.0');

            $content = '<div class="chartContainer" id="chartContainer' . $chart->id . '">';

            $align = '';
            switch ($chartConfig->chart_align) {
                case 'left':
                    $align = ' margin : 0 auto 0 0; ';
                    break;
                case 'right':
                    $align = ' margin : 0 0 0 auto ';
                    break;
                case 'none':
                    break;
                case 'center':
                default:
                    $align = ' margin : 0 auto 0 auto ';
                    break;
            }

            $content .= '<div class="canvasWraper" style="max-height:' . $chartConfig->height
                        . 'px; max-width:' . $chartConfig->width . 'px;' . $align . '" >';
            $content .= '<canvas class="canvas"  height="' . $chartConfig->height . '" width="' . $chartConfig->width . '"></canvas>';
            $content .= '</div></div>';
            $content .= '<script>' . $js . '</script>';
        }

        return $content;
    }

    /**
     * Render style
     *
     * @param object $table Data table
     *
     * @return void
     */
    private function styleRender($table)
    {
        $hightlight_color      = $table->hightlight_color;
        $hightlight_font_color = $table->hightlight_font_color;
        $hightlight_opacity    = $table->hightlight_opacity;
        if ($hightlight_color !== 'not hightlight') {
            require_once dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'chartStyleSet.php';
            $chartStyleObj         = new ChartStyleSet($hightlight_color);
            $highlighting_rgbcolor = $chartStyleObj->hex2rgba($hightlight_color, $hightlight_opacity);
            $table->hightlight_css = '.droptables-highlight-horizontal, .droptables-highlight-vertical  {  color: ' . $hightlight_font_color . ' !important; background: ' . $highlighting_rgbcolor . ' !important; }';
        } else {
            $table->hightlight_css = '';
        }

        $this->hightLightCss = $table->hightlight_css;
        require_once dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'wptmHelper.php';
        $wptmHelper = new WptmHelper();
        $wptmHelper->styleRender($table);

        $upload_url = wp_upload_dir();
        if (is_ssl()) {
            $upload_url['baseurl'] = str_replace('http://', 'https://', $upload_url['baseurl']);
        }
        $upload_url = $upload_url['baseurl'] . '/wptm/';
        wp_enqueue_style('wptm-table-' . $table->id, $upload_url . $table->id . '_' . $table->hash . '.css', array(), WPTM_VERSION);
        wp_enqueue_style('wptm-front', plugins_url('assets/css/front.css', __FILE__), array(), WPTM_VERSION);
    }

    /**
     * Build js chart
     *
     * @param array  $data            Data cell in chart
     * @param string $type            Type chart
     * @param object $config          Data config chart
     * @param string $currency_symbol Currency symbol
     * @param string $decimal_symbol  Decimal symbol
     * @param string $thousand_symbol Thousand symbol
     *
     * @return mixed|string|void
     */
    private function buildJsChartData(
        $data,
        $type,
        $config,
        $currency_symbol,
        $decimal_symbol,
        $thousand_symbol
    ) {
        $result = '';

        if (!$config || !is_object($config)) {
            $config            = new stdClass();
            $config->pieColors = '';
            $config->colors    = '';
        }
        $config->dataUsing           = isset($config->dataUsing) ? $config->dataUsing : 'row';
        $config->useFirstRowAsLabels = isset($config->useFirstRowAsLabels) ? $config->useFirstRowAsLabels : false;
        $dataSets                    = $this->getDataSets(
            $data,
            $config->dataUsing,
            $currency_symbol,
            $decimal_symbol,
            $thousand_symbol
        );
        if (!isset($dataSets->data) || (count($dataSets->data) === 0)) {
            return $result;
        }

        switch ($type) {
            case 'PolarArea':
            case 'Pie':
            case 'Doughnut':
                $chartData = $this->convertForPie($dataSets->data[0], $dataSets->axisLabels, $config->pieColors);
                break;

            case 'Bar':
            case 'Radar':
            case 'Line':
            default:
                $chartData = $this->convertForLine($dataSets, $config->useFirstRowAsLabels, $config->colors);
                break;
        }
        $result = json_encode($chartData);
        return $result;
    }

    /**
     * Check column is int
     *
     * @param array  $cellsData       Data cell
     * @param string $currency_symbol Currrency symbol
     *
     * @return array
     */
    private function replaceCell($cellsData, $currency_symbol)
    {
        $data1 = array();
        $i     = 0;
        $data2 = - 1;
        foreach ($cellsData as $k => $v) {
            $v1 = preg_replace('/[0-9\.\,\-]/', '', $v);
            $v1 = str_replace($currency_symbol, '', $v1);
            if ($v1 === '') {
                $data1[$i] = $k;
                $i ++;
            } elseif ($v1 !== '') {
                $data2 = $k;
            }
        }
        $data    = array();
        $data[0] = $data1;
        if ($data2 !== - 1) {
            $data[1] = $data2;
        }
        return $data;
    }

    /**
     * Convert to number
     *
     * @param array|string $arr Data cell
     *
     * @return array|mixed
     */
    public function convertToNumber($arr)
    {
        if (is_array($arr)) {
            $countArr = count($arr);
            for ($i = 0; $i < $countArr; $i ++) {
                if (is_array($arr[$i])) {
                    $count = count($arr[$i]);
                    for ($j = 0; $j < $count; $j ++) {
                        $arr[$i][$j] = str_replace(',', '', $arr[$i][$j]);
                    }
                } else {
                    $arr[$i] = str_replace(',', '', $arr[$i]);
                }
            }
        } else {
            $arr = str_replace(',', '', $arr);
        }
        return $arr;
    }

    /**
     * Get data cell to chart
     *
     * @param array  $cellsData       Data cells
     * @param string $dataUsing       Data Switch
     * @param string $currency_symbol Currency symbol
     * @param string $decimal_symbol  Decimal symbol
     * @param string $thousand_symbol Thousand symbol
     *
     * @return stdClass
     */
    private function getDataSets(
        $cellsData,
        $dataUsing,
        $currency_symbol,
        $decimal_symbol,
        $thousand_symbol
    ) {
        $result             = new stdClass();
        $result->data       = array();
        $result->graphLabel = array();
        $result->axisLabels = array();

        $axisLabels = array();
        $grapLabels = array();

        $rValid     = $this->hasNumbericRow($cellsData, $currency_symbol);
        $rCellsData = $this->transposeArr($cellsData);
        $cValid     = $this->hasNumbericRow($rCellsData, $currency_symbol);
        $cell       = array();
        if (!$rValid && !$cValid) { //remove first row and column
            $axisLabels = array_shift($cellsData);
            array_shift($axisLabels);
            $cell       = $this->replaceCell($cellsData[0], $currency_symbol);
            $cellsData1 = array();
            $countCell = count($cell[0]);
            foreach ($cellsData as $k => $v) {
                for ($i = 0; $i < $countCell; $i ++) {
                    $v[$cell[0][$i]]    = str_replace($thousand_symbol, '', $v[$cell[0][$i]]);
                    $v[$cell[0][$i]]    = str_replace($decimal_symbol, '.', $v[$cell[0][$i]]);
                    $cellsData1[$k][$i] = preg_replace('/[^0-9\\.\-]/', '', $v[$cell[0][$i]]);
                }
                $grapLabels[$k] = $cellsData[$k][$cell[1]];
            }
            $cellsData = $cellsData1;
        } elseif (!$rValid && $cValid) { // remove first column
            $cell = $this->replaceCell($cellsData[0], $currency_symbol);
            $countCell = count($cell[0]);
            foreach ($cellsData as $k => $v) {
                for ($i = 0; $i < $countCell; $i ++) {
                    $v[$cell[0][$i]]             = str_replace($thousand_symbol, '', $v[$cell[0][$i]]);
                    $v[$cell[0][$i]]             = str_replace($decimal_symbol, '.', $v[$cell[0][$i]]);
                    $cellsData[$k][$cell[0][$i]] = preg_replace('/[^0-9\\.\-]/', '', $v[$cell[0][$i]]);
                }
                $grapLabels[$k] = $cellsData[$k][$cell[1]];
            }
            $axisLabels = $cellsData[0];
        } elseif (!$cValid && $rValid) { //remove first row
            $axisLabels = array_shift($cellsData);
            $cell       = $this->replaceCell($cellsData[0], $currency_symbol);
            $countCell = count($cell[0]);
            foreach ($cellsData as $k => $v) {
                for ($i = 0; $i < $countCell; $i ++) {
                    $v[$cell[0][$i]]             = str_replace($thousand_symbol, '', $v[$cell[0][$i]]);
                    $v[$cell[0][$i]]             = str_replace($decimal_symbol, '.', $v[$cell[0][$i]]);
                    $cellsData[$k][$cell[0][$i]] = preg_replace('/[^0-9\\.\-]/', '', $v[$cell[0][$i]]);
                }
            }
            $rcellsData = $this->transposeArr($cellsData);
            $grapLabels = $rcellsData[0];
            if (!$this->isNumbericArray($cellsData[0], $currency_symbol)) {
                array_shift($cellsData);
                array_shift($grapLabels);
            }
        } else {
            //do nothing yet
            $axisLabels = $cellsData[0];
            $cell       = $this->replaceCell($cellsData[0], $currency_symbol);
            $countCell = count($cell[0]);
            foreach ($cellsData as $k => $v) {
                for ($i = 0; $i < $countCell; $i ++) {
                    $v[$cell[0][$i]]             = str_replace($thousand_symbol, '', $v[$cell[0][$i]]);
                    $v[$cell[0][$i]]             = str_replace($decimal_symbol, '.', $v[$cell[0][$i]]);
                    $cellsData[$k][$cell[0][$i]] = preg_replace('/[^0-9\\.\-]/', '', $v[$cell[0][$i]]);
                }
            }

            $rcellsData = $this->transposeArr($cellsData);
            $grapLabels = $rcellsData[0];
        }
        //switch cell data and label
        if ($dataUsing !== 'row') {
            $cellsData  = $this->transposeArr($cellsData);
            $temp       = $axisLabels;
            $axisLabels = $grapLabels;
            $grapLabels = $temp;
        }
        $result->axisLabels = $axisLabels;
        $j                  = 0;
        $countCellData = count($cellsData);
        for ($i = 0; $i < $countCellData; $i ++) {
            if ($this->isNumbericArray($cellsData[$i], $currency_symbol)) {
                $result->data[$j]       = $cellsData[$i];
                $result->graphLabel[$j] = $grapLabels[$i];
                $j ++;
            }
        }
        $count = $result;

        if (count((array)$count) === 0) {
            $cellsData = $this->transposeArr($cellsData);
            $countCellData = count($cellsData);
            for ($i = 0; $i < $countCellData; $i ++) {
                if ($this->isNumbericArray($cellsData[$i], $currency_symbol)) {
                    $result->data[$j]       = $cellsData[$i];
                    $result->graphLabel[$j] = !empty($grapLabels[$i]) ? $grapLabels[$i] : '0';
                    $j ++;
                }
            }
        }
        $result->data = $this->convertToNumber($result->data);
        return $result;
    }

    /**
     * Convert for line table
     *
     * @param object  $dataSets            Data chart after change
     * @param boolean $useFirstRowAsLabels Use First Row As Labels
     * @param string  $colors              Color lines in chart
     *
     * @return stdClass
     */
    private function convertForLine($dataSets, $useFirstRowAsLabels, $colors)
    {
        $result           = new stdClass();
        $result->labels   = array();
        $result->datasets = array();
        if (!is_array($dataSets->data) || (count($dataSets->data) === 0)) {
            return $result;
        }
        $countDatasets = count($dataSets->data[0]);
        if ($useFirstRowAsLabels) {
            for ($i = 0; $i < $countDatasets; $i ++) {
                $result->labels[$i] = $dataSets->axisLabels[$i];
            }
        } else {
            for ($i = 0; $i < $countDatasets; $i ++) {
                $result->labels[$i] = '';
            }
        }

        $count = count($dataSets->data);
        for ($i = 0; $i < $count; $i ++) {
            $dataSet              = new stdClass();
            $dataSet->data        = $dataSets->data[$i];
            $dataSet->label       = $dataSets->graphLabel[$i];
            $styleSet             = $this->getStyleSet($i, $colors);
            $dataSet              = (object) array_merge((array) $dataSet, (array) $styleSet);
            $result->datasets[$i] = $dataSet;
        }

        return $result;
    }

    /**
     * Convert from datas chart var(pie)
     *
     * @param array  $data       Data cells of pie chart
     * @param array  $axisLabels Data axis Labels
     * @param string $pieColors  Color pie
     *
     * @return array
     */
    private function convertForPie($data, $axisLabels, $pieColors)
    {
        $datas = array();
        $defaultColors = array('#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360');
        $highlights    = array('#FF5A5E', '#5AD3D1', '#FFC870', '#A8B3C5', '#616774');

        if (!$pieColors) {
            $colors = $defaultColors;
        } else {
            $colors = explode(',', $pieColors);
        }
        $countData =  count($data);
        for ($i = 0; $i < $countData; $i ++) {
            $dataSet        = new stdClass();
            $dataSet->value = (float) $data[$i];
            $dataSet->label = (string) $axisLabels[$i];
            if (isset($colors[$i])) {
                $dataSet->color     = $colors[$i];
                $dataSet->highlight = $this->alterBrightness($colors[$i], 0.3);
            } else {
                $dataSet->color     = $defaultColors[$i % 5];
                $dataSet->highlight = $highlights[$i % 5];
            }

            $datas[$i] = $dataSet;
        }

        return $datas;
    }

    /**
     * Convert string color
     *
     * @param string  $colourstr Color str
     * @param integer $steps     Steps
     *
     * @return string
     */
    public function alterBrightness($colourstr, $steps)
    {
        $colourstr = str_replace('#', '', $colourstr);
        $rhex      = substr($colourstr, 0, 2);
        $ghex      = substr($colourstr, 2, 2);
        $bhex      = substr($colourstr, 4, 2);

        $r = hexdec($rhex);
        $g = hexdec($ghex);
        $b = hexdec($bhex);

        $r = max(0, min(255, $r + $r * $steps));
        $g = max(0, min(255, $g + $g * $steps));
        $b = max(0, min(255, $b + $b * $steps));

        return '#' . dechex($r) . dechex($g) . dechex($b);
    }

    /**
     * Get style
     *
     * @param integer $i      Order line
     * @param string  $colors Color line
     *
     * @return ChartStyleSet|null
     */
    private function getStyleSet($i, $colors)
    {
        $result        = null;
        $defaultColors = array('#DCDCDC', '#97BBCD', '#4C839E');

        if (!$colors) {
            $arrColors = $defaultColors;
        } else {
            $arrColors = explode(',', $colors);
        }

        if (count($arrColors) && isset($arrColors[$i])) {
            $color = $arrColors[$i];
        } else {
            $color = $defaultColors[$i % 3];
        }
        require_once dirname(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'chartStyleSet.php';
        $result = new ChartStyleSet($color);

        return $result;
    }

    /**
     * Check exist number in array
     *
     * @param array  $arr             Array needs to check
     * @param string $currency_symbol Currency symbol
     *
     * @return boolean
     */
    private function isNumbericArray($arr, $currency_symbol)
    {
        $valid = true;
        $countArr = count($arr);
        for ($c = 0; $c < $countArr; $c ++) {
            if ($arr[$c] !== '') {
                $arr[$c] = str_replace($currency_symbol, '', (string) $arr[$c]);
                $arr[$c] = preg_replace('/[\.\,\-]/', '', $arr[$c]);
                if (!is_numeric($arr[$c])) {
                    $valid = false;
                }
            }
        }

        return $valid;
    }

    /**
     * Check number in row
     *
     * @param array  $cells           Data cells
     * @param string $currency_symbol Currency symbol
     *
     * @return boolean
     */
    private function hasNumbericRow($cells, $currency_symbol)
    {
        $rValid = true;
        $rNaN   = 0;
        $countCells = count($cells);
        for ($r = 0; $r < $countCells; $r ++) {
            $valid = true;
            $count = count($cells[$r]);
            for ($c = 0; $c < $count; $c ++) {
                $cells[$r][$c] = str_replace($currency_symbol, '', (string) $cells[$r][$c]);
                if (!is_numeric(preg_replace('/[\.\,\-]/', '', $cells[$r][$c]))) {
                    $valid = false;
                }
            }

            if (!$valid) {
                $rNaN ++;
            }
        }
        if ($rNaN === count($cells)) {
            $rValid = false;
        }

        return $rValid;
    }

    /**
     * Check second dimension
     *
     * @param array $array Data cells
     *
     * @return array
     */
    private function transposeArr($array)
    {
        $transposed_array = array();
        if ($array) {
            foreach ($array as $row_key => $row) {
                if (is_array($row) && !empty($row)) { //check to see if there is a second dimension
                    foreach ($row as $column_key => $element) {
                        $transposed_array[$column_key][$row_key] = $element;
                    }
                } else {
                    $transposed_array[0][$row_key] = $row;
                }
            }
            return $transposed_array;
        }
    }

    /**
     * Get chart data
     *
     * @param string $cellRange Cells range
     * @param string $tData     Data table
     *
     * @return array
     */
    private function getChartData($cellRange, $tData)
    {
        $result         = array();
        $arr_cellRanges = json_decode($cellRange);
        $tableData      = json_decode($tData);
        $countCellRanges = count($arr_cellRanges);
        for ($i = 0; $i < $countCellRanges; $i ++) {
            $row = $arr_cellRanges[$i];
            $countRow = count($row);
            for ($j = 0; $j < $countRow; $j ++) {
                $result[$i][$j] = $this->getCellData($row[$j], $tableData);
            }
        }

        return $result;
    }

    /**
     * Get Cell Data
     *
     * @param string $cellPos   Cell Pos
     * @param array  $tableData Table Data
     *
     * @return string
     */
    private function getCellData($cellPos, $tableData)
    {
        $result = '';
        list($r, $c) = explode(':', $cellPos);
        $result = $tableData[$r][$c];
        return $result;
    }
}
