<?php 

require_once( "../../../../../wp-config.php" );
require_once( "teamchart-admin.php" );
// or require_once( "../../../../wp-load.php" );


if(!isset($_GET["popup"]))
	return false;
else {

 ?>

<div id="column">
<div id="chart" class="cols">

		<p>
			<label><?php _e( 'Chart name', 'WP-team-display' ) ?> :</label>
			<input type="text" name="chart-name" id="chart-name">
		</p>
		<a href="#" class="button-primary" id="add-chart">+ <?php _e( 'add new chart', 'WP-team-display' ) ?></a>
		<hr/>

<?php
	if(isset($_GET["id"]))
	echo "<input type='hidden' id='id-chart' value='".$_GET["id"]."'/> ";
?>		

		<h4><?php _e( 'Chart', 'WP-team-display' ) ?> :</h4>
		<div id="chart-list">
			
		</div>
		<h4><?php _e( 'Search person', 'WP-team-display' ) ?> :</h4>
		<div id="person-list">
		</div>
</div>



<div id="team-chart" class="cols">
	
	
	<div id='chartgraph'>
		<div id="loading"> <?php _e( 'Save', 'WP-team-display' )?> </div>
<span class='tips'><?php _e( 'Please select or add chart to build it !', 'WP-team-display' ) ?> </span>
	
	
	<div id="build-chart">
	
	</div>
	</div>
</div>



<div id="person" class="cols hidden">
<a href="#" class="button button-primary button-hero upload-button" id="save-chart">
<?php _e( 'Insert chart', 'WP-team-display' ) ?>
</a><br/>
<hr/>
<?php _e( 'Disable "responsive mode" ', 'WP-team-display' ) ?>
<span data-title="<?php _e( 'Check if you have a large Chart.', 'WP-team-display' ) ?>" class="tooltip">?</span>

<input type="checkbox" value="Yes" name="responsivemode" id="responsivemode" style="margin-left:10px;" <?php if (isset($_GET["responsive"])) echo 'checked'; ?>/>
<hr/><br/>
	
	<div id="choose-theme">	
		
	</div>
	
<hr/>
		<form id="add-new-person" class="add-person">
		<label><?php _e( 'Picture', 'WP-team-display' ) ?> * :</label><br/>
		<a href="#" class="custom_media_upload picture">
		<?php _e( 'Upload or choose picture', 'WP-team-display' ) ?>
		</a>
		<input type="text" name="media" id="media-upload-hidden" required>

		<br/>
		
		<label><?php _e( 'Name', 'WP-team-display' ) ?> * :</label><br/>
		<input type="text" name="name" id="new-person" placeholder="<?php _e( 'Enter name...', 'WP-team-display' ) ?>" required/>
		<label><?php _e( 'Position', 'WP-team-display' ) ?> :</label><br/>
		<input type="text" name="position" id="job" placeholder="<?php _e( 'Enter position…', 'WP-team-display' ) ?>"/>
        <br>
        <div id="socialInfo">
            <label for="socialInfo"><?php _e( 'Social Networks Information', 'WP-team-display' ) ?>:</label><br/>
            <ul>
                <li>
                    <label for="socialInfoFacebook"><?php _e( 'Facebook', 'WP-team-display' ) ?></label>
                    <input type="text" id="socialInfoFacebook" name="socialInfoFacebook" placeholder="<?php _e( 'Facebook URL', 'WP-team-display' ) ?>">
                </li>
                <li>
                    <label for="socialInfoEmail"><?php _e( 'Email', 'WP-team-display' ) ?></label>
                    <input type="text" id="socialInfoEmail" name="socialInfoEmail" placeholder="<?php _e( 'Email URL', 'WP-team-display' ) ?>">
                </li>
                <li>
                    <label for="socialInfoIn"><?php _e( 'LinkedIn', 'WP-team-display' ) ?></label>
                    <input type="text" id="socialInfoIn" name="socialInfoIn" placeholder="<?php _e( 'LinkedIn URL', 'WP-team-display' ) ?>">
                </li>
                <li>
                    <label for="socialInfoTwitter"><?php _e( 'Twitter', 'WP-team-display' ) ?></label>
                    <input type="text" id="socialInfoTwitter" name="socialInfoTwitter" placeholder="<?php _e( 'Twitter URL', 'WP-team-display' ) ?>">
                </li>
            </ul>
        </div>
        <div>
        	<label for="description"><?php echo _e( 'Description', 'WP-team-display' ) ?>:</label><br/>
			<textarea name="description" id="description" rows="10"></textarea>
			<div class="disableTextEditor">
				<label for="disableTextEditor"><?php echo _e( 'Disable editor', 'WP-team-display' ) ?></label>
				<input id="disableTextEditor" type="checkbox" class="checkbox" />
			</div>
		</div>
			<input type="submit" class="button button-primary" id="submit-person" value="<?php _e( 'Add person', 'WP-team-display' ) ?>"/>
		</form>
<hr/>

</div>



</div>
</div>

<script>
jQuery(document).ready(function() {
	tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'description');
	tinymce.EditorManager.execCommand('mceAddEditor',true, 'description');
	jQuery('input#disableTextEditor').on('click' ,function() {
		if(jQuery("input#disableTextEditor").is(':checked')) {
			tinymce.EditorManager.execCommand('mceToggleEditor',false,'description');
		} else {
			tinymce.EditorManager.execCommand('mceToggleEditor',true,'description');
		}
	});

		
var jsonData = [                    
                {image:'<?php echo plugins_url('asset/images/default.png', dirname(dirname(__FILE__)));?>', value:'1', text:'<?php _e( 'Default theme', 'WP-team-display' ) ?>'},
                {image:'<?php echo plugins_url('asset/images/circle.png', dirname(dirname(__FILE__)));?>', value:'2', text:'<?php _e( 'Circle theme', 'WP-team-display' ) ?>'},
                {image:'<?php echo plugins_url('asset/images/nature.png', dirname(dirname(__FILE__)));?>',  value:'3', text:'<?php _e( 'Nature theme', 'WP-team-display' ) ?>'},
                {image:'<?php echo plugins_url('asset/images/default.png', dirname(dirname(__FILE__)));?>', value:'4', text:'<?php _e( 'Flat theme', 'WP-team-display' ) ?>'},
                ];

oDropdown = jQuery("#choose-theme").msDropdown({enableAutoFilter:false,byJson:{data:jsonData, name:'Template',selectedIndex: 0}}).data("dd");
if (typeof oDropdown !== 'undefined') {
    oDropdown.set('disabled', true);
}

init ();
});
</script>

<?php
 error_log( 'Something called me!' , 0 );
}
?>