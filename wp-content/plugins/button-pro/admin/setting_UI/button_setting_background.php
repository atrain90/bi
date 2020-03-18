<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="container">
	<h2 class="home_title"><?php _e( 'Background', 'button-pro' ); ?></h2>
	<div class="input_group background_color_section">
		<div class="input_label">
			<label><?php _e( 'Background Color', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<div class="input two_col">
				<label><?php _e( 'start', 'button-pro' ); ?></label>
				<input name="button_bg_color_start" type="text" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['button_bg_color_start']); ?>" class="button_color_field" data-default-color="#000000" />
			</div>

			<div class="input two_col hexagons_none" >
				<label><?php _e( 'End', 'button-pro' ); ?></label>
				<input name="button_bg_color_end" type="text" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['button_bg_color_end']); ?>" class="button_color_field" data-default-color="#000000" />
			</div>				

		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group background_hover_color_section">
		<div class="input_label">
			<label><?php _e( 'Hover Color', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<div class="input two_col">
				<label><?php _e( 'start', 'button-pro' ); ?></label>
				<input name="button_bg_hover_color_start" type="text" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['button_bg_hover_color_start']); ?>" class="button_color_field" data-default-color="#000000" />
			</div>

			<div class="input two_col hexagons_none" >
				<label><?php _e( 'End', 'button-pro' ); ?></label>
				<input name="button_bg_hover_color_end" type="text" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['button_bg_hover_color_end']); ?>" class="button_color_field" data-default-color="#000000" />
			</div>				

		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group button_opacity_section hexagons_none">
		<div class="input_label">
			<label><?php _e( 'Opacity', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<div class="input two_col">
				<label><?php _e( 'Start', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="opacity_start" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['opacity_start']); ?>">
				
			</div>

			<div class="input text_right two_col" >
				<label><?php _e( 'End', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="opacity_end" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['opacity_end']); ?>">
				
			</div>				

		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group button_hover_opacity_section hexagons_none">
		<div class="input_label">
			<label><?php _e( 'Hover Opacity', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<div class="input two_col">
				<label><?php _e( 'Start', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="hover_opacity_start" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['hover_opacity_start']); ?>">
				
			</div>

			<div class="input text_right two_col" >
				<label><?php _e( 'End', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="hover_opacity_end" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['hover_opacity_end']); ?>">
				
			</div>				

		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group button_gradient_section border_none hexagons_none">
		<div class="input_label">
			<label><?php _e( 'Gradient stop', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<div class="input two_col">				
				<input type="number" class="input_box" name="gradient_stop" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['gradient_stop']); ?>">
			</div>
		</div>
		<div class="clear_fix"></div> 
	</div>
</div>