<?php

if ($poll["poll_is_live"]){

    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

$var = '<div class="weblator-poll-container" data-results="'; ($results) ? $var .= "1" : "0"; $var .= '" data-poll-id="' . intval($poll["id"]) . '" data-random-id="' . $randomString . '" data-_wpnonce="'.wp_create_nonce("weblator_poll_vote_".intval($poll["id"])).'">';

if (!$results) {
  if ( $poll["poll_allow_view_results"] != 5){

      $var .= '<div class="weblator-poll-loading">' . $this->get_single_message($poll["id"], "weblator_load") . '</div>

      <div class="panel panel-default">
          <div class="panel-heading">
              <h3 class="panel-title">' . $this->prepare_data_for_output($poll["poll_name"]) . '</h3>
          </div>

          <div class="panel-body weblator-poll-vote">

              <div class="alert alert-success">' . $this->get_single_message($poll["id"], "weblator_thank_you") . '</div>
              <div class="alert alert-warning">' . $this->get_single_message($poll["id"], "weblator_already_voted") . '</div>
              <div class="alert alert-danger">' . $this->get_single_message($poll["id"], "weblator_please_select") . '</div>

              <ul>';
                  foreach($options as $key => $option){
                      $var .= '<li><input type="radio" data-index="' . intval($key) . '" name="weblator-chart-options" id="option-' . intval($option["id"]) . '" data-poll-id="' . intval($poll["id"]) . '" value="' . intval($option["id"]) . '"/>
                          <label class="weblator-poll-label" for="option-' . $option["id"] . '">' . stripslashes($option["option_value"]) . '</label>
                      </li>';
                  }

              $var .= '</ul></div>';

      $var .= '<div class="panel-footer">
              <div class="button-vote">
                  <button class="btn btn-default vote-button weblator-poll-submit"><i class="fa fa-spinner fa-spin vote-spin"></i> ' . $this->get_single_message($poll["id"], "weblator_vote") . '</button>
              </div>
              <div class="weblator-view-results">';

              if ( $total_votes == 0 )
                  $var .= '<a href="#" class="weblator-view-poll btn btn-default" disabled="disabled">' . $this->get_single_message($poll["id"], "weblator_show") . '</a>';
              else
                  $var .= '<a href="#" class="weblator-view-poll btn btn-default">' . $this->get_single_message($poll["id"], "weblator_show") . '</a>';


                  $var .= '<a href="#" class="weblator-hide-poll btn btn-default">' . $this->get_single_message($poll["id"], "weblator_hide") . '</a>

              </div>

          </div>
      </div>';

  }
}

	if ( $poll["poll_max_width"] > 0 )
		$width = intval($poll["poll_max_width"]) . "px";
	else
        $width = "100%";


    $var .= "<div class='width-control' style='width:" . $width . "'>";

    if ($poll["poll_chart_type"] == 2 || $poll["poll_chart_type"] == 3 || $poll ["poll_chart_type"] == 6){

        $legendStyle = "style='font-style:" . $this->prepare_data_for_output($poll["chart_legend_font_style"]) . "; color:" . $this->prepare_data_for_output($poll["chart_legend_font_colour"]) . "; '";

        if ($poll["chart_legend"]){

            if ( substr($poll["chart_legend_position"], 0, -1) == "t" )
                $var .= "<ul " . $legendStyle . " data-font-size='" . $this->prepare_data_for_output($poll["chart_legend_font_size"]) . "' id=\"legend-" . intval($poll["id"]) . "\" class=\"weblator-poll-legend " . $this->prepare_data_for_output($poll["chart_legend_position"]) . "\"></ul>";
        }
    }

    $var .= '<canvas data-width="'.$width.'" data-weblator-poll-id="' . intval($poll["id"]) . '" id="weblator-chart-' . intval($poll["id"]) . '" class="weblator-chart"></canvas>';

    if ($poll["poll_chart_type"] == 2 || $poll["poll_chart_type"] == 3 || $poll ["poll_chart_type"] == 6){

        $legendStyle = "style='font-style:" . $this->prepare_data_for_output($poll["chart_legend_font_style"]) . "; color:" . $this->prepare_data_for_output($poll["chart_legend_font_colour"]) . "; '";

        if ($poll["chart_legend"]){

            if ( substr($poll["chart_legend_position"], 0, -1) == "b" )
                $var .= "<ul " . $legendStyle . " data-font-size='" . $this->prepare_data_for_output($poll["chart_legend_font_size"]) . "' id=\"legend-" . intval($poll["id"]) . "\" class=\"weblator-poll-legend " . $this->prepare_data_for_output($poll["chart_legend_position"]) . "\"></ul>";
        }
    }

    $var .= '</div>';



 $var .= '</div>';
}
