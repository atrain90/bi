<?php
/* Based on some work of wp Data Tables plugin */
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */
use Joomunited\WPFramework\v1_0_5\Model;
use Joomunited\WPFramework\v1_0_5\Factory;

defined('ABSPATH') || die();

/**
 * Class WptmModelDbtable
 */
class WptmModelDbtable extends Model
{
    /*     * * For the WP DB type query ** */
    /**
     * Select arr
     *
     * @var array
     */
    private $select_arr = array();

    /**
     * Where arr
     *
     * @var array
     */
    private $where_arr = array();

    /**
     * Group arr
     *
     * @var array
     */
    private $group_arr = array();

    /**
     * From arr
     *
     * @var array
     */
    private $from_arr = array();

    /**
     * Inner join arr
     *
     * @var array
     */
    private $inner_join_arr = array();

    /**
     * Left join
     *
     * @var array
     */
    private $left_join_arr = array();

    /**
     * Check have group
     *
     * @var boolean
     */
    private $has_groups = false;

    /**
     * Query data
     *
     * @var string
     */
    private $query = '';

    /**
     * Get list sql table
     *
     * @return array
     */
    public function listMySQLTables()
    {

        $tables = array();
        global $wpdb;
        $result = $wpdb->get_results('SHOW TABLES', ARRAY_N);

        // Formatting the result to plain array
        foreach ($result as $row) {
            $tables[] = $row[0];
        }

        return $tables;
    }

    /**
     * Return a list of columns for the selected tables
     *
     * @param array $tables Data table
     *
     * @return array
     */
    public static function listMySQLColumns($tables)
    {
        $columns = array('all_columns' => array(), 'sorted_columns' => array());
        if (!empty($tables)) {
            global $wpdb;
            foreach ($tables as $table) {
                $columns['sorted_columns'][$table] = array();

                //phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- $table is name table in database, it already escaped
                $table_columns = $wpdb->get_results('SHOW COLUMNS FROM ' . $table, ARRAY_A);

                foreach ($table_columns as $table_column) {
                    $columns['sorted_columns'][$table][] = $table . '.' . $table_column['Field'];
                    $columns['all_columns'][]            = $table . '.' . $table_column['Field'];
                }
            }
        }

        return $columns;
    }

    /**
     * Return a build query and preview table
     *
     * @param array $table_data Data table
     *
     * @return array
     */
    public function generateQueryAndPreviewdata($table_data)
    {
        global $wpdb;
        $this->table_data = apply_filters('wdt_before_generate_mysql_based_query', $table_data);

        if (!isset($this->table_data['where_conditions'])) {
            $this->table_data['where_conditions'] = array();
        }

        if (isset($this->table_data['grouping_rules'])) {
            $this->has_groups = true;
        }

        if (!isset($table_data['mysql_columns'])) {
            $table_data['mysql_columns'] = array();
        }

        // Initializing structure for the SELECT part of query
        $this->prepareMySQLSelectBlock($table_data['mysql_columns']);

        // Initializing structure for the WHERE part of query
        $this->prepareMySQLWhereBlock();

        // Prepare the GROUP BY block
        $this->prepareMySQLGroupByBlock();

        // Prepare the join rules
        $this->prepareMySQLJoinedQueryStructure();

        // Prepare the query itself
        $this->query = $this->buildMySQLQuery();

        if (isset($this->table_data['default_ordering']) && $this->table_data['default_ordering']) {
            $this->query .= ' Order by ' . $this->table_data['default_ordering'] . ' ' . $this->table_data['default_ordering_dir'];
        }

        $result = array(
            'query'   => $this->query,
            'preview' => $this->getQueryPreview()
        );

        return $result;
    }

    /**
     * Generate the sample table with 5 rows from MySQL query
     *
     * @return string|void
     */
    public function getQueryPreview()
    {
        global $wpdb;

        //phpcs:ignore WordPress.WP.PreparedSQL.NotPrepared -- query already escaped
        $result = $wpdb->get_results($this->query . ' LIMIT 10', ARRAY_A);

        if (!empty($result)) {
            $headers = $this->table_data['custom_titles'];
            ob_start();
            include(WPTM_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'dbtable' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'table_preview.inc.php');
            $ret_val = ob_get_contents();
            ob_end_clean();
        } else {
            $ret_val = __('No results found', 'wptm');
        }
        return $ret_val;
    }

