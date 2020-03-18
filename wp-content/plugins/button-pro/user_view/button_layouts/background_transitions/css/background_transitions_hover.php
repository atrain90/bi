<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
/* BACKGROUND TRANSITIONS */

if($custom_data['background_transitions']=='wd_hvr_fade'){
  ?> <style type="text/css">

  /* Fade */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    overflow: hidden;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: color, background-color;
    transition-property: color, background-color;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>))!important;
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>)!important;
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>)!important;
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>)!important;

  }
  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_back_pulse'){
  ?> <style type="text/css">
  /* Back Pulse */
  @-webkit-keyframes <?php echo $custom_data['background_transitions'].'_'.$id;?> {
    50% {
      background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
      background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
      background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
      background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    }
  }

  @keyframes <?php echo $custom_data['background_transitions'].'_'.$id;?> {
    50% {
     background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
     background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
     background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
     background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
   }
 }

 .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0)
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  overflow: hidden;
  -webkit-transition-duration: 0.5s;
  transition-duration: 0.5s;
  -webkit-transition-property: color, background-color;
  transition-property: color, background-color;
}
.<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
  -webkit-animation-name: <?php echo $custom_data['background_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['background_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-delay: 0.5s;
  animation-delay: 0.5s;
  -webkit-animation-timing-function: linear;
  animation-timing-function: linear;
  -webkit-animation-iteration-count: infinite;
  animation-iteration-count: infinite;

}

</style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_sweep_to_right'){
  ?> <style type="text/css">
  /* Sweep To Right */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);

    -webkit-transform: scaleX(0);
    transform: scaleX(0);
    -webkit-transform-origin: 0 50%;
    transform-origin: 0 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleX(1);
    transform: scaleX(1);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_sweep_to_left'){
  ?> <style type="text/css">
  /* Sweep To Left */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);

    -webkit-transform: scaleX(0);
    transform: scaleX(0);
    -webkit-transform-origin: 100% 50%;
    transform-origin: 100% 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleX(1);
    transform: scaleX(1);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_sweep_to_bottom'){
  ?> <style type="text/css">
  /* Sweep To Bottom */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
    -webkit-transform-origin: 50% 0;
    transform-origin: 50% 0;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_sweep_to_top'){
  ?> <style type="text/css">
  /* Sweep To Top */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
    -webkit-transform-origin: 50% 100%;
    transform-origin: 50% 100%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_bounce_to_right'){
  ?> <style type="text/css">
  /* Bounce To Right */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transform: scaleX(0);
    transform: scaleX(0);
    -webkit-transform-origin: 0 50%;
    transform-origin: 0 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleX(1);
    transform: scaleX(1);
    -webkit-transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
    transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_bounce_to_left'){
  ?> <style type="text/css">
  /* Bounce To Left */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transform: scaleX(0);
    transform: scaleX(0);
    -webkit-transform-origin: 100% 50%;
    transform-origin: 100% 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleX(1);
    transform: scaleX(1);
    -webkit-transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
    transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_bounce_to_bottom'){
  ?> <style type="text/css">
  /* Bounce To Bottom */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
    -webkit-transform-origin: 50% 0;
    transform-origin: 50% 0;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
    -webkit-transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
    transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_bounce_to_top'){
  ?> <style type="text/css">
  /* Bounce To Top */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
    -webkit-transform-origin: 50% 100%;
    transform-origin: 50% 100%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
    -webkit-transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
    transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_radial_out'){
  ?> <style type="text/css">
  /* Radial Out */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_start; ?>), color-stop(1, <?php echo $button_bg_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    border-radius: 100%;
    -webkit-transform: scale(0);
    transform: scale(0);
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scale(2);
    transform: scale(2);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_radial_in'){
  ?> <style type="text/css">
  /* Radial In */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);

    
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_start; ?>), color-stop(1, <?php echo $button_bg_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    border-radius: 100%;
    -webkit-transform: scale(2);
    transform: scale(2);
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scale(0);
    transform: scale(0);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_rectangle_in'){
  ?> <style type="text/css">
  /* Rectangle In */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_start; ?>), color-stop(1, <?php echo $button_bg_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    -webkit-transform: scale(1);
    transform: scale(1);
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scale(0);
    transform: scale(0);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_rectangle_out'){
  ?> <style type="text/css">
  /* Rectangle Out */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_start; ?>), color-stop(1, <?php echo $button_bg_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transform: scale(0);
    transform: scale(0);
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scale(1);
    transform: scale(1);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_shutter_in_horizontal'){
  ?> <style type="text/css">
  /* Shutter In Horizontal */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_start; ?>), color-stop(1, <?php echo $button_bg_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    -webkit-transform: scaleX(1);
    transform: scaleX(1);
    -webkit-transform-origin: 50%;
    transform-origin: 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleX(0);
    transform: scaleX(0);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_shutter_out_horizontal'){
  ?> <style type="text/css">
  /* Shutter Out Horizontal */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transform: scaleX(0);
    transform: scaleX(0);
    -webkit-transform-origin: 50%;
    transform-origin: 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleX(1);
    transform: scaleX(1);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_shutter_in_vertical'){
  ?> <style type="text/css">
  /* Shutter In Vertical */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_start; ?>), color-stop(1, <?php echo $button_bg_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
    -webkit-transform-origin: 50%;
    transform-origin: 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
  }

  </style><?php
}elseif($custom_data['background_transitions']=='wd_hvr_shutter_out_vertical'){
  ?> <style type="text/css">
  /* Shutter Out Vertical */
  .<?php echo $custom_data['background_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_start; ?>), color-stop(1, <?php echo $button_bg_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
    -webkit-transition-property: color;
    transition-property: color;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
    background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
    -webkit-transform: scaleY(0);
    transform: scaleY(0);
    -webkit-transform-origin: 50%;
    transform-origin: 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active {
    color: white;
  }
  .<?php echo $custom_data['background_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['background_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: scaleY(1);
    transform: scaleY(1);
  }

  </style><?php
}
?>
