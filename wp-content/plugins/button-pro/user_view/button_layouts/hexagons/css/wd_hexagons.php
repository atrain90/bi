<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<style type="text/css">
/* 2.1 - Default Button style
-------------------------------------------------*/
.wd_hb_<?php echo $id;?> {
	display: inline-block;
	position:relative;
	text-align: center; /*  Default text align center */
	z-index: 0;
}

.wd_hb_<?php echo $id;?>:before,
.wd_hb_<?php echo $id;?>:after {
	position: absolute;
	content: "";  
	left: -1px;
	top: 0;
	z-index: -1;
}

.wd_hb_<?php echo $id;?>:before {
	-webkit-transform: rotate(60deg);
	-moz-transform: rotate(60deg);
	-ms-transform: rotate(60deg);
	-o-transform: rotate(60deg);
	transform: rotate(60deg);
}

.wd_hb_<?php echo $id;?>:after {
	-webkit-transform: rotate(-60deg);
	-moz-transform: rotate(-60deg);
	-ms-transform: rotate(-60deg);
	-o-transform: rotate(-60deg);
	transform: rotate(-60deg);
}

.wd_hb_<?php echo $id;?> i {
	z-index: 9;
	-webkit-transition: all .25s ease;
	-moz-transition: all .25s ease;
	-ms-transition: all .25s ease;
	-o-transition: all .25s ease;
	transition: all .25s ease;
}


.wd_hb_<?php echo $id;?>,
.wd_hb_<?php echo $id;?>:before,
.wd_hb_<?php echo $id;?>:after { 
	box-sizing: border-box;

	 /* default transition time is set .25s = 250 millisecond  
	 Uncomment following if you want to set transition on hexagon color change */ 

	 -webkit-transition: all .25s ease;
	 -moz-transition: all .25s ease;
	 -ms-transition: all .25s ease;
	 -o-transition: all .25s ease;
	 transition: all .25s ease;
	}

	
	.wd_hb_<?php echo $id;?>,
	.wd_hb_<?php echo $id;?>:before,
	.wd_hb_<?php echo $id;?>:after {
		background: <?php echo $custom_data['button_bg_color_start'];?>;
		
		border-left:1px <?php echo $custom_data['border_style'];?>;
		border-right:1px <?php echo $custom_data['border_style'];?>;
		border-color: <?php echo $custom_data['border_color'];?>;
		color: <?php echo $custom_data['button_text_color'];?>; /* Default font color */
	}

	.wd_hb_<?php echo $id;?>:hover,
	.wd_hb_<?php echo $id;?>:hover:before,
	.wd_hb_<?php echo $id;?>:hover:after {
		background:<?php echo $custom_data['button_bg_hover_color_start'];?> ;
		border-left:1px <?php echo $custom_data['border_style'];?>;
		border-right:1px <?php echo $custom_data['border_style'];?>;  
		border-color: <?php echo $custom_data['border_hover_color'];?>;
		color: <?php echo $custom_data['button_text_hover_color'];?>!important; /* Default hover font color */
	}

	.wd_hb_xs_<?php echo $id;?> {
		line-height: 21.5px;
		font-size: 21.5px;
		margin: 9.5px 0px; /* Original output, margin 0 of the button */ 
		/*margin: 33.5px 15px;*/ /* New output, margin 15px of the button,  9.5 + 15 = 24.5 */ 
	}
	.wd_hb_xs_<?php echo $id;?> ,
	.wd_hb_xs_<?php echo $id;?>:before ,
	.wd_hb_xs_<?php echo $id;?>:after {
		height:21.5px;
		width:38px; 
	}


	/***************************/

	</style>