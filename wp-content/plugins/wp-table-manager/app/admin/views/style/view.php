<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\View;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class wptmViewStyle
 */
class WptmViewStyle extends View
{
    /**
     * Render style
     *
     * @param null $tpl Tpl
     *
     * @return void
     */
    public function render($tpl = null)
    {

        $id    = Utilities::getInt('id');
        $model = $this->getModel('style');
        $item  = $model->getItem($id);
        header('Content-Type: application/json; charset=utf-8', true);
        echo json_encode($item);
        die();
    }
}
