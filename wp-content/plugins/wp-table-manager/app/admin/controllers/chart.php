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
 * Class WptmControllerChart
 */
class WptmControllerChart extends Controller
{
    /**
     * Function save chart
     *
     * @return void
     */
    public function save()
    {
        $id_chart = Utilities::getInt('id', 'POST');
        $datas    = Utilities::getInput('jform', 'POST', 'none');
        $model    = $this->getModel();
        if ($model->save($id_chart, $datas)) {
            $this->exitStatus(true);
        } else {
            $this->exitStatus(__('error while saving table', 'wptm'));
        }
    }

    /**
     * Function create new chart
     *
     * @return void
     */
    public function add()
    {
        $id_table = Utilities::getInt('id_table');
        $datas    = Utilities::getInput('datas', 'POST', 'string');
        $model    = $this->getModel();
        $id       = $model->add($id_table, $datas);
        if ($id) {
            $chart = $model->getItem($id);
            $this->exitStatus(true, array(
                'id'    => $id,
                'datas' => $chart->datas,
                'type'  => 'Line',
                'title' => __('New chart', 'wptm')
            ));
        }
        $this->exitStatus(__('error while adding chart', 'wptm'));
    }

    /**
     * Function delete chart
     *
     * @return void
     */
    public function delete()
    {
        $id     = Utilities::getInt('id');
        $model  = $this->getModel();
        $result = $model->delete($id);
        if ($result) {
            $this->exitStatus(true);
        }
        $this->exitStatus(__('An error occurred!', 'wptm'));
    }

    /**
     * Function set title chart
     *
     * @return void
     */
    public function setTitle()
    {
        $id        = Utilities::getInt('id');
        $new_title = Utilities::getInput('title', 'GET', 'string');
        $model     = $this->getModel();
        $id        = $model->setTitle($id, $new_title);
        if ($id) {
            $this->exitStatus(true);
        }
        $this->exitStatus(__('An error occurred!', 'wptm'));
    }
}
