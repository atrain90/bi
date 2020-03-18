<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
$button_bg_color_start=Button_rgba( $custom_data['button_bg_color_start'], $custom_data['opacity_start']);
$button_bg_color_end=Button_rgba( $custom_data['button_bg_color_end'], $custom_data['opacity_end']);
$button_bg_hover_color_start=Button_rgba( $custom_data['button_bg_hover_color_start'], $custom_data['hover_opacity_start']);
$button_bg_hover_color_end=Button_rgba( $custom_data['button_bg_hover_color_end'], $custom_data['hover_opacity_end']);

function Button_rgba( $color,$opacity=null ) {
	if ( $color[0] == '#' ) {
		$color = substr( $color, 1 );
	}
	if ( strlen( $color ) == 6 ) {
		list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) == 3 ) {
		list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return false;
	}
	$r = hexdec( $r );
	$g = hexdec( $g );
	$b = hexdec( $b );
	$opacity=$opacity/100;
	$result='rgba('.$r.','.$g.','.$b.','.$opacity.')';
	return $result;
}
?>