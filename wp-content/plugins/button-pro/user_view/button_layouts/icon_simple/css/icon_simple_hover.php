<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if($custom_data['icon_simple_effect']!='None'){
 if($custom_data['icon_simple_effect']=='wd_hvr_up'){
  ?> <style type="text/css">
  /*wd_hvr_up */
  #<?php echo $custom_data['icon_simple_effect'].'_'.$id;?> {
    transition: transform 0.2s linear!important;
  }

  #<?php echo $custom_data['icon_simple_effect'].'_'.$id;?>:hover {
    transform: translateY(-10px)!important;
  }
  </style>
  <?php
}elseif($custom_data['icon_simple_effect']=='wd_hvr_rotate'){
  ?> <style type="text/css">

  /*wd_hvr_rotate*/
  #<?php echo $custom_data['icon_simple_effect'].'_'.$id;?> {
    transition: transform 0.5s ease-in-out!important;
  }

  #<?php echo $custom_data['icon_simple_effect'].'_'.$id;?>:hover {
    transform: rotateX(180deg) rotateY(180deg) rotateZ(180deg)!important;
  }
  </style>
  <?php
}elseif($custom_data['icon_simple_effect']=='wd_hvr_scale'){
  ?> <style type="text/css">
  /*wd_hvr_scale*/
  #<?php echo $custom_data['icon_simple_effect'].'_'.$id;?>{
    transition: transform 0.2s ease-in-out 0!important;
  }

  #<?php echo $custom_data['icon_simple_effect'].'_'.$id;?>:hover {
    transform: rotateZ(27deg) scale(1.1)!important;
  }
  </style>
  <?php
} 



} ?>