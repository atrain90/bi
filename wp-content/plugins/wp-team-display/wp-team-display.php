<?php
/*
Plugin Name: WP Team Display
Plugin URI: http://www.joomunited.com/wordpress-products/wp-team-display
Description: Plugin WP Team Display, generate customized flaw chart easily.
Text Domain: WP-team-display
Domain Path: /locale
Author: JoomUnited
Version: 1.3.9
Author URI: http://www.joomunited.com/
*/



require_once(plugin_dir_path(__FILE__)."/include/admin/teamchart-admin.php");
require_once(plugin_dir_path(__FILE__)."/include/template/teamchart-template.php");
require_once(plugin_dir_path(__FILE__)."/include/template/teamchartLightboxTemplate.php");
require_once(plugin_dir_path(__FILE__)."/include/admin/teamchart-admin-ajax.php");

           

global $team_chart_pro_version;
$team_chart_pro_version = "1.3.9";

function team_chart_install() {
	
	
	if ( is_plugin_active('team-chart/teamchart-free.php') ) {
		 add_action('update_option_active_plugins', 'deactivate_free');
    }
	else {
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   
   global $wpdb;
   global $team_chart_pro_version;
   
	// Database PERSON
   $table_name_person = $wpdb->prefix . "team_chart_person";
      
   $sql = "CREATE TABLE $table_name_person (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  name tinytext NOT NULL,
  job tinytext NOT NULL,
  description text NULL,
  mediaid VARCHAR(150) DEFAULT '' NOT NULL,
  socialInfoFacebook text NULL ,
  socialInfoEmail text NULL ,
  socialInfoIn text NULL ,
  socialInfoTwitter text NULL ,
  UNIQUE KEY id (id)
    )  CHARACTER SET utf8;";
   dbDelta( $sql );
   
   
   // Database CHART
   $table_name_chart = $wpdb->prefix . "team_chart";      
   $sql = "CREATE TABLE $table_name_chart (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  name tinytext NOT NULL,
  theme mediumint(9) DEFAULT '1' NULL,
  UNIQUE KEY id (id)
    )  CHARACTER SET utf8;";
   dbDelta( $sql );
   
   // Database CHART-PERSON
   $table_name_assoc = $wpdb->prefix . "team_chart_assoc";
     
  $sql = "CREATE TABLE $table_name_assoc (
  idchart mediumint(9) NOT NULL,
  idperson mediumint(9) NOT NULL,
  parent mediumint(9) DEFAULT '-1' NULL,
  pos mediumint(9) NULL,
  UNIQUE KEY id_chart_person (idchart , idperson)
    )  CHARACTER SET utf8;";
   dbDelta( $sql );
   
 
   add_option( "team_chart_pro_version", $team_chart_pro_version );
	}
}


register_activation_hook( __FILE__, 'team_chart_install' );



function team_chart_uninstall() {

}

register_deactivation_hook( __FILE__ , 'team_chart_uninstall' );




function deactivate_free(){
    $dependent = 'team-chart/teamchart-free.php';
    deactivate_plugins($dependent);
}




function generateFilename( $file, $w, $h ){
        $info = pathinfo($file);
        $dir = $info['dirname'];
        $ext = $info['extension'];
        $name = wp_basename($file, ".$ext");
        $suffix = "{$w}x{$h}";
        $destfilename = "{$dir}/{$name}-{$suffix}.{$ext}";
        
        return $destfilename;
}


/**
 *  Trad function
 */
 
add_action('init', 'teamchart_trad');
function teamchart_trad()
{
	load_plugin_textdomain('WP-team-display', false, dirname ( plugin_basename( __FILE__ ))."/locale/");
}





/**
 *  Shortcode Frontend
 */
