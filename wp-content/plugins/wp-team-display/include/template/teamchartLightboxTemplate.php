<?php
/**
 *
 * Light box theme template
 *
 */

function buildChartLightBoxTheme($myrows,$parent=-1,$count,$largeur=0,$nbparent=1,$parentenfant=null, $themeName=null)
{
    $result= "";

    foreach ($myrows as $row) {
        $urlimage = wp_get_attachment_image_src($row->mediaid, array(182, 182));

        //Si l'image ne fait pas 182x182 > on resize
        if (!$urlimage[3]) {
            //var_dump($urlimage);


            $image = wp_get_image_editor($urlimage[0]);
            if (!is_wp_error($image)) {
                //$image->update_size( ($size["width"]*3), ($size["height"]*3));
                $resize = $image->resize(182, 182, true);
                // If resize FAIL
                if (is_wp_error($resize) == true) {
                    $size = $image->get_size();
                    if ($size["width"] > $size["height"])
                        $origin = $size["height"];
                    else
                        $origin = $size["width"];
                    $crop = $image->crop(0, 0, $origin, $origin, 182, 182, false);
                }
                $sourceImgPath = get_attached_file($row->mediaid);

                $_filepath = generateFilename($sourceImgPath, 182, 182);
                $_filepath_info = pathinfo($_filepath);

                $image->save($_filepath);
                $_new_meta = array(

                    'file' => $_filepath_info['basename'],

                    'width' => 182,

                    'height' => 182);

                $_new_meta['crop'] = "cropped";
                $_imageSize = new \stdClass;
                $post_metadata['sizes'][$_imageSize->name] = $_new_meta;

                wp_update_attachment_metadata($row->mediaid, $post_metadata);
                $urlimage = wp_get_attachment_image_src($row->mediaid, array(182, 182));
            }

        }


        $urlimagefull = wp_get_attachment_image_src($row->mediaid, array(182, 182));


        $result .= "<li";

        if ($largeur > 4)
            $result .= " bloc";
        /** theme light box */
        if ($themeName == 4) {
            $facebookLink = "";
            $googlelink = "";
            $inLink = "";
            $twitterLink = "";
            if (isset($row->socialInfoFacebook) && !empty($row->socialInfoFacebook)) {
                $facebookLink = "<li><a target='_blank' href='" . $row->socialInfoFacebook . "'><i class='fa fa-facebook'></i></a></li>";
            }

            if (isset($row->socialInfoEmail) && !empty($row->socialInfoEmail)) {
                $googlelink = "<li><a target='_blank' href='" . $row->socialInfoEmail . "'><i class='fa fa-envelope-o'></i></a></li>";
            }

            if (isset($row->socialInfoIn) && !empty($row->socialInfoIn)) {
                $inLink = "<li><a target='_blank' href='" . $row->socialInfoIn . "'><i class='fa fa-linkedin'></i></a></li>";
            }

            if (isset($row->socialInfoTwitter) && !empty($row->socialInfoTwitter)) {
                $twitterLink = "<li><a target='_blank' href='" . $row->socialInfoTwitter . "'><i class='fa fa-twitter'></i></a></li>";
            }
            //$content = apply_filters( 'the_content', $row->description );
            $result .= "><div class='person'>
	    	 	  <div class='image'>
	    	 	  <img src='" . $urlimage[0] . "' alt='" . htmlspecialchars(stripslashes(($row->name)), ENT_NOQUOTES) . "' />
	    	 	  <div class='image-hover-overlay'></div>
	    	 	  </div>
	    	 	  <div class='imagefull' style='display:none;'> <img src='" . $urlimagefull[0] . "' alt='" . htmlspecialchars(stripslashes(($row->name)), ENT_NOQUOTES) . "' /></div>
	    	 	  <div class='text'>
	    	 	  <div class='name'><p> " . htmlspecialchars(stripslashes(($row->name)), ENT_NOQUOTES) . " </p></div>
	    	 	  <div class='Job'> " . htmlspecialchars(stripslashes(($row->job)), ENT_NOQUOTES) . "</div>
	    	 	  <div class='description' style='display:none;'> " . htmlspecialchars(stripslashes((apply_filters( 'the_content', $row->description ))), ENT_NOQUOTES) . "</div>
	    	 	  </div>
	    	 	  <ul class='socialIcon'>
	    	 	  " . $facebookLink . $googlelink . $inLink . $twitterLink . "
	    	 	  </ul>
	    	 	  </div>
	    	 	  ";

        }
    }
        $result.= "</ul>";
    return $result;
}