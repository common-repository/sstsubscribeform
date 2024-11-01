<?php
if (!defined('ABSPATH')) exit;
class sstssfbDeleteFormAjax {
	function __construct() {
		add_action("wp_ajax_delete_form", array($this, "sstssfbajax_delete_form"));
	}
	
	function sstssfbajax_delete_form() {
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
		
		// delete displayed theme first...
		$theme = get_post_meta($id, "sstssfb_save_themedata_metakey", true);
		
		if(isset($theme['theme'])) {		
			$themefile_paths = SSTSSFB_THEMEDIR . $theme['theme'];
			$theme_displayed_dir = dirname($themefile_paths) . '/displayed/';
			$basename = basename($themefile_paths);
			$css_path = $theme_displayed_dir . $basename . $id . ".css";
			$html_path = $theme_displayed_dir . $basename . $id . ".html";
			if(file_exists($css_path) && file_exists($html_path)) {
				unlink($css_path);
				unlink($html_path);			
			} else {
				echo "There is something wrong when deleting the theme files!";
			}
		}
		
		$deletepost = wp_delete_post($id, true);
		$deletepost = json_encode($deletepost);
		$deletepost = json_decode($deletepost, true);
		echo $deletepost['ID'];
			
		exit;
	}
}
new sstssfbDeleteFormAjax();
?>