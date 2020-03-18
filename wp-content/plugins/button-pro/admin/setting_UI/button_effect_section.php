<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="container">
	<div class="2d_transitions button_effect_section">
		<h2 align="center" class="heading_title"><?php _e( '2D Transition', 'button-pro' ); ?></h2>
		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Hover Effect', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">			
				<select name="2d_transitions">				
					<?php 
					$two_D_effect=array('wd_hvr_grow','wd_hvr_shrink','wd_hvr_pulse','wd_hvr_pulse_grow','wd_hvr_pulse_shrink','wd_hvr_push','wd_hvr_pop','wd_hvr_bounce_in','wd_hvr_bounce_out','wd_hvr_rotate','wd_hvr_grow_rotate','wd_hvr_float','wd_hvr_sink','wd_hvr_bob','wd_hvr_hang','wd_hvr_skew','wd_hvr_skew_forward','wd_hvr_skew_backward','wd_hvr_wobble_horizontal','wd_hvr_wobble_vertical','wd_hvr_wobble_to_bottom_right','wd_hvr_wobble_to_top_right','wd_hvr_wobble_top','wd_hvr_wobble_bottom','wd_hvr_wobble_skew','wd_hvr_buzz','wd_hvr_buzz_out');

					foreach($two_D_effect as $val){
						?>
						<option value="<?php echo $val;?>" <?php selected($custom_data['2d_transitions'],$val) ?>><?php echo str_replace("wd_hvr_", '', $val);?></option>
						<?php
					}				
					?>
				</select>
			</div>
			<div class="clear_fix"></div> 
		</div>
	</div>

	<div class="border_transitions button_effect_section">
		<h2 align="center" class="heading_title"><?php _e( 'Border Transition Manage', 'button-pro' ); ?></h2>
		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Hover Effect', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">			
				<select name="border_transitions" class="border_transitions_select">				
					<?php 
					$border_transitions=array('wd_hvr_border_fade','wd_hvr_hollow','wd_hvr_trim','wd_hvr_ripple_out','wd_hvr_ripple_in','wd_hvr_outline_out','wd_hvr_outline_in','wd_hvr_round_corners','wd_hvr_underline_from_left','wd_hvr_underline_from_center','wd_hvr_underline_from_right','wd_hvr_reveal','wd_hvr_underline_reveal','wd_hvr_overline_reveal','wd_hvr_overline_from_left','wd_hvr_overline_from_center','wd_hvr_overline_from_right');

					foreach($border_transitions as $val){
						?>
						<option value="<?php echo $val;?>" <?php selected($custom_data['border_transitions'],$val) ?>><?php echo str_replace("wd_hvr_", '', $val);?></option>
						<?php
					}				
					?>
				</select>
			</div>
			<div class="clear_fix"></div> 
		</div>
		<div class="input_group transition_border_section">		
			<div class="input_label">
				<label><?php _e( 'Transition Border', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">
				<div class="input three_col" >
					<label><?php _e( 'Width', 'button-pro' ); ?></label>
					<input type="number" class="input_box" name="border_transitions_width" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['border_transitions_width']); ?>">
					<span class="px"><?php _e( 'PX', 'button-pro' ); ?></span>
				</div>	

				<div class="input text_right three_col " >
					<label style="margin-top: -22px;"><?php _e( 'Color', 'button-pro' ); ?></label>
					<input id="border_transitions_color" name="border_transitions_color" type="text" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['border_transitions_color']); ?>" class="button_color_field" data-default-color="#000000" />

				</div>	
			</div>
			<div class="clear_fix"></div> 
		</div>
	</div>

	<div class="curl button_effect_section">
		<h2 align="center" class="heading_title"><?php _e( 'Curl', 'button-pro' ); ?></h2>
		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Hover Effect', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">			
				<select name="curl">				
					<?php 
					$curl=array('wd_hvr_curl_top_left','wd_hvr_curl_top_right','wd_hvr_curl_bottom_right','wd_hvr_curl_bottom_left');

					foreach($curl as $val){
						?>
						<option value="<?php echo $val;?>" <?php selected($custom_data['curl'],$val) ?>><?php echo str_replace("wd_hvr_", '', $val);?></option>
						<?php
					}				
					?>
				</select>
			</div>
			<div class="clear_fix"></div> 
		</div>
	</div>

	<div class="speech_bubbles button_effect_section">
		<h2 align="center" class="heading_title"><?php _e( 'Speech Bubbles', 'button-pro' ); ?></h2>
		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Hover Effect', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">			
				<select name="speech_bubbles">				
					<?php 
					$curl=array('wd_hvr_bubble_top','wd_hvr_bubble_right','wd_hvr_bubble_bottom','wd_hvr_bubble_left','wd_hvr_bubble_float_top','wd_hvr_bubble_float_right','wd_hvr_bubble_float_bottom','wd_hvr_bubble_float_left');

					foreach($curl as $val){
						?>
						<option value="<?php echo $val;?>" <?php selected($custom_data['speech_bubbles'],$val) ?>><?php echo str_replace("wd_hvr_", '', $val);?></option>
						<?php
					}				
					?>
				</select>
			</div>
			<div class="clear_fix"></div> 
		</div>

		<div class="input_group transition_border_section">		
			<div class="input_label">
				<label><?php _e( 'Bubble Color', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">
				<div class="input" >					
					<input name="speech_bubbles_color" type="text" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['speech_bubbles_color']); ?>" class="button_color_field" data-default-color="#000000" />
				</div>	
			</div>
			<div class="clear_fix"></div> 
		</div>
	</div>

	<div class="background_transitions button_effect_section">
		<h2 align="center" class="heading_title"><?php _e( 'Background Transitions', 'button-pro' ); ?></h2>
		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Hover Effect', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">			
				<select name="background_transitions">				
					<?php 
					$background_transitions=array('wd_hvr_fade','wd_hvr_back_pulse','wd_hvr_sweep_to_right','wd_hvr_sweep_to_left','wd_hvr_sweep_to_bottom','wd_hvr_sweep_to_top','wd_hvr_bounce_to_right','wd_hvr_bounce_to_left','wd_hvr_bounce_to_bottom','wd_hvr_bounce_to_top','wd_hvr_radial_out','wd_hvr_radial_in','wd_hvr_rectangle_in','wd_hvr_rectangle_out','wd_hvr_shutter_in_horizontal','wd_hvr_shutter_out_horizontal','wd_hvr_shutter_in_vertical','wd_hvr_shutter_out_vertical',);

					foreach($background_transitions as $val){
						?>
						<option value="<?php echo $val;?>" <?php selected($custom_data['background_transitions'],$val) ?>><?php echo str_replace("wd_hvr_", '', $val);?></option>
						<?php
					}				
					?>
				</select>
			</div>
			<div class="clear_fix"></div> 
		</div>
	</div>

	<div class="icon_simple button_effect_section">
		<h2 align="center" class="heading_title"><?php _e( 'Icon Button', 'button-pro' ); ?></h2>
		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Button Icon', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">				
				<input data-placement="bottomRight" id="icon_simple"  class="form-control icp icp-auto ip_input" value="<?php echo $custom_data['button_icon']; ?>" type="text" readonly="readonly" />
				<span class="input-group-addon px"></span>
			</div>
			<div class="clear_fix"></div> 
		</div>

		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Hover Effect', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">				
				<select name="icon_simple_effect">				
					<?php 
					$icon_simple_effect=array('None','wd_hvr_up','wd_hvr_rotate','wd_hvr_scale');

					foreach($icon_simple_effect as $val){
						?>
						<option value="<?php echo $val;?>" <?php selected($custom_data['icon_simple_effect'],$val) ?>><?php echo str_replace("wd_hvr_", '', $val);?></option>
						<?php
					}				
					?>
				</select>
			</div>
			<div class="clear_fix"></div> 
		</div>
		
		<div class="input_group" style="display:none;">
			<p>Note :-  Button height and width same.</p>
			<div class="clear_fix"></div> 
		</div>

	</div>

	<div class="icon_with_text button_effect_section">
		<h2 align="center" class="heading_title"><?php _e( 'Icon With Text Button', 'button-pro' ); ?></h2>
		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Button Icon', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">				
				<input data-placement="bottomRight" id="icon_text"  class="form-control icp icp-auto ip_input" value="<?php echo $custom_data['button_icon']; ?>" type="text" readonly="readonly" />
				<span class="input-group-addon px"></span>
			</div>
			<div class="clear_fix"></div> 
		</div>	

		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Icon Size', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">				
				<?php $icon_size=array('fa-1x','fa-2x','fa-3x','fa-4x','fa-5x'); ?>				
				<select name="button_icon_size" style="width: 54%;">
					<?php 
					foreach ($icon_size as $val ) {
						?>
						<option value="<?php echo $val;?>" <?php selected($custom_data['button_icon_size'],$val) ?>><?php printf( __( '%s', 'button-pro' ),str_replace("fa-", "", $val)); ?></option>
						<?php
					}
					?>													
				</select>
			</div>
			<div class="clear_fix"></div> 
		</div>	
	</div>

	<div class="hexagons button_effect_section">
		<h2 align="center" class="heading_title"><?php _e( 'Hexagons', 'button-pro' ); ?></h2>
		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Button Icon', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">				
				<input data-placement="bottomRight" id="icon_hexagons"  class="form-control icp icp-auto ip_input" value="<?php echo $custom_data['button_icon']; ?>" type="text" readonly="readonly" />
				<span class="input-group-addon px"></span>
			</div>
			<div class="clear_fix"></div> 
		</div>

		<div class="input_group">
			<div class="input_label">
				<label><?php _e( 'Hover Effect', 'button-pro' ); ?></label>
			</div>
			<div class="input_field">				
				<select name="hexagons_effect">				
					<?php 
					$hexagons_effect=array('wd_hvr_inv','wd_hvr_spin','wd_hvr_spin_icon');

					foreach($hexagons_effect as $val){
						?>
						<option value="<?php echo $val;?>" <?php selected($custom_data['hexagons_effect'],$val) ?>><?php echo str_replace("wd_hvr_", '', $val);?></option>
						<?php
					}				
					?>
				</select>
			</div>
			<div class="clear_fix"></div> 
		</div>	
	</div>	
</div>