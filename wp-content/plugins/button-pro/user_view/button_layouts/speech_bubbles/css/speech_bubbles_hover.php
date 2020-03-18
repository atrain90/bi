<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
/* SPEECH BUBBLES */
if($custom_data['speech_bubbles']=='wd_hvr_bubble_top'){ 
  ?> <style type="text/css">
/* Bubble Top */
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:before {
    pointer-events: none;
    position: absolute;
    z-index: -1;
    content: '';
    border-style: solid;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
    left: calc(50% - 10px);
    top: 0;
    border-width: 0 10px 10px 10px;
    border-color: transparent transparent <?php echo $custom_data['speech_bubbles_color'];?> transparent;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active:before {
    -webkit-transform: translateY(-10px);
    transform: translateY(-10px);
  }

  </style><?php 
} elseif($custom_data['speech_bubbles']=='wd_hvr_bubble_right'){
  ?> <style type="text/css">
/* Bubble Right */
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:before {
    pointer-events: none;
    position: absolute;
    z-index: -1;
    content: '';
    border-style: solid;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
    top: calc(50% - 10px);
    right: 0;
    border-width: 10px 0 10px 10px;
    border-color: transparent transparent transparent <?php echo $custom_data['speech_bubbles_color'];?>;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active:before {
    -webkit-transform: translateX(10px);
    transform: translateX(10px);
  }

  </style><?php 
}elseif($custom_data['speech_bubbles']=='wd_hvr_bubble_bottom'){
  ?> <style type="text/css">
/* Bubble Bottom */
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:before {
    pointer-events: none;
    position: absolute;
    z-index: -1;
    content: '';
    border-style: solid;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
    left: calc(50% - 10px);
    bottom: 0;
    border-width: 10px 10px 0 10px;
    border-color: <?php echo $custom_data['speech_bubbles_color'];?> transparent transparent transparent;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active:before {
    -webkit-transform: translateY(10px);
    transform: translateY(10px);
  }
  </style><?php 
}elseif($custom_data['speech_bubbles']=='wd_hvr_bubble_left'){
  ?> <style type="text/css">
/* Bubble Left */
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:before {
    pointer-events: none;
    position: absolute;
    z-index: -1;
    content: '';
    border-style: solid;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
    top: calc(50% - 10px);
    left: 0;
    border-width: 10px 10px 10px 0;
    border-color: transparent <?php echo $custom_data['speech_bubbles_color'];?> transparent transparent;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active:before {
    -webkit-transform: translateX(-10px);
    transform: translateX(-10px);
  }

  </style><?php 
}elseif($custom_data['speech_bubbles']=='wd_hvr_bubble_float_top'){
  ?> <style type="text/css">
/* Bubble Float Top */
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:before {
    position: absolute;
    z-index: -1;
    content: '';
    left: calc(50% - 10px);
    top: 0;
    border-style: solid;
    border-width: 0 10px 10px 10px;
    border-color: transparent transparent <?php echo $custom_data['speech_bubbles_color'];?> transparent;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active {
    -webkit-transform: translateY(10px);
    transform: translateY(10px);
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active:before {
    -webkit-transform: translateY(-10px);
    transform: translateY(-10px);
  }

  </style><?php 
}elseif($custom_data['speech_bubbles']=='wd_hvr_bubble_float_right'){
  ?> <style type="text/css">
/* Bubble Float Right */
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:before {
    position: absolute;
    z-index: -1;
    top: calc(50% - 10px);
    right: 0;
    content: '';
    border-style: solid;
    border-width: 10px 0 10px 10px;
    border-color: transparent transparent transparent <?php echo $custom_data['speech_bubbles_color'];?>;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active {
    -webkit-transform: translateX(-10px);
    transform: translateX(-10px);
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active:before {
    -webkit-transform: translateX(10px);
    transform: translateX(10px);
  }

  </style><?php 
}elseif($custom_data['speech_bubbles']=='wd_hvr_bubble_float_bottom'){
  ?> <style type="text/css">
/* Bubble Float Bottom */
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:before {
    position: absolute;
    z-index: -1;
    content: '';
    left: calc(50% - 10px);
    bottom: 0;
    border-style: solid;
    border-width: 10px 10px 0 10px;
    border-color: <?php echo $custom_data['speech_bubbles_color'];?> transparent transparent transparent;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active {
    -webkit-transform: translateY(-10px);
    transform: translateY(-10px);
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active:before {
    -webkit-transform: translateY(10px);
    transform: translateY(10px);
  }

  </style><?php 
}elseif($custom_data['speech_bubbles']=='wd_hvr_bubble_float_left'){
  ?> <style type="text/css">
/* Bubble Float Left */
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:before {
    position: absolute;
    z-index: -1;
    content: '';
    top: calc(50% - 10px);
    left: 0;
    border-style: solid;
    border-width: 10px 10px 10px 0;
    border-color: transparent <?php echo $custom_data['speech_bubbles_color'];?> transparent transparent;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active {
    -webkit-transform: translateX(10px);
    transform: translateX(10px);
  }
  .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:hover:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:focus:before, .<?php echo $custom_data['speech_bubbles'].'_'.$id;?>:active:before {
    -webkit-transform: translateX(-10px);
    transform: translateX(-10px);
  }
  </style><?php 
}
?>