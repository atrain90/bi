<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Application;
use Joomunited\WPFramework\v1_0_5\View;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class WpfdViewStatistics
 */
class WpfdViewStatistics extends View
{

    /**
     * Render view statistics
     *
     * @param null $tpl Template name
     *
     * @return void
     */
    public function render($tpl = null)
    {
        Application::getInstance('Wpfd');
        $model       = $this->getModel();
        $this->files = $model->getItems();
        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $cat = wp_get_post_terms($file->ID, 'wpfd-category');
                if (!empty($cat)) {
                    $file->cattitle = $cat[0]->name;
                }
            }
        }
        $this->selectionValues = $model->getSelectionValues();
        $this->total           = $model->getTotal();
        //get download count by date of each file
        $this->dates = array();
        $minDate     = date('Y-m-d');
        $maxDate     = date('Y-m-d');

        $filter_order      = Utilities::getInput('filter_order', 'POST', 'none');
        $filter_order_Dir  = Utilities::getInput('filter_order_Dir', 'POST', 'none');
        $this->ordering    = $filter_order !== null ? $filter_order : 'count_hits';
        $this->orderingdir = $filter_order_Dir !== null ? $filter_order_Dir : 'desc';

        // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
        if (is_countable($this->files) && count($this->files)) {
            $reverse     = $this->orderingdir === 'asc' ? false : true;
            $this->files = wpfd_sort_by_property($this->files, $this->ordering, 'ID', $reverse);
            $fids        = array();
            foreach ($this->files as $file) {
                $fids[] = $file->ID;
            }
            $this->dates = $model->getDownloadCountByDate($fids);
            $date_arr    = array_keys($this->dates);
            if (!empty($date_arr)) {
                if (strtotime($date_arr[0]) < strtotime($minDate)) {
                    $minDate = $date_arr[0];
                }
                if (strtotime(end($date_arr)) > strtotime($maxDate)) {
                    $maxDate = end($date_arr);
                }
            }
        }
        //calculate date range to draw chart
        $date_from = Utilities::getInput('fdate', 'POST', 'string');
        $date_to   = Utilities::getInput('tdate', 'POST', 'string');

        if (empty($date_from) && empty($date_to)) {
            $date_from = date('Y-m-d', strtotime('-1 month', time()));
            $date_to   = date('Y-m-d');
        } elseif (empty($date_to)) {
            $date_to = date('Y-m-d', strtotime('+1 day', strtotime($maxDate)));
        } elseif (empty($date_from)) {
            $date_from = date('Y-m-d', strtotime('-1 day', strtotime($minDate)));
        }
        //buil data for chart
        $this->dateFiles = array();
        $begin           = new DateTime($date_from);
        $end             = new DateTime($date_to);
        $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $period   = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $temp                   = $dt->format('Y-m-d');
            $this->dateFiles[$temp] = array();
            foreach ($this->files as $file) {
                if (isset($this->dates[$temp][$file->ID])) {
                    $this->dateFiles[$temp][$file->ID] = $this->dates[$temp][$file->ID];
                } else {
                    $this->dateFiles[$temp][$file->ID] = 0;
                }
            }
        }
        // default global download count
        if (Utilities::getInput('selection', 'POST', 'string') === ''
            && Utilities::getInput('query', 'POST', 'string') === ''
            && Utilities::getInput('fdate', 'POST', 'string') === ''
            && Utilities::getInput('tdate', 'POST', 'string') === ''
        ) {
            $this->files = array();
        }
        $this->allCount = $model->getAllDownloadCount();
        parent::render($tpl);
    }
}
