<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\View;

defined('ABSPATH') || die();

/**
 * Class wptmViewWptm
 */
class WptmViewWptm extends View
{
    /**
     * Render
     *
     * @param null $tpl Tpl
     *
     * @return void
     */
    public function render($tpl = null)
    {
        $modelCat         = $this->getModel('categories');
        $this->categories = $modelCat->getCategories();

        $modelTables  = $this->getModel('tables');
        $this->tables = $modelTables->getItems();
        require_once plugin_dir_path(WPTM_PLUGIN_FILE) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'tables.php';
        $this->tables = WptmTablesHelper::categoryObject($this->tables);

        $modelStyles  = $this->getModel('styles');
        $this->styles = $modelStyles->getStyles();

        $modelCharts      = $this->getModel('charts');
        $this->chartTypes = $modelCharts->getChartTypes();

        $modelConfig  = $this->getModel('config');
        $this->params = $modelConfig->getConfig();

        $this->dbtable_cat = (int) get_option('_wptm_dbtable_cat', 0);

        $this->idUser = get_current_user_id();
        parent::render($tpl);
    }
}
