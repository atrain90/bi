<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 2.3
 */
use Joomunited\WPFramework\v1_0_5\Controller;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class WptmControllerRole
 */
class WptmControllerRole extends Controller
{
    /**
     * Save role user
     *
     * @return void
     */
    public function save()
    {
        if (!isset($_POST['wptm_role_nonce']) || !check_admin_referer('wptm_role_settings', 'wptm_role_nonce') || !current_user_can('manage_options')) {
            return;
        }
        $role_caps = get_option('_wptm_role_caps', array());

        if (!isset($wp_roles)) {
            $wp_roles = new WP_Roles();
        }

        $roles       = $wp_roles->role_objects;
        $roles_names = $wp_roles->role_names;

        $post_type = get_post_type_object('wptm_file');

        $post_type_caps  = (array) $post_type->cap;
        $wp_default_caps = array(
            'read',
            'read_post',
            'read_private_posts',
            'create_posts',
            'edit_posts',
            'edit_post',
            'edit_others_posts',
            'delete_post',
            'publish_posts'
        );
        foreach ($wp_default_caps as $default_cap) {
            unset($post_type_caps[$default_cap]);
        }

        foreach ($roles as $user_role => $role) {
            $user_role_caps = Utilities::getInput($user_role, 'POST', 'none');
            foreach ($post_type_caps as $post_key => $post_cap) {
                if (isset($user_role_caps[$post_key]) && $user_role_caps[$post_key] === 'on') {
                    $role->add_cap($post_key);
                } else {
                    $role->remove_cap($post_key);
                }
            }
        }
        $this->redirect('admin.php?page=wptm-role&updated=1');
        wp_die();
    }
}
