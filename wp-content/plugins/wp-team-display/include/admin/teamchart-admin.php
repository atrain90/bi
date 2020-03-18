<?php



add_filter( 'media_buttons_context', 'popup_admin');
add_image_size('cropped', 182, 182, true);
add_action( 'wp_enqueue_scripts', 'temDisplay_jquery_scripts' );

// Load modal in admin footer
add_action('admin_enqueue_scripts', 'loadAdmin');
function loadAdmin()
{
    $current_screen = get_current_screen();
    if ($current_screen->base === 'post') {
        add_action('admin_footer', 'renderModal');
    }
}
// Load modal in front footer
add_action('wp_footer', 'renderModal');


function temDisplay_jquery_scripts() {
    wp_enqueue_script('jquery');
}

//wp_enqueue_script('media-upload');

function popup_admin($button){
	// Import CSS
	wp_enqueue_style( 'lightbox', plugins_url('asset/style/style.css', dirname(dirname(__FILE__))) );
	wp_enqueue_style( 'fancybox-css', plugins_url('asset/style/jquery.fancybox.css?v=2.1.5', dirname(dirname(__FILE__))) );
	wp_enqueue_style( 'dropdown-css', plugins_url('asset/style/dd.css', dirname(dirname(__FILE__))) );
	wp_enqueue_style( 'cropimage-css', plugins_url('asset/style/imgareaselect-default.css', dirname(dirname(__FILE__))) );
	// Import JS
	wp_enqueue_script( 'fancybox-js', plugins_url('asset/js/jquery.fancybox.pack.js?v=2.1.5', dirname(dirname(__FILE__))),array('jquery'),"2.1.5",true);
	wp_enqueue_script( 'jquery-ui-js', plugins_url('asset/js/jquery-ui.min.js', dirname(dirname(__FILE__))),array('jquery'),"1.8",true );
	wp_enqueue_script( 'jquery-chart-js', plugins_url('asset/js/jquery.jOrgChart.js', dirname(dirname(__FILE__))) ,array('jquery'),"1.0.0",true);
	wp_enqueue_script('teamchart-popup', plugins_url('asset/js/teamchart-popup.js', dirname(dirname(__FILE__))),array('jquery'),"1.0.0",true);
	wp_enqueue_script('teamchart-popup-theme', plugins_url('asset/js/jquery.dd.js', dirname(dirname(__FILE__))),array('jquery'),"1.0.0",true);
	
	wp_enqueue_script('teamchart-cropimage', plugins_url('asset/js/jquery.imgareaselect.pack.js', dirname(dirname(__FILE__))),array('jquery'),"1.0.0",true);
	wp_localize_script('teamchart-popup', 'teamchart_popup_vars', array(
			'deleteconfirm' => __('Are you sure to delete this person ?', 'WP-team-display'),
			'deletechart' => __('Delete this chart ?', 'WP-team-display'),
			'yes' => __('Yes', 'WP-team-display'),
			'no' => __('No', 'WP-team-display'),
			'tips' => __('Please select or add chart to build it !', 'WP-team-display'),
			'loading' => __('loading', 'WP-team-display'),
			'updateperson' => __('Update person', 'WP-team-display'),
			'updateform' => __('Update', 'WP-team-display'),
			'mediabutton' => __('Upload or choose picture', 'WP-team-display'),
			'addperson' => __('Add person', 'WP-team-display')
		)
	);
    // Enqueue fancyboxup
    wp_enqueue_script('teamchart-fancyboxup', plugins_url('asset/js/fancyboxup.js', dirname(dirname(__FILE__))),array('jquery'),"1.0.0",true);
    wp_localize_script('teamchart-fancyboxup', 'teamchart_fancyboxup_vars', array(
            'defaul_url_image' => plugins_url('asset/images/default.png', dirname(dirname(__FILE__))),
            'circle_url_image' => plugins_url('asset/images/circle.png', dirname(dirname(__FILE__))),
            'nature_url_image' => plugins_url('asset/images/nature.png', dirname(dirname(__FILE__))),
            'default_name'     => __('Default theme', 'WP-team-display'),
            'circle_name'      => __('Circle theme', 'WP-team-display'),
            'nature_name'      => __('Nature theme', 'WP-team-display'),
            'flat_name'        => __('Flat theme', 'WP-team-display')
        )
    );

	$button .= '<a href="#column" class="button various" id="add_team_chart"><span class="dashicons dashicons-networking"></span><span style="margin:0 5px;">'.__('WP Team Display','WP-team-display').'</span></a>';
	return $button;
}