function teamchart_shortcode( $atts ) {
	
	extract( shortcode_atts( array(
		'id' => 'teamchart',
		'responsivemode' => '',
	), $atts ) );

	ob_start();

	
	/*
		1. Repertorié les styles
		2. Charger les CSS requis
		3. Ajouter un param "theme" à chaque chart	
	*/
	
	$classtheme="defaut";
	
	global $wpdb;
	
	$table_name = $wpdb->prefix . "team_chart";
	$classtheme = $wpdb->get_row( "SELECT theme FROM ".$table_name." WHERE id=".$id);

	switch($classtheme->theme){
	 case '1':$prefixtheme="default";break;	
	 case '2':$prefixtheme="circle";break;	
	 case '3':$prefixtheme="nature";break;
     case '4':$prefixtheme = "lightbox-flat"; break;

	}
	
	switch($classtheme->theme){
	 case '1':wp_enqueue_style( 'theme-default', plugins_url('wp-team-display/asset/style/theme-default.css', dirname(__FILE__)) );break;	
	 case '2':wp_enqueue_style( 'theme-circle', plugins_url('wp-team-display/asset/style/theme-circle.css', dirname(__FILE__)) );break;	
	 case '3':wp_enqueue_style( 'theme-nature', plugins_url('wp-team-display/asset/style/theme-nature.css', dirname(__FILE__)) );break;
     case '4':wp_enqueue_style( 'theme-lightbox', plugins_url('wp-team-display/asset/style/theme-lightbox.css', dirname(__FILE__)) );break;

	}
	
	
	
	
	
		
	// Ajout script
	
	wp_enqueue_style( 'fancybox-css', plugins_url('wp-team-display/asset/style/jquery.fancybox.css?v=2.1.5', dirname(__FILE__)) );
	wp_enqueue_style( 'font-awesome-css', plugins_url('wp-team-display/asset/style/font-awesome.min.css?v=4.4.0', dirname(__FILE__)) );
	wp_enqueue_script( 'fancybox-js', plugins_url('wp-team-display/asset/js/jquery.fancybox.pack.js?v=2.1.5', dirname(__FILE__)),array('jquery'),"2.1.5",true);
	add_action('wp_footer','fancybox_script');
	
	// Read Chart
	
	
	include(plugin_dir_path(__FILE__)."/include/template/default/teamchart-theme-default.php");

	// save and return the content that has been output

$content = ob_get_clean();
return $content;
}
add_shortcode( 'teamchart', 'teamchart_shortcode' );




function fancybox_script() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		
		
		var zoom=1;
		jQuery('#uiteamchart .zoomin').click(function(e){
			e.preventDefault();
			zoom=zoom+0.1;
			jQuery(this).parent().next("#teamchart-div").find("#chart").css("-moz-transform","scale("+(zoom)+")");
			jQuery(this).parent().next("#teamchart-div").find("#chart").css("zoom",zoom);
		});
		
		jQuery('#uiteamchart .zoomout').click(function(e){
			e.preventDefault();
			zoom=zoom-0.1;
			jQuery(this).parent().next("#teamchart-div").find("#chart").css("-moz-transform","scale("+(zoom)+")");
			jQuery(this).parent().next("#teamchart-div").find("#chart").css("zoom",zoom);
			
		});
		jQuery('#teamchart-div.framemode').scrollTo( '30%', {axis: 'x'} );
		jQuery('#teamchart-div.framemode').dragscrollable();

        // theme light box
        if (jQuery("div#teamchart-div").attr('class') === "lightbox-flat  "){
            jQuery(window).load(function() {
                var arrayHeightImage = [];
                jQuery("div#teamchart-div .person .image").each(function() {
                        var imageHeight = jQuery(this).height();
                        arrayHeightImage.push(imageHeight);
                    }
                );
                var imageHeightImageMin = Math.min.apply(Math, arrayHeightImage);
                jQuery("div#teamchart-div .person .image").each(function() {
                    jQuery(this).height(imageHeightImageMin);
                });
            });

            jQuery('#teamchart-div .person .image, #teamchart-div .person .text').click(function() {


                var obj = jQuery(this).parent();
                var classtheme = obj.parentsUntil("#teamchart-div").parent().attr('class');
                var name = obj.find(".name").children("p").html();
                var job = obj.find(".Job").html();
                var photo = obj.find(".imagefull").html();
                var decoded = jQuery("<div/>").html(obj.find(".description").html()).text();
                jQuery.fancybox(
                    "<div class='person-fancybox "+classtheme+"'><div class='photo'>"+photo+"</div><div class='text'><h2>"+name+"</h2><h4>"+job+"</h4><div class='teamchartdescription'>"+decoded+"</div></div></div>"
                    ,
                    {
                        maxWidth	: 600,
                        maxHeight	: 250,
                        wrapCSS		: classtheme,
                        fitToView	: false,
                        width		: "80%",
                        height		: "90%",
                        autoSize	: false,
                        closeClick	: false,
                        openEffect	: "none",
                        closeEffect	: "none",
                        'onComplete': function() {
                            $("#fancybox-wrap").addClass(classtheme);
                            $(".fancybox-overlay").addClass(classtheme);

                        }
                    }
                );

                return false;

            });
        } else {
            jQuery('#teamchart-div .person').click(function(){


                var obj = jQuery(this);
                var classtheme = obj.parentsUntil("#teamchart-div").parent().attr('class');
                var name = obj.find(".name").children("p").html();
                var job = obj.find(".Job").html();
                var photo = obj.find(".imagefull").html();
                var decoded = jQuery("<div/>").html(obj.find(".description").html()).text();
                jQuery.fancybox(
                    "<div class='person-fancybox "+classtheme+"'><div class='photo'>"+photo+"</div><div class='text'><h2>"+name+"</h2><h4>"+job+"</h4><div class='teamchartdescription'>"+decoded+"</div></div></div>"
                    ,
                    {
                        maxWidth	: 600,
                        maxHeight	: 250,
                        wrapCSS		: classtheme,
                        fitToView	: false,
                        width		: "80%",
                        height		: "90%",
                        autoSize	: false,
                        closeClick	: false,
                        openEffect	: "none",
                        closeEffect	: "none",
                        'onComplete': function() {
                            $("#fancybox-wrap").addClass(classtheme);
                            $(".fancybox-overlay").addClass(classtheme);

                        }
                    }
                );
                if ((jQuery("div.photo").length) &&(jQuery("div.fancybox-outer").length)) {
                    var imageHeight = jQuery("div.photo").height();
                    var fancyboxOuterHeight = jQuery("div.fancybox-outer").height();

                    if (imageHeight > fancyboxOuterHeight) {
                        jQuery("div.teamchartdescription").css("overflow-y","visible");
                    }
                }


                return false;

            });
        }



	});
	
	
	</script>
	
	<?php

		
	
}

