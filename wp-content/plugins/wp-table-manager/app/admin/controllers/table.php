<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Controller;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class WptmControllerTable
 * function of table
 */
class WptmControllerTable extends Controller
{

    /**
     * Check role user for table
     *
     * @param string $id    Id of table
     * @param string $check Var check function get checkRoleTable
     *
     * @return integer
     */
    private function checkRoleTable($id, $check)
    {
        global $wpdb;
        $wptm_delete_tables = current_user_can('wptm_delete_tables');
        $wptm_create_tables = current_user_can('wptm_create_tables');
        $wptm_edit_tables = current_user_can('wptm_edit_tables');
        $wptm_edit_own_tables = current_user_can('wptm_edit_own_tables');
        if ($check === 'delete' && !empty($wptm_delete_tables)) {
            return 1;
        } elseif ($check === 'new' && !empty($wptm_create_tables)) {
            return 1;
        } elseif ($check === 'save' && !empty($wptm_edit_tables)) {
            return 1;
        } elseif ($check === 'save' && !empty($wptm_edit_own_tables)) {
            $idUser = (string) get_current_user_id();
            $model  = $this->getModel();
            $data   = $model->getTableData(
                'SELECT t.author FROM ' . $wpdb->prefix . 'wptm_tables AS t  WHERE t.id = ' . $id
            );
            if ($data === false) {
                return 0;
            }
            $data = (int) $data[0]['author'] === (int) $idUser ? 1 : 0;
            return $data;
        } else {
            return 0;
        }
    }

    /**
     * Save data table
     *
     * @return void
     */
    public function save()
    {
        $id_table   = Utilities::getInt('id', 'POST');
        $datas      = Utilities::getInput('jform', 'POST', 'none');
        $check_role = $this->checkRoleTable($id_table, 'save');
        if ($check_role === 0) {
            $this->exitStatus(__('error while saving table', 'wptm'));
        }
        $model = $this->getModel();
        if ($model->save((int) $id_table, $datas)) {
            $this->exitStatus(true);
        } else {
            $this->exitStatus(__('error while saving table', 'wptm'));
        }
    }

    /**
     * Create new table
     *
     * @return void
     */
    public function add()
    {
        $id_category = Utilities::getInt('id_category');
        $check_role  = $this->checkRoleTable((int) $id_category, 'new');
        if ($check_role === 0) {
            $this->exitStatus(__('error while adding table', 'wptm'));
        }
        $model = $this->getModel();
        $id    = $model->add($id_category);
        if ($id) {
            $this->exitStatus(true, array('id' => $id, 'title' => __('New table', 'wptm')));
        }
        $this->exitStatus(__('error while adding table', 'wptm'));
    }

    /**
     * Copy table
     *
     * @return void
     */
    public function copy()
    {
        $id         = Utilities::getInt('id');
        $check_role = $this->checkRoleTable((int) $id, 'new');
        if ($check_role === 0) {
            $this->exitStatus(__('error while coppy table', 'wptm'));
        }
        $model   = $this->getModel();
        $newItem = $model->copy($id);
        if ($newItem) {
            $table = $model->getItem($newItem);
            $this->exitStatus(true, array('id' => $table->id, 'title' => $table->title));
        }
        $this->exitStatus(__('error while adding table', 'wptm'));
    }

    /**
     * Delete table
     *
     * @return void
     */
    public function delete()
    {
        $id         = Utilities::getInt('id');
        $check_role = $this->checkRoleTable((int) $id, 'delete');
        if ($check_role === 0) {
            $this->exitStatus(__('You don\'t have permission to delete table', 'wptm'));
        }
        $model  = $this->getModel();
        $result = $model->delete($id);
        if ($result) {
            $this->exitStatus(true);
        }
        $this->exitStatus(__('An error occurred!', 'wptm'));
    }

    /**
     * Change title table
     *
     * @return void
     */
    public function setTitle()
    {
        $id         = Utilities::getInt('id');
        $check_role = $this->checkRoleTable((int) $id, 'save');
        if ($check_role === 0) {
            $this->exitStatus(__('You don\'t have permission to set title table', 'wptm'));
        }
        $new_title = Utilities::getInput('title', 'GET', 'string');
        $model     = $this->getModel();
        $result    = $model->setTitle($id, $new_title);
        if ($result !== false) {
            $this->exitStatus(true);
        }
        $this->exitStatus(__('An error occurred!', 'wptm'));
    }

    /**
     * Change order tables
     *
     * @return void
     */
    public function order()
    {
        $tables = Utilities::getInput('data', 'GET', 'string');
        $model  = $this->getModel();
        $result = $model->setPosition($tables);
        if ($result !== false) {
            $this->exitStatus(true);
        }
        $this->exitStatus(__('An error occurred!', 'wptm'));
    }

    /**
     * Set category parent of table
     *
     * @return void
     */
    public function changeCategory()
    {
        $id_table = Utilities::getInt('id');
        $category = Utilities::getInt('category');
        $model    = $this->getModel();
        $result   = $model->setCategory($id_table, $category);
        if ($result !== false) {
            $this->exitStatus(true);
        } else {
            $this->exitStatus(__('An error occurred!', 'wptm'));
        }
    }
}
