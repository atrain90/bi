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
 * Class WptmControllerCategories
 * Functions delete, set order category
 * ? where delete function
 */
class WptmControllerCategories extends Controller
{

    /**
     * Function delete category
     *
     * @return void
     */
    public function delete()
    {
        $wptm_delete_category = current_user_can('wptm_delete_category');
        if (!empty($wptm_delete_category)) {
            $category = Utilities::getInt('id_category');
            $model    = $this->getModel();
            if ($model->delete($category)) {
                $this->exitStatus(true);
            }
        }
        $this->exitStatus(__('An error occurred!', 'wptm'));
    }

    /**
     * Function set order category
     *
     * @return void
     */
    public function order()
    {
        if (Utilities::getInput('position') === 'after') {
            $position = 'after';
        } else {
            $position = 'first-child';
        }
        $pk  = Utilities::getInt('pk');
        $ref = Utilities::getInt('ref');
        if ($ref === 0) {
            $ref = 1;
        }
        $model = $this->getModel();
        if ($model->move($pk, $ref, $position)) {
            $this->exitStatus(true, $pk . ' ' . $position . ' ' . $ref);
        }
        $this->exitStatus(__('An error occurred!', 'wptm'));
    }
}
