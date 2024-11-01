<?php
use \VPS\MailChimp;
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbMchimpFrontAjax {	
	
	function __construct() {
		add_action("sstssfb_subscribe_process", array($this, "sstssfb_mchimp_front_ajax"));
	}
		
	public function sstssfb_mchimp_front_ajax() {
		global $post, $wpdb;
		
		$values = isset($_POST['values']) ? $_POST['values'] : "";		
		if(isset($values["service"]) && $values["service"] == "mailchimp") {

		if(!isset($values['id'])) {
			die("Registration cannot be processed!");
		}
		
		$id = $values['id'];
		$mchimpdata = get_post_meta($id,"sstssfb_mailchimp_saved_metakey", true);
		$meta = get_post_meta($id, "sstssfb_mailchimp_saved_api_metadata", true);
		$api_key = isset($meta['api_key'][0]) ? $meta['api_key'][0] : "";
		$list_id = isset($mchimpdata['lists']) ? $mchimpdata['lists'] : "";
		$status = isset($mchimpdata['subscribe']) ? "subscribed" : "pending";
		
		$mc = new MailChimp();
		$mc->setApiKey($api_key);
		
		// CHECK FOR LNAME MERGE FIELDS and also create one if not exists
		if(get_option("mailchimp_lname_tags") != "registered"){
			$result = $mc->post("/lists/$list_id/merge-fields", array(
							"tag" => "LNAME",
							"name" => "Last name",
							"type" => "text",
							"public" => true,
							"help_text" => "Subscriber's last name"
						));
			update_option(sanitize_key("mailchimp_lname_tags"), esc_attr("registered"));			
		}
		
		$subscription_data = array();
		foreach($values as $key => $val) {
			if(trim($key) == 'email') {
				$subscription_data['email_address'] = trim($val);
			} elseif(trim($key) == 'fname' && trim($val) != "") {
				if(strpos(trim($val), " ") === false) {
					$subscription_data['merge_fields']['FNAME'] = trim($val);
				} else {
					list($fname, $lname) = explode(" ", trim($val), 2);
					$subscription_data['merge_fields']['FNAME'] = trim($fname);
					$subscription_data['merge_fields']['LNAME'] = trim($lname);
				}
			}
		}
		$subscription_data['status'] = $status;
		
		$md5email = md5(strtolower($values['email']));
		$check = $mc->get("/lists/$list_id/members/$md5email");
		
		$action = "";
		$result = "";
		$response = "";
		if(isset($check['status'])) {
			if($check['status'] == "subscribed") {
				$action = "deny";
				echo $action;
		exit;
			} elseif($check['status'] == "pending") {
				$action = "confirmation";
				echo $action;
		exit;
			} elseif($check['status'] == "404") {
				$action = "subscription";
				$result = $mc->post("/lists/$list_id/members", $subscription_data);
				$response = isset($result['id']) && $result['id'] == md5(strtolower($values['email'])) ? "success" : $result["detail"];
			} elseif($check['status'] == "cleaned" || $check['status'] == "unsubscribed") {
				$action = "update";
				unset($subscription_data['email_address']);
				$result = $mc->patch("/lists/$list_id/members/$md5email", $subscription_data);
				$response = isset($result['id']) && $result['id'] == md5(strtolower($values['email'])) ? "success" : $result["detail"];
			}
		}
		
		if($response == "success") {
			echo $action . $status;
		} else { 
			echo $response;
		}
			
		exit;	
		}
	}	
}

new sstssfbMchimpFrontAjax();
?>