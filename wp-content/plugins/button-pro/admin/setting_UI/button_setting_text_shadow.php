<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="container hexagons_none circle_icon_none">
	<h2 class="home_title"><?php _e( 'Text Shadow', 'button-pro' ); ?></h2>
	<div class="input_group button_shadow_offset_section">
		<div class="input_label">
			<label><?php _e( 'Shadow Offset', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<div class="input two_col">
				<label><?php _e( 'Left', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="shadow_offset_left" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['shadow_offset_left']); ?>">
				
			</div>

			<div class="input text_right two_col" >
				<label><?php _e( 'Top', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="shadow_offset_top" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['shadow_offset_top']); ?>">
				
			</div>	
		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group background_shadow_color_section">
		<div class="input_label">
			<label><?php _e( 'Shadow', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<div class="input two_col">
				<label><?php _e( 'Color', 'button-pro' ); ?></label>
				<input name="shadow_color" type="text" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['shadow_color']); ?>" class="button_color_field" data-default-color="#000000" />
			</div>

			<div class="input two_col" >
				<label><?php _e( 'Hover', 'button-pro' ); ?></label>
				<input name="shadow_hover_color" type="text" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['shadow_hover_color']); ?>" class="button_color_field" data-default-color="#000000" />
			</div>				

		</div>
		<div class="clear_fix"></div> 
	</div>

	<div class="input_group button_shadow_blur_section border_none">
		<div class="input_label">
			<label><?php _e( 'Shadow Blur', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<div class="input two_col">				
				<input type="number" class="input_box" name="shadow_blur" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['shadow_blur']); ?>">
				
			</div>
		</div>
		<div class="clear_fix"></div> 
	</div>
</div>