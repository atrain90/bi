<?php

/**
 * Weblator Polling.
 *
 * @package   Weblator_Polling
 * @author    Weblator <daniel.prior@weblator.com>
 * @license   GPL-2.0+
 * @link      http://weblator.com
 * @copyright 2014 Weblator
 */

class Weblator_Public_Ajax {

    static function save_vote(){

        if(isset($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], "weblator_poll_vote_".intval($_POST["poll_id"])))
        {

            global $wpdb;
            $poll_id = $_POST["poll_id"];


            //Check the option sent through is numeric
            if (!self::validate_int($_POST["option"])){
                die("Option is not an integer");
            }

            if (!self::validate_int($poll_id)){
                die("Poll ID is not an integer");
            }

            //Get the poll options
            $options = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}weblator_polls WHERE id = %d", $poll_id), ARRAY_A );


            //If the user can only vote once
            if ($options["poll_one_vote"]){

                if(!self::can_user_vote($poll_id))
                    die("User has already voted");

                //Set cookie
                setcookie('poll_id_' . $poll_id, "1", time() + ((86400 * 7) * 365), COOKIEPATH, COOKIE_DOMAIN, false);
            }



            $user_ID = get_current_user_id();

            $wpdb->query(
                $wpdb->prepare("
                    INSERT INTO {$wpdb->prefix}weblator_poll_votes (poll_id, option_id, user_id, ipv4, date_voted)
                    VALUES ('%d','%d','%d',INET_ATON('%s'),'%s')", $poll_id, $_POST["option"], $user_ID, $_SERVER["REMOTE_ADDR"], date("Y-m-d H:i:s")
                )
            );

