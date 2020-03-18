<?php

class Weblator_Tables extends WP_List_Table {

    public $items;
    public $timeAgo;

    function __construct(){

         require_once("class-timeago.php");
         $this->timeAgo = new TimeAgo();

        parent::__construct( array(
            'singular'  => __( 'poll', 'polltable' ),     //singular name of the listed records
            'plural'    => __( 'polls', 'polltable' ),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?

        ) );

    }

    function get_table_classes(){

        return array("widefat", "fixed", "polls", "weblator_table", "striped");
    }

    function no_items() {
        _e( 'No polls found.' );
    }

    function column_default( $item, $column_name ) {

        switch( $column_name ) {
            case 'id':
            case 'option_count':
            case 'total_voted':
                return $item[ $column_name ];
            break;
            case 'poll_created_date':
                return $this->timeAgo->inWords($item[$column_name], "now") . " ago";
            break;
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'id' => array('id', false),
            'poll_name'  => array('poll_name',false),
            'poll_created_date' => array('poll_created_date', false),
        );
        return $sortable_columns;
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'id' => __( 'ID', 'polltable'),
            'poll_name'    => __( 'Poll Name', 'polltable' ),
            'option_count' => __( 'Poll Options', 'polltable'),
            'total_voted' => __( 'Total Votes', 'polltable'),
            'poll_created_date' => __( 'Created', 'polltable' )
        );
        return $columns;
    }

    function usort_reorder( $a, $b ) {

        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'poll_name';
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        $result = strcmp( $a[$orderby], $b[$orderby] );

        return ( $order === 'asc' ) ? $result : -$result;
    }

    function column_poll_name($item){
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&edit_poll=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
            'delete'    => "<a href=\"".wp_nonce_url("?page={$_REQUEST['page']}&action=delete&delete_poll={$item['id']}", "weblator_poll_delete_{$item['id']}")."\" class=\"delete_poll\" data-poll-id=\"{$item['id']}\">Delete</a>",
        );

        return sprintf('%1$s %2$s', stripslashes($item['poll_name']), $this->row_actions($actions) );
    }

    function get_bulk_actions() {
        $actions = array(
            'delete' => __( 'Delete' , 'polltable')
        );
        return $actions;
    }

    function process_bulk_action() {

        if(isset($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], "bulk-".$this->_args['plural']))
        {
            $entry_id = ( is_array( $_REQUEST['chart_check'] ) ) ? $_REQUEST['chart_check'] : array( $_REQUEST['chart_check'] );

            if ( 'delete' === $this->current_action() )
            {
                global $wpdb;

                foreach ( $entry_id as $id ) {
                    $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}weblator_polls SET `poll_deleted_date` = NOW() WHERE `id` = %d", intval($id)) );
                }

                wp_redirect( admin_url() . 'admin.php?page=polls', 301 );
                exit();
            }
        }
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="chart_check[]" value="%s" />', $item['id']
        );
    }

    function prepare_items() {

        global $wpdb;

        $query = "SELECT
         {$wpdb->prefix}weblator_polls.id,
         {$wpdb->prefix}weblator_polls.poll_name,
         {$wpdb->prefix}weblator_polls.poll_allow_view_results,
         {$wpdb->prefix}weblator_polls.poll_one_vote,
         {$wpdb->prefix}weblator_polls.poll_is_live,
         {$wpdb->prefix}weblator_polls.poll_created_date, (SELECT count(*) FROM {$wpdb->prefix}weblator_poll_options WHERE poll_id = {$wpdb->prefix}weblator_polls.id AND option_deleted_date IS NULL) AS option_count,
         (SELECT group_concat({$wpdb->prefix}weblator_poll_options.id SEPARATOR ',' ) AS options_id FROM {$wpdb->prefix}weblator_poll_options WHERE poll_id = {$wpdb->prefix}weblator_polls.id AND option_deleted_date IS NULL) AS live_options
         FROM {$wpdb->prefix}weblator_polls WHERE poll_deleted_date IS NULL";

        if (isset($_POST["s"]))
            $query .= " AND poll_name LIKE '%" . $wpdb->_real_escape($_POST["s"]). "%'";


        $orderby = !empty($_GET["orderby"]) ? $wpdb->_real_escape($_GET["orderby"]) : 'ASC';
        $order = !empty($_GET["order"]) ? $wpdb->_real_escape($_GET["order"]) : '';

        if(!empty($orderby) & !empty($order))
            $query.=' ORDER BY '.$orderby.' '.$order;
        else
            $query.=' ORDER BY ' . $wpdb->prefix . 'weblator_polls.`poll_created_date` DESC';

        $totalitems = $wpdb->query($query); //return the total number of affected rows

        $perpage = (isset($_POST["s"])?5000:20);
        $paged = !empty($_GET["paged"]) ? $wpdb->_real_escape($_GET["paged"]) : '';
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
        $totalpages = ceil($totalitems/$perpage);
        if(!empty($paged) && !empty($perpage)){
            $offset=($paged-1)*$perpage;
            $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
        }

        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ) );

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $wpdb->get_results($query, ARRAY_A);

        foreach($this->items as $key => $item){

            $this->items[$key]["total_voted"] = $this->get_total_votes($item["live_options"]);
        }


        if (isset($_POST["chart_check"]))
            $this->process_bulk_action();
    }

    private function get_total_votes($options){

        global $wpdb;

        $ops = explode(",", $options);

        $total = null;

        foreach($ops as $op){

            $total = $total + $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}weblator_poll_votes WHERE option_id = $op");


        }

        return $total;

    }

}
