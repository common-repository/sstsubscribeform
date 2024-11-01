<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbCloseMinimize {
	function __construct() {
		add_filter("sstssfb_front_additional_elem", array($this, "sstssfb_add_closeminimize"));
		add_filter("sstssfb_theme_handler_after", array($this, "sstssfb_add_closeminimize"));
		add_action("sstssfb_subscribeform_after", array($this, "sstssfb_enqueue_scripts"), 99999, 3);
		add_action("admin_enqueue_scripts", array($this, "sstssfb_enqueue_scripts"), 99999, 3);
	}
	
	function sstssfb_add_closeminimize($elem) {

		$elem .= "<div class='sstssfbcloseminimizewrapper' style='display: none;'>";
		$elem .= "<span class='dashicons dashicons-no-alt sstssfbclose'></span>";		
		$elem .= "<span class='dashicons dashicons-arrow-right-alt2 sstssfbminimize'></span>";		
		$elem .= "</div>";		
		$elem .= "<div class='sstssfbmaximizewrapper' style='display: none;'>";
		$elem .= "<span class='dashicons dashicons-arrow-left-alt2 sstssfbmaximize_right'></span>";
		$elem .= "</div>";
		
		return $elem;
	}
	
	function sstssfb_enqueue_scripts() {
		if(!wp_style_is( "dashicons", $list = 'enqueued' )) {
			wp_enqueue_script("dashicons");
		}
		wp_enqueue_script( 'sstssfb_openclosejs', SSTSSFB_ADDONS . 'closeminimize/asset-ex/js/openclose.js', array(), '1.0.0', true );
		wp_enqueue_style( "sstssfb_openclosecss", SSTSSFB_ADDONS . "closeminimize/asset-ex/css/style.css");
	}
	
}
new sstssfbCloseMinimize();
?>