            die("Voted Added");
        }
        else
            die("CSRF Error");

    }

    static function can_user_vote($poll_id){

        global $wpdb;

        $user_ID = get_current_user_id();

        $by_ip = $wpdb->get_var( $wpdb->prepare("SELECT poll_one_vote_ip FROM {$wpdb->prefix}weblator_polls WHERE id = %d ", $poll_id) );
        $by_cookie = $wpdb->get_var( $wpdb->prepare("SELECT poll_one_vote_cookie FROM {$wpdb->prefix}weblator_polls WHERE id = %d ", $poll_id) );
        $by_user = $wpdb->get_var( $wpdb->prepare("SELECT poll_one_vote_userid FROM {$wpdb->prefix}weblator_polls WHERE id = %d ", $poll_id) );

        //If $user id is false then there is no user logged in
        if ($user_ID){

            if ( $by_user ){

                $user_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}weblator_poll_votes WHERE user_id = %d AND poll_id = %d", $user_ID, $poll_id) );

                //If user count is true then the user has voted already
                if ($user_count)
                    return false;
            }
        }

        if ( $by_cookie ){

            //Check if a cookie has been set
            if (isset($_COOKIE['poll_id_' . $_POST["poll_id"]])){
                return false;
            }

        }

        if ( $by_ip ){

            $ip = $_SERVER["REMOTE_ADDR"];
            $ip_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}weblator_poll_votes WHERE ipv4 = %d AND poll_id = %d", ip2long($ip), $poll_id) );

            if ($ip_count)
                return false;

        }

        return true;
    }

    static function validate_int($int){

        if (filter_var($int, FILTER_VALIDATE_INT) !== FALSE) {
            return true;
        }
        else{
            return false;
        }


    }

    static function get_chart_data(){

        global $wpdb;

        if (!self::validate_int($_POST["poll_id"])){
            die("Poll ID is not an integer");
        }

        $poll_id = $_POST["poll_id"];

        $chart_type = $wpdb->get_var( $wpdb->prepare("SELECT poll_chart_type FROM {$wpdb->prefix}weblator_polls WHERE id = %d", $poll_id) );

        $colour_options = array();

        if ($chart_type == 1)

            $colour_options[] = array(
                "fillColor" => Weblator_Polling::prepare_data_for_output($wpdb->get_var( $wpdb->prepare("SELECT style_value FROM {$wpdb->prefix}weblator_style_values WHERE poll_id = %d AND style_id = 1", $poll_id) )),
                "strokeColor" => Weblator_Polling::prepare_data_for_output($wpdb->get_var( $wpdb->prepare("SELECT style_value FROM {$wpdb->prefix}weblator_style_values WHERE poll_id = %d AND style_id = 2", $poll_id) ))
            );

        else if($chart_type == 4 || $chart_type == 5)

            $colour_options[] = array(
                "fillColor" => Weblator_Polling::prepare_data_for_output($wpdb->get_var( $wpdb->prepare("SELECT style_value FROM {$wpdb->prefix}weblator_style_values WHERE poll_id = %d AND style_id = 1", $poll_id) )),
                "strokeColor" => Weblator_Polling::prepare_data_for_output($wpdb->get_var( $wpdb->prepare("SELECT style_value FROM {$wpdb->prefix}weblator_style_values WHERE poll_id = %d AND style_id = 2", $poll_id) )),
                "pointColor" => Weblator_Polling::prepare_data_for_output($wpdb->get_var( $wpdb->prepare("SELECT style_value FROM {$wpdb->prefix}weblator_style_values WHERE poll_id = %d AND style_id = 3", $poll_id) )),
                "pointStrokeColor" => Weblator_Polling::prepare_data_for_output($wpdb->get_var( $wpdb->prepare("SELECT style_value FROM {$wpdb->prefix}weblator_style_values WHERE poll_id = %d AND style_id = 4", $poll_id) ))
            );

        $results = $wpdb->get_results(
            $wpdb->prepare("
                SELECT {$wpdb->prefix}weblator_poll_options.id,
                {$wpdb->prefix}weblator_poll_options.option_value,
                {$wpdb->prefix}weblator_poll_options.option_colour,
                (SELECT count(*) FROM {$wpdb->prefix}weblator_poll_votes WHERE option_id = {$wpdb->prefix}weblator_poll_options.id AND poll_id = {$wpdb->prefix}weblator_poll_options.poll_id) AS total
                FROM {$wpdb->prefix}weblator_poll_options WHERE poll_id = %d AND option_deleted_date IS NULL ORDER BY option_order ASC", $poll_id), ARRAY_A);


        $labels = array_column($results, 'option_value');
        $totals = array_column($results, 'total');
        $colours = array_column($results, 'option_colour');

        foreach($labels as $key => $label){
            $labels[$key] = Weblator_Polling::prepare_data_for_output($label);
        }

        $maxWidth = intval($wpdb->get_var( $wpdb->prepare("SELECT poll_max_width FROM {$wpdb->prefix}weblator_polls WHERE id = %d", $poll_id) ));
        $minWidth = intval($wpdb->get_var( $wpdb->prepare("SELECT poll_min_width FROM {$wpdb->prefix}weblator_polls WHERE id = %d", $poll_id) ));
        $legend = Weblator_Polling::prepare_data_for_output($wpdb->get_var( $wpdb->prepare("SELECT chart_legend FROM {$wpdb->prefix}weblator_polls WHERE id = %d", $poll_id) ));
        $percentage = floatval($wpdb->get_var( $wpdb->prepare("SELECT poll_percentage_values FROM {$wpdb->prefix}weblator_polls WHERE id = %d", $poll_id) ));

        $options = $wpdb->get_results(
            $wpdb->prepare("
                SELECT
                    {$wpdb->prefix}weblator_style_options.style_key,
                    {$wpdb->prefix}weblator_style_values.style_value
                FROM
                    {$wpdb->prefix}weblator_style_values,
                    {$wpdb->prefix}weblator_style_chart_link,
                    {$wpdb->prefix}weblator_style_options
                WHERE
                    {$wpdb->prefix}weblator_style_values.poll_id = %d
                    AND {$wpdb->prefix}weblator_style_chart_link.chart_id = %d
                    AND {$wpdb->prefix}weblator_style_chart_link.style_id = {$wpdb->prefix}weblator_style_values.style_id
                    AND {$wpdb->prefix}weblator_style_options.id = {$wpdb->prefix}weblator_style_values.style_id
                    AND {$wpdb->prefix}weblator_style_options.style_key IS NOT NULL
            ", $poll_id, $chart_type)
        );

        $option_key = array();
        $option_value = array();
        foreach($options as $option){

            $option_key[] = $option->style_key;
            $option_value[] = (is_numeric($option->style_value) ? (int)$option->style_value : $option->style_value);

        }

        $option_key[] = "responsive";
        $option_value[] = true;

        if ( $percentage ) {

            $option_key[] = "scaleLabel";
            $option_value[] = "<%= value + '%'%>";

            $option_key[] = "scaleStartValue";
            $option_value[] = 0;

            $option_key[] = "tooltipTemplate";
            $option_value[] = "<%if (label){%><%=label%>: <%}%><%= value + '%' %>";

        }


        echo json_encode(array("percentage" => (INT)$percentage, "labels" => $labels, "options" => array_combine($option_key, $option_value), "maxWidth" => $maxWidth, "minWidth" => $minWidth, "showLegend" => $legend, "totals" => $totals, "option_colours" => $colours, "styles" => $colour_options, "chart_type" => $chart_type));

        die();

    }

    static function updated_results() {

        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare("
            SELECT {$wpdb->prefix}weblator_poll_options.id AS option_id,
            (SELECT count(*) FROM {$wpdb->prefix}weblator_poll_votes WHERE option_id = {$wpdb->prefix}weblator_poll_options.id AND poll_id = {$wpdb->prefix}weblator_poll_options.poll_id) AS total
            FROM {$wpdb->prefix}weblator_poll_options WHERE poll_id = %d AND option_deleted_date IS NULL ORDER BY option_order ASC", $_POST["poll_id"]
            )
        );

        echo json_encode($results);
        die();

    }

    static function load_poll(){

        global $wpdb;

        $poll_id = $_POST["poll_id"];

        if (!self::validate_int($poll_id)){
            die("Poll ID is not an integer");
        }

        $can_vote = $wpdb->get_var( $wpdb->prepare( "SELECT poll_one_vote FROM {$wpdb->prefix}weblator_polls WHERE id = %d", $poll_id) );

        if ($can_vote){
            if (!self::can_user_vote($poll_id))
                echo 1;
            else
                echo 0;

            die();
        }
        else
            echo 0;

        die();
    }

    static function result_view(){

        global $wpdb;

        $poll_id = $_POST["poll_id"];

        if (!self::validate_int($poll_id)){
            die("Poll ID is not an integer");
        }


        $result = $wpdb->get_var( $wpdb->prepare( "SELECT poll_allow_view_results FROM {$wpdb->prefix}weblator_polls WHERE id = %d", $poll_id) );
        echo $result;

        die();

    }

}
