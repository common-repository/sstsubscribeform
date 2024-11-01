<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;

class sstssfbFlyIn {
	function __construct() {
			add_action("wp_footer", array($this, "display"));
			add_filter("sstssfb_position_hints", array($this, "sstssfb_add_hints"));
			add_action("sstssfb_subscribeform_after", array($this, "sstssfb_flyin_enqueue_scripts"), 99999, 3);
	}
	
	function display() {
		global $wpdb, $post;
		$display = new sstssfbShowForm();
		$forms = $display::sstssfb_display();
		
		if(empty($forms) || !is_array($forms)) {
			return;
		}
		
		// Loop
		// show/ put a copy of selected theme to the footer if there are set for this page and in fly-in position
		foreach($forms as $key => $fr_id) {
			$place = get_post_meta($fr_id, "sstssfb_placement_data_metakey", true);			
			if(isset($place["placement"]) && $place["placement"] == "fly-in"){
				echo do_shortcode("[sstssfb_form id='$fr_id']");
			}
		}
	}
	
	function sstssfb_add_hints($hintscontent) {
		$hintscontent .= "<span class='fly-in_hints'>Display subscribe form as fly in slide on the bottom right corner of your website's page!</span>";
		return $hintscontent;
	}
	
	function sstssfb_flyin_enqueue_scripts($args) {
		$pos = $args[2];
		if($pos == "fly-in") {
			wp_enqueue_script( 'sstssfb_posfront_js', SSTSSFB_ADDONS . 'fly-in_pos/asset-ex/js/front.js', array(), '1.0.0', true );
			wp_enqueue_style( "sstssfb_posfront_css", SSTSSFB_ADDONS . "fly-in_pos/asset-ex/css/style.css");
			return;			
		}
	}
}
new sstssfbFlyIn();

class sstssbAddMarkToEndOfPost {
	function __construct() {
		add_filter("the_content", array($this, "postfilter"));
	}
	
	function postfilter($content) {
		$content .= '<span class="sstssfbendofpost"></span>';
		return $content;
	}
}
new sstssbAddMarkToEndOfPost();
?>