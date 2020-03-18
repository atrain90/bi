<?php 



function iajax_save_function() {	

	global $post;

	
	if($_POST['type'] == 'save'){
	
		global $wpdb;
		$table_name = $wpdb->prefix . "team_chart";
		
		if(empty($_POST['chartid'])){
		echo "Error no chart select";
		
		}
		else {
			// on récupere l'id du chart
			$idchart=$_POST['chartid'];
			$table_name = $wpdb->prefix . "team_chart_person";
				
				$data=array();
				$data['name']=$_POST['persons']['name'];
				$data['job']=$_POST['persons']['job'];
				$data['description']=$_POST['persons']['description'];
				$data['mediaid']=$_POST['persons']['media-id'];
                $data['socialInfoFacebook']=$_POST['persons']['socialInfoFacebook'];
                $data['socialInfoEmail']=$_POST['persons']['socialInfoEmail'];
                $data['socialInfoIn']=$_POST['persons']['socialInfoIn'];
                $data['socialInfoTwitter']=$_POST['persons']['socialInfoTwitter'];

				if($wpdb->insert($table_name,$data)===FALSE){
					echo "Error insert person";
				}
				else {
					$idperson=$wpdb->insert_id;
					
					$data_assoc=array();
					$data_assoc['idchart']=$idchart;
					$data_assoc['idperson']=$idperson;					
					$table_name = $wpdb->prefix . "team_chart_assoc";	
									
					if($wpdb->insert($table_name,$data_assoc)===FALSE){
					echo "Error team chart assoc".$wpdb->show_errors();
					}
				}
					
			echo $idperson;					
		}		
		die;
	}
	if($_POST['type'] == 'updatepos'){
				
		global $wpdb;
		$table_name = $wpdb->prefix."team_chart_assoc";
		
			foreach ($_POST['persons'] as $person){
				if (isset($person['parent'])) {		
					$idperson=array( 'idperson' => $person['id'],'idchart' => $_POST['chartid'] );		
					$data=array();
					$data['parent']=$person['parent'];
					$data['pos']=$person['pos'];
					$wpdb->update($table_name,$data,$idperson);
					//echo "Error";
				
				}
			}
		die();		
	
	}
	
	if($_POST['type'] == 'delete'){
			
		global $wpdb;
		$table_name = $wpdb->prefix."team_chart_assoc";
		
			foreach ($_POST['persons'] as $person){
										
					$idperson=array( 'idperson' => $person['id'],'idchart' => $_POST['chartid'] );		

					if($wpdb->delete( $table_name, $idperson)===FALSE)
					echo "Error delete";

			}
		die();		
		
	}
	
	if($_POST['type'] == 'addperson'){
			
		global $wpdb;
		$data_assoc=array();
		$data_assoc['idchart']=$_POST['chartid'];
		$data_assoc['idperson']=$_POST['personid'];					
		$table_name = $wpdb->prefix."team_chart_assoc";
					
		if($wpdb->insert($table_name,$data_assoc)===FALSE){
		echo "Error team chart assoc".$wpdb->show_errors();
		}
		
		die();	
	}	
	
	if($_POST['type'] == 'updateperson'){
			
		global $wpdb;
		$table_name = $wpdb->prefix."team_chart_person";
		$idperson=array( 'id' => $_POST['persons']['id']);		
		$data=array();
		$data['name']=$_POST['persons']['name'];
		$data['job']=$_POST['persons']['job'];
		$data['description']=$_POST['persons']['description'];
		$data['mediaid']=$_POST['persons']['media-id'];
        $data['socialInfoFacebook']=$_POST['persons']['socialInfoFacebook'];
        $data['socialInfoEmail']=$_POST['persons']['socialInfoEmail'];
        $data['socialInfoIn']=$_POST['persons']['socialInfoIn'];
        $data['socialInfoTwitter']=$_POST['persons']['socialInfoTwitter'];

		if($wpdb->update($table_name,$data,$idperson)===FALSE)	
			echo "erreur update";
		die();	
	}	

}

add_action('wp_ajax_iajax_save', 'iajax_save_function');



function addajax_chart_function() {	
	global $post;

	if(isset($_POST['type']) && $_POST['type'] == 'addChart'){
		global $wpdb;
		$table_name = $wpdb->prefix . "team_chart";
		if($wpdb->insert($table_name,array('name'=>$_POST['chartname'],'theme'=>"1"))===FALSE){
		echo "Error";
		}
		else{
			$idchart=$wpdb->insert_id;
			teamchart_readChart($idchart);	
		}
	}
	else {
		if (isset($_POST['chartid']))
		teamchart_readChart($_POST['chartid']);
		else	
		teamchart_readChart();	
	}
}
add_action('wp_ajax_addajax_chart', 'addajax_chart_function');




