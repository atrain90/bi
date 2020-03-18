<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if($custom_data['hexagons_effect']=='wd_hvr_inv'){
	?>
<style type="text/css">
/*inv*/
.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>,
.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>:after,
.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>:before {
	color: <?php echo $custom_data['button_text_color'];?>; 
	border-color: <?php echo $custom_data['border_color'];?>;
	background: <?php echo $custom_data['button_bg_color_start'];?>;
}
.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>:hover,
.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>:hover:after,
.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>:hover:before {
	background:<?php echo $custom_data['button_bg_hover_color_start'];?> ; 
	border-color: <?php echo $custom_data['border_hover_color'];?>;
	color: <?php echo $custom_data['button_text_hover_color'];?>!important;  
}
</style>
	<?php
}elseif($custom_data['hexagons_effect']=='wd_hvr_spin'){
?>
<style type="text/css">
/*wd_hvr_spin*/
.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>,
.<?php echo $custom_data['hexagons_effect'].'_'.$id;?> i {
	-webkit-transition: -webkid-transform .25s  ease;
	-moz-transition: -moz-transform .25s  ease;
	-ms-transition: -ms-transform .25s  ease;
	-o-transition: -o-transform .25s  ease;
	transition: transform .25s  ease;
}

.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>:hover i,
.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>:hover {
	-webkit-transform:rotate(360deg);
	-moz-transform:rotate(360deg);
	-ms-transform:rotate(360deg);
	-o-transform:rotate(360deg);
	transform:rotate(360deg);	
}
</style>
	<?php
}elseif($custom_data['hexagons_effect']=='wd_hvr_spin_icon'){
?>
<style type="text/css">
/*wd_hvr_spin_icon*/

.<?php echo $custom_data['hexagons_effect'].'_'.$id;?> i {
	-webkit-transition: -webkid-transform .25s  ease;
	-moz-transition: -moz-transform .25s  ease;
	-ms-transition: -ms-transform .25s  ease;
	-o-transition: -o-transform .25s  ease;
	transition: transform .25s  ease;
}

.<?php echo $custom_data['hexagons_effect'].'_'.$id;?>:hover i{
	-webkit-transform:rotate(360deg);
	-moz-transform:rotate(360deg);
	-ms-transform:rotate(360deg);
	-o-transform:rotate(360deg);
	transform:rotate(360deg);	
}

</style>
	<?php
} ?>