<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Model;

defined('ABSPATH') || die();

/**
 * Class WptmModelTables
 */
class WptmModelTables extends Model
{
    /**
     * Get items
     *
     * @return boolean|mixed
     */
    public function getItems()
    {
        global $wpdb;
        $query  = 'SELECT c.* FROM ' . $wpdb->prefix . 'wptm_tables as c ORDER BY c.id_category ASC, c.position ASC ';
        //phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- No variable from user in the query
        $result = $wpdb->query($query);
        if ($result === false) {
            return false;
        }
        //phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- No variable from user in the query
        return stripslashes_deep($wpdb->get_results($query, OBJECT));
    }
}
