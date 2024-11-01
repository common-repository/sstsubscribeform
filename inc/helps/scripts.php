<?php
if (!defined('ABSPATH')) exit;
class sstssfbHelpsScripts {
	
	function __construct() {
		$this->sstssfb_enqueuescripts();
	}
	
	function sstssfb_enqueuescripts() {
		add_action('admin_enqueue_scripts', array($this, 'sstssfbscripts_and_style'), 99999);
	}
	
	function sstssfbscripts_and_style() {
		global $pagenow, $typenow;
		if ($pagenow == 'edit.php' && $typenow ==='sstssfb_builder' && isset($_GET['page'])) {
			
			// JAVASCRIPT
			wp_enqueue_script( 'sstssfb_youtube_platform_js', plugins_url('/js/platform.js', __FILE__), array(), '1.0.0', true );
			// CSS
			wp_enqueue_style('sstssfb_helpsglobalstyle_css', plugins_url("/css/style.css", __FILE__));
		}
	}
}

new sstssfbHelpsScripts();
?>