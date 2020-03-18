<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
require(Button_PATH.'admin/includes/custom_setting_get.php');
?>
<div class="buton_layout_container">
	<input type="radio" name="button_layout" id="simple_button" data-wdbutton="buttons" value="simple_button" <?php echo ($custom_data['button_layout']=='simple_button')?'checked':''; ?>>
	<label for="simple_button">
		<img src="<?php echo Button_URL;?>/images/layouts/button_layout_1.png">
		<h2 align="center" class="h2">Simple Button</h2>
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/">Demo</a>
	</label>

	<input type="radio" name="button_layout" id="2d_transitions" data-wdbutton="buttons" value="2d_transitions" <?php echo ($custom_data['button_layout']=='2d_transitions')?'checked':''; ?>>
	<label for="2d_transitions">
		<img src="<?php echo Button_URL;?>/images/layouts/2d_transition_layout.png">
		<h2 align="center" class="h2">2D Transitions</h2>
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/2d-transitions/">Demo</a>
	</label>

	<input type="radio" name="button_layout" id="border_transitions" data-wdbutton="buttons" value="border_transitions" <?php echo ($custom_data['button_layout']=='border_transitions')?'checked':''; ?>>
	<label for="border_transitions">
		<img src="<?php echo Button_URL;?>/images/layouts/border_transitions.png">
		<h2 align="center" class="h2">Border Transitions</h2>
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/border-transitions/">Demo</a>
	</label>

	<input type="radio" name="button_layout" id="curl" data-wdbutton="buttons" value="curl" <?php echo ($custom_data['button_layout']=='curl')?'checked':''; ?>>
	<label for="curl">
		<img src="<?php echo Button_URL;?>/images/layouts/curl_layout.png">
		<h2 align="center" class="h2">Curl</h2>
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/curl/">Demo</a>
	</label>

	<input type="radio" name="button_layout" id="speech_bubbles" data-wdbutton="buttons" value="speech_bubbles" <?php echo ($custom_data['button_layout']=='speech_bubbles')?'checked':''; ?>>
	<label for="speech_bubbles">
		<img src="<?php echo Button_URL;?>/images/layouts/speech_bouble_layout.png">
		<h2 align="center" class="h2">Speech Bubble</h2>
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/speech-bubble/">Demo</a>
	</label>

	<input type="radio" name="button_layout" id="background_transitions" data-wdbutton="buttons" value="background_transitions" <?php echo ($custom_data['button_layout']=='background_transitions')?'checked':''; ?>>
	<label for="background_transitions">
		<img src="<?php echo Button_URL;?>/images/layouts/background_transition_layout.png">
		<h2 align="center" class="h2">BG Transition</h2>
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/bg-transition/">Demo</a>
	</label>

	<input type="radio" name="button_layout" id="icons" data-wdbutton="buttons" value="icon_simple" <?php echo ($custom_data['button_layout']=='icon_simple')?'checked':''; ?>>
	<label for="icons">
		<img src="<?php echo Button_URL;?>/images/layouts/icon_simple.png">
		<h2 align="center" class="h2">Icon Button</h2>
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/icon-button/">Demo</a>
	</label>	

	<input type="radio" name="button_layout" id="icon_with_text" data-wdbutton="buttons" value="icon_with_text" <?php echo ($custom_data['button_layout']=='icon_with_text')?'checked':''; ?>>
	<label for="icon_with_text">
		<img src="<?php echo Button_URL;?>/images/layouts/icon_with_text.png">
		<h2 align="center" class="h2">Icon With Text</h2>
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/icon-with-text/">Demo</a>
	</label>	

	<input type="radio" name="button_layout" id="hexagons" data-wdbutton="buttons" value="hexagons" <?php echo ($custom_data['button_layout']=='hexagons')?'checked':''; ?>>
	<label for="hexagons">
		<img src="<?php echo Button_URL;?>/images/layouts/hexagons_layout.png">
		<h2 align="center" class="h2">Hexagon</h2>
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/hexagon/">Demo</a>
	</label>		
</div>

<!--------------button sets------------>

<div class="buton_layout_container buton_sets_container">
	<h2>Button Sets</h2>
	<input type="radio" name="button_layout" id="social_button_set_with_icon" data-wdbutton="btn_set" value="social_button_set_with_icon" <?php echo ($custom_data['button_layout']=='social_button_set_with_icon')?'checked':''; ?>>
	<label for="social_button_set_with_icon">
		<img src="<?php echo Button_URL;?>/images/button_sets/button_sets.png">		
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/social-button-sets/">Demo</a>
	</label>

	<input type="radio" name="button_layout" id="social_model_1" data-wdbutton="btn_set" value="social_model_1" <?php echo ($custom_data['button_layout']=='social_model_1')?'checked':''; ?>>
	<label for="social_model_1">
		<img src="<?php echo Button_URL;?>/images/button_sets/social_model_1.png">		
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/social-button-sets/">Demo</a>
	</label>
	

	<input type="radio" name="button_layout" id="social_model_2" data-wdbutton="btn_set" value="social_model_2" <?php echo ($custom_data['button_layout']=='social_model_2')?'checked':''; ?>>
	<label for="social_model_2">
		<img src="<?php echo Button_URL;?>/images/button_sets/social_model_2.png">		
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/social-button-sets/">Demo</a>
	</label>

	<input type="radio" name="button_layout" id="social_model_3" data-wdbutton="btn_set" value="social_model_3" <?php echo ($custom_data['button_layout']=='social_model_3')?'checked':''; ?>>
	<label for="social_model_3">
		<img src="<?php echo Button_URL;?>/images/button_sets/social_model_3.png">		
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/social-button-sets/">Demo</a>
	</label>
	<input type="radio" name="button_layout" id="social_model_4" data-wdbutton="btn_set" value="social_model_4" <?php echo ($custom_data['button_layout']=='social_model_4')?'checked':''; ?>>
	<label for="social_model_4">
		<img src="<?php echo Button_URL;?>/images/button_sets/social_model_4.png">		
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/social-button-sets/">Demo</a>
	</label>
	<input type="radio" name="button_layout" id="social_model_5" data-wdbutton="btn_set" value="social_model_5" <?php echo ($custom_data['button_layout']=='social_model_5')?'checked':''; ?>>
	<label for="social_model_5">
		<img src="<?php echo Button_URL;?>/images/button_sets/social_model_5.png">		
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/social-button-sets/">Demo</a>
	</label>
	<input type="radio" name="button_layout" id="social_model_6" data-wdbutton="btn_set" value="social_model_6" <?php echo ($custom_data['button_layout']=='social_model_6')?'checked':''; ?>>
	<label for="social_model_6">
		<img src="<?php echo Button_URL;?>/images/button_sets/social_model_6.png">		
		<a href="http://webdzier.com/demo/plugins/wd-button-pro/social-button-sets/">Demo</a>
	</label>	
</div>



<?php require(Button_PATH.'admin/setting_UI/button_effect_section.php'); ?>


