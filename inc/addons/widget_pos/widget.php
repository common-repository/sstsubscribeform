<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;

class sstssfbWidget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'sstssfb_optinwidget', // Base ID
			__( 'SST Subscribe Form', 'sstssfbuilder' ), // Name
			array( 'description' => __( 'Widget for "SoursopTree Subscribe Form Builder" plugin.', 'sstssfbuilder' ), ) // Args
		);
	}
	
	//Front-end display of widget.
	public function widget( $args, $instance ) {
	  $title = ! empty( $instance['title'] ) ? $instance['title'] : "";
	  $id = ! empty($instance['ssf_id']) ? $instance['ssf_id'] : "";		
		$display = new sstssfbShowForm();
		$form = $display::sstssfb_display();
		if(isset($form[$id])) {
			$place = get_post_meta($form[$id], "sstssfb_placement_data_metakey", true);
			if($place['placement'] != "widget")	{
				return;
			}		
			echo $args['before_widget'];
			
			//title
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'sstssfb_widget_title', $title ). $args['after_title'];
			}

			// content
			echo do_shortcode('[sstssfb_form id="' . $form[$id] . '"]');
			
			echo $args['after_widget'];
		}		
	}
	
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : "";
		$form_id = ! empty( $instance['ssf_id'] ) ? $instance['ssf_id'] : "";
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		
		<?php
		// Loop through SSFB custom post type here to display the id in a dropdown select
		$options = "";
		$the_query = new WP_Query("post_type=sstssfb_builder&field=ids&posts_per_page=-1&meta_key=sstssfb_active_inactive_switcher");	
			if ($the_query->have_posts()) {
				while ($the_query->have_posts()){
				$the_query->the_post();
				$id = get_the_ID();
				$title = get_the_title($id);
				$title = $title == "" ? "Optin form $id" : $title;
				$place = get_post_meta($id, "sstssfb_placement_data_metakey", true);
				if($place['placement'] != "widget")	{
					continue;
				}
				$options .= sprintf(
							'<option value="%1$s" %2$s>%3$s</option>',
							esc_attr($id),
							selected($id, $form_id, false),
							esc_html($title)
							);
			}
			wp_reset_postdata();
		}
		
		$input_id = $this->get_field_id('ssf_id');
		$comm = "";
		if($options == "") {
			$comm = "Please set any form position as widget first!";
		} else {
			$comm = "Select a form..";
		}
		
		echo "<label for='$input_id'>Optin form:</label><br/>";
		printf(
			'<select name="%1$s" id="%2$s" class="widefat"><option value="">%3$s</option>%4$s</select><p></p>',
			esc_attr($this->get_field_name('ssf_id')),
			esc_attr($input_id),
			esc_attr($comm),
			$options
		);
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['ssf_id'] = ( ! empty( $new_instance['ssf_id'] ) ) ? sanitize_text_field( $new_instance['ssf_id'] ) : '';
		
		return $instance;
	}
}

function sstssfb_register_widget() {
    register_widget( 'sstssfbWidget' );
}
add_action( 'widgets_init', 'sstssfb_register_widget' );

class sstssfbWidgetHint {
	function __construct() {
		add_filter("sstssfb_position_hints", array($this, "sstssfb_add_hints"));
	}
	
	function sstssfb_add_hints($hintscontent) {
		$widgurl = admin_url() . "widgets.php";
		$widgurl = "<a href='$widgurl' target='_blank'><b>widget editor page</b></a>";
		$hintscontent .= "<span class='widget_hints'>Please go to $widgurl and then drag n drop <b><i>SST Subscribe Form</i></b> widget to the sidebar area to set your subscribe form as widget! Select this option if you want it can be shown as widget and shortcode.</span>";
		return $hintscontent;
	}
}
new sstssfbWidgetHint();
?>