function updatechart_function() {	
	global $post;
	
	if($_POST['type'] == 'updatechart'){
		
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . "team_chart";		
		
		
		$id=array( 'id' => $_POST['chartid'] );				
		
		
		$params = array();
		parse_str($_POST['name'], $params);
				
		$data=array();
		$data['name']=$params['name'];
			
		if($wpdb->update($table_name,$data,$id)===FALSE)	
			echo "erreur update";
			
		die();				
				
	}
}
add_action('wp_ajax_updatechart', 'updatechart_function');




function updatetheme_function() {	
	global $post;
	
	if($_POST['type'] == 'updatetheme'){
		
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . "team_chart";		
		
		
		$id=array( 'id' => $_POST['chartid'] );				
		
		
		$params = array('theme' => $_POST['theme'] );
	
				
		$data=array();
		$data['theme']=$params['theme'];
			
		if($wpdb->update($table_name,$data,$id)===FALSE)	
			echo "erreur update";
			
		die();				
				
	}
}
add_action('wp_ajax_updatetheme', 'updatetheme_function');






function deleteperson_function() {	
	global $post;
	if($_POST['type'] == 'deleteperson'){
		global $wpdb;
		$idperson=$_POST['idperson'];
		$table_name_person = $wpdb->prefix . "team_chart_person";
		
		if($myrows=$wpdb->delete( $table_name_person, array( 'id' => $idperson ) )===FALSE){
			echo 'delete person error';	
		}
		else {
			echo 'OK';
		}
		die();
			
	}
}
add_action('wp_ajax_deleteperson', 'deleteperson_function');






function deletechart_function() {	
	global $post;
	if($_POST['type'] == 'deletechart'){
		global $wpdb;
		$idchart=$_POST['chartid'];
		$table_name_assoc = $wpdb->prefix . "team_chart_assoc";
		$table_name = $wpdb->prefix . "team_chart";
		
		if($myrows=$wpdb->delete( $table_name_assoc, array( 'idchart' => $idchart ) )===FALSE){
		echo 'delete assoc error';	
		}
		else {
			if($myrows=$wpdb->delete( $table_name, array( 'id' => $idchart ) )===FALSE){
				echo 'delete chart error';	
			}
		}
		die();
			
	}
}
add_action('wp_ajax_deletechart', 'deletechart_function');




