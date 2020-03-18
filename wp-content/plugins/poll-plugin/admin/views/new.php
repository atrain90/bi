<div class="wrap new-poll weblator-admin">
    <h2>New Poll</h2>
    <?php wp_nonce_field("weblator_poll_add_poll"); ?>
    <div id="message" class="updated below-h2"><p>Poll updated.</p></div>
    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content" class="edit-form-section">
                <div id="namediv" class="stuffbox">
                    <h3><label for="name">Chart Settings</label></h3>

                    <div class="inside">
                        <table class="form-table editcomment poll-settings">
                            <tbody>
                            <tr valign="top">
                                <td class="first">Question:</td>
                                <td><input type="text" name="weblator_polling_name" size="30" id="name" value=""></td>
                            </tr>

                            <tr valign="top">
                                <td class="first">Show Results:</td>
                                <td>
                                    <select name="weblator_polling_view_results" id="">
                                        <option value="2">Always show results before a user votes</option>
                                        <option value="3">Allow users to click a link to show results before voting</option>
                                        <option value="1">Only show results after a user has voted</option>
                                        <option value="4">Never show results</option>
                                        <option value="5">Only show results</option>
                                    </select>
                                </td>
                            </tr>

                            <tr valign="top" class="option_hide">
                                <td class="first">Chart Type:</td>
                                <td><?php $charts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}weblator_chart_type "); ?>
                                    <select name="weblator_polling_chart_type" id="weblator_polling_chart_type">


                                        <?php foreach($charts as $chart): ?>
                                            <option value="<?php echo intval($chart->id); ?>"><?php echo $chart->chart_type; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>

                            <tr valign="top" class="option_hide">
                                <td class="first">Chart Max Width:</td>
                                <td class="">
                                    <input type="text" name="poll_max_width" size="30" id="poll_max_width" value="400">
                                    <p class="description">This sets the maximum width of the chart in pixels. The chart will not get any bigger than the value set here. However, the chart will scale down responsively.
                                        If this field is left blank (or set to 0) the chart will be 100% width and will be fully responsive</p>
                                </td>
                            </tr>






                            <tr valign="top">
                                <td class="first">Allow only one vote per user:</td>
                                <td><input type="checkbox" checked="checked" name="weblator_polling_one_vote" value="1"></td>
                            </tr>

                            <tr valign="top" class="indent">
                                <td class="first"><span>Limit by IP:</span></td>
                                <td><input type="checkbox" checked="checked" name="weblator_polling_one_vote_ip" value="1"></td>
                            </tr>

                            <tr valign="top" class="indent">
                                <td class="first"><span>Limit by Cookie:</span></td>
                                <td><input type="checkbox" checked="checked" name="weblator_polling_one_vote_cookie" value="1"></td>
                            </tr>

                            <tr valign="top" class="indent">
                                <td class="first"><span>Limit by User ID:</span></td>
                                <td><input type="checkbox" checked="checked" name="weblator_polling_one_vote_userid" value="1"></td>
                            </tr>

                            <tr valign="top" class="option_hide legend-option">
                                <td class="first">Show Legend:</td>
                                <td class="">
                                    <?php if (isset($poll->chart_legend)): ?>
                                        <input type="checkbox" name="chart_legend" id="chart_legend" value="<?php echo ($poll->chart_legend ? 1 : 0); ?>" <?php echo ($poll->chart_legend ? "checked='checked'" : ""); ?> >
                                    <?php else: ?>
                                        <input type="checkbox" name="chart_legend" id="chart_legend" value="0">
                                    <?php endif; ?>


                                </td>
                            </tr>

                            <tr valign="top" class="option_hide legend-option-secondary" style="display:none;">
                                <td class="first">Legend Position:</td>
                                <td>
                                    <select name="weblator_charts_legend_position" id="weblator_charts_legend_position">
                                        <option value="tl" <?php echo (isset($poll->chart_legend_position) && $poll->chart_legend_position == "tl") ? "selected='selected'" : ""; ?>>Top Left</option>
                                        <option value="tm" <?php echo (isset($poll->chart_legend_position) && $poll->chart_legend_position == "tm") ? "selected='selected'" : ""; ?>>Top Middle Inline</option>
                                        <option value="tr" <?php echo (isset($poll->chart_legend_position) && $poll->chart_legend_position == "tr") ? "selected='selected'" : ""; ?>>Top Right</option>
                                        <option value="bl" <?php echo (isset($poll->chart_legend_position) && $poll->chart_legend_position == "bl") ? "selected='selected'" : ""; ?>>Bottom Left</option>
                                        <option value="bm" <?php echo (isset($poll->chart_legend_position) && $poll->chart_legend_position == "bm") ? "selected='selected'" : ""; ?>>Bottom Middle Inline</option>
                                        <option value="br" <?php echo (isset($poll->chart_legend_position) && $poll->chart_legend_position == "br") ? "selected='selected'" : ""; ?>>Bottom Right</option>
                                    </select>
                                </td>
                            </tr>

                            <tr valign="top" class="option_hide legend-option-secondary" style="display:none;">
                                <td class="first">Legend Font Size:</td>
                                <td>
                                    <select name="weblator_charts_legend_font_size" id="weblator_charts_legend_font_size">
                                        <?php for ($i=8; $i < 29; $i++): ?>
                                            <?php if (isset($poll->chart_legend_font_size) && $poll->chart_legend_font_size) : ?>
                                                <option value="<?php echo $i;?>" <?php echo (isset($poll->chart_legend_font_size) && $poll->chart_legend_font_size == $i) ? "selected='selected'" : ""; ?>><?php echo $i; ?>px</option>
                                            <?php else: ?>
                                                <option value="<?php echo $i;?>" <?php echo ($i == 12) ? "selected='selected'" : ""; ?>><?php echo $i; ?>px</option>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                            </tr>


                            <tr valign="top" class="option_hide legend-option-secondary" style="display:none;">
                                <td class="first">Legend Font Style:</td>
                                <td>
                                    <select name="weblator_charts_chart_legend_font_style" id="weblator_charts_chart_legend_font_style">
                                        <option value="normal" <?php echo (isset($poll->chart_legend_font_style) && $poll->chart_legend_font_style == "normal") ? "selected='selected'" : ""; ?>>normal</option>
                                        <option value="italic" <?php echo (isset($poll->chart_legend_font_style) && $poll->chart_legend_font_style == "italic") ? "selected='selected'" : ""; ?>>italic</option>
                                        <option value="oblique" <?php echo (isset($poll->chart_legend_font_style) && $poll->chart_legend_font_style == "oblique") ? "selected='selected'" : ""; ?>>oblique</option>
                                    </select>
                                </td>
                            </tr>

                            <tr valign="top" class="option_hide legend-option-secondary" style="display:none;">
                                <td class="first">Legend Font Colour:</td>

                                <td class="fill colour-option">
                                    <div style="position: relative;">
                                        <input type="text"name="chart_legend_font_colour" class="poll-colour" size="30" value="<?php echo (isset($poll->chart_legend_font_colour)) ? $this->prepare_data_for_output($poll->chart_legend_font_colour) : "rgb(102, 102, 102)"; ?>">
                                    </div>
                                </td>

                            </tr>

                            <tr valign="top" class="results-percentage">
                                <td class="first">Display Results as Percentage Values:</td>
                                <td class="">
                                    <input type="checkbox" name="poll_percentage_values" id="poll_percentage_values" value="1">
                                    <p class="description">This will display your results as percentage values as opposed to its total number of votes.
                                </td>
                            </tr>

                            </tbody>
                        </table>
                        <br>
                    </div>
                </div>

                <div id="namediv" class="stuffbox style-settings">
                    <h3><label for="name">Style Settings</label></h3>

                    <div class="inside">
                        <table class="form-table editcomment chart-settings">
                            <tbody>

                            </tbody>
                        </table>
                        <br>
                    </div>
                </div>

                <div id="namediv" class="stuffbox message-settings">
                    <h3><label for="name">Message Settings</label></h3>
                    <p style="padding-left:20px;">Enter custom messages here, if no messages are entered then the global messages will be used</p>
                    <div class="inside">
                        <table class="form-table editcomment poll-settings">
                            <tbody>


                            <tr valign="top" class="">
                                <td class="first" style="vertical-align:top;">Already Voted:</td>
                                <td class="">
                                    <input type="text" name="weblator_already_voted"  id="weblator_already_voted" value="">
                                    <p class="description">The text to be displayed when a user has already voted on a Poll and is only allowed one vote.</p>
                                </td>
                            </tr>

                            <tr valign="top" class="">
                                <td class="first" style="vertical-align:top;">Please Select on Option:</td>
                                <td class="">
                                    <input type="text" name="weblator_please_select"  id="weblator_please_select" value="">
                                    <p class="description">The text to be displayed when a user click the vote button without selecting an Option.</p>
                                </td>
                            </tr>

                            <tr valign="top" class="">
                                <td class="first" style="vertical-align:top;">Thank You:</td>
                                <td class="">
                                    <input type="text" name="weblator_thank_you"  id="weblator_thank_you" value="">
                                    <p class="description">The text to be displayed when a user votes.</p>
                                </td>
                            </tr>

                            <tr valign="top" class="">
                                <td class="first" style="vertical-align:top;">Vote:</td>
                                <td class="">
                                    <input type="text" name="weblator_vote"  id="weblator_vote" value="">
                                    <p class="description">The text to be displayed on the vote button.</p>
                                </td>
                            </tr>

                            <tr valign="top" class="">
                                <td class="first" style="vertical-align:top;">Hide:</td>
                                <td class="">
                                    <input type="text" name="weblator_hide"  id="weblator_hide" value="">
                                    <p class="description">The text to be displayed on the Hide Results button.</p>
                                </td>
                            </tr>

                            <tr valign="top" class="">
                                <td class="first" style="vertical-align:top;">Show:</td>
                                <td class="">
                                    <input type="text" name="weblator_show"  id="weblator_show" value="">
                                    <p class="description">The text to be displayed on the Show Results button.</p>
                                </td>
                            </tr>

                            <tr valign="top" class="">
                                <td class="first" style="vertical-align:top;">Loading:</td>
                                <td class="">
                                    <input type="text" name="weblator_loading"  id="weblator_loading" value="">
                                    <p class="description">The text to be displayed when the poll is loading.</p>
                                </td>
                            </tr>


                            </tbody>
                        </table>
                        <br>
                    </div>
                </div>


                <div id="namediv" class="stuffbox response-settings">
                    <h3><label for="name">Response Options</label></h3>

                    <div class="inside">
                        <table class="form-table editcomment poll-options">
                            <tbody>

                            <tr valign="top" data-order="1">
                                <td class="first">
                                    Option:
                                </td>
                                <td>
                                    <input type="text"name="poll_option" class="poll-option" size="30">
                                </td>
                                <td class="fill colour-option">
                                    <div style="position:relative">
                                    <input type="text"name="poll_colour" class="poll-colour" size="30" value="rgba(151,187,205,0.5)">
                                    </div>
                                </td>
                                <td class="remove-option">
                                    <button class="button poll-option-remove"><i class="icon-minus-squared"></i> Remove Option</button>
                                </td>
                                <td class="drag">
                                    <i class="icon-menu"></i>
                                </td>
                            </tr>


                            </tbody>
                        </table>
                        <button id="poll-option" class="button"><i class="icon-plus-squared"></i> Add Option</button>
                    </div>
                </div>


            </div><!-- /post-body-content -->

            <div id="postbox-container-1" class="postbox-container">
                <div id="submitdiv" class="stuffbox">
                    <h3><span class="hndle">Status</span></h3>
                    <div class="inside">
                        <div class="submitbox" id="submitcomment">
                            <div id="minor-publishing">


                                <div id="misc-publishing-actions">

                                    <div class="misc-pub-section misc-pub-comment-status" id="comment-status-radio">
                                        <label class="approved"><input type="radio" checked="checked" name="poll_status" value="1">Approved</label><br>
                                        <label class="spam"><input type="radio" name="poll_status" value="0">Hidden</label>
                                    </div>


                                    <div class="misc-pub-section curtime misc-pub-curtime">
                                        <!--<span id="timestamp">Created on: <b>Jan 14, 2014 @ 9:33</b></span>-->
                                    </div>
                                </div> <!-- misc actions -->
                                <div class="clear"></div>
                            </div>

                            <div id="major-publishing-actions">

                                <div id="delete-action" class="delete-action">
                                    <span class="spinner"></span>
                                </div>

                                <div id="publishing-action">
                                    <input type="submit" name="save" id="save" class="button button-primary save-button" value="Update">
                                </div>
                                    <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div><!-- /submitdiv -->
            </div>

            <div id="postbox-container-2" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable"></div></div>

        </div><!-- /post-body -->


    </div>
