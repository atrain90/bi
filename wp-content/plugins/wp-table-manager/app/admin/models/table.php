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
 * Class WptmModelTable
 */
class WptmModelTable extends Model
{
    /**
     * Save table
     *
     * @param integer $id_table Id table
     * @param array   $datas    Data table
     *
     * @return false|integer
     */
    public function save($id_table, $datas)
    {
        global $wpdb;
        $hash = md5($datas['style'] . $datas['css']);

        if (empty($datas['datas'])) {
            $result = $wpdb->update(
                $wpdb->prefix . 'wptm_tables',
                array('style' => $datas['style'], 'css' => $datas['css'], 'hash' => $hash),
                array('ID' => (int)$id_table)
            );
        } else {
            $result = $wpdb->update(
                $wpdb->prefix . 'wptm_tables',
                array('datas' => $datas['datas'], 'style' => $datas['style'], 'css' => $datas['css'], 'hash' => $hash, 'params' => json_encode($datas['params'])),
                array('ID' => (int)$id_table)
            );
        }
        if ($result === false) {
            echo esc_sql($wpdb->last_query);
            exit();
        }
        if ((int)$result === 0) {
            $result = $id_table;
        }

        return $result;
    }

    /**
     * Add new table
     *
     * @param integer $id_category Id category
     *
     * @return integer
     */
    public function add($id_category)
    {
        global $wpdb;

        $lastPos = (int)$wpdb->get_var($wpdb->prepare('SELECT MAX(c.position) AS lastPos FROM ' . $wpdb->prefix . 'wptm_tables as c WHERE c.id_category = %d', (int)$id_category));
        $lastPos++;
        $wpdb->query(
            $wpdb->prepare(
                'INSERT INTO ' . $wpdb->prefix . 'wptm_tables (id_category, title, created_time, modified_time, author, position) VALUES ( %d,%s,%s,%s,%d,%d)',
                $id_category,
                __('New table', 'wptm'),
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                get_current_user_id(),
                $lastPos
            )
        );
        return $wpdb->insert_id;
    }

    /**
     * Copy table
     *
     * @param integer $id_table Id table
     *
     * @return boolean|integer
     */
    public function copy($id_table)
    {
        global $wpdb;

        $result = $wpdb->query($wpdb->prepare('SELECT c.* FROM ' . $wpdb->prefix . 'wptm_tables as c WHERE c.id = %d', (int)$id_table));
        if ($result === false) {
            return false;
        }
        $table = $wpdb->get_row($wpdb->prepare('SELECT c.* FROM ' . $wpdb->prefix . 'wptm_tables as c WHERE c.id = %d', (int)$id_table), OBJECT);
        $wpdb->query(
            $wpdb->prepare(
                'INSERT INTO ' . $wpdb->prefix . 'wptm_tables (id_category, title,datas,style,css,hash,params,created_time, modified_time, author, position) VALUES ( %d,%s,%s,%s,%s,%s,%s,%s,%s,%d,%d)',
                $table->id_category,
                $table->title . __(' (copy)', 'wptm'),
                $table->datas,
                $table->style,
                $table->css,
                $table->hash,
                $table->params,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                get_current_user_id(),
                $table->position
            )
        );
        return $wpdb->insert_id;
    }

    /**
     * Function dalete table
     *
     * @param integer $id Id table
     *
     * @return false|integer
     */
    public function delete($id)
    {
        global $wpdb;
        $result = $wpdb->delete(
            $wpdb->prefix . 'wptm_tables',
            array('id' => (int)$id)
        );

        return $result;
    }

    /**
     * Function set title of table
     *
     * @param integer $id    Id table
     * @param string  $title Title table
     *
     * @return false|integer
     */
    public function setTitle($id, $title)
    {
        global $wpdb;
        $result = $wpdb->update(
            $wpdb->prefix . 'wptm_tables',
            array('title' => $title),
            array('id' => (int)$id)
        );

        return $result;
    }

    /**
     * Function select table
     *
     * @param integer $id Id table
     *
     * @return boolean|mixed
     */
    public function getItem($id)
    {
        global $wpdb;
        $result = $wpdb->query($wpdb->prepare('SELECT c.* FROM ' . $wpdb->prefix . 'wptm_tables as c WHERE c.id = %d', (int)$id));
        if ($result === false) {
            return false;
        }
        $item = $wpdb->get_row($wpdb->prepare('SELECT c.* FROM ' . $wpdb->prefix . 'wptm_tables as c WHERE c.id = %d', (int)$id), OBJECT);

        $item->params = str_replace(array("\n\r", "\r\n", "\n", "\r", '&#10;'), ' ', $item->params);
        $params = json_decode($item->params);
        if (!isset($params->query)) {
            if (!is_object($params)) {
                $params = new stdClass();
            }
            $params->query = '';
        }
        $item->params = $params;
        $params->query = str_replace(array("\n\r", "\r\n", "\n", "\r", '&#10;'), ' ', $params->query);
        if ($params->query) {
            $tableData = $this->getTableData(stripslashes_deep($params->query . ' Limit 50'));
            $cols = array_keys($tableData[0]);
            $headerCols = array();
            $i = 0;
            foreach ($cols as $col) {
                $headerCols[$col] = $params->custom_titles[$i];
                $i++;
            }
            array_unshift($tableData, $headerCols);
            if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
                //phpcs:ignore PHPCompatibility.PHP.NewConstants.json_unescaped_unicodeFound -- the use of JSON_UNESCAPED_UNICODE has check PHP version
                $item->datas = json_encode($tableData, JSON_UNESCAPED_UNICODE);
            } else {
                $item->datas = json_encode($tableData);
            }

            $item->query = $params->query;
        }
        return stripslashes_deep($item);
    }

    /**
     * Set position table
     *
     * @param string $tables Data position table
     *
     * @return false|integer
     */
    public function setPosition($tables)
    {
        global $wpdb;
        $i = 1;
        $ids = explode(',', $tables);
        foreach ($ids as $id) {
            $result = $wpdb->update(
                $wpdb->prefix . 'wptm_tables',
                array('position' => $i),
                array('id' => (int)$id)
            );
            $i++;
        }
        return $result;
    }

    /**
     * Set category to table
     *
     * @param integer $id       Id table
     * @param integer $category Id category
     *
     * @return false|integer
     */
    public function setCategory($id, $category)
    {
        global $wpdb;
        $result = $wpdb->update(
            $wpdb->prefix . 'wptm_tables',
            array('id_category' => $category, 'position' => 0),
            array('id' => (int)$id)
        );
        return $result;
    }

    /**
     * Get result data of build query
     *
     * @param string $query Query
     *
     * @return array|boolean|null|object
     */
    public function getTableData($query)
    {
        global $wpdb;
        //phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- sql already escaped
        $result = $wpdb->query($query);
        if ($result === false) {
            return false;
        }
        //phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- sql already escaped
        return $wpdb->get_results($query, ARRAY_A);
    }
}