    /**
     * Helper function to generate the fields structure from MySQL tables
     *
     * @return void
     */
    private function prepareMySQLSelectBlock()
    {
        foreach ($this->table_data['mysql_columns'] as $mysql_column) {
            $mysql_column_arr = explode('.', $mysql_column);
            if (!isset($this->select_arr[$mysql_column_arr[0]])) {
                $this->select_arr[$mysql_column_arr[0]] = array();
            }
            $this->select_arr[$mysql_column_arr[0]][] = $mysql_column;

            if (!in_array($mysql_column_arr[0], $this->from_arr)) {
                $this->from_arr[] = $mysql_column_arr[0];
            }
        }
    }

    /**
     * Prepare a Where block for MySQL based
     *
     * @return void
     */
    private function prepareMySQLWhereBlock()
    {
        if (empty($this->table_data['where_conditions'])) {
            return;
        }

        foreach ($this->table_data['where_conditions'] as $where_condition) {
            $where_column_arr = explode('.', $where_condition['column']);

            if (!in_array($where_column_arr[0], $this->from_arr)) {
                $this->from_arr[] = $where_column_arr[0];
            }

            $this->where_arr[$where_column_arr[0]][] = self::buildWhereCondition(
                $where_condition['column'],
                $where_condition['operator'],
                $where_condition['value']
            );
        }
    }

    /**
     * Prepare a GROUP BY block for MySQL based
     *
     * @return void
     */
    private function prepareMySQLGroupByBlock()
    {
        if (!$this->has_groups) {
            return;
        }

        foreach ($this->table_data['grouping_rules'] as $grouping_rule) {
            if (empty($grouping_rule)) {
                continue;
            }
            $this->group_arr[] = $grouping_rule;
        }
    }

    /**
     * Prepares the structure of the JOIN rules for MySQL based tables
     *
     * @return void
     */
    private function prepareMySQLJoinedQueryStructure()
    {
        if (!isset($this->table_data['join_rules'])) {
            return;
        }

        foreach ($this->table_data['join_rules'] as $join_rule) {
            if (empty($join_rule['initiator_column']) || empty($join_rule['connected_column'])) {
                continue;
            }

            $connected_column_arr = explode('.', $join_rule['connected_column']);

            if (in_array($connected_column_arr[0], $this->from_arr) && count($this->from_arr) > 1) {
                if ((string)$join_rule['type'] === 'left') {
                    $this->left_join_arr[$connected_column_arr[0]] = $connected_column_arr[0];
                } else {
                    $this->inner_join_arr[$connected_column_arr[0]] = $connected_column_arr[0];
                }
                unset($this->from_arr[array_search($connected_column_arr[0], $this->from_arr)]);
            } else {
                if ((string)$join_rule['type'] === 'left') {
                    $this->left_join_arr[$connected_column_arr[0]] = $connected_column_arr[0];
                } else {
                    $this->inner_join_arr[$connected_column_arr[0]] = $connected_column_arr[0];
                }
            }

            $this->where_arr[$connected_column_arr[0]][] = self::buildWhereCondition(
                $join_rule['initiator_table'] . '.' . $join_rule['initiator_column'],
                'eq',
                $join_rule['connected_column'],
                false
            );
        }
    }

    /**
     * Prepares the query text for MySQL based table
     *
     * @return string
     */
    private function buildMySQLQuery()
    {
        // Build the final output
        $query = 'SELECT ';
        $i     = 0;
        foreach ($this->select_arr as $table_alias => $select_block) {
            $query .= implode(",\n       ", $select_block);
            $i++;
            if ($i < count($this->select_arr)) {
                $query .= ",\n       ";
            }
        }
        $query .= "\n FROM ";
        $query .= implode(', ', $this->from_arr) . "\n";

        if (!empty($this->inner_join_arr)) {
            $i = 0;
            foreach ($this->inner_join_arr as $table_alias => $inner_join_block) {
                $query .= '  INNER JOIN' . $inner_join_block . '\n';
                if (!empty($this->where_arr[$table_alias])) {
                    $query .= '     ON ' . implode("\n     AND ", $this->where_arr[$table_alias]) . "\n";
                    unset($this->where_arr[$table_alias]);
                }
            }
        }

        if (!empty($this->left_join_arr)) {
            foreach ($this->left_join_arr as $table_alias => $left_join_block) {
                $query .= '  LEFT JOIN ' . $left_join_block . "\n";
                if (!empty($this->where_arr[$table_alias])) {
                    $query .= '     ON ' . implode("\n     AND ", $this->where_arr[$table_alias]) . "\n";
                    unset($this->where_arr[$table_alias]);
                }
            }
        }
        if (!empty($this->where_arr)) {
            $query .= "WHERE 1=1 \n   AND ";
            $i     = 0;
            foreach ($this->where_arr as $table_alias => $where_block) {
                $query .= implode("\n   AND ", $where_block);
                $i++;
                if ($i < count($this->where_arr)) {
                    $query .= "\n   AND ";
                }
            }
        }
        if (!empty($this->group_arr)) {
            $query .= "\nGROUP BY " . implode(', ', $this->group_arr);
        }
        return $query;
    }


