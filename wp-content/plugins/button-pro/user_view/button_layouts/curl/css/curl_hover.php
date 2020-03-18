<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php
/* CURLS */ 
if($custom_data['curl']=='wd_hvr_curl_top_left'){
?> <style type="text/css">
/* Curl Top Left */
.<?php echo $custom_data['curl'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  position: relative;
}
.<?php echo $custom_data['curl'].'_'.$id;?>:before {
  pointer-events: none;
  position: absolute;
  content: '';
  height: 0;
  width: 0;
  top: 0;
  left: 0;
  background: none;
  /* IE9 */
  background: linear-gradient(135deg, white 45%, #aaaaaa 50%, #cccccc 56%, white 80%);
  filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#ffffff', endColorstr='#000000');
  /*For IE7-8-9*/
  z-index: 1000;
  box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.4);
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: width, height;
  transition-property: width, height;
}
.<?php echo $custom_data['curl'].'_'.$id;?>:hover:before, .<?php echo $custom_data['curl'].'_'.$id;?>:focus:before, .<?php echo $custom_data['curl'].'_'.$id;?>:active:before {
  width: 25px;
  height: 25px;
}

</style> <?php
}elseif($custom_data['curl']=='wd_hvr_curl_top_right'){
?> <style type="text/css">
/* Curl Top Right */
.<?php echo $custom_data['curl'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  position: relative;
}
.<?php echo $custom_data['curl'].'_'.$id;?>:before {
  pointer-events: none;
  position: absolute;
  content: '';
  height: 0;
  width: 0;
  top: 0;
  right: 0;
  background: white;
  /* IE9 */
  background: linear-gradient(225deg, white 45%, #aaaaaa 50%, #cccccc 56%, white 80%);
  box-shadow: -1px 1px 1px rgba(0, 0, 0, 0.4);
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: width, height;
  transition-property: width, height;
}
.<?php echo $custom_data['curl'].'_'.$id;?>:hover:before, .<?php echo $custom_data['curl'].'_'.$id;?>:focus:before, .<?php echo $custom_data['curl'].'_'.$id;?>:active:before {
  width: 25px;
  height: 25px;
}

</style> <?php
}elseif($custom_data['curl']=='wd_hvr_curl_bottom_right'){
?> <style type="text/css">
/* Curl Bottom Right */
.<?php echo $custom_data['curl'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  position: relative;
}
.<?php echo $custom_data['curl'].'_'.$id;?>:before {
  pointer-events: none;
  position: absolute;
  content: '';
  height: 0;
  width: 0;
  bottom: 0;
  right: 0;
  background: white;
  /* IE9 */
  background: linear-gradient(315deg, white 45%, #aaaaaa 50%, #cccccc 56%, white 80%);
  box-shadow: -1px -1px 1px rgba(0, 0, 0, 0.4);
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: width, height;
  transition-property: width, height;
}
.<?php echo $custom_data['curl'].'_'.$id;?>:hover:before, .<?php echo $custom_data['curl'].'_'.$id;?>:focus:before, .<?php echo $custom_data['curl'].'_'.$id;?>:active:before {
  width: 25px;
  height: 25px;
}

</style> <?php
}elseif($custom_data['curl']=='wd_hvr_curl_bottom_left'){
?> <style type="text/css">
/* Curl Bottom Left */
.<?php echo $custom_data['curl'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  position: relative;
}
.<?php echo $custom_data['curl'].'_'.$id;?>:before {
  pointer-events: none;
  position: absolute;
  content: '';
  height: 0;
  width: 0;
  bottom: 0;
  left: 0;
  background: white;
  /* IE9 */
  background: linear-gradient(45deg, white 45%, #aaaaaa 50%, #cccccc 56%, white 80%);
  box-shadow: 1px -1px 1px rgba(0, 0, 0, 0.4);
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: width, height;
  transition-property: width, height;
}
.<?php echo $custom_data['curl'].'_'.$id;?>:hover:before, .<?php echo $custom_data['curl'].'_'.$id;?>:focus:before, .<?php echo $custom_data['curl'].'_'.$id;?>:active:before {
  width: 25px;
  height: 25px;
}

</style> <?php
}
 ?>