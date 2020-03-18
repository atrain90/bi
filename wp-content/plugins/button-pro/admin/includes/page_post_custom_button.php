<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
wp_enqueue_style('post_page_button',Button_URL.'admin/css/post_and_page_button.css');
?>
<div id="wd_button_div" style="display:none;">
	<h2 align="center">Button Shortcode</h2>
	<?php 
	$All_posts = wp_count_posts('wd_button')->publish;
	$ARGSM= array('post_type'=>'wd_button','posts_per_page'=>$All_posts);			
	$button_short=new wp_Query($ARGSM);
	if($button_short->have_posts()){
		
		while($button_short->have_posts()):$button_short->the_post();
		$custom_data = unserialize(get_post_meta(get_the_ID(),'button_custom_setting_'.get_the_ID(), true));
		$id=get_the_ID();
		require(Button_PATH.'user_view/includes/font_family.php');
		require_once(Button_PATH.'user_view/includes/rgba_color.php');

		/**bg color**/
		$button_bg_color_start=Button_rgba( $custom_data['button_bg_color_start'], $custom_data['opacity_start']);
		$button_bg_color_end=Button_rgba( $custom_data['button_bg_color_end'], $custom_data['opacity_end']);
		$button_bg_hover_color_start=Button_rgba( $custom_data['button_bg_hover_color_start'], $custom_data['hover_opacity_start']);
		$button_bg_hover_color_end=Button_rgba( $custom_data['button_bg_hover_color_end'], $custom_data['hover_opacity_end']);	
		
		require(Button_PATH.'user_view/coman_css/custom_css.php');
		
		if($custom_data['button_layout']=="simple_button"){
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'simple_button/simple_button.php');
		}elseif($custom_data['button_layout']=="2d_transitions"){
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'2d_transitions/2d_transitions.php');
			
		}elseif($custom_data['button_layout']=="border_transitions"){
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'border_transitions/border_transitions.php');
			
		}elseif($custom_data['button_layout']=="curl"){
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'curl/curl.php');
			
		}elseif($custom_data['button_layout']=="speech_bubbles"){
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'speech_bubbles/speech_bubbles.php');
			
		}elseif($custom_data['button_layout']=="background_transitions"){
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'background_transitions/background_transitions.php');
			
		}elseif($custom_data['button_layout']=="icon_simple"){
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'icon_simple/icon_simple.php');
			
		}elseif($custom_data['button_layout']=="icon_with_text"){
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'icon_with_text/icon_with_text.php');
			
		}elseif($custom_data['button_layout']=="hexagons"){			
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'hexagons/hexagons.php');
		}elseif($custom_data['button_layout']=="social_button_set_with_icon"){			
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/social_button_set_with_icon/social_button_set_with_icon.php');

		}elseif($custom_data['button_layout']=="social_model_1"){		

			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_1/model_1.php');
		}elseif($custom_data['button_layout']=="social_model_2"){			
			
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_2/model_2.php');
		}elseif($custom_data['button_layout']=="social_model_3"){			
			
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_3/model_3.php');
		}elseif($custom_data['button_layout']=="social_model_4"){			

			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_4/model_4.php');
		}elseif($custom_data['button_layout']=="social_model_5"){			
			
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_5/model_5.php');
		}elseif($custom_data['button_layout']=="social_model_6"){			
			wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_6/model_6.php');
		}
		
		endwhile;
	}else{
		echo "<h2>Empty</h2>";
	}


	function wd_custom_btn($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,$path){
		?>
		<div class="wd_button_container">
			<h2 align="center"><?php the_title(); ?></h2>
			<div class="col-2">

				<?php require(Button_PATH.'user_view/button_layouts/'.$path); ?>			
			</div>	
			<div class="col-2">
				<button class="btn_insert" value="<?php echo $id;?>">Use This Button</button>
			</div>				
			<div class="clear_fix"></div>
		</div>
		<?php
	}

	function wd_btn_sets($src,$id){
		?>
		<div class="wd_button_container">
			<h2 align="center"><?php the_title(); ?></h2>
			<div class="col-2">
				<img src="<?php echo $src;?>">
			</div>
			<div class="col-2">
				<button class="btn_insert" value="<?php echo $id;?>">Use This Button</button>
			</div>				
			<div class="clear_fix"></div>
		</div>
		<?php
	}
	?>
</div>
<script type="text/javascript">
jQuery(function(){
	jQuery('.wd_button_container a').click(function(e){
		e.preventDefault();
	});

	jQuery('.btn_insert').click(function(){
		var button_id=jQuery(this).val();
		window.send_to_editor('<p>[WD_Button id='+button_id+']</p>');
		tb_remove();
	});

});
</script>