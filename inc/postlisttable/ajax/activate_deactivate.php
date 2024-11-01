<?php
if (!defined('ABSPATH')) exit;
class sstssfbActiveDeactiveAjax {
	function __construct() {
		add_action("wp_ajax_active_deactive", array($this, "sstssfbajax_active_deactivate"));
	}
	
	function sstssfbajax_active_deactivate() {
		global $wpdb, $post;
		$nonce = "";
		if(isset($_POST['nonce'])) {
			$nonce = $_POST['nonce'];
		}
		if(!wp_verify_nonce($nonce, 'sstssfb_useractions_admin_ajax_process')) {
			exit;
		}
		$id = isset($_POST['id']) && $_POST['id'] != "" ? $_POST['id'] : "";
		
		if($id == "")
			exit;

			 $provider = get_post_meta($id, "sstssfb_autoresponder_saved_metakey", true);
			 $provider = isset($provider['service']) ? $provider['service'] : "selectone..";
			 $list = "";
			 $warning = "";
			 
			 if($provider != "selectone..") {				 
				 $list = get_post_meta($id, "sstssfb_$provider" . "_saved_metakey", true);
				 $list = $list['lists'];
			 }
			 
			 if($provider == "selectone.." || $list == "Select one..") {
				 echo "null";
				exit;				 
			 }

		
		$status = get_post_meta($id, "sstssfb_active_inactive_switcher", true);
		$status = isset($status['active']) ? $status['active'] : "";
		
		if($status == "active") {
			delete_post_meta($id, "sstssfb_active_inactive_switcher");
			update_post_meta($id, sanitize_key("sstssfb_active_default"), esc_attr("off"));	
			exit;
		}
		
		$val = array(
				esc_attr("active") => esc_attr("active")
			);
		update_post_meta($id, sanitize_key("sstssfb_active_inactive_switcher"), $val);	
		exit;
	}
}
new sstssfbActiveDeactiveAjax();
?>