<?php
if (!defined('ABSPATH')) exit;
class sstssfbSave {

	function __construct() {
		add_action( 'save_post', array( $this, 'sstssfb_save' ) );
	}

	function sstssfb_save( $post_id ){
	global $post;
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['sstssfb_meta_box_nonce'] ) )
			return;

		$nonce = $_POST['sstssfb_meta_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'sstssfb_save_meta_box_data' ) )
			return;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		// Check the user's permissions.
		if ( 'sstssfb_builder' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return;
		}
		/* OK, its safe for us to save the data now. */	

		//On Off Switch
		if(isset($_POST["sstssfb_activate"]) && !empty($_POST['sstssfb_activate']) && is_array($_POST['sstssfb_activate'])) {
			$active = $_POST['sstssfb_activate'];
			$active = esc_attr($active['active']);
			$active = array(esc_attr('active') => $active);
			update_post_meta($post_id, sanitize_key("sstssfb_active_inactive_switcher"), $active);
		} else {
			delete_post_meta($post_id,"sstssfb_active_inactive_switcher");
			update_post_meta($post_id, sanitize_key("sstssfb_active_default"), esc_attr("off"));
		}
		
		// Theme
		$customvals = array();
		$theme_edited = "";
		$theme_to_save = "";
		$css_to_save = "";
		$parent_id = "";
		
		if(isset($_POST["sstssfb_custom"]) && !empty($_POST['sstssfb_custom']) && is_array($_POST['sstssfb_custom'])) {
				$custom = $_POST['sstssfb_custom'];				
				$use_edited = esc_attr($custom['use_edited']);
				$previous = sanitize_text_field($custom['previous']);
				$theme_edited =  htmlentities(stripslashes($custom['theme_edited']), ENT_QUOTES, 'UTF-8');
				$customvals[esc_attr('previous')] = $previous;
				$customvals[esc_attr('theme_edited')] = $theme_edited;
				$customvals[esc_attr('use_edited')] = $use_edited;	
		}
		
		if(isset($_POST['sstssfb']) && !empty($_POST['sstssfb']) && is_array($_POST['sstssfb'])) {
			$data = $_POST['sstssfb'];			
			$data = sanitize_text_field($data['theme']);
			$data = array(esc_attr('theme') => $data);	
			update_post_meta($post_id, sanitize_key("sstssfb_save_themedata_metakey"), $data);
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

					$data = $_POST['sstssfb'];
					$themefile_paths = SSTSSFB_THEMEDIR . $data['theme'];
					$theme_displayed_dir = dirname($themefile_paths) . '/displayed/';
					$basename = basename($themefile_paths);
					
					$theme_html = "$themefile_paths.html";
					
					$theme_css = "$themefile_paths.css";
					
					$css_original = $wp_filesystem->get_contents($theme_css);
					preg_match_all('/(?ims)([a-z0-9\s\.\,\[\]\=\"\:\+\#_\-@]+)\{([^\}]*)\}/', $css_original, $match);
					$parent_or = $match[1][0];
					
					$parent_id = trim(str_replace("#", "", $parent_or));
					$customvals[esc_attr('parent_id')] = sanitize_text_field($parent_id);
					$new_id = $parent_id . $post_id;
					$new_id = str_replace(" ", "", $new_id);
				
					$new_cssid = trim($parent_id) . $post_id . " ";
					
					// If edited theme is exist
					if(trim($theme_edited) != "" && $use_edited == "on") {									
						$theme_to_save = str_replace($parent_id, $new_id, $theme_edited);						
					} else {
						$theme_original = $wp_filesystem->get_contents($theme_html);
						$theme_to_save = str_replace($parent_id, $new_id, $theme_original);
						$theme_to_save = htmlentities(stripslashes($theme_to_save), ENT_QUOTES, 'UTF-8');						
					}
							
					$css_to_save = str_replace("$parent_id", "$new_cssid", $css_original);
					
					if(!$wp_filesystem->is_dir($theme_displayed_dir)) 
					{
						$wp_filesystem->mkdir($theme_displayed_dir);
					}
					
					$css_path = $theme_displayed_dir . $basename . $post_id . ".css";
					$html_path = $theme_displayed_dir . $basename . $post_id . ".html";
					$wp_filesystem->put_contents($css_path, sanitize_text_field($css_to_save), FS_CHMOD_FILE);
					$wp_filesystem->put_contents($html_path, sanitize_text_field($theme_to_save), FS_CHMOD_FILE);
				}			
		}
		update_post_meta($post_id, sanitize_key("sstssfb_custom_saved_metakey"), $customvals);
		
		// Email Service
		if(isset($_POST["sstssfb_mail"]) && !empty($_POST['sstssfb_mail']) && is_array($_POST['sstssfb_mail'])) {
			$emailservice = $_POST['sstssfb_mail'];
				$service = array();
				foreach($emailservice as $key => $value) {
					if(is_array($value)) {
						foreach($value as $k => $v) {
							$service[sanitize_text_field($key)][sanitize_text_field($k)] = sanitize_text_field($v);
						}
					} else {
						$service[sanitize_text_field($key)] = sanitize_text_field($value);
					}
				}
			update_post_meta($post_id, sanitize_key("sstssfb_autoresponder_saved_metakey"), $service);
			// Deactivate form if email service isn't set
			if(isset($service['service']) && $service['service'] == "selectone..") {
				delete_post_meta($post_id,"sstssfb_active_inactive_switcher");
				update_post_meta($post_id, sanitize_key("sstssfb_active_default"), esc_attr("off"));
			}
		}		
		

		// Rules
		if(isset($_POST["sstssfb_rules"]) && !empty($_POST['sstssfb_rules']) && is_array($_POST['sstssfb_rules'])) {
			$rulesdata = $_POST['sstssfb_rules'];
				$rules_array = array();
				foreach($rulesdata as $key => $value) {
					if(is_array($value)) {
						foreach($value as $k => $v) {
							$rules_array[sanitize_text_field($key)][sanitize_text_field($k)] = sanitize_text_field($v);
						}
					} else {
						$rules_array[sanitize_text_field($key)] = sanitize_text_field($value);
					}
				}			
			update_post_meta($post_id, sanitize_key("sstssfb_save_rulesdata_metakey"), $rules_array);
		}					
		
		// Placement
		if(isset($_POST["sstssfb_place"]) && !empty($_POST['sstssfb_place']) && is_array($_POST['sstssfb_place'])) {
			$place_data = $_POST['sstssfb_place'];
				$placements = array();
				foreach($place_data as $key => $value) {
					if(is_array($value)) {
						foreach($value as $ky => $val) {
							$placements[sanitize_text_field($key)][sanitize_text_field($ky)] = sanitize_text_field($val);
						}
					} else {
						$placements[sanitize_text_field($key)] = sanitize_text_field($value);
					}
				}			
			update_post_meta($post_id, sanitize_key("sstssfb_placement_data_metakey"), $placements);
		}		
		
		
		// Location		
		if(isset($_POST["sstssfb_loc"]) && !empty($_POST['sstssfb_loc']) && is_array($_POST['sstssfb_loc'])) {
			$locdata = $_POST['sstssfb_loc'];
				$locations = array();
				foreach($locdata as $key => $value) {
					if(is_array($value)) {
						foreach($value as $ky => $val) {
							$locations[sanitize_text_field($key)][sanitize_text_field($ky)] = sanitize_text_field($val);
						}
					} else {
						$locations[sanitize_text_field($key)] = sanitize_text_field($value);
					}
				}			
			update_post_meta($post_id, sanitize_key("sstssfb_save_locdata_metakey"), $locations);
		}
		
		if(!isset($_POST["sstssfb_loc"]['parent'])) {
			$locations = array();
			$locations[esc_attr('parent')] = esc_attr("all_loc");
			update_post_meta($post_id, sanitize_key("sstssfb_save_locdata_metakey"), $locations);
		}
	}
}
new sstssfbSave();