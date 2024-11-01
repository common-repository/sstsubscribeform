<?php
if (!defined('ABSPATH')) exit;

class sstssfbAddonsManagementScript {
		
	function __construct() {
		add_action("admin_enqueue_scripts", array($this, "sstssfb_addons_management_panel"), 99999);
	}
	
	function sstssfb_addons_management_panel() {
		global $pagenow, $typenow;
		
		if($pagenow == "edit.php" && $typenow == "sstssfb_builder" && isset($_GET['page']) && ($_GET['page'] == "sstssfb-addons_manager_page" || $_GET['page'] == "sstssfb-theme_uploader")) {
			// Scripts		
			wp_enqueue_script( 'sstssfb_addonmanagermain_js', SSTSSFB_INCURL . 'addonsmanager/js/jsmain.js', array(), '1.0.0', true );
			wp_localize_script(
								"sstssfb_addonmanagermain_js",
								"AddonsManager",
								array(
									   "SstssfbAddonsManagerAdmin" => wp_create_nonce("sstssfb_addons_management_admin_ajax_process")
									)
							   );
			
			// Style
			wp_enqueue_style('sstssfb_addonsmanager_css', SSTSSFB_INCURL . "addonsmanager/css/global.css");
		}
		
	}
}

new sstssfbAddonsManagementScript();

?>