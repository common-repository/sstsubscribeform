<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbHelpTourScriptStyle {
	function __construct() {
		$this->sstssfb_enqueuescripts();
	}
	
	function sstssfb_enqueuescripts() {
		add_action('admin_enqueue_scripts', array($this, 'sstssfb_admin_script_style'), 999);
	}

	function sstssfb_admin_script_style() {
		global $pagenow, $typenow;
		
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && ($typenow ==='sstssfb_builder' || $typenow ==='sstssfb_multiemail')) {
			
			// JAVASCRIPT
			wp_enqueue_script( 'sstssfb_helptourscripts_js', SSTSSFB_ADDONS . 'help/js/helptourscripts.js', array(), '1.0.0', true );
			
			//CSS
			wp_enqueue_style('sstssfb_helptourstyle_css',  SSTSSFB_ADDONS . 'help/css/style.css');
		}

	}
}

new sstssfbHelpTourScriptStyle();
?>