function renderModal() {
    ?>
    <div id="column" style="display: none">
        <div id="chart" class="cols">
            <p>
                <label><?php _e('Chart name', 'WP-team-display') ?> :</label>
                <input type="text" name="chart-name" id="chart-name">
            </p>
            <a href="#" class="button-primary teamchart-addnew" id="add-chart">+ <?php _e('add new chart', 'WP-team-display') ?></a>
            <hr/>
            <?php
            if (isset($_GET["id"])) {
                echo "<input type='hidden' id='id-chart' value='" . $_GET["id"] . "'/> ";
            }
            ?>
            <h4><?php _e('Chart', 'WP-team-display') ?> :</h4>
            <div id="chart-list">

            </div>
            <h4><?php _e('Search person', 'WP-team-display') ?> :</h4>
            <div id="person-list">
            </div>
        </div>
        <div id="team-chart" class="cols">
            <div id='chartgraph'>
                <div id="loading"> <?php _e('Save', 'WP-team-display') ?> </div>
                <span class='tips'><?php _e('Please select or add chart to build it !', 'WP-team-display') ?> </span>


                <div id="build-chart">

                </div>
            </div>
        </div>
        <div id="person" class="cols hidden">
            <a href="#" class="button button-primary button-hero upload-button" id="save-chart">
                <?php _e('Insert chart', 'WP-team-display') ?>
            </a><br/>
            <hr/>
            <?php _e('Disable "responsive mode" ', 'WP-team-display') ?>
            <span data-title="<?php _e('Check if you have a large Chart.', 'WP-team-display') ?>"
                  class="tooltip">?</span>

            <input type="checkbox" value="Yes" name="responsivemode" id="responsivemode"
                   style="margin-left:10px;" <?php if (isset($_GET["responsive"])) {
                echo 'checked';
            } ?>/>
            <hr/>
            <br/>
            <div id="choose-theme">

            </div>
            <hr/>
            <form id="add-new-person" class="add-person">
                <label><?php _e('Picture', 'WP-team-display') ?> * :</label><br/>
                <a href="#" class="custom_media_upload picture">
                    <?php _e('Upload or choose picture', 'WP-team-display') ?>
                </a>
                <input type="text" name="media" id="media-upload-hidden" required>

                <br/>

                <label><?php _e('Name', 'WP-team-display') ?> * :</label><br/>
                <input type="text" name="name" id="new-person"
                       placeholder="<?php _e('Enter name...', 'WP-team-display') ?>" required/>
                <label><?php _e('Position', 'WP-team-display') ?> :</label><br/>
                <input type="text" name="position" id="job"
                       placeholder="<?php _e('Enter position…', 'WP-team-display') ?>"/>
                <br>
                <div id="socialInfo">
                    <label for="socialInfo"><?php _e('Social Networks Information', 'WP-team-display') ?>:</label><br/>
                    <ul>
                        <li>
                            <label for="socialInfoFacebook"><?php _e('Facebook', 'WP-team-display') ?></label>
                            <input type="text" id="socialInfoFacebook" name="socialInfoFacebook"
                                   placeholder="<?php _e('Facebook URL', 'WP-team-display') ?>">
                        </li>
                        <li>
                            <label for="socialInfoEmail"><?php _e('Email', 'WP-team-display') ?></label>
                            <input type="text" id="socialInfoEmail" name="socialInfoEmail"
                                   placeholder="<?php _e('Email URL', 'WP-team-display') ?>">
                        </li>
                        <li>
                            <label for="socialInfoIn"><?php _e('LinkedIn', 'WP-team-display') ?></label>
                            <input type="text" id="socialInfoIn" name="socialInfoIn"
                                   placeholder="<?php _e('LinkedIn URL', 'WP-team-display') ?>">
                        </li>
                        <li>
                            <label for="socialInfoTwitter"><?php _e('Twitter', 'WP-team-display') ?></label>
                            <input type="text" id="socialInfoTwitter" name="socialInfoTwitter"
                                   placeholder="<?php _e('Twitter URL', 'WP-team-display') ?>">
                        </li>
                    </ul>
                </div>
                <div>
                    <label for="description"><?php echo _e('Description', 'WP-team-display') ?>:</label><br/>
                    <textarea name="description" id="description" rows="10"></textarea>
                    <div class="disableTextEditor">
                        <label for="disableTextEditor"><?php echo _e('Disable editor', 'WP-team-display') ?></label>
                        <input id="disableTextEditor" type="checkbox" class="checkbox"/>
                    </div>
                </div>
                <input type="submit" class="button button-primary" id="submit-person"
                       value="<?php _e('Add person', 'WP-team-display') ?>"/>
            </form>
            <hr/>

        </div>
    </div>
    </div>
    <?php
}
		
function shortcodeMCE(){
add_filter('mce_external_plugins', "add_custom_tinymce_plugin");
add_filter('tiny_mce_before_init', "myformatTinyMCE" );
}

