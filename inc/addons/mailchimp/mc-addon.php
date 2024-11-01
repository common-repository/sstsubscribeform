<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
require_once("config-ex.php");

class sstssfbMChimpAdmin {
	function __construct() {
		add_action("sstssfb_autoresponder", array($this, "sstssfb_emailServiceAdmin"));
		add_filter('sstssfb_autoresponder_list', array($this, "sstssfb_register_service"));
	}
	
	// Register service to plugin
	function sstssfb_register_service($autoresponder) {
		 $autoresponder['sstssfb_mchimp'] = 'Mailchimp';
		 return $autoresponder;
	}
	
	function sstssfb_emailServiceAdmin() {
		global $post;
		$mchimpdata = get_post_meta($post->ID,"sstssfb_mailchimp_saved_metakey", true);
		// show this on multiaccounts post type too
		?>
		<div id="sstssfb_mchimp" class="mail_service_option">
		<div id="error_notification"></div>
			<table>
			<tr class="mchimp_row_hide mail_row_hide">
				<td><label for="sstssfb_mchimp_name">Give a Name</label></td>
				<td><input type="text" name="sstssfb_mchimp_id[name][]" value="" id="sstssfb_mchimp_name" size="30"/></td>
				<td><span class="mchimphelp dashicon">Type any name</span></td>
			</tr>
			<tr class="mchimp_row_hide mail_row_hide">
				<td><label for="sstssfb_mchimp_api_key">Api Key</label></td>
				<td><input type="text" name="sstssfb_mchimp_id[api_key][]" value="" id="sstssfb_mchimp_api_key" size="30"/></td>
				<td><span class="mchimphelp dashicon"><a target="_blank" href="http://kb.mailchimp.com/article/where-can-i-find-my-api-key/">Help?</a></span></td>
			</tr>
			<tr class="submit_tr mchimp_row_hide mail_row_hide">
				<td></td>
				<td><button id="mail_submit_button" class="button">Authenticate</button></td>
				<td><span class="button button-small cancel_new_account">cancel</span></td>
			</tr>
			</table>
		</div>		
		<?php
		
		$selection = isset($mchimpdata['subscribe']) ? $mchimpdata['subscribe'] : "";
		if(is_array($mchimpdata) && !empty($mchimpdata)) {
			$data_lists = json_decode($mchimpdata['objects'], true);
			$selected = $mchimpdata['lists'];
			$options = "";
			foreach($data_lists as $key => $value) {
					$lid = esc_attr($key);
					$lname = esc_html($value);
					$list_ids[$lid] = $lname;
					
					$options .= sprintf(
									'<option value="%1$s" %2$s>%3$s</option>',
									$lid,
									selected($lid, $selected, false),
									$lname
									);
				}
				printf(
				'<table id="sstssfb_mchimp_result" class="sstssfb_mail_result">
				<tbody>
				<tr class="mchimp_lists">
				<td><label for="mchimp_select_options">List Name:</label></td>
				<td><select id="mchimp_select_options" name="%1$s">
				%2$s</select>
				</td>
				</tr>
				<tr class="mchimp_lists">
				<td></td>
				<td><input title="Abusing this feature may result in banned account!" type="checkbox" id="disable_double_optin" name="mchimp_data[subscribe]" class="warning" %3$s/><label class="warning" title="Abusing this feature may result in banned account!" for="disable_double_optin">Disable double optin</label></td>
				</tr>
				<tr class="mchimp_lists">
				<td></td>
				<td><span class="button button-small add_new_account">new account</span></td>
				</tr>
				</tbody></table><input type="hidden" name="mchimp_data[objects]" value="%4$s"/>',
				esc_attr('mchimp_data[lists]'),
				$options,
				checked($selection, "on", false),
				esc_attr($mchimpdata['objects'])
				);
		}
		
	}	
}
new sstssfbMChimpAdmin();
?>