    /**
     * Prepares the structure of the WHERE rules for MySQL based tables
     *
     * @param string  $leftOperand  Left Operand
     * @param string  $operator     Operator
     * @param string  $rightOperand Right Operand
     * @param boolean $isValue      Value
     *
     * @return string
     */
    public static function buildWhereCondition($leftOperand, $operator, $rightOperand, $isValue = true)
    {
        $rightOperand = stripslashes_deep($rightOperand);
        $wrap = $isValue ? "'" : '';
        switch ($operator) {
            case 'eq':
                return $leftOperand .' = ' . $wrap . $rightOperand . $wrap;
            case 'neq':
                return $leftOperand .' != ' . $wrap . $rightOperand . $wrap;
            case 'gt':
                return $leftOperand .' > ' . $wrap . $rightOperand . $wrap;
            case 'gtoreq':
                return $leftOperand .' >= ' . $wrap . $rightOperand . $wrap;
            case 'lt':
                return $leftOperand .' < ' . $wrap . $rightOperand . $wrap;
            case 'ltoreq':
                return $leftOperand .' <= ' . $wrap . $rightOperand . $wrap;
            case 'in':
                return $leftOperand .' IN (' . $rightOperand . ')';
            case 'like':
                return $leftOperand .' LIKE ' . $wrap . $rightOperand . $wrap;
            case 'plikep':
                return $leftOperand .' LIKE ' . $wrap . '%' . $rightOperand . '%' . $wrap;
        }
    }

