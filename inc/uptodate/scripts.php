<?php
if (!defined('ABSPATH')) exit;

class sstssfbStayUpateEnqScriptStyle {
	
	function __construct() {
		$this->sstssfb_enqueuescripts();
	}
	
	function sstssfb_enqueuescripts() {
		add_action('admin_enqueue_scripts', array($this, 'sstssfb_admin_script_style'), 999);
	}

	function sstssfb_admin_script_style() {
		global $pagenow, $typenow;

		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && ($typenow ==='sstssfb_builder' || $typenow ==='sstssfb_multiemail') && !isset($_GET['page']) ) {
			
			// JAVASCRIPT
			wp_enqueue_script( 'sstssfb_uptodatemain_js', SSTSSFB_INCURL . '/uptodate/js/main.js', array(), '1.0.0', true );
			wp_localize_script("sstssfb_uptodatemain_js", "Notify", array("SstssfbNotifyAdmin" => wp_create_nonce("sstssfb_notify_admin_ajax_process")));
			//CSS
			wp_enqueue_style('sstssfb_uptodatemain_css', SSTSSFB_INCURL . "/uptodate/css/main.css");
		}

	}
}

$hide = get_option("sstssfb_notify_admin_hide");		
if($hide != "confirm") {
	new sstssfbStayUpateEnqScriptStyle();
}

?>