<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Model;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class WpfdModelStatistics
 */
class WpfdModelStatistics extends Model
{

    /**
     * Total count
     *
     * @var integer
     */
    private $total = 0;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        add_filter('posts_clauses', array($this, 'postClauses'), 0);
    }

    /**
     * Get selection values
     *
     * @return array
     */
    public function getSelectionValues()
    {
        $selection = Utilities::getInput('selection', 'POST', 'string');
        $options   = array();
        if (!empty($selection)) {
            if ($selection === 'category') {
                $cats = get_terms('wpfd-category', array(
                    'hide_empty' => false,
                ));
                if ($cats) {
                    foreach ($cats as $cat) {
                        $options[$cat->term_id] = $cat->name;
                    }
                }
            } elseif ($selection === 'files') {
                $args = array(
                    'post_type'        => 'wpfd_file',
                    'post_status'      => 'publish',
                    'suppress_filters' => false,
                    'posts_per_page'   => -1
                );

                $files = get_posts($args);
                if ($files) {
                    foreach ($files as $file) {
                        $options[$file->ID] = $file->post_title;
                    }
                }
            }
        }

        return $options;
    }

    /**
     * Get items
     *
     * @return mixed
     */
    public function getItems()
    {
        $args          = array(
            'post_type'        => 'wpfd_file',
            'post_status'      => 'publish',
            'suppress_filters' => false,
            'posts_per_page'   => -1
        );
        $paged         = Utilities::getInput('paged', 'POST', 'string');
        $paged         = $paged !== '' ? $paged : 1;
        $args['paged'] = $paged;

        // Filter by search in title.
        $search = Utilities::getInput('query', 'POST', 'string');
        if (!empty($search)) {
            $args['s'] = $search;
        }
        $selection = Utilities::getInput('selection', 'POST', 'string');
        if (!empty($selection)) {
            $selection_value = Utilities::getInput('selection_value', 'POST', 'none');

            if (!empty($selection_value)) {
                if ($selection === 'files') {
                    $args['post__in'] = $selection_value;
                } elseif ($selection === 'category') {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy'         => 'wpfd-category',
                            'terms'            => $selection_value,
                            'field'            => 'term_id',
                            'include_children' => true
                        )
                    );
                }
            }
        }

        $this->total = count(get_posts($args));

        $limit                  = Utilities::getInput('limit', 'POST', 'string');
        $limit                  = $limit !== '' ? $limit : 5;
        $args['posts_per_page'] = $limit;

        $this->total = ceil($this->total / $limit);

        // Add the list ordering clause.
        $orderCol        = Utilities::getInput('filter_order', 'POST', 'string');
        $orderDirn       = Utilities::getInput('filter_order_Dir', 'POST', 'string');
        $orderCol        = $orderCol !== '' ? $orderCol : 'ID';
        $orderDirn       = $orderDirn !== '' ? $orderDirn : 'DESC';
        $args['orderby'] = $orderCol;
        $args['order']   = $orderDirn;
        $items           = get_posts($args);

        return $items;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get download count by date
     *
     * @param array $fids Fids
     *
     * @return array
     */
    public function getDownloadCountByDate($fids)
    {
        global $wpdb;
        $clean_fids = array();
        foreach ($fids as $fid) {
            $clean_fids[] = (int) $fid;
        }
        $sql   = 'SELECT f.ID, ch.date, ch.count FROM ' . $wpdb->posts . ' AS f INNER JOIN ';
        $sql   .= $wpdb->prefix . 'wpfd_statistics AS ch ON ch.related_id = f.ID WHERE f.ID IN (';
        $sql   .= implode(',', $clean_fids) . ") AND f.post_type='wpfd_file'";
        // phpcs:ignore WordPress.Security.EscapeOutput.NotPrepared -- input cleaned
        $items = $wpdb->get_results($sql);
        $rows  = array();
        if (count($items)) {
            foreach ($items as $item) {
                $rows[$item->date][$item->ID] = $item->count;
            }
        }

        return $rows;
    }

    /**
     * Append clauses query
     *
     * @param array $args Args
     *
     * @return array
     */
    public function postClauses($args)
    {
        global $wpdb;
        $args['fields'] .= ', SUM(ch.count) AS count_hits ';
        $args['join']   .= 'INNER JOIN ' . $wpdb->prefix  . 'wpfd_statistics AS ch ON (ch.related_id = ' . $wpdb->posts . '.ID)';
        $date_from      = Utilities::getInput('fdate', 'POST', 'string');
        $date_to        = Utilities::getInput('tdate', 'POST', 'string');
        if ($date_from) {
            $args['where'] .= " AND ( ch.date >= '" . esc_sql($date_from) . "') ";
        }
        if ($date_to) {
            $args['where'] .= " AND ( ch.date <= '" . esc_sql($date_to) . "') ";
        }
        if (empty($date_from) && empty($date_to)) {
            $dfrom         = date('Y-m-d', strtotime('-1 month', time()));
            $dto           = date('Y-m-d');
            $args['where'] .= " AND ( ch.date >= '" . esc_sql($dfrom) . "') ";
            $args['where'] .= " AND ( ch.date <= '" . esc_sql($dto) . "') ";
        }
        $args['groupby'] = $wpdb->posts . '.ID';

        return $args;
    }

    /**
     * Get all download count
     *
     * @return mixed
     */
    public function getAllDownloadCount()
    {
        global $wpdb;
        $items = $wpdb->get_results('SELECT s.date, SUM(s.count) as count FROM ' . $wpdb->prefix . 'wpfd_statistics AS s GROUP BY s.date');

        return $items;
    }
}
