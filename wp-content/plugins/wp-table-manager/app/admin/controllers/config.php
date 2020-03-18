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

defined('ABSPATH') || die();

/**
 * Class WptmControllerConfig
 */
class WptmControllerConfig extends Controller
{
    /**
     * Save config
     *
     * @return void
     */
    public function saveconfig()
    {
        $model = $this->getModel();

        $form = new Form();
        if (!$form->load('config')) {
            $this->exitStatus(__('error while saving setting', 'wptm'));
        }
        if (!$form->validate()) {
            $this->exitStatus(__('error while saving setting', 'wptm'));
        }
        $datas = $form->sanitize();

        $fdata = $model->save($datas);
        if (!$fdata) {
            $this->exitStatus(__('error while saving setting', 'wptm'));
        }
        $this->exitStatus(true);
    }
}