    /**
     * Create new table for selected mysql tables
     *
     * @param array $table_data Data table
     *
     * @return integer
     */
    public function createTable($table_data)
    {
        global $wpdb;
        $id_category = $this->getCategoryId();
        $lastPos     = (int) $wpdb->get_var($wpdb->prepare('SELECT MAX(c.position) AS lastPos FROM ' . $wpdb->prefix . 'wptm_tables as c WHERE c.id_category = %d', (int) $id_category));
        $lastPos++;
        $style                           = json_decode('{   "table":{      "alternate_row_even_color":"#fafafa",      "use_sortable":"1"   },   "rows":{      "0":[         0,         {            "height":30,            "cell_padding_top":"3",            "cell_padding_right":"3",            "cell_padding_bottom":"3",            "cell_padding_left":"3",            "cell_font_family":"Arial",            "cell_font_size":"13",            "cell_font_color":"#333333",            "cell_border_bottom":"2px solid #707070",            "cell_background_color":"#ffffff",            "cell_font_bold":true,            "cell_vertical_align":"middle"         }      ],      "1":[         1,         {            "height":30,            "cell_padding_top":"3",            "cell_padding_right":"3",            "cell_padding_bottom":"3",            "cell_padding_left":"3",            "cell_font_color":"#333333",            "cell_border_bottom":"1px solid #d6d6d6",            "cell_vertical_align":"middle"         }      ],      "2":[         2,         {            "height":30,            "cell_padding_top":"3",            "cell_padding_right":"3",            "cell_padding_bottom":"3",            "cell_padding_left":"3",            "cell_font_color":"#333333",            "cell_border_bottom":"1px solid #d6d6d6",            "cell_vertical_align":"middle"         }      ],      "3":[         3,         {            "height":30,            "cell_padding_top":"3",            "cell_padding_right":"3",            "cell_padding_bottom":"3",            "cell_padding_left":"3",            "cell_font_color":"#333333",            "cell_border_bottom":"1px solid #d6d6d6",            "cell_vertical_align":"middle"         }      ],      "4":[         4,         {            "height":30,            "cell_padding_top":"3",            "cell_padding_right":"3",            "cell_padding_bottom":"3",            "cell_padding_left":"3",            "cell_font_color":"#333333",            "cell_border_bottom":"1px solid #d6d6d6",            "cell_vertical_align":"middle"         }      ],      "5":[         5,         {            "height":30,            "cell_padding_top":"3",            "cell_padding_right":"3",            "cell_padding_bottom":"3",            "cell_padding_left":"3",            "cell_font_color":"#333333",            "cell_border_bottom":"1px solid #d6d6d6",            "cell_vertical_align":"middle"         }      ],      "6":[         6,         {            "height":30,            "cell_padding_top":"3",            "cell_padding_right":"3",            "cell_padding_bottom":"3",            "cell_padding_left":"3",            "cell_font_color":"#333333",            "cell_border_bottom":"1px solid #d6d6d6",            "cell_vertical_align":"middle"         }      ],      "7":[         7,         {            "height":30,            "cell_padding_top":"3",            "cell_padding_right":"3",            "cell_padding_bottom":"3",            "cell_padding_left":"3",            "cell_font_color":"#333333",            "cell_border_bottom":"1px solid #d6d6d6",            "cell_vertical_align":"middle"         }      ],      "8":[         8,         {            "height":30,            "cell_padding_top":"3",            "cell_padding_right":"3",            "cell_padding_bottom":"3",            "cell_padding_left":"3",            "cell_font_color":"#333333",            "cell_border_bottom":"1px solid #d6d6d6",            "cell_vertical_align":"middle"         }      ]   },   "cols":{      "0":[         0,         {            "width":50,            "cell_text_align":"center",            "cell_font_bold":true         }      ],      "1":[         1,         {            "width":122,"cell_text_align":"center"         }      ],      "2":[         2,         {            "width":137,"cell_text_align":"center"         }      ],      "3":[         3,         {            "width":133,"cell_text_align":"center"         }      ],      "4":[         4,         {            "width":150,"cell_text_align":"center"         }      ],      "5":[         5,         {            "width":50,"cell_text_align":"center"         }      ]   },   "cells":{         }}');
        $style->table->enable_pagination = $table_data['enable_pagination'];
        $style->table->limit_rows        = $table_data['limit_rows'];

        $params               = $table_data;
        $params['table_type'] = 'mysql';

        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            //phpcs:ignore PHPCompatibility.PHP.NewConstants.json_unescaped_unicodeFound -- the use of JSON_UNESCAPED_UNICODE has check PHP version
            $data_params = json_encode($params, JSON_UNESCAPED_UNICODE);
        } else {
            $data_params = $params;
        }

        $wpdb->query($wpdb->prepare(
            'INSERT INTO ' . $wpdb->prefix . 'wptm_tables (id_category, title, datas, style, params, created_time, modified_time, author, position) VALUES ( %d,%s,%s,%s,%s,%s,%s,%d,%d)',
            $id_category,
            __('New table', 'wptm'),
            $table_data['query'],
            json_encode($style),
            $data_params,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            get_current_user_id(),
            $lastPos
        ));

        return $wpdb->insert_id;
    }

    /**
     * Update table with new change
     *
     * @param integer $id_table   Id table
     * @param array   $table_data Data table
     *
     * @return false|integer
     */
    public function updateTable($id_table, $table_data)
    {
        global $wpdb;

        $params               = $table_data;
        $params['table_type'] = 'mysql';
        $ret                  = $wpdb->update(
            $wpdb->prefix . 'wptm_tables',
            array('datas'         => $table_data['query'],
                  'params'        => json_encode($params),
                  'modified_time' => date('Y-m-d H:i:s')
            ),
            array('id' => $id_table)
        );
        return $ret;
    }

    /**
     * GetID of special category for database tables.
     *
     * @return integer
     */
    public function getCategoryId()
    {

        $config_dbtable_cat = (int) get_option('_wptm_dbtable_cat');
        $modelCategory      = Model::getInstance('category');
        if (!$config_dbtable_cat || !$modelCategory->isCategoryExist($config_dbtable_cat)) {
            $cat_id = $modelCategory->addCategory('Table from Database');
            update_option('_wptm_dbtable_cat', $cat_id);
            return $cat_id;
        }

        return (int) $config_dbtable_cat;
    }
}
