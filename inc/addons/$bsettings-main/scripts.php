<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbMailAccountScript {
	function __construct() {
		add_action("admin_enqueue_scripts", array($this, "sstssfb_mchimp_enqueue_scripts"), 99999);
	}	
	function sstssfb_mchimp_enqueue_scripts() {
		global $pagenow, $typenow;		
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow ==='sstssfb_builder' && !isset($_GET['page'])) {
			wp_enqueue_script("sstssfb_mailadmin_js", SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/admin/mailadmin.js", array(), '0.1', true);
			wp_enqueue_style("sstssfb_mailadmin_css", SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/admin/mailadmin.css");
		}
	}
}

new sstssfbMailAccountScript();
?>