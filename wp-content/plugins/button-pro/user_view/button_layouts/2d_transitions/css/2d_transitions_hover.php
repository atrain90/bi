<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 

if(isset($_POST['hover_class'])){
  $custom_data['2d_transitions']=$_POST['hover_class'];
  $id='';
  $hover="";
  $focus="";
  $active="";
}else{
  $hover=":hover";
  $focus=":focus";
  $active=":active";
}

if($custom_data['2d_transitions']=='wd_hvr_grow'){
?><style type="text/css">
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: transform;
  transition-property: transform;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_shrink'){
?><style type="text/css">
/* Shrink */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: transform;
  transition-property: transform;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: scale(0.9);
  transform: scale(0.9);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_pulse'){
?><style type="text/css">
/* Pulse */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  25% {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }

  75% {
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  25% {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }

  75% {
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-timing-function: linear;
  animation-timing-function: linear;
  -webkit-animation-iteration-count: infinite;
  animation-iteration-count: infinite;
}
</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_pulse_grow'){
?><style type="text/css">
/* Pulse Grow */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  to {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  to {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 0.3s;
  animation-duration: 0.3s;
  -webkit-animation-timing-function: linear;
  animation-timing-function: linear;
  -webkit-animation-iteration-count: infinite;
  animation-iteration-count: infinite;
  -webkit-animation-direction: alternate;
  animation-direction: alternate;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_pulse_shrink'){
?><style type="text/css">
/* Pulse Shrink */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  to {
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  to {
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 0.3s;
  animation-duration: 0.3s;
  -webkit-animation-timing-function: linear;
  animation-timing-function: linear;
  -webkit-animation-iteration-count: infinite;
  animation-iteration-count: infinite;
  -webkit-animation-direction: alternate;
  animation-direction: alternate;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_push'){
?><style type="text/css">
/* Push */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  50% {
    -webkit-transform: scale(0.8);
    transform: scale(0.8);
  }

  100% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  50% {
    -webkit-transform: scale(0.8);
    transform: scale(0.8);
  }

  100% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 0.3s;
  animation-duration: 0.3s;
  -webkit-animation-timing-function: linear;
  animation-timing-function: linear;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_pop'){
?><style type="text/css">
/* Pop */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  50% {
    -webkit-transform: scale(1.2);
    transform: scale(1.2);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  50% {
    -webkit-transform: scale(1.2);
    transform: scale(1.2);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 0.3s;
  animation-duration: 0.3s;
  -webkit-animation-timing-function: linear;
  animation-timing-function: linear;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_bounce_in'){
?><style type="text/css">
/* Bounce In */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.5s;
  transition-duration: 0.5s;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: scale(1.2);
  transform: scale(1.2);
  -webkit-transition-timing-function: cubic-bezier(0.47, 2.02, 0.31, -0.36);
  transition-timing-function: cubic-bezier(0.47, 2.02, 0.31, -0.36);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_bounce_out'){
?><style type="text/css">
/* Bounce Out */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.5s;
  transition-duration: 0.5s;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: scale(0.8);
  transform: scale(0.8);
  -webkit-transition-timing-function: cubic-bezier(0.47, 2.02, 0.31, -0.36);
  transition-timing-function: cubic-bezier(0.47, 2.02, 0.31, -0.36);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_rotate'){
?><style type="text/css">
/* Rotate */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: transform;
  transition-property: transform;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: rotate(4deg);
  transform: rotate(4deg);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_grow_rotate'){
?><style type="text/css">

/* Grow Rotate */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: transform;
  transition-property: transform;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: scale(1.1) rotate(4deg);
  transform: scale(1.1) rotate(4deg);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_float'){
?><style type="text/css">
/* Float */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: transform;
  transition-property: transform;
  -webkit-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: translateY(-8px);
  transform: translateY(-8px);
}
</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_sink'){
?><style type="text/css">
/* Sink */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: transform;
  transition-property: transform;
  -webkit-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: translateY(8px);
  transform: translateY(8px);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_bob'){
?><style type="text/css">
/* Bob */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  0% {
    -webkit-transform: translateY(-8px);
    transform: translateY(-8px);
  }

  50% {
    -webkit-transform: translateY(-4px);
    transform: translateY(-4px);
  }

  100% {
    -webkit-transform: translateY(-8px);
    transform: translateY(-8px);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  0% {
    -webkit-transform: translateY(-8px);
    transform: translateY(-8px);
  }

  50% {
    -webkit-transform: translateY(-4px);
    transform: translateY(-4px);
  }

  100% {
    -webkit-transform: translateY(-8px);
    transform: translateY(-8px);
  }
}

@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?>-float {
  100% {
    -webkit-transform: translateY(-8px);
    transform: translateY(-8px);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?>-float {
  100% {
    -webkit-transform: translateY(-8px);
    transform: translateY(-8px);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>-float, <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>-float, <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: .3s, 1.5s;
  animation-duration: .3s, 1.5s;
  -webkit-animation-delay: 0s, .3s;
  animation-delay: 0s, .3s;
  -webkit-animation-timing-function: ease-out, ease-in-out;
  animation-timing-function: ease-out, ease-in-out;
  -webkit-animation-iteration-count: 1, infinite;
  animation-iteration-count: 1, infinite;
  -webkit-animation-fill-mode: forwards;
  animation-fill-mode: forwards;
  -webkit-animation-direction: normal, alternate;
  animation-direction: normal, alternate;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_hang'){
?><style type="text/css">

/* Hang */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  0% {
    -webkit-transform: translateY(8px);
    transform: translateY(8px);
  }

  50% {
    -webkit-transform: translateY(4px);
    transform: translateY(4px);
  }

  100% {
    -webkit-transform: translateY(8px);
    transform: translateY(8px);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  0% {
    -webkit-transform: translateY(8px);
    transform: translateY(8px);
  }

  50% {
    -webkit-transform: translateY(4px);
    transform: translateY(4px);
  }

  100% {
    -webkit-transform: translateY(8px);
    transform: translateY(8px);
  }
}

@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?>-sink {
  100% {
    -webkit-transform: translateY(8px);
    transform: translateY(8px);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?>-sink {
  100% {
    -webkit-transform: translateY(8px);
    transform: translateY(8px);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>-sink, <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>-sink, <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: .3s, 1.5s;
  animation-duration: .3s, 1.5s;
  -webkit-animation-delay: 0s, .3s;
  animation-delay: 0s, .3s;
  -webkit-animation-timing-function: ease-out, ease-in-out;
  animation-timing-function: ease-out, ease-in-out;
  -webkit-animation-iteration-count: 1, infinite;
  animation-iteration-count: 1, infinite;
  -webkit-animation-fill-mode: forwards;
  animation-fill-mode: forwards;
  -webkit-animation-direction: normal, alternate;
  animation-direction: normal, alternate;
}
</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_skew'){
?><style type="text/css">
/* Skew */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: transform;
  transition-property: transform;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: skew(-10deg);
  transform: skew(-10deg);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_skew_forward'){
?><style type="text/css">
/* Skew Forward */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: transform;
  transition-property: transform;
  -webkit-transform-origin: 0 100%;
  transform-origin: 0 100%;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: skew(-10deg);
  transform: skew(-10deg);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_skew_backward'){
?><style type="text/css">
/* Skew Backward */
.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-property: transform;
  transition-property: transform;
  -webkit-transform-origin: 0 100%;
  transform-origin: 0 100%;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-transform: skew(10deg);
  transform: skew(10deg);
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_wobble_horizontal'){
?><style type="text/css">
/* Wobble Horizontal */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: translateX(8px);
    transform: translateX(8px);
  }

  33.3% {
    -webkit-transform: translateX(-6px);
    transform: translateX(-6px);
  }

  49.95% {
    -webkit-transform: translateX(4px);
    transform: translateX(4px);
  }

  66.6% {
    -webkit-transform: translateX(-2px);
    transform: translateX(-2px);
  }

  83.25% {
    -webkit-transform: translateX(1px);
    transform: translateX(1px);
  }

  100% {
    -webkit-transform: translateX(0);
    transform: translateX(0);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: translateX(8px);
    transform: translateX(8px);
  }

  33.3% {
    -webkit-transform: translateX(-6px);
    transform: translateX(-6px);
  }

  49.95% {
    -webkit-transform: translateX(4px);
    transform: translateX(4px);
  }

  66.6% {
    -webkit-transform: translateX(-2px);
    transform: translateX(-2px);
  }

  83.25% {
    -webkit-transform: translateX(1px);
    transform: translateX(1px);
  }

  100% {
    -webkit-transform: translateX(0);
    transform: translateX(0);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-timing-function: ease-in-out;
  animation-timing-function: ease-in-out;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_wobble_vertical'){
?><style type="text/css">
/* Wobble Vertical */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: translateY(8px);
    transform: translateY(8px);
  }

  33.3% {
    -webkit-transform: translateY(-6px);
    transform: translateY(-6px);
  }

  49.95% {
    -webkit-transform: translateY(4px);
    transform: translateY(4px);
  }

  66.6% {
    -webkit-transform: translateY(-2px);
    transform: translateY(-2px);
  }

  83.25% {
    -webkit-transform: translateY(1px);
    transform: translateY(1px);
  }

  100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: translateY(8px);
    transform: translateY(8px);
  }

  33.3% {
    -webkit-transform: translateY(-6px);
    transform: translateY(-6px);
  }

  49.95% {
    -webkit-transform: translateY(4px);
    transform: translateY(4px);
  }

  66.6% {
    -webkit-transform: translateY(-2px);
    transform: translateY(-2px);
  }

  83.25% {
    -webkit-transform: translateY(1px);
    transform: translateY(1px);
  }

  100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-timing-function: ease-in-out;
  animation-timing-function: ease-in-out;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_wobble_to_bottom_right'){
?><style type="text/css">
/* Wobble To Bottom Right */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: translate(8px, 8px);
    transform: translate(8px, 8px);
  }

  33.3% {
    -webkit-transform: translate(-6px, -6px);
    transform: translate(-6px, -6px);
  }

  49.95% {
    -webkit-transform: translate(4px, 4px);
    transform: translate(4px, 4px);
  }

  66.6% {
    -webkit-transform: translate(-2px, -2px);
    transform: translate(-2px, -2px);
  }

  83.25% {
    -webkit-transform: translate(1px, 1px);
    transform: translate(1px, 1px);
  }

  100% {
    -webkit-transform: translate(0, 0);
    transform: translate(0, 0);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: translate(8px, 8px);
    transform: translate(8px, 8px);
  }

  33.3% {
    -webkit-transform: translate(-6px, -6px);
    transform: translate(-6px, -6px);
  }

  49.95% {
    -webkit-transform: translate(4px, 4px);
    transform: translate(4px, 4px);
  }

  66.6% {
    -webkit-transform: translate(-2px, -2px);
    transform: translate(-2px, -2px);
  }

  83.25% {
    -webkit-transform: translate(1px, 1px);
    transform: translate(1px, 1px);
  }

  100% {
    -webkit-transform: translate(0, 0);
    transform: translate(0, 0);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-timing-function: ease-in-out;
  animation-timing-function: ease-in-out;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}
</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_wobble_to_top_right'){
?><style type="text/css">
/* Wobble To Top Right */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: translate(8px, -8px);
    transform: translate(8px, -8px);
  }

  33.3% {
    -webkit-transform: translate(-6px, 6px);
    transform: translate(-6px, 6px);
  }

  49.95% {
    -webkit-transform: translate(4px, -4px);
    transform: translate(4px, -4px);
  }

  66.6% {
    -webkit-transform: translate(-2px, 2px);
    transform: translate(-2px, 2px);
  }

  83.25% {
    -webkit-transform: translate(1px, -1px);
    transform: translate(1px, -1px);
  }

  100% {
    -webkit-transform: translate(0, 0);
    transform: translate(0, 0);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: translate(8px, -8px);
    transform: translate(8px, -8px);
  }

  33.3% {
    -webkit-transform: translate(-6px, 6px);
    transform: translate(-6px, 6px);
  }

  49.95% {
    -webkit-transform: translate(4px, -4px);
    transform: translate(4px, -4px);
  }

  66.6% {
    -webkit-transform: translate(-2px, 2px);
    transform: translate(-2px, 2px);
  }

  83.25% {
    -webkit-transform: translate(1px, -1px);
    transform: translate(1px, -1px);
  }

  100% {
    -webkit-transform: translate(0, 0);
    transform: translate(0, 0);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-timing-function: ease-in-out;
  animation-timing-function: ease-in-out;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_wobble_top'){
?><style type="text/css">
/* Wobble Top */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: skew(-12deg);
    transform: skew(-12deg);
  }

  33.3% {
    -webkit-transform: skew(10deg);
    transform: skew(10deg);
  }

  49.95% {
    -webkit-transform: skew(-6deg);
    transform: skew(-6deg);
  }

  66.6% {
    -webkit-transform: skew(4deg);
    transform: skew(4deg);
  }

  83.25% {
    -webkit-transform: skew(-2deg);
    transform: skew(-2deg);
  }

  100% {
    -webkit-transform: skew(0);
    transform: skew(0);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: skew(-12deg);
    transform: skew(-12deg);
  }

  33.3% {
    -webkit-transform: skew(10deg);
    transform: skew(10deg);
  }

  49.95% {
    -webkit-transform: skew(-6deg);
    transform: skew(-6deg);
  }

  66.6% {
    -webkit-transform: skew(4deg);
    transform: skew(4deg);
  }

  83.25% {
    -webkit-transform: skew(-2deg);
    transform: skew(-2deg);
  }

  100% {
    -webkit-transform: skew(0);
    transform: skew(0);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transform-origin: 0 100%;
  transform-origin: 0 100%;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-timing-function: ease-in-out;
  animation-timing-function: ease-in-out;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_wobble_bottom'){
?><style type="text/css">
/* Wobble Bottom */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: skew(-12deg);
    transform: skew(-12deg);
  }

  33.3% {
    -webkit-transform: skew(10deg);
    transform: skew(10deg);
  }

  49.95% {
    -webkit-transform: skew(-6deg);
    transform: skew(-6deg);
  }

  66.6% {
    -webkit-transform: skew(4deg);
    transform: skew(4deg);
  }

  83.25% {
    -webkit-transform: skew(-2deg);
    transform: skew(-2deg);
  }

  100% {
    -webkit-transform: skew(0);
    transform: skew(0);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: skew(-12deg);
    transform: skew(-12deg);
  }

  33.3% {
    -webkit-transform: skew(10deg);
    transform: skew(10deg);
  }

  49.95% {
    -webkit-transform: skew(-6deg);
    transform: skew(-6deg);
  }

  66.6% {
    -webkit-transform: skew(4deg);
    transform: skew(4deg);
  }

  83.25% {
    -webkit-transform: skew(-2deg);
    transform: skew(-2deg);
  }

  100% {
    -webkit-transform: skew(0);
    transform: skew(0);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transform-origin: 100% 0;
  transform-origin: 100% 0;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-timing-function: ease-in-out;
  animation-timing-function: ease-in-out;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}
</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_wobble_skew'){
?><style type="text/css">
/* Wobble Skew */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: skew(-12deg);
    transform: skew(-12deg);
  }

  33.3% {
    -webkit-transform: skew(10deg);
    transform: skew(10deg);
  }

  49.95% {
    -webkit-transform: skew(-6deg);
    transform: skew(-6deg);
  }

  66.6% {
    -webkit-transform: skew(4deg);
    transform: skew(4deg);
  }

  83.25% {
    -webkit-transform: skew(-2deg);
    transform: skew(-2deg);
  }

  100% {
    -webkit-transform: skew(0);
    transform: skew(0);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  16.65% {
    -webkit-transform: skew(-12deg);
    transform: skew(-12deg);
  }

  33.3% {
    -webkit-transform: skew(10deg);
    transform: skew(10deg);
  }

  49.95% {
    -webkit-transform: skew(-6deg);
    transform: skew(-6deg);
  }

  66.6% {
    -webkit-transform: skew(4deg);
    transform: skew(4deg);
  }

  83.25% {
    -webkit-transform: skew(-2deg);
    transform: skew(-2deg);
  }

  100% {
    -webkit-transform: skew(0);
    transform: skew(0);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-timing-function: ease-in-out;
  animation-timing-function: ease-in-out;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_buzz'){
?><style type="text/css">
/* Buzz */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  50% {
    -webkit-transform: translateX(3px) rotate(2deg);
    transform: translateX(3px) rotate(2deg);
  }

  100% {
    -webkit-transform: translateX(-3px) rotate(-2deg);
    transform: translateX(-3px) rotate(-2deg);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  50% {
    -webkit-transform: translateX(3px) rotate(2deg);
    transform: translateX(3px) rotate(2deg);
  }

  100% {
    -webkit-transform: translateX(-3px) rotate(-2deg);
    transform: translateX(-3px) rotate(-2deg);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 0.15s;
  animation-duration: 0.15s;
  -webkit-animation-timing-function: linear;
  animation-timing-function: linear;
  -webkit-animation-iteration-count: infinite;
  animation-iteration-count: infinite;
}

</style><?php
}elseif($custom_data['2d_transitions']=='wd_hvr_buzz_out'){
 ?>
<style type="text/css">
/* Buzz Out */
@-webkit-keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  10% {
    -webkit-transform: translateX(3px) rotate(2deg);
    transform: translateX(3px) rotate(2deg);
  }

  20% {
    -webkit-transform: translateX(-3px) rotate(-2deg);
    transform: translateX(-3px) rotate(-2deg);
  }

  30% {
    -webkit-transform: translateX(3px) rotate(2deg);
    transform: translateX(3px) rotate(2deg);
  }

  40% {
    -webkit-transform: translateX(-3px) rotate(-2deg);
    transform: translateX(-3px) rotate(-2deg);
  }

  50% {
    -webkit-transform: translateX(2px) rotate(1deg);
    transform: translateX(2px) rotate(1deg);
  }

  60% {
    -webkit-transform: translateX(-2px) rotate(-1deg);
    transform: translateX(-2px) rotate(-1deg);
  }

  70% {
    -webkit-transform: translateX(2px) rotate(1deg);
    transform: translateX(2px) rotate(1deg);
  }

  80% {
    -webkit-transform: translateX(-2px) rotate(-1deg);
    transform: translateX(-2px) rotate(-1deg);
  }

  90% {
    -webkit-transform: translateX(1px) rotate(0);
    transform: translateX(1px) rotate(0);
  }

  100% {
    -webkit-transform: translateX(-1px) rotate(0);
    transform: translateX(-1px) rotate(0);
  }
}

@keyframes <?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  10% {
    -webkit-transform: translateX(3px) rotate(2deg);
    transform: translateX(3px) rotate(2deg);
  }

  20% {
    -webkit-transform: translateX(-3px) rotate(-2deg);
    transform: translateX(-3px) rotate(-2deg);
  }

  30% {
    -webkit-transform: translateX(3px) rotate(2deg);
    transform: translateX(3px) rotate(2deg);
  }

  40% {
    -webkit-transform: translateX(-3px) rotate(-2deg);
    transform: translateX(-3px) rotate(-2deg);
  }

  50% {
    -webkit-transform: translateX(2px) rotate(1deg);
    transform: translateX(2px) rotate(1deg);
  }

  60% {
    -webkit-transform: translateX(-2px) rotate(-1deg);
    transform: translateX(-2px) rotate(-1deg);
  }

  70% {
    -webkit-transform: translateX(2px) rotate(1deg);
    transform: translateX(2px) rotate(1deg);
  }

  80% {
    -webkit-transform: translateX(-2px) rotate(-1deg);
    transform: translateX(-2px) rotate(-1deg);
  }

  90% {
    -webkit-transform: translateX(1px) rotate(0);
    transform: translateX(1px) rotate(0);
  }

  100% {
    -webkit-transform: translateX(-1px) rotate(0);
    transform: translateX(-1px) rotate(0);
  }
}

.<?php echo $custom_data['2d_transitions'].'_'.$id;?> {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
}
.<?php echo $custom_data['2d_transitions'].'_'.$id; echo $hover;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $focus;?>, .<?php echo $custom_data['2d_transitions'].'_'.$id; echo $active;?> {
  -webkit-animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  animation-name: <?php echo $custom_data['2d_transitions'].'_'.$id;?>;
  -webkit-animation-duration: 0.75s;
  animation-duration: 0.75s;
  -webkit-animation-timing-function: linear;
  animation-timing-function: linear;
  -webkit-animation-iteration-count: 1;
  animation-iteration-count: 1;
}
</style>
<?php
}
 ?>