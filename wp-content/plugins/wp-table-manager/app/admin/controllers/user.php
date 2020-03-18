<?php
/**
 * WP table manager
 *
 * @package WP table manager
 * @author  Joomunited
 * @version 2.3
 */

use Joomunited\WPFramework\v1_0_5\Controller;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class WptmControllerUser
 */
class WptmControllerUser extends Controller
{
    /**
     * Function save category params role
     *
     * @return void
     */
    public function save()
    {
        $id   = Utilities::getInt('id', 'POST');
        $type = Utilities::getInt('type', 'POST') === 1 ? 'table' : 'category';
        $data = Utilities::getInput('data', 'POST', 'none');
        $data = str_replace('\\', '', $data);
        $data = json_decode($data);

        $model = $this->getModel();
        if ($type === 'table') {
            $author = (int) $data->{0};

            if ($model->save($id, $author, 1)) {
                $this->exitStatus(true);
            } else {
                $this->exitStatus(__('error while saving role', 'wptm'));
            }
        }
        $params = $model->getItem($id);
        if (isset($params[0]->params)) {
            $params = $params[0]->params;
            $param  = json_decode($params);
            if (isset($param->role)) {
                $params = $param;
            } else {
                $params       = new stdClass();
                $params->role = new stdClass();
            }
            $id_user = get_current_user_id();
            $id_user = $id_user !== 0 ? $id_user : - 1;
            $wptm_edit_category = current_user_can('wptm_edit_category');
            if (!empty($wptm_edit_category)
                || (current_user_can('wptm_edit_own_category') && isset($params->role->{0}) && (int) $params->role->{0} === $id_user)
            ) {
                $params->role = $data;
            } else {
                $this->exitStatus(__('You have no right to change the data category', 'wptm'));
            }
            $data = json_encode($params);
        } else {
            $this->exitStatus(__('error while saving role', 'wptm'));
        }

        if ($model->save($id, $data, 0)) {
            $this->exitStatus(true);
        } else {
            $this->exitStatus(__('error while saving role', 'wptm'));
        }
    }
}
