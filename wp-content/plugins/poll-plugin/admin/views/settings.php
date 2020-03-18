<div class="wrap weblator-poll-options">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php wp_nonce_field("weblator_poll_update_global_settings"); ?>
	<div id="message" class="updated below-h2"><p>Poll Options updated.</p></div>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content" class="edit-form-section">

                <div id="namediv" class="stuffbox">
                    <h3><label for="name">Text Settings</label></h3>
                    <div class="inside weblator-settings">

						<p>These are global settings for messages displayed in your polls. Messages can be overridden within a polls setting page	</p>

                        <div class="input-row">
                            <div class="left">
                                <label for="weblator_already_voted">Already Voted</label>
                            </div>
                            <div class="right">
                                <input type="text" class="regular-text" name="weblator_already_voted" id="weblator_already_voted" value="<?php echo $this->get_global_text_option("weblator_already_voted"); ?>">
								<p class="description">The text to be displayed when a user has already voted on a Poll and is only allowed one vote.</p>
                            </div>
                        </div>

						<div class="input-row">
                            <div class="left">
                                <label for="weblator_please_select">Please Select on Option</label>
                            </div>
                            <div class="right">
                                <input type="text" class="regular-text" name="weblator_please_select" id="weblator_please_select" value="<?php echo $this->get_global_text_option("weblator_please_select"); ?>">
								<p class="description">The text to be displayed when a user click the vote button without selecting an Option.</p>
                            </div>
                        </div>

						<div class="input-row">
                            <div class="left">
                                <label for="weblator_thank_you">Thank You</label>
                            </div>
                            <div class="right">
                                <input type="text" class="regular-text" name="weblator_thank_you" id="weblator_thank_you" value="<?php echo $this->get_global_text_option("weblator_thank_you"); ?>">
								<p class="description">The text to be displayed when a user votes.</p>
                            </div>
                        </div>

						<div class="input-row">
                            <div class="left">
                                <label for="weblator_vote">Vote</label>
                            </div>
                            <div class="right">
                                <input type="text" class="regular-text" name="weblator_vote" id="weblator_vote" value="<?php echo $this->get_global_text_option("weblator_vote"); ?>">
								<p class="description">The text to be displayed on the vote button.</p>
                            </div>
                        </div>

						<div class="input-row">
                            <div class="left">
                                <label for="weblator_hide">Hide</label>
                            </div>
                            <div class="right">
                                <input type="text" class="regular-text" name="weblator_hide" id="weblator_hide" value="<?php echo $this->get_global_text_option("weblator_hide"); ?>">
								<p class="description">The text to be displayed on the Hide Results button.</p>
                            </div>
                        </div>

						<div class="input-row">
                            <div class="left">
                                <label for="weblator_show">Show</label>
                            </div>
                            <div class="right">
                                <input type="text" class="regular-text" name="weblator_show" id="weblator_show" value="<?php echo $this->get_global_text_option("weblator_show"); ?>">
								<p class="description">The text to be displayed on the Show Results button.</p>
                            </div>
                        </div>

						<div class="input-row">
                            <div class="left">
                                <label for="weblator_show">Loading</label>
                            </div>
                            <div class="right">
                                <input type="text" class="regular-text" name="weblator_load" id="weblator_load" value="<?php echo $this->get_global_text_option("weblator_load"); ?>">
								<p class="description">The text to be displayed when the poll is loading.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

			<div id="postbox-container-1" class="postbox-container">
				<div id="submitdiv" class="stuffbox">

					<div class="inside">
						<div class="submitbox" id="submitcomment">

							<div id="major-publishing-actions">

								<div id="delete-action" class="delete-action">
									<span class="spinner"></span>
								</div>

								<div id="publishing-action">
									<input type="submit" name="save" id="save" class="button button-primary save-options-button" value="Update">
								</div>
									<div class="clear"></div>
							</div>
						</div>
					</div>
				</div><!-- /submitdiv -->
			</div>

			<div id="postbox-container-2" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable"></div></div>

        </div>
    </div>
</div>
