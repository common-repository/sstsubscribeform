<?php
if (!defined('ABSPATH')) exit;
class sstssfbCustomLisTableScripts {
	
	function __construct() {
		$this->sstssfb_enqueuescripts();
	}
	
	function sstssfb_enqueuescripts() {
		add_action('admin_enqueue_scripts', array($this, 'sstssfbscripts_and_style'), 99999);
	}
	
	function sstssfbscripts_and_style() {
		global $pagenow, $typenow;
		if (($pagenow == 'edit.php' && $pagenow != 'post.php' && $pagenow != 'post-new.php') && ($typenow ==='sstssfb_builder' || $typenow ==='sstssfb_multiemail') && !isset($_GET['page'])) {
			
			// Javascript
			wp_enqueue_script( 'sstssfb_listtable_js',  plugins_url("assets/js/listtable.js", __FILE__), array(), '1.0.0', true );
			wp_localize_script("sstssfb_listtable_js", "ActionIcons", array("SstssfbUserActions" => wp_create_nonce("sstssfb_useractions_admin_ajax_process")));
			
			// CSS
			wp_enqueue_style('sstssfb_listtable_global_css', plugins_url("assets/css/global.css", __FILE__));
			wp_enqueue_style('sstssfb_listtable_actions_css', plugins_url("assets/css/actions.css", __FILE__));
			
		}
	}
}

new sstssfbCustomLisTableScripts();
?>