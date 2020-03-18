<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<style type="text/css">
.wd_flat_button_<?php echo $id;?>{
	color: <?php echo $custom_data['button_text_color'];?>;
	width: <?php echo $custom_data['button_width'];?>px!important;
	display: inline-block;
	background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_start; ?>), color-stop(1, <?php echo $button_bg_color_end; ?>));
	background: -moz-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
	background: -o-linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
	background: linear-gradient(<?php echo $button_bg_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_color_end; ?>);
	border:none;
	outline:none;
	
	text-shadow:<?php echo $custom_data['shadow_offset_left'];?>px <?php echo $custom_data['shadow_offset_top'];?>px <?php echo $custom_data['shadow_blur'];?>px <?php echo $custom_data['shadow_color'];?>;
	border-style: <?php echo $custom_data['border_style'];?>;
	border-width: <?php echo $custom_data['border_width'];?>px;
	border-color: <?php echo $custom_data['border_color'];?>;
	border-top-left-radius: <?php echo $custom_data['border_top_left'];?>px;
	border-top-right-radius: <?php echo $custom_data['border_top_right'];?>px;
	border-bottom-left-radius:  <?php echo $custom_data['border_bottom_left'];?>px;
	border-bottom-right-radius: <?php echo $custom_data['border_bottom_right'];?>px;
	-webkit-box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_color'];?>!important;
	-moz-box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_color'];?>!important;
	box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_color'];?>!important;
	transition: all 0.5s ease-out;
	-webkit-transition: all 0.3s ease-out;
	-moz-transition: all 0.3s ease-out;
	-ms-transition: all 0.3s ease-out;
	-o-transition: all 0.3s ease-out;	
	
}
.wd_flat_button_<?php echo $id;?>:hover {
	text-shadow:<?php echo $custom_data['shadow_offset_left'];?>px <?php echo $custom_data['shadow_offset_top'];?>px <?php echo $custom_data['shadow_blur'];?>px <?php echo $custom_data['shadow_hover_color'];?>;
	text-shadow-color:<?php echo $custom_data['shadow_hover_color'];?>;	
	color: <?php echo $custom_data['button_text_hover_color'];?>!important;
	background: -webkit-gradient(linear, left top, left bottom, color-stop(<?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_start; ?>), color-stop(1, <?php echo $button_bg_hover_color_end; ?>));
	background: -moz-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
	background: -o-linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
	background: linear-gradient(<?php echo $button_bg_hover_color_start; ?> <?php echo $custom_data['gradient_stop']; ?>%, <?php echo $button_bg_hover_color_end; ?>);
	border-color: <?php echo $custom_data['border_hover_color'];?>;
	-webkit-box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_hover_color'];?>!important;
	-moz-box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_hover_color'];?>!important;
	box-shadow: <?php echo $custom_data['border_shadow_offset_left'];?>px <?php echo $custom_data['border_shadow_offset_top'];?>px <?php echo $custom_data['border_shadow_blur'];?>px <?php echo $custom_data['border_shadow_hover_color'];?>!important;
	transition: all 0.5s ease-out;
	-webkit-transition: all 0.3s ease-out;
	-moz-transition: all 0.3s ease-out;
	-ms-transition: all 0.3s ease-out;
	-o-transition: all 0.3s ease-out;	
}
.wd_flat_button_<?php echo $id;?> span i{
	line-height: 45px!important;
	text-shadow:none;	
}

.wd_flat_button_<?php echo $id;?> span{	
	width: 45px;
	display: inline-block;
	text-align: center;
	float: left;
	margin: 3px 0px 0px 2px;
}
.wd_flat_button_<?php echo $id;?> label{	
	padding: 10px;
	float: left;
	border-left: 1px solid rgba(0,0,0,0.1);
	line-height: 1.75;
	font-size: 17px;	
	width: auto!important;
	margin: 0;
	background:none!important;
	cursor: pointer;
	font-style: <?php echo $custom_data['text_italic'];?>;
	
	
	font-family:<?php echo $custom_data['font_family'];?>;	
	font-weight:<?php  if($custom_data['text_bold']!=''){echo $custom_data['text_bold'];}else{echo 'inherit';} ?> ;
}

.wd_flat_button_<?php echo $id;?> label:hover{
	border-left: 1px solid rgba(0,0,0,0.1)!important;	
	border-color: <?php echo $custom_data['border_hover_color'];?>;
	
}
</style>