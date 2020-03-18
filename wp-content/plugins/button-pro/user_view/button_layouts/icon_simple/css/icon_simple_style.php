<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<style type="text/css">

.wd_simple_icon_<?php echo $id;?> {
  display: inline-block;
  line-height: 1.42857143;
  width: <?php echo $custom_data['button_width'];?>px!important;
  height: <?php echo $custom_data['button_width'];?>px!important;
  padding-top: <?php echo $custom_data['padding_top'];?>px!important;
  padding-right: <?php echo $custom_data['padding_right'];?>px!important;
  padding-bottom: <?php echo $custom_data['padding_bottom'];?>px!important;
  padding-left: <?php echo $custom_data['padding_left'];?>px!important;
  font-size: <?php echo $custom_data['font_size'];?>px!important;
  text-align: center;
  color: <?php echo $custom_data['button_text_color'];?>!important;
  border-style: <?php echo $custom_data['border_style'];?>;
  border-width: <?php echo $custom_data['border_width'];?>px;
  border-color: <?php echo $custom_data['border_color'];?>;
  text-shadow:<?php echo $custom_data['shadow_offset_left'];?>px <?php echo $custom_data['shadow_offset_top'];?>px <?php echo $custom_data['shadow_blur'];?>px <?php echo $custom_data['shadow_color'];?>;

  <?php 
  if($custom_data['button_circle']==1 && $custom_data['button_layout']=='icon_simple'){
    ?>
    border-radius:50%;
    <?php
    }else{
      ?>
      border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
      border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
      border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
      border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
      <?php
      } ?>

      -webkit-box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_color'];?>;
      -moz-box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_color'];?>;
      box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_color'];?>;

      background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_start; ?>), color-stop(1, <?php echo $button_bg_color_end; ?>));
      background: -moz-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
      background: -o-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
      background: linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
      -webkit-transition: all 0.3s;
      -moz-transition: all 0.3s;
      transition: all 0.3s;
    }
    .wd_simple_icon_<?php echo $id;?>:hover{
      color: <?php echo $custom_data['button_text_hover_color'];?>!important;
      text-shadow:<?php echo $custom_data['shadow_offset_left'];?>px <?php echo $custom_data['shadow_offset_top'];?>px <?php echo $custom_data['shadow_blur'];?>px <?php echo $custom_data['shadow_hover_color'];?>;
      border-color: <?php echo $custom_data['border_hover_color'];?>;
      background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
      background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
      background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
      background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
      -webkit-box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_hover_color'];?>;
      -moz-box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_hover_color'];?>;
      box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_hover_color'];?>;
      -webkit-transition: all 0.3s;
      -moz-transition: all 0.3s;
      transition: all 0.3s;
    }

    </style>