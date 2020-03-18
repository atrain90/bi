<?php 

global $wpdb;
$table_name = $wpdb->prefix . "team_chart_person, ".$wpdb->prefix . "team_chart_assoc";
$myrows = $wpdb->get_results("SELECT p.socialInfoFacebook, p.socialInfoEmail, p.socialInfoIn, p.socialInfoTwitter, p.id, p.name, p.job, p.description, p.mediaid, a.parent, a.pos FROM ".$wpdb->prefix."team_chart_person p
INNER JOIN  ".$wpdb->prefix."team_chart_assoc a
ON p.id = a.idperson
 AND a.idchart = $id 
 ORDER BY a.parent ASC, a.pos ASC");

    /** get theme*/
    $themeName = $wpdb->get_var("SELECT theme FROM ".$wpdb->prefix."team_chart WHERE id=$id");
  $nbparent=0;
      foreach ($myrows as $row)
 	 {
 	 	if ($row->parent == -1){
 	 		// parents + enfants
 	 		 $trigparent[$nbparent]=count_children($myrows,$row->id);
 	 		 $nbparent++; 	 	 
 	 	
 	 	}
 	 }
// parents + enfants

/*

	--> Créer un LI vide
	--> $parentenfant

*/
	$parentenfant=false;
	$uikit="";
	

if (isset($trigparent[1]))
	$parentenfant=true;

$classresponsive=" ";
if ($responsivemode == "false"){
	$classresponsive="framemode";
	$uikit="<div id='uiteamchart'><a href='#zoom-in' class='zoomin'>+</a><a href='#zoom-out' class='zoomout'>-</a></div>"; 
}

/*
 * theme Lightbox
 */
if ($themeName == 4) {
    echo "$uikit<div id='teamchart-div' class='$prefixtheme $classresponsive'>
    <ul id='chart'>".buildChartLightBoxTheme($myrows,-1,0,0,$nbparent,$parentenfant, $themeName)."</ul></div>";

} else {
    echo "$uikit<div id='teamchart-div' class='$prefixtheme $classresponsive'>
    <ul id='chart'>".build_chart($myrows,-1,0,0,$nbparent,$parentenfant, $themeName)."</ul></div>";
}


?>
