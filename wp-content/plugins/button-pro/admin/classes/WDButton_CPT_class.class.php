<?php 
/**
* 
*/
if ( ! defined( 'ABSPATH' ) ) exit;
class WDButton_CPT_class 
{
	
	function __construct(){
		add_action( 'init', array(&$this,'WDBUTTON_CPT'));
		add_action('media_buttons_context', array(&$this,'WD_custom_button'),17);	
		add_action('admin_footer', array(&$this,'WD_button_popup_content'));
		add_action('in_admin_header',array(&$this,'wdbutton_header_info')); 	
	}

	function WDBUTTON_CPT(){
		require_once(Button_PATH.'admin/includes/BUTTON_CPT.php');

		function wd_custom_btn_cpt($id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,$path){
			?>
			<div class="wd_button_container">			
				<div>
					<?php require(Button_PATH.'user_view/button_layouts/'.$path); ?>			
				</div>						
				<div class="clear_fix"></div>
			</div>
			<?php
		}
		
	}

	function wd_button_columns($columns){
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title' ),
			'button_style' => __( 'Button' ),
			'shortcode' => __( 'Shortcode' ),
			'date' => __( 'Date' )
			);
		return $columns;
	}

	function wd_button_manage_columns($columns,$post_id){
		global $post;
		switch( $columns ) {
			case 'shortcode' :
			echo '<input type="text" value="[WD_Button  id='.$post_id.']" onclick="jQuery(this).select()" readonly="readonly" />';
			break;
			case 'button_style' :

			$custom_data = unserialize(get_post_meta(get_the_ID(),'button_custom_setting_'.$post_id, true));
			require(Button_PATH.'user_view/includes/font_family.php');
			require_once(Button_PATH.'user_view/includes/rgba_color.php');

			/**bg color**/
			$button_bg_color_start=Button_rgba( $custom_data['button_bg_color_start'], $custom_data['opacity_start']);
			$button_bg_color_end=Button_rgba( $custom_data['button_bg_color_end'], $custom_data['opacity_end']);
			$button_bg_hover_color_start=Button_rgba( $custom_data['button_bg_hover_color_start'], $custom_data['hover_opacity_start']);
			$button_bg_hover_color_end=Button_rgba( $custom_data['button_bg_hover_color_end'], $custom_data['hover_opacity_end']);	

			require(Button_PATH.'user_view/coman_css/custom_css.php');
			if($custom_data['button_layout']=="simple_button"){
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'simple_button/simple_button.php');
			}elseif($custom_data['button_layout']=="2d_transitions"){
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'2d_transitions/2d_transitions.php');

			}elseif($custom_data['button_layout']=="border_transitions"){
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'border_transitions/border_transitions.php');

			}elseif($custom_data['button_layout']=="curl"){
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'curl/curl.php');

			}elseif($custom_data['button_layout']=="speech_bubbles"){
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'speech_bubbles/speech_bubbles.php');

			}elseif($custom_data['button_layout']=="background_transitions"){
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'background_transitions/background_transitions.php');

			}elseif($custom_data['button_layout']=="icon_simple"){
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'icon_simple/icon_simple.php');

			}elseif($custom_data['button_layout']=="icon_with_text"){
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'icon_with_text/icon_with_text.php');

			}elseif($custom_data['button_layout']=="hexagons"){			
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'hexagons/hexagons.php');
			}elseif($custom_data['button_layout']=="social_button_set_with_icon"){			
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/social_button_set_with_icon/social_button_set_with_icon.php');

			}elseif($custom_data['button_layout']=="social_model_1"){		

				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_1/model_1.php');
			}elseif($custom_data['button_layout']=="social_model_2"){			

				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_2/model_2.php');
			}elseif($custom_data['button_layout']=="social_model_3"){			

				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_3/model_3.php');
			}elseif($custom_data['button_layout']=="social_model_4"){			

				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_4/model_4.php');
			}elseif($custom_data['button_layout']=="social_model_5"){			

				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_5/model_5.php');
			}elseif($custom_data['button_layout']=="social_model_6"){			
				wd_custom_btn_cpt($post_id,$custom_data,$button_bg_color_start,$button_bg_color_end,$button_bg_hover_color_start,$button_bg_hover_color_end,'social_button_sets/model_6/model_6.php');
			}
			break;
			default :
			break;
		}
	}

	function WD_custom_button($context){
		$context .= '<a class="button thickbox"  title="'."Select Button Title to insert into post".'"  
		href="#TB_inline?width=400&inlineId=wd_button_div">
		<span class="wp-media-buttons-icon" style="background: url('.Button_URL.'/images/button_cpt_icon.png); background-repeat: no-repeat; background-position: left bottom;"></span>
		Button Pro Shortcode</a>';
		return $context;
	}

	function WD_button_popup_content(){
		require(Button_PATH.'admin/includes/page_post_custom_button.php');		
	}

	function wdbutton_header_info(){
		if(get_post_type()=="wd_button") {
			?>
			<style type="text/css">
			.wdbutton_ac_h_i{
				background:url('<?php echo Button_URL.'images/wdbutton-header.jpg'; ?>') 50% 0 repeat fixed;
			}
			</style>
			<div class="wdbutton_ac_h_i ">
				<div class="wdbutton_container">
					<a href="http://webdzier.com/plugins/button-pro/" target="_blank">
						<a class="wpsm-rate-us" style=" text-decoration: none; height: 40px; width: 40px;" href="https://wordpress.org/plugins/button/" target="_blank">
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
						</a>
						<div class="link_wdbutton">
							<a href="http://webdzier.com/demo/plugins/wd-button-pro/" target="_blank" class="view_demo_btn">View Demo</a>							
							<a href="http://webdzier.com/amember/login" target="_blank" class="get_support_btn">Get Support</a>
						</div>
						
					</a>
				</div>
			</div>
			<?php
		}
	}

}

?>