add_action('init', 'shortcodeMCE');

	


	//include the tinymce javascript plugin
    function add_custom_tinymce_plugin($plugin_array) {
       $plugin_array['teamchart'] = 	plugins_url('asset/js/editor_plugin.js', dirname(dirname(__FILE__)));
        return $plugin_array;
    }

	//include the css file to style the graphic that replaces the shortcode
    function myformatTinyMCE($in)
    {
        if (isset($in['content_css'])) {
            $in['content_css'] .= ",".plugins_url('asset/style/editor-style.css', dirname(dirname(__FILE__)));
        }
         return $in;
    }



	

if(!function_exists('teamchart_resize')){
    function teamchart_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {

        // Validate inputs.
        if ( ! $url || ( ! $width && ! $height ) ) return false;

        // Caipt'n, ready to hook.
        if ( true === $upscale ) add_filter( 'image_resize_dimensions', 'teamchart_upscale', 10, 6 );

        // Define upload path & dir.
        $upload_info = wp_upload_dir();
        $upload_dir = $upload_info['basedir'];
        $upload_url = $upload_info['baseurl'];

        $http_prefix = "http://";
        $https_prefix = "https://";

        /* if the $url scheme differs from $upload_url scheme, make them match
           if the schemes differe, images don't show up. */
        if(!strncmp($url,$https_prefix,strlen($https_prefix))){ //if url begins with https:// make $upload_url begin with https:// as well
            $upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
        }
        elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){ //if url begins with http:// make $upload_url begin with http:// as well
            $upload_url = str_replace($https_prefix,$http_prefix,$upload_url);
        }


        // Check if $img_url is local.
        if ( false === strpos( $url, $upload_url ) ) return false;

        // Define path of image.
        $rel_path = str_replace( $upload_url, '', $url );
        $img_path = $upload_dir . $rel_path;

        // Check if img path exists, and is an image indeed.
        if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) return false;

        // Get image info.
        $info = pathinfo( $img_path );
        $ext = $info['extension'];
        list( $orig_w, $orig_h ) = getimagesize( $img_path );

        // Get image size after cropping.
        $dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
        $dst_w = $dims[4];
        $dst_h = $dims[5];

        // Return the original image only if it exactly fits the needed measures.
        if ( ! $dims && ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
            $img_url = $url;
            $dst_w = $orig_w;
            $dst_h = $orig_h;
        } else {
            // Use this to check if cropped image already exists, so we can return that instead.
            $suffix = "{$dst_w}x{$dst_h}";
            $dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
            $destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

            if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
                // Can't resize, so return false saying that the action to do could not be processed as planned.
                return false;
            }
            // Else check if cache exists.
            elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
                $img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
            }
            // Else, we resize the image and return the new resized image url.
            else {

                // Note: This pre-3.5 fallback check will edited out in subsequent version.
                if ( function_exists( 'wp_get_image_editor' ) ) {

                    $editor = wp_get_image_editor( $img_path );

                    if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
                        return false;

                    $resized_file = $editor->save();

                    if ( ! is_wp_error( $resized_file ) ) {
                        $resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
                        $img_url = $upload_url . $resized_rel_path;
                    } else {
                        return false;
                    }

                } else {

                    $resized_img_path = image_resize( $img_path, $width, $height, $crop ); // Fallback foo.
                    if ( ! is_wp_error( $resized_img_path ) ) {
                        $resized_rel_path = str_replace( $upload_dir, '', $resized_img_path );
                        $img_url = $upload_url . $resized_rel_path;
                    } else {
                        return false;
                    }

                }

            }
        }

        // Okay, leave the ship.
        if ( true === $upscale ) remove_filter( 'image_resize_dimensions', 'teamchart_upscale' );

        // Return the output.
        if ( $single ) {
            // str return.
            $image = $img_url;
        } else {
            // array return.
            $image = array (
                0 => $img_url,
                1 => $dst_w,
                2 => $dst_h
            );
        }

        return $image;
    }
}


if(!function_exists('teamchart_upscale')){
    function teamchart_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
        if ( ! $crop ) return null; // Let the wordpress default function handle this.

        // Here is the point we allow to use larger image size than the original one.
        $aspect_ratio = $orig_w / $orig_h;
        $new_w = $dest_w;
        $new_h = $dest_h;

        if ( ! $new_w ) {
            $new_w = intval( $new_h * $aspect_ratio );
        }

        if ( ! $new_h ) {
            $new_h = intval( $new_w / $aspect_ratio );
        }

        $size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

        $crop_w = round( $new_w / $size_ratio );
        $crop_h = round( $new_h / $size_ratio );

        $s_x = floor( ( $orig_w - $crop_w ) / 2 );
        $s_y = floor( ( $orig_h - $crop_h ) / 2 );

        return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
    }
}

     




?>