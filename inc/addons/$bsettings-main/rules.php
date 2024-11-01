<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbRules extends sstssfbAdminSettings {
	
	function __construct() {		
		add_action("sstssfb_settings_tab", array($this, "sstssfb_tabbutton"), 4);
		add_action("sstssfb_settings_tabbox", array($this, "sstssfb_tabcontent"), 4);
	}	
	
	function sstssfb_tabbutton() {
		?>
			<li><a href="#tab-2">Rules</a></li>
		<?php
	}

	function sstssfb_tabcontent() {
		?>
			<div id="tab-2" class="tab-item">
			<div class="sstssfbtabheader_area">
			<h1>RULES</h1>	
			<span>Set rules for your subscribe form. This will override the settings you defined in the <b>Locations</b> tab.</span>
			</div>
				<?php $this->sstssfb_tabcontentitem(); /* predefined rules items */ ?>
			</div>
		<?php
	}
	
	function sstssfb_tabcontentitem() {
		global $CountryNames, $post;
		$rules = get_post_meta($post->ID, "sstssfb_save_rulesdata_metakey", true);
		$rdr = get_post_meta($post->ID, "sstssfb_redirection_rules_metakey", true);
		$dfl = !is_array($rules) && !isset($rules['login_also']) ? "checked" : "";
		?>
		<div class="sstssfb_tabcontent_area">
			<table id="additional_rules">
			<tbody>
			
			<tr><td><input type="checkbox" id="to_login_user" name="sstssfb_rules[login_also]" value="to_login_user" <?php echo isset($rules['login_also']) && $rules['login_also'] == "to_login_user" ? "checked" : $dfl; ?> /><label for="to_login_user">Show to logged-in user</label></td></tr>
			
			<tr><td><input type="checkbox" id="exclude_mobile" name="sstssfb_rules[exclude_mobile]" value="exclude_mobile" <?php echo isset($rules['exclude_mobile']) ? "checked" : ""; ?>/><label for="exclude_mobile">Hide on mobile devices</label></td></tr>
			
			<tr><td><input type="checkbox" id="sev_only" name="sstssfb_rules[sev_only]" value="sev_only" <?php echo isset($rules['sev_only']) ? "checked" : ""; ?>/><label for="sev_only">Search engine visitors only</label></td></tr>
			
			<tr><td><input type="checkbox" id="use_cookies" name="sstssfb_rules[cookies]" value="use_cookies" <?php echo isset($rules['cookies']) ? "checked" : ""; ?>/><label for="use_cookies">Show Every <i>(use cookie)</i>:</label></td></tr>
			<tr><td><input type="text" class="medium_input_field" id="cookies_expr" name="sstssfb_rules[cookiesval]" value="<?php echo isset($rules['cookiesval']) ? $rules['cookiesval'] : ""; ?>"  placeholder="Expire after ..."/> <span class="sstssfb_label_class">Days</span></td></tr>
			
			</tbody>
			</table>
			
			<div id="country_items">
			<h5 id="country_label">Show only for the following geo area:</h5>
			<select multiple class="sstssfb_sltmultiple" name="sstssfb_rules[countryitem][]">
			<?php
			foreach($CountryNames as $key => $country){
			?>
			<option value="<?php echo $country; ?>" <?php echo isset($rules['countryitem']) && is_array($rules['countryitem']) ? in_array($country, $rules['countryitem']) ? 'selected' : '' : ''; ?>><?php echo $country; ?></option>
			<?php
			}
			?>
			</select>
			</div>
			<div class="clear"></div>
			<hr>
			<div id="sstssfb_redirection">
				<input type="checkbox" id="sstssfb_redirection_option" value="on" name="redirect[switch]" <?php echo isset($rdr['switch']) && $rdr['switch'] == "on" ? "checked" : ""; ?>/>
				<label for="sstssfb_redirection_option">Redirect after success</label>
				<br/>
				<label for="sstssfb_redirection_url">URL</label>
				<input type="text" size="30" name="redirect[url]" id="sstssfb_redirection_url" value="<?php echo isset($rdr['url']) && $rdr['url'] != "" ? $rdr['url'] : ""; ?>" placeholder="http://example.com"/>
			</div>
			<?php $this->sstssfb_displayrules();  /* anything new will be added here */ ?>				
		</div>	
		<?php
	}
	
}
new sstssfbRules();
?>