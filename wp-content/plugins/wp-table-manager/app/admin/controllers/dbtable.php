<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Controller;
use Joomunited\WPFramework\v1_0_5\Form;
use Joomunited\WPFramework\v1_0_5\Utilities;
use Joomunited\WPFramework\v1_0_5\Factory;

defined('ABSPATH') || die();

/**
 * Class WptmControllerDbtable
 */
class WptmControllerDbtable extends Controller
{
    /**
     * Function change Tables
     *
     * @return void
     */
    public function changeTables()
    {
        $tables  = Utilities::getInput('tables', 'POST', 'none');
        $model   = $this->getModel();
        $columns = $model->listMySQLColumns($tables);

        $this->exitStatus(true, array('columns' => $columns));
    }

    /**
     * Function generateQueryAndPreviewdata
     *
     * @return void
     */
    public function generateQueryAndPreviewdata()
    {
        $table_data = Utilities::getInput('table_data', 'POST', 'none');
        $model      = $this->getModel();
        $result     = $model->generateQueryAndPreviewdata($table_data);

        $this->exitStatus(true, $result);
    }

    /**
     * Function create new table for selected mysql tables
     *
     * @return void
     */
    public function createTable()
    {
        $table_data = Utilities::getInput('table_data', 'POST', 'none');
        $model      = $this->getModel();
        $result     = $model->createTable($table_data);

        $this->exitStatus(true, $result);
    }

    /**
     * Function update table with new change
     *
     * @return void
     */
    public function updateTable()
    {
        $table_data          = Utilities::getInput('table_data', 'POST', 'none');
        $id_table            = (int) $table_data['id_table'];
        $model               = $this->getModel();
        $buildQueryandData   = $model->generateQueryAndPreviewdata($table_data);
        $table_data['query'] = $buildQueryandData['query'];

        $result = $model->updateTable($id_table, $table_data);

        $this->exitStatus(true, $result);
    }
}
