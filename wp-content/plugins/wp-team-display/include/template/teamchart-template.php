<?php 


function build_chart($myrows,$parent=-1,$count,$largeur=0,$nbparent=1,$parentenfant=null)
{
    $result= "";
	
	  if ($parentenfant){
	  	$count=$nbparent." parents ";
	  	$nbparent=1;	  	
	  }
	  
	  if ($parent!=-1){	  	
	  	$result = "<ul";
	  	if($largeur>4)
	    	$result.=" class='bloc' ";
	    $result.= ">";	  
	  }
	
	  
	  if ($nbparent!=1)
	  		$result = "<ul id='first'>";
	  
	  foreach ($myrows as $row)
	  {
	  	
	  	// 1 seul parent > N enfant
	  	
	    if (($row->parent == $parent) && ($nbparent==1)){
	    	$urlimage=wp_get_attachment_image_src($row->mediaid,array(182,182));

	    	//Si l'image ne fait pas 182x182 > on resize
	    	if (!$urlimage[3]){
	    		//var_dump($urlimage);


           		 $image = wp_get_image_editor( $urlimage[0] );
					if ( ! is_wp_error( $image ) ) {
                        if (empty($post_metadata)) {
                            $post_metadata = array();
                        }
						//$image->update_size( ($size["width"]*3), ($size["height"]*3));
						$resize=$image->resize( 182, 182, true );
						// If resize FAIL
						if (is_wp_error($resize) == true) {
							$size=$image->get_size();
							if ($size["width"]>$size["height"])
								$origin=$size["height"];
							else
								$origin=$size["width"];
							$crop=$image->crop( 0, 0, $origin, $origin, 182, 182, false );
						}
						$sourceImgPath = get_attached_file($row->mediaid);

						$_filepath = generateFilename($sourceImgPath, 182, 182);
						$_filepath_info = pathinfo($_filepath);

						$image->save($_filepath);
						$_new_meta = array(

						'file'=>$_filepath_info['basename'],

						'width'=>182,

						'height'=>182);

						$_new_meta['crop'] = "cropped";


						if (isset($_imageSize->name)) {
                            $post_metadata['sizes'][$_imageSize->name] = $_new_meta;
                        }

						wp_update_attachment_metadata( $row->mediaid, $post_metadata);
						$urlimage=wp_get_attachment_image_src($row->mediaid,array(182,182));
					}

	    	}



	    	$urlimagefull=wp_get_attachment_image_src($row->mediaid,array(182,182));


	    	 	$result.= "<li class='col-$count wp-td-child";

	    	 	if($largeur>4)
	    	 	$result.=" bloc";

                    $result.= "'><div class='person'>
	    	 	  <div class='image'><img src='".$urlimage[0]."' alt='".htmlspecialchars(stripslashes(($row->name)),ENT_NOQUOTES)."' /></div>
	    	 	  <div class='imagefull' style='display:none;'> <img src='".$urlimagefull[0]."' alt='".htmlspecialchars(stripslashes(($row->name)),ENT_NOQUOTES)."' /></div>
	    	 	  <div class='text'><div class='name'><p> ".htmlspecialchars(stripslashes(($row->name)),ENT_NOQUOTES)." </p></div>
	    	 	  <div class='Job'> ".htmlspecialchars(stripslashes(($row->job)),ENT_NOQUOTES)."</div>
	    	 	  <div class='description' style='display:none;'> ".htmlspecialchars(stripslashes(apply_filters( 'the_content', $row->description )),ENT_NOQUOTES)."</div>
	    	 	  </div>
	    	 	  </div>
	    	 	  ";

	    	 
	      if (has_children($myrows,$row->id)){
	      	// $count= ;
	        $result.= build_chart($myrows,$row->id,count_children($myrows,$row->id),count_children($myrows,$row->id), $nbparent, $parentenfant);
	      }
	      $result.= "</li>";
	    }
	    
	    // N parent > 1 enfant
	    
	     if (($row->parent == $parent) && ($nbparent!=1)){

	    	$urlimage=wp_get_attachment_image_src($row->mediaid,array(182,182));	
	    	
	    	//Si l'image ne fait pas 182x182 > on resize	    	
	    	if (!$urlimage[3]){
	    		//var_dump($urlimage);
	    		
           	
           		 $image = wp_get_image_editor( $urlimage[0] );
						if ( ! is_wp_error( $image ) ) {
						//$image->update_size( ($size["width"]*3), ($size["height"]*3));
						$resize=$image->resize( 182, 182, true );							
						// If resize FAIL
						if (is_wp_error($resize) == true) {						
							$size=$image->get_size();	
							if ($size["width"]>$size["height"])
								$origin=$size["height"];
							else
								$origin=$size["width"];
							$crop=$image->crop( 0, 0, $origin, $origin, 182, 182, false );							
						}
						$sourceImgPath = get_attached_file($row->mediaid);
						$_filepath = generateFilename($sourceImgPath, 182, 182);
						$_filepath_info = pathinfo($_filepath);						
						$image->save($_filepath);												
						$_new_meta = array(
						'file'=>$_filepath_info['basename'],
						'width'=>182,
						'height'=>182);					
						$_new_meta['crop'] = "cropped";					
						$post_metadata['sizes'][$_imageSize->name] = $_new_meta;
						wp_update_attachment_metadata( $row->mediaid, $post_metadata);																							
						$urlimage=wp_get_attachment_image_src($row->mediaid,array(182,182));			
					}
	    	
	    	}

	    	
	    	$urlimagefull=wp_get_attachment_image_src($row->mediaid,array(182,182));
	    	 	$result.= "<li class='col-$nbparent wp-td-child";
	    	 	
	    	 	if($largeur>4)
	    	 	$result.=" bloc";


	    	    $result.= "'>	<div class='person'>
	    	 	  <div class='image'><img src='".$urlimage[0]."' alt='".htmlspecialchars(stripslashes(($row->name)),ENT_NOQUOTES)."' /></div>
	    	 	  <div class='imagefull' style='display:none;'> <img src='".$urlimagefull[0]."' alt='".htmlspecialchars(stripslashes(($row->name)),ENT_NOQUOTES)."' /></div>
	    	 	  <div class='text'><div class='name'><p> ".htmlspecialchars(stripslashes(($row->name)),ENT_NOQUOTES)." </p></div>
	    	 	  <div class='Job'> ".htmlspecialchars(stripslashes(($row->job)),ENT_NOQUOTES)."</div>
	    	 	  <div class='description' style='display:none;'> ".htmlspecialchars(stripslashes(apply_filters( 'the_content', $row->description )),ENT_NOQUOTES)."</div>
	    	 	  </div>
	    	 	  </div>
	    	 	  ";
	    	$result.= "</li>";
	    	
	    	  if (has_children($myrows,$row->id)){
	      	$parentorigin=$row->id;
			  }
	    	
	     }
	    
	  }
	
	  if(isset($parentorigin)){     
	      $result.= "</ul>";
		  $result.= build_chart($myrows,$parentorigin,"3",count_children($myrows,$row->id));
	  }
	  
	  if ($parent!=-1)
	  $result.= "</ul>";
	  return $result;
}





function count_children($myrows,$id) {
		  
		  $i=0;
		  foreach ($myrows as $row) {
		    if ($row->parent == $id)
		    $i++;		    
		  }
		  return $i;
}
	





?>