if(is_admin()){
	//config section        
	if(!defined('JU_BASE')){
		define( 'JU_BASE', 'https://www.joomunited.com/' );
	}
	
	$remote_updateinfo =   JU_BASE.'juupdater_files/wp-team-display.json';
	 //end config
	
	require 'juupdater/juupdater.php';
	$UpdateChecker = Jufactory::buildUpdateChecker(
		   $remote_updateinfo,
			__FILE__
	);

    //Check plugin requirements
    if (version_compare(PHP_VERSION, '5.3', '<')) {
        if( !function_exists('wptd_disable_plugin') ){
            function wptd_disable_plugin(){
                if ( current_user_can('activate_plugins') && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
                    deactivate_plugins( __FILE__ );
                    unset( $_GET['activate'] );
                }
            }
        }

        if( !function_exists('wptd_show_error') ){
            function wptd_show_error(){
                echo '<div class="error"><p><strong>WP Team Display</strong> need at least PHP 5.3 version, please update php before installing the plugin.</p></div>';
            }
        }

        //Add actions
        add_action( 'admin_init', 'wptd_disable_plugin' );
        add_action( 'admin_notices', 'wptd_show_error' );

        //Do not load anything more
        return;
    }

    //Include the jutranslation helpers
    include_once('jutranslation' . DIRECTORY_SEPARATOR . 'jutranslation.php');
    \Joomunited\WPTeamDisplay\Jutranslation\Jutranslation::init(__FILE__, 'wp-team-display', 'WP Team Display', 'WP-team-display', 'locale' . DIRECTORY_SEPARATOR . 'WP-team-display-en_US.mo');


    function wptd_settings_page()
    {
        add_options_page(
            __('WP Team Display', 'WP-team-display'),
            __('WP Team Display', 'WP-team-display'),
            'manage_options',
            'wptd-settings',
            'wptd_settings_page_html'
        );
    }
    add_action('admin_menu', 'wptd_settings_page');
}


function wptd_settings_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    echo \Joomunited\WPTeamDisplay\Jutranslation\Jutranslation::getInput();

}
?>