</div>

<script id="optionTmpl" type="text/template">
    <tr valign="top" data-order="">
        <td class="first">
            Option:
        </td>
        <td>
            <input type="text" name="poll_option" data-id="0" class="poll-option" size="30">
        </td>
        <td class="fill colour-option">
            <div style="position: relative;">
                <input type="text"name="poll_colour" class="poll-colour" size="30" value="rgba(151,187,205,0.5)">
            </div>
        </td>
        <td class="remove-option-remove">
            <button class="button poll-option-remove"><i class="icon-minus-squared"></i> Remove Option</button>
        </td>
        <td class="drag">
            <i class="icon-menu"></i>
        </td>
    </tr>
</script>

<script id="optionRow" type="text/template">

    <tr valign="top" class="style-options">
        <td class="first">{{style_label}}:</td>
        <td class="{{#style_colorpicker}}fill{{/style_colorpicker}}">
            <div style="position:relative">
            {{#style_bool}}
                <input type="checkbox" {{#style_default}}checked="checked"{{/style_default}} data-style-id="{{ id }}" name="{{style_option}}" size="30" id="{{style_option}}" {{#style_default}}value="1"{{/style_default}}{{^style_default}}value="0"{{/style_default}}>
            {{/style_bool}}
            {{^style_bool}}
                {{#style_dropdown}}
                <select name="{{style_option}}" id="{{style_option}}" data-style-id="{{ id }}">
                    {{#each dropdown_options}}
                    <option value="{{value}}" {{#if selected}} selected="selected" {{/if}}>{{key}}</option>
                    {{/each}}
                </select>
                {{/style_dropdown}}
                {{^style_dropdown}}
                <input type="text" data-style-id="{{ id }}" name="{{style_option}}" size="30" id="{{style_option}}" value="{{style_default}}">
                {{/style_dropdown}}
            {{/style_bool}}
            <p class="description">{{style_description}}</p>
                </div>
        </td>
    </tr>

</script>
