<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="container">
	<h2 class="home_title"><?php _e( 'Container', 'button-pro' ); ?></h2>
	<div class="input_group container_use_section">
		<div class="input_label">
			<label><?php _e( 'Container Use', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="container_use" value="1" <?php if($custom_data['container_use']==1){echo "checked";} ?>>
		</div>
		<div class="clear_fix"></div> 	
	</div>

	<div class="input_group container_center_section">
		<div class="input_label">
			<label><?php _e( 'Container Center', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<input type="checkbox" name="container_center" value="1" <?php if($custom_data['container_center']==1){echo "checked";} ?>>
		</div>
		<div class="clear_fix"></div> 	
	</div>

	<div class="input_group container_section">
		<div class="input_label">
			<label><?php _e( 'Container Width', 'button-pro' ); ?> </label>
		</div>
		<div class="input_field">			
			<input type="number" name="container_width" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['container_width']); ?>">
			<span class="px"><?php _e( 'PX', 'button-pro' ); ?></span>
		</div>
		<div class="clear_fix"></div> 	
	</div>

	<div class="input_group container_align_section">
		<div class="input_label">
			<label><?php _e( 'Button Align', 'button-pro' ); ?> </label>
		</div>
		<div class="input_field">
			<?php $_align=array('left','center','right'); ?>			
			<select name="button_align">
				<?php 
				foreach ($_align as $val ) {
					?>
					<option value="<?php echo $val;?>" <?php selected($custom_data['button_align'],$val) ?>><?php printf( __( '%s', 'button-pro' ),$val); ?></option>
					<?php
				}
				?>	
				
			</select>
			
		</div>
		<div class="clear_fix"></div> 	
	</div>

	<div class="input_group margin_section border_none">
		<div class="input_label">
			<label><?php _e( 'Margin', 'button-pro' ); ?></label>
		</div>
		<div class="input_field">
			<div class="input input_inline button_margin_top">
				<label><?php _e( 'Top', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="margin_top" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['margin_top']); ?>">
				<span class="px"><?php _e( 'PX', 'button-pro' ); ?></span>
			</div>

			<div class="input input_inline button_margin_right" >
				<label><?php _e( 'Right', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="margin_right" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['margin_right']); ?>">
				<span class="px"><?php _e( 'PX', 'button-pro' ); ?></span>
			</div>

			<div class="input input_inline button_margin_bottom">
				<label><?php _e( 'Bottom', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="margin_bottom" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['margin_bottom']); ?>">
				<span class="px"><?php _e( 'PX', 'button-pro' ); ?></span>
			</div>

			<div class="input text_right input_inline button_margin_left">
				<label><?php _e( 'Left', 'button-pro' ); ?></label>
				<input type="number" class="input_box" name="margin_left" value="<?php printf( __( '%s', 'button-pro' ),$custom_data['margin_left']); ?>">
				<span class="px"><?php _e( 'PX', 'button-pro' ); ?></span>
			</div>
		</div>
		<div class="clear_fix"></div> 
	</div>
</div>

<div class="custom_css_box">
	<h3>Custom Css</h3>
	<textarea name="custom_css" id="wdbuttoncustom_css"><?php echo $custom_data['custom_css']; ?></textarea>
	<p>Enter Css without <strong>&lt;style&gt; &lt;/style&gt; </strong> tag</p>
</div>