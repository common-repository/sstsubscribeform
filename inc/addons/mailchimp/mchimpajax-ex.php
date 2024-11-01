<?php
use \VPS\MailChimp;
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbMchimpAjax {
	function __construct() {
		add_action("wp_ajax_mchimp_admin", array($this, "sstssfb_mchimp_admin_ajax"));
		add_action("admin_enqueue_scripts", array($this, "sstssfb_mchimp_enqueue_scripts"), 99999);
	}
	
	function sstssfb_mchimp_admin_ajax(){
		global $wpdb, $wp_error;
		$nonce = "";
		if(isset($_POST['nonce'])) {
			$nonce = $_POST['nonce'];
		}
		if(!wp_verify_nonce($nonce, 'sstssfb_admin_ajax_process')) {
			exit;
		}
		
		$reqtype = isset($_POST['req_type']) && $_POST['req_type'] != "" ? $_POST['req_type'] : "";
		$wrapper = isset($_POST['wrppr']) && $_POST['wrppr'] != "" ? wp_strip_all_tags($_POST['wrppr']) : "";		
		$name = isset($_POST['id']) && $_POST['id'] != "" ? wp_strip_all_tags($_POST['id']) : "";		
		$post_type = 'sstssfb_multiemail';
		$post_id = isset($_POST['post_id']) && $_POST['post_id'] != "" ? $_POST['post_id'] : "";
		
		
			// CHECK IF THERE ARE ALREADY SAVED ACCOUNT
			if($reqtype == 'retrieval') {
				if(post_type_exists($post_type)) {
					$metakey = 'sstssfb_autoresponder_service_vendor';
					$saved_accs = '';
					$not_blank = 'true';
						$the_query = new WP_Query("post_type=$post_type&meta_key=$metakey&meta_value=$name&posts_per_page=-1");	
						if ($the_query->have_posts()) {
							while ($the_query->have_posts()){
							$the_query->the_post();
								$data_id = get_the_ID();							
								$title = the_title();
								$saved_accs .= sprintf(
												'<options value="%1$s">%$2s</option>',
												esc_attr($data_id),
												esc_attr($title)
												);				
							}
							wp_reset_postdata();
							printf(
							'<select name="mchimp_data[name_id]" id="mailchimp_select_name"><option value="add_new">Add account</option>%1$s</select>',
							$saved_accs
							);
						} else {
							$not_blank = 'false';
							echo $not_blank;
							exit;
						}
			} else {
				$mchimpdata = get_post_meta($post_id,"sstssfb_mailchimp_saved_metakey", true);
				$selection = isset($mchimpdata['objects']) ? $mchimpdata['objects'] : "";
				if(is_array($mchimpdata) && !empty($mchimpdata) && $selection != "") {
					echo "exist";
					exit;
				} else {
					echo "no_exist";
					exit;
				}
			}
		}
	
		// RETRIEVE LIST IDS BASED ON ACCOUNT NAME
		if($reqtype == 'retrieve_lid') {
			$acc_id = isset($_POST['post_id']) && $_POST['post_id'] != "" ? $_POST['post_id'] : "";
			$list_ids = "";
							$lists = get_post_meta($acc_id, "sstssfb_mailchimp_metadata_key", true);							
							foreach($data_accounts as $key => $detail) {
								if(is_array($lists)) {
									foreach($lists as $key => $val) {
										$list_ids .= sprintf(
												'<options value="%1$s">%$2s</option>',
												esc_attr($key),
												esc_html($val)
												);
									}
								}
							}					
					printf(
					'<select name="mchimp_data[lists]"><option value="none">Select list..</option>%1$s</select>',
					$list_ids
					);
		}
		
		// REGISTRATION PROCESS
		if($reqtype == 'authentication') {
			$api_key = isset($_POST['api']) && $_POST['api'] != "" ? sanitize_text_field($_POST['api']) : "";
			$api_key = esc_attr($api_key);
			$list_ids = array();
		
			// Authentication
			$mc = new MailChimp();
			$mc->setApiKey($api_key);
			$result = $mc->get('/lists/');
			$generated_el = '';
			if(!empty($result)) {
				foreach($result['lists'] as $key => $list){
					$lid = esc_attr($list['id']);
					$lname = esc_html($list['name']);
					$list_ids[$lid] = $lname;
					
					$generated_el .= sprintf(
									'<option value="%1$s">%2$s</option>',
									$lid,
									$lname
									);
				}
			printf(
			'<tr class="mchimp_lists">
			<td><label for="mchimp_select_options">List Name:</label></td>
			<td><select id="mchimp_select_options" name="%1$s">%2$s</select></td>
			</tr>
			<tr class="mchimp_lists">
			<td></td>
			<td><input class="warning" title="Abusing this feature may result in banned account!" type="checkbox" value="on" id="disable_double_optin" name="mchimp_data[subscribe]"><label class="warning" title="Abusing this feature may result in banned account!" for="disable_double_optin">Disable double optin</label></td>
			</tr>
			<input type="hidden" name="mchimp_data[objects]" value="%3$s"/>',
			esc_attr('mchimp_data[lists]'),
			$generated_el,
			esc_attr(json_encode($list_ids))
			);
			} else {
				echo "invalid";
				exit;
			}
			
			// Save mailchimp data to multiple_mail custom post type
			if(post_type_exists($post_type)) {
				$mail_data = array(
							sanitize_key("post_type") => esc_attr("sstssfb_multiemail"),
							sanitize_key("post_title") => esc_attr($name),
							sanitize_key("post_status") => esc_attr("publish")							
							);
				$post_id = wp_insert_post($mail_data, $wp_error);
				$post_id = esc_attr($post_id);
				
				$vendor = esc_attr("sstssfb_mchimp");
				$values = array(
							esc_attr("api_key") => esc_attr($api_key),
							esc_attr("lists") => esc_attr($list_ids)
						);
				update_post_meta($post_id, sanitize_key("sstssfb_mailchimp_metadata_key"), $values);
				update_post_meta($post_id, sanitize_key("sstssfb_autoresponder_service_vendor"), $vendor);				
			} 
		}
			
		wp_die();	
	}
	
	function sstssfb_mchimp_enqueue_scripts() {
		global $pagenow, $typenow;		
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && ($typenow ==='sstssfb_builder' || $typenow ==='sstssfb_multiemail') && !isset($_GET['page'])) {
			wp_enqueue_script("sstssfb_mchimp_admin_js", SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/mchimp.js", array("jquery"), '0.1', true);
			wp_localize_script("sstssfb_mchimp_admin_js", "MchimpAdmin", array("SstssfbAdminAjax" => wp_create_nonce("sstssfb_admin_ajax_process")));
			wp_enqueue_style('sstssfb_mchimp_css', SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/mchimp.css");			
		}
	}
}
new sstssfbMchimpAjax();
?>