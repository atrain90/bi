<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 2.3
 */

// No direct access.
defined('ABSPATH') || die();

global $wp_roles;

if (!isset($wp_roles)) {
    //phpcs:ignore WordPress.Variables.GlobalVariables.OverrideProhibited -- to assign values to $wp_roles
    $wp_roles = new WP_Roles();
}
$roles       = $wp_roles->role_objects;
$roles_names = $wp_roles->role_names;

$post_type      = get_post_type_object('wptm_file');
$post_type_caps = $post_type->cap;

?>
<div id="mybootstrap" class="wrap wptm-role" style="background: none; margin: 10px 10px 20px 15px;">
    <div id="icon-options-general" class="icon32"></div>
    <h3><?php esc_html_e('User Roles', 'wptm'); ?></h3>

    <form id="wptm-role-form" method="post" action="admin.php?page=wptm-role&amp;task=role.save">
        <?php wp_nonce_field('wptm_role_settings', 'wptm_role_nonce'); ?>
        <ul id="wptm-tabs" class="nav-tab-wrapper">
            <?php
            $tab_count = 0;
            foreach ($roles_names as $key => $name) {
                $li_class = '';
                if ($tab_count === 0) {
                    $li_class = 'active';
                }
                echo '<a class="nav-tab ' . esc_html($li_class) . '" href="#role-' . esc_html($key) . '" data-tab-id="' . esc_html($key) . '"> ' . esc_html($name) . ' </a>';
                $tab_count++;
            }
            ?>
        </ul>
        <div id="wptm-tabs-content" class="tab-content">
            <?php
            $role_count = 0;
            foreach ($roles as $role_name => $role) {
                ?>
                <div class="tab-pane <?php echo ($role_count === 0) ? 'active' : ''; ?>"
                             id="wptm-role-<?php echo esc_html($role_name); ?>">
                    <?php
                    $caps = (array)$post_type_caps;
                    $wp_default_caps = array('read','read_post','read_private_posts','create_posts','edit_posts','edit_post','edit_others_posts','delete_post','publish_posts');
                    foreach ($wp_default_caps as $default_cap) {
                        unset($caps[$default_cap]);
                    }

                    foreach ($caps as $post_key => $post_cap) {
                        ?>
                        <label for="wptm-<?php echo esc_html($role_name); ?>-<?php echo esc_html($post_key); ?>-edit">
                            <input type="checkbox" id="wptm-<?php echo esc_attr($role_name); ?>-<?php echo esc_attr($post_cap); ?>-edit"
                                   name="<?php echo esc_attr($role_name . '[' . $post_key . ']'); ?>"<?php checked(isset($role->capabilities[$post_key]), 1); ?> />
                            <?php
                            // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- Dynamic translate
                            esc_html_e($post_cap, 'wptm');
                            ?>
                        </label>
                    <?php }
                    $role_count++;
                    ?>
                </div>
            <?php } ?>

        </div>

        <p class="submit">
            <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e('Save', 'wptm'); ?>"/>
        </p>
    </form>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#wptm-tabs .nav-tab").click(function(e) {
            e.preventDefault();
            $("#wptm-tabs .nav-tab").removeClass('active');
            $( e.target).addClass('active');
            id_tab = $(this).data('tab-id');
            $("#wptm-tabs-content .tab-pane").removeClass('active');
            $("#wptm-role-"+ id_tab).addClass('active');
        })

    });
</script>