<?php
if (!defined('ABSPATH')) exit;
class sstssfbCloneFormAjax {
	function __construct() {
		add_action("wp_ajax_clone_form", array($this, "sstssfbajax_clone_form"));
	}
	
	function sstssfbajax_clone_form() {
		global $wpdb, $post, $wp_error;
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

		$form = get_post($id);		
		$formname = $form->post_title != '' ?  esc_attr($form->post_title . ' copy') :  esc_attr("form $id - copy");		
		// Create form's core data
		$clone = array(
			'post_name' => wp_strip_all_tags($form->post_name . '_copy'),
			'post_title' => $formname,
			'post_status' => 'publish',
			'post_type'  => 'sstssfb_builder',
			'post_author' => esc_html($form->post_author),
			'post_date' => date('Y-m-d H:i:s')
		);
		$cloneid = wp_insert_post($clone, false);		
		
		if($cloneid == 0) {
			echo $cloneid;
			exit;
		}
		
		// Get cloned form metadata
		$metadata = get_post_custom_keys($id);
		
		// Clone form meta
		foreach($metadata as $key => $value) {
			$metavalue = get_post_meta($id, $value, true);
			update_post_meta($cloneid, sanitize_key($value), $metavalue);
		}


		// Make a copy of displayed theme
		$theme = get_post_meta($id, "sstssfb_save_themedata_metakey", true);
		if(!isset($theme['theme'])) {
			echo "Theme isn't set!";
			exit;
		}
		
		$themefile_paths = SSTSSFB_THEMEDIR . $theme['theme'];
		$theme_displayed_dir = dirname($themefile_paths) . '/displayed/';
		$basename = basename($themefile_paths);
		$css_path = $theme_displayed_dir . $basename . $id . ".css";
		$html_path = $theme_displayed_dir . $basename . $id . ".html";
		$css_pathcopy = $theme_displayed_dir . $basename . $cloneid . ".css";
		$html_pathcopy = $theme_displayed_dir . $basename . $cloneid . ".html";
		
		// START WP_FILESYSTEM
		$access_type = get_filesystem_method();
			if($access_type === 'direct') {
				$url = site_url() . '/wp-admin/';
				$creds = request_filesystem_credentials($url, '', false, false, array());
				
				/* initialize the API */
					if ( ! WP_Filesystem($creds) ) {
						return false;
					}
				global $wp_filesystem;
				
				// Edit displayed theme
				$css_original = $wp_filesystem->get_contents($css_path);
				$html_original = $wp_filesystem->get_contents($html_path);
				
				preg_match_all('/(?ims)([a-z0-9\s\.\,\[\]\=\"\:\+\#_\-@]+)\{([^\}]*)\}/', $css_original, $match);
				$parent_or = $match[1][0];				
				$parent_id = trim(str_replace("#", "", $parent_or));
				$attr_id = str_replace($id, $cloneid, $parent_id);
				
				// edit css and html
				$csscopy = str_replace($parent_id, $attr_id, $css_original);
				$htmlcopy = str_replace($parent_id, $attr_id, $html_original);
				
				$wp_filesystem->put_contents($css_pathcopy, sanitize_text_field($csscopy), FS_CHMOD_FILE);
				$wp_filesystem->put_contents($html_pathcopy, sanitize_text_field($htmlcopy), FS_CHMOD_FILE);
			}
		
		exit;
	}
}
new sstssfbCloneFormAjax();
?>