function teamchart_person_function() {	
	global $post;
	if($_POST['type'] == 'teamchart_person'){
		global $wpdb;
		//mysql_query("SET NAMES UTF8");
		$table_name_assoc = $wpdb->prefix . "team_chart_assoc";
		$table_name_person = $wpdb->prefix . "team_chart_person";
		$idchart=$_POST['chartid'];
		
		$myrows = $wpdb->get_results("
		
		SELECT * ,  ( SELECT COUNT(*) FROM $table_name_assoc WHERE idperson=id ) AS 'delete'
		FROM $table_name_person
		WHERE  NOT EXISTS ( SELECT * FROM $table_name_assoc 
		WHERE idchart = $idchart
		AND  $table_name_person.id=$table_name_assoc.idperson )
		
		
		
		 ");
		
		if (empty($myrows)) {
		echo __('No person found.','WP-team-display');
		}
		
		else {
		$result="<ul>";
		foreach($myrows as $row){
			
			if ($row->delete == 0)
				$delete="<span class='button-primary delete'></span>";
			else
				$delete='';
			
			$urlimage=wp_get_attachment_image_src($row->mediaid,array(182,182));
			
			//$image = teamchart_resize( $urlimage[0], 182, 182, true ); //resize & crop the image

			$result.="<li>";
			$result.="<a href='#person' class='person'
			data-id='".$row->id."' 
			data-name='".htmlentities(stripslashes(($row->name)),ENT_QUOTES)."' 
			data-job='".htmlentities(stripslashes(($row->job)),ENT_QUOTES)."' 
			data-mediaid='".$row->mediaid."' 
			data-mediaurl='".$urlimage[0]."'
			data-socialInfoFacebook='". htmlentities($row->socialInfoFacebook) ."'
			data-socialInfoEmail='". htmlentities($row->socialInfoEmail) ."'
			data-socialInfoIn='". htmlentities($row->socialInfoIn) ."'
			data-socialInfoTwitter='". htmlentities($row->socialInfoTwitter) ."'
			data-description='".htmlentities(stripslashes(($row->description)),ENT_QUOTES)."' >
			<img src='".$urlimage[0]."' />
			".htmlentities(stripslashes(($row->name)),ENT_QUOTES)." <span class='button'> ".__('add','WP-team-display')." </span>
			$delete
			</a> 
			";
			$result.="</li>";
				
		}
		echo stripslashes($result);
		}
			
		die();
			
	}
}
add_action('wp_ajax_teamchart_person', 'teamchart_person_function');




/**
* CropImage
**/


function cropimage_function() {	
	global $post;
	if($_POST['type'] == 'cropimage'){
			
		$urlimage=wp_get_attachment_image_src($_POST['idmedia'],"full");		
		echo "<span class='tips'>"._e( 'Click and drag on the image to select an area.', 'WP-team-display' )."</span>";
		echo "<img id='cropimage' src='".$urlimage[0]."'/><br/><br/>";
		echo "<a href='#savecrop' id='savecrop' class='button button-primary button-hero'>";
		_e( 'Save', 'WP-team-display' );
		echo "</a> ";
		echo " <a href='#closecrop' id='closecrop' class='button button-hero'>";
		_e( 'Close', 'WP-team-display' );
		echo "</a>";
		die();
			
	}
}
add_action('wp_ajax_cropimage', 'cropimage_function');



/**
* CropImage
**/


function croppingimage_function() {	
	global $post;
	if($_POST['type'] == 'croppingimage'){
		extract($_POST);	
		
		
	/*	
		$dest_file=wp_get_attachment_image_src($src,"cropped");	
		echo $dest_file[0];
		$cropped=wp_crop_image( $src, $src_x, $src_y, $src_w, $src_h, $dst_w, $dst_h,$dest_file[0],$dest_file[0]);
	*/
		
		
		
		
		/** get data **/

			$upload_dir = wp_upload_dir();

			$tmp_dir = $upload_dir['basedir']."/tmp/";

			
		
	    //from DB

			$dbImageSizes = "cropped";

			

			

			$sourceImgPath = get_attached_file( $src );

			$post_metadata = wp_get_attachment_metadata($src, true);//get the attachement metadata of the post
			$_changed_image_format = true;
		
			$_filepath = generateFilename($sourceImgPath, $dst_w, $dst_h);

			$_filepath_info = pathinfo($_filepath);

			$_tmp_filepath = $tmp_dir.$_filepath_info['basename'];

			//$this->addDebug("filename:".$_filepath);
			$_delete_old_file="";
			
			/*
			if (file_exist($_tmp_filepath))
				echo 'present !';
			*/
			$result=wp_crop_image( $src, $src_x, $src_y, $src_w, $src_h, $dst_w, $dst_h,$dest_file[0],$_tmp_filepath);
			$_error = false;
			/*
			echo $result."<br/>";
			echo $_filepath."<br/>";
			echo $_filepath_info['dirname'].'/'.$_delete_old_file."<br/>";
			*/
			
			if(!empty($_delete_old_file)) {

						@unlink($_filepath_info['dirname'].'/'.$_delete_old_file);

					}

					if(!@copy($result,$_filepath)) {

						$_error=true;

					}

					if(!@unlink($result)) {

						$_error=true;

					}
				
		if ($_error) {
		echo "erreur";
		}
		else {
					//update metadata --> otherwise new sizes will not be updated

					$_new_meta = array(

						'file'=>$_filepath_info['basename'],

						'width'=>intval($dst_w),

						'height'=>intval($dst_h));

					

						$_new_meta['crop'] = "cropped";

					

					$post_metadata['sizes'][$_imageSize->name] = $_new_meta;
					wp_update_attachment_metadata( $src, $post_metadata);
					
					
		}
		die();
			
	}
}
add_action('wp_ajax_croppingimage', 'croppingimage_function');




/**
* GraphDataBase
**/

function graphchart_function() {	
	global $post;
	if($_POST['type'] == 'graphchart'){
		global $wpdb; 
		
		$idchart=$_POST['chartid'];
			
		$table_name_assoc = $wpdb->prefix . "team_chart_assoc";
		$table_name_person = $wpdb->prefix . "team_chart_person";

	
		$myrows = $wpdb->get_results("SELECT p.id, p.socialInfoFacebook, p.socialInfoEmail, p.socialInfoIn, p.socialInfoTwitter, p.name, p.job, p.description, p.mediaid, a.parent, a.pos FROM $table_name_person p
		 INNER JOIN  $table_name_assoc a
		 ON p.id = a.idperson
	     AND a.idchart = $idchart 
	     ORDER BY a.parent ASC, a.pos ASC");
	     
	     $nbparent=0;
	      foreach ($myrows as $row)
	 	 {
	 	 	if ($row->parent == -1)
	 	 	 $nbparent++;
	 	 }
	     
	    	     
		echo build_menu($myrows,-1,$nbparent);
		die();
			
	}
}
add_action('wp_ajax_graphchart', 'graphchart_function');




/**
* Build tree 
**/

function build_menu($myrows,$parent=-1)
{
      $result = "";
	  if ($parent!=-1)
	  $result = "<ul>";
	 	  
	  foreach ($myrows as $row)
	  {
	  	$urlimage=wp_get_attachment_image_src($row->mediaid,array(182,182));
	  	/**
	  	* SINGLE DIRIGEANT
	  	**/
	  	
	    if (($row->parent == $parent)){
	    	 if ($parent==-1){
	    	 	$result.= "<li id='first'
	    	 	 data-id='".$row->id."'
	    	 	 data-name='".htmlentities(stripslashes(($row->name)),ENT_QUOTES)."'
	    	 	 data-job='".htmlentities(stripslashes(($row->job)),ENT_QUOTES)."'
	    	 	 data-description='".htmlentities(stripslashes(($row->description)),ENT_QUOTES)."'
	    	 	 data-socialInfoFacebook='". htmlentities($row->socialInfoFacebook) ."'
			     data-socialInfoEmail='". htmlentities($row->socialInfoEmail) ."'
			     data-socialInfoIn='". htmlentities($row->socialInfoIn) ."'
			     data-socialInfoTwitter='". htmlentities($row->socialInfoTwitter) ."'
	    	 	 data-mediaid='".$row->mediaid."'
	    	 	 data-mediaurl='".$urlimage[0]."'
	    	 	 data-parent='".$row->parent."'
	    	 	 data-pos='".$row->pos."'
	    	 	 >".htmlentities(stripslashes(($row->name)),ENT_QUOTES)."<div class='imgnode'><img src='".$urlimage[0]."'/></div>";
	    	 }
	    	 else {
	    	 	$result.= "<li data-id='".$row->id."'
	    	 	 data-name='".htmlentities(stripslashes(($row->name)),ENT_QUOTES)."'
	    	 	 data-job='".htmlentities(stripslashes(($row->job)),ENT_QUOTES)."'
	    	 	 data-description='".htmlentities(stripslashes(($row->description)),ENT_QUOTES)."'
	    	 	 data-socialInfoFacebook='". htmlentities($row->socialInfoFacebook) ."'
			     data-socialInfoEmail='". htmlentities($row->socialInfoEmail) ."'
			     data-socialInfoIn='". htmlentities($row->socialInfoIn) ."'
			     data-socialInfoTwitter='". htmlentities($row->socialInfoTwitter) ."'
	    	 	 data-mediaid='".$row->mediaid."'
	    	 	 data-mediaurl='".$urlimage[0]."'
	    	 	 data-parent='".$row->parent."'
	    	 	 data-pos='".$row->pos."'>".htmlentities(stripslashes(($row->name)),ENT_QUOTES)."<div class='imgnode'><img src='".$urlimage[0]."'/></div>";
	    	 }
	      if (has_children($myrows,$row->id))
	        $result.= build_menu($myrows,$row->id);
	      $result.= "</li>";
	    }

	  	
	    
	  }			
	  if ($parent!=-1)
	  $result.= "</ul>";
		  	  	
	  return $result;
}



function has_children($myrows,$id) {
		  foreach ($myrows as $row) {
		    if ($row->parent == $id)
		      return true;
		  }
		  return false;
}
	
	
	
	
	
	
	
/**
* BUILD CHART FRONTEND
**/	
	
	
/**
* Build tree 
**/

	
	
	
	
	
	
	
	

/**
* Read Chart
**/

function teamchart_readChart($newchart=false){
	
	global $wpdb;
	$table_name = $wpdb->prefix . "team_chart";
	$myrows = $wpdb->get_results( "SELECT id, name,theme FROM ".$table_name);
	if (empty($myrows)) {
		echo __('No chart found.','WP-team-display');
	}
	else {
		foreach ($myrows as $result){
			if ($newchart==$result->id)
				echo "<li><a href='#".$result->id."' class='button button-primary tc-element-button' data-id='".$result->id."' data-theme='".$result->theme."'>".htmlentities(stripslashes(($result->name)),ENT_QUOTES)."
				<span class='edit'></span><span class='delete'></span></a></li>";
			else
				echo "<li><a href='#".$result->id."' class='button tc-element-button' data-id='".$result->id."' data-theme='".$result->theme."'>".htmlentities(stripslashes(($result->name)),ENT_QUOTES)."
                <span class='edit'></span><span class='delete'></span></a></li>";
		}
	}
	die();
}







?>