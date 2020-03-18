<?php 

/**
 * Adds Foo_Widget widget.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class WDButton_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wd_button_widget', // Base ID
			__( 'Button Widget'), // Name
			array( 'description' => __( 'Display Custom Button in widget area.' ), ) // Args
			);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$Title    	=   apply_filters( 'button_widget_title', $instance['Title'] );
		$buttonId	=   apply_filters( 'button_widget_shortcode', $instance['Shortcode'] ); 
        
		echo $args['before_widget'];
		if(!empty($buttonId)){
			if ( !empty( $instance['Title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['Title'] ). $args['after_title'];
			}

			echo do_shortcode( '[WD_Button id='.$buttonId.']' );		
		}else{
			echo "<p>No Button Shortcode Found !!!!.</p>";
		}
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'Title' ] ) ) {
			$Title = $instance[ 'Title' ];
		} else {
			$Title = "Button";
		}
		if ( isset( $instance[ 'Shortcode' ] ) ) {            
           
			$Shortcode = explode(",", $instance[ 'Shortcode' ]);            
		} else {
			$Shortcode = array("--Select--");
		}
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'Title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'Title' ); ?>" name="<?php echo $this->get_field_name( 'Title' ); ?>" type="text" value="<?php echo esc_attr( $Title ); ?>">
		</p>
		<label for="<?php echo $this->get_field_id( 'Shortcode' ); ?>"><?php _e( 'Select' ); ?> (Required)</label>
		<?php 

		$ITPL_CPT_NAME="wd_button";
		$ITPL_ALL_POSTS=wp_count_posts($ITPL_CPT_NAME)->publish;
		global $all_button;
		$all_button=array('post_type'=>$ITPL_CPT_NAME, 'orderby'=>'ASC', 'posts_per_page'=>$ITPL_ALL_POSTS);
		$all_button=new wp_query($all_button);
		?>
		<select id="<?php echo $this->get_field_id( 'Shortcode' ); ?>" name="<?php echo $this->get_field_name( 'Shortcode' ); ?>[]" style="width: 100%;" multiple="multiple">
			<option value="--Select--" <?php if($Shortcode=='--Select--'){echo 'selected="selected"';} ?>>--Select--</option>
			<?php 
			if($all_button->have_posts())
			{
				while($all_button->have_posts()): $all_button->the_post();
				$Pid=get_the_ID();
				$P_title=get_the_title($Pid);
				?>
				<option value="<?php echo $Pid; ?>" <?php if(in_array($Pid, $Shortcode)) echo 'selected="selected"'; ?>><?php if($P_title) echo $P_title; else echo "No Title"; ?></option>

				<?php
				endwhile;
			}else  { 
				echo "<option>Sorry! No Button Shortcode Found.</option>";
			}

			?>
		</select>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
       
        $val= implode(',', array_values($new_instance['Shortcode']));       
        $instance = array();
        $instance['Title'] = ( ! empty( $new_instance['Title'] ) ) ? strip_tags( $new_instance['Title'] ) : '';
        $instance['Shortcode'] = ( ! empty( $val ) ) ? strip_tags( $val ) : 'Select Any Button';
		//$instance['Shortcode']=array_map(callback, arr1)
        return $instance;
    }

} // class Foo_Widget

// register Foo_Widget widget
function register_BUTTON_WIDGET() {
	register_widget( 'WDButton_widget' );
}
add_action( 'widgets_init', 'register_BUTTON_WIDGET' );
?>