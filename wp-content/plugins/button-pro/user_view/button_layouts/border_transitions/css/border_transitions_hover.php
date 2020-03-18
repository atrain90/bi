<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
/* BORDER TRANSITIONS */
if($custom_data['border_transitions']=='wd_hvr_border_fade'){
  ?><style type="text/css">
  /* Border Fade */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: box-shadow;
    transition-property: box-shadow;
    box-shadow: inset 0 0 0 <?php echo $custom_data['border_transitions_width'];?>px <?php echo $custom_data['button_bg_color_start'];?>, 0 0 1px rgba(0, 0, 0, 0);
    /* Hack to improve aliasing on mobile/tablet devices */
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active {
    box-shadow: inset 0 0 0 <?php echo $custom_data['border_transitions_width'];?>px <?php echo $custom_data['border_transitions_color'];?>, 0 0 1px rgba(0, 0, 0, 0);
    /* Hack to improve aliasing on mobile/tablet devices */
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_hollow'){
  ?><style type="text/css">

  /* Hollow */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: background;
    transition-property: background;
    box-shadow: inset 0 0 0 <?php echo $custom_data['border_transitions_width'];?>px <?php echo $custom_data['border_transitions_color'];?>, 0 0 1px rgba(0, 0, 0, 0) !important;
    /* Hack to improve aliasing on mobile/tablet devices */
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active {
    background: none!important;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_trim'){
  ?><style type="text/css">
  /* Trim */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: '';
    position: absolute;
    border: <?php echo $custom_data['border_transitions_width'];?>px solid <?php echo $custom_data['border_transitions_color'];?>!important;
    top: 4px;
    left: 4px;
    right: 4px;
    bottom: 4px;
    opacity: 0;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: opacity;
    transition-property: opacity;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    opacity: 1;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_ripple_out'){
  ?><style type="text/css">
  /* Ripple Out */
  @-webkit-keyframes <?php echo $custom_data['border_transitions'].'_'.$id;?> {
    100% {
      top: -12px;
      right: -12px;
      bottom: -12px;
      left: -12px;
      opacity: 0;
    }
  }

  @keyframes <?php echo $custom_data['border_transitions'].'_'.$id;?> {
    100% {
      top: -12px;
      right: -12px;
      bottom: -12px;
      left: -12px;
      opacity: 0;
    }
  }

  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: '';
    position: absolute;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    border: <?php echo $custom_data['border_transitions_width'];?>px solid <?php echo $custom_data['border_transitions_color'];?>!important;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    -webkit-animation-name: <?php echo $custom_data['border_transitions'].'_'.$id;?>;
    animation-name: <?php echo $custom_data['border_transitions'].'_'.$id;?>;
  }
  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_ripple_in'){
  ?><style type="text/css">
  /* Ripple In */
  @-webkit-keyframes <?php echo $custom_data['border_transitions'].'_'.$id;?> {
    100% {
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      opacity: 1;
    }
  }

  @keyframes <?php echo $custom_data['border_transitions'].'_'.$id;?> {
    100% {
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      opacity: 1;
    }
  }

  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);    
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: '';
    position: absolute;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    border: <?php echo $custom_data['border_transitions_width'];?>px solid <?php echo $custom_data['border_transitions_color'];?>!important;
    top: -12px;
    right: -12px;
    bottom: -12px;
    left: -12px;
    opacity: 0;
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    -webkit-animation-name: <?php echo $custom_data['border_transitions'].'_'.$id;?>;
    animation-name: <?php echo $custom_data['border_transitions'].'_'.$id;?>;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_outline_out'){
  ?><style type="text/css">
  /* Outline Out */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: '';
    position: absolute;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    border: <?php echo $custom_data['border_transitions_width'];?>px solid <?php echo $custom_data['border_transitions_color'];?>!important;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: top, right, bottom, left;
    transition-property: top, right, bottom, left;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    top: -8px;
    right: -8px;
    bottom: -8px;
    left: -8px;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_outline_in'){
  ?><style type="text/css">
  /* Outline In */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    pointer-events: none;
    content: '';
    position: absolute;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    border: <?php echo $custom_data['border_transitions_width'];?>px solid <?php echo $custom_data['border_transitions_color'];?>!important;
    top: -16px;
    right: -16px;
    bottom: -16px;
    left: -16px;
    opacity: 0;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: top, right, bottom, left;
    transition-property: top, right, bottom, left;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    top: -8px;
    right: -8px;
    bottom: -8px;
    left: -8px;
    opacity: 1;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_round_corners'){
  ?><style type="text/css">
  /* Round Corners */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: border-radius;
    transition-property: border-radius;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active {
    border-radius: 1em!important;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_underline_from_left'){
  ?><style type="text/css">
  /* Underline From Left */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 0;
    right: 100%;
    bottom: 0;
    background: <?php echo $custom_data['border_transitions_color'];?>;
    height: <?php echo $custom_data['border_transitions_width'];?>px;
    -webkit-transition-property: right;
    transition-property: right;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    right: 0;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_underline_from_center'){
  ?><style type="text/css">
  /* Underline From Center */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 50%;
    right: 50%;
    bottom: 0;
    background: <?php echo $custom_data['border_transitions_color'];?>;
    height: <?php echo $custom_data['border_transitions_width'];?>px;
    -webkit-transition-property: left, right;
    transition-property: left, right;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    left: 0;
    right: 0;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_underline_from_right'){
  ?><style type="text/css">
  /* Underline From Right */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 100%;
    right: 0;
    bottom: 0;
    background: <?php echo $custom_data['border_transitions_color'];?>;
    height: <?php echo $custom_data['border_transitions_width'];?>px;
    -webkit-transition-property: left;
    transition-property: left;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    left: 0;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_reveal'){
  ?><style type="text/css">
  /* Reveal */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
    border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
    border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
    border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
    border-color: <?php echo $custom_data['border_transitions_color'];?>;;
    border-style: solid;;
    border-width: 0;
    -webkit-transition-property: border-width;
    transition-property: border-width;
    -webkit-transition-duration: 0.1s;
    transition-duration: 0.1s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: translateY(0);
    transform: translateY(0);
    border-width:<?php echo $custom_data['border_transitions_width'];?>px;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_underline_reveal'){
  ?><style type="text/css">
  /* Underline Reveal */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 0;
    right: 0;
    bottom: 0;
    background: <?php echo $custom_data['border_transitions_color'];?>;
    height: <?php echo $custom_data['border_transitions_width'];?>px;
    -webkit-transform: translateY(4px);
    transform: translateY(4px);
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_overline_reveal'){
  ?><style type="text/css">
  /* Overline Reveal */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 0;
    right: 0;
    top: 0;
    background: <?php echo $custom_data['border_transitions_color'];?>;
    height: <?php echo $custom_data['border_transitions_width'];?>px;
    -webkit-transform: translateY(-4px);
    transform: translateY(-4px);
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }
  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_overline_from_left'){
  ?><style type="text/css">
  /* Overline From Left */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 0;
    right: 100%;
    top: 0;
    background: <?php echo $custom_data['border_transitions_color'];?>;
    height: <?php echo $custom_data['border_transitions_width'];?>px;
    -webkit-transition-property: right;
    transition-property: right;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    right: 0;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_overline_from_center'){
  ?><style type="text/css">
  /* Overline From Center */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 50%;
    right: 50%;
    top: 0;
    background: <?php echo $custom_data['border_transitions_color'];?>;
    height: <?php echo $custom_data['border_transitions_width'];?>px;
    -webkit-transition-property: left, right;
    transition-property: left, right;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    left: 0;
    right: 0;
  }

  </style> <?php
}else if($custom_data['border_transitions']=='wd_hvr_overline_from_right'){
  ?><style type="text/css">

  /* Overline From Right */
  .<?php echo $custom_data['border_transitions'].'_'.$id;?> {
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
   
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -moz-osx-font-smoothing: grayscale;
    position: relative;
    overflow: hidden;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:before {
    content: "";
    position: absolute;
    z-index: -1;
    left: 100%;
    right: 0;
    top: 0;
    background: <?php echo $custom_data['border_transitions_color'];?>;
    height: <?php echo $custom_data['border_transitions_width'];?>px;
    -webkit-transition-property: left;
    transition-property: left;
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  .<?php echo $custom_data['border_transitions'].'_'.$id;?>:hover:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:focus:before, .<?php echo $custom_data['border_transitions'].'_'.$id;?>:active:before {
    left: 0;
  }
  </style> <?php
}
?>