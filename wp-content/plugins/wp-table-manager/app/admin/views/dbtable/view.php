<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\View;
use Joomunited\WPFramework\v1_0_5\Factory;
use Joomunited\WPFramework\v1_0_5\Form;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class wptmViewDbtable
 */
class WptmViewDbtable extends View
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
        $model                      = $this->getModel('dbtable');
        $id_table                   = Utilities::getInt('id_table');
        $this->selected_tables      = array();
        $this->availableColumns     = array();
        $this->selected_columns     = array();
        $this->join_rules           = array();
        $this->params               = new stdClass();
        $this->id_table             = $id_table;
        $this->default_ordering_dir = 'asc';
        if ($id_table) {
            $modelTable            = $this->getModel('table');
            $item                  = $modelTable->getItem($id_table);
            $params                = $item->params;
            $this->params          = $params;
            $this->selected_tables = $params->tables;
            $columns               = $model->listMySQLColumns($this->selected_tables);

            $this->availableColumns        = $columns['all_columns'];
            $this->selected_columns        = $params->mysql_columns;
            $this->sorted_columns          = $columns['sorted_columns'];
            $this->join_rules              = $params->join_rules;
            $this->default_ordering_column = $params->default_ordering;
            $this->default_ordering_dir    = $params->default_ordering_dir;
            $this->custom_titles           = $params->custom_titles;
        }
        $this->mysql_tables = $model->listMySQLTables();
        parent::render($tpl);
    }
}
