<?php
if (!defined('ABSPATH')) exit;
class sstssfbStayUpate{
	function __construct() {
		add_action("sstssfb_after_tab", array($this, "stayupdate"));
		add_action("wp_ajax_nevershow_notification", array($this, "admin_notify_update"));
	}
	
	function stayupdate() {
		?>
		<span class="sstssfbgetupdate dashicons dashicons-megaphone" title="Watch update"></span>
		<div id="sstssfb_getupdatebysocialmedia">
		<span class="sstssfbcloseupdate dashicons dashicons-no-alt"></span>
			<span class="sstssfbupdatenote">
				Watch This Plugin's Update
			</span>			
			<span class="sstssfbupdateexplain">
				This plugin will continously being updated and maintained!
				<br/>
				Follow SoursopTree to get notifications when there is any update for this plugin!
			</span>
			<a class="sstssfbtwitter dashicons dashicons-twitter" title="Follow on twitter" href="https://twitter.com/SoursopTree" target="_blank">
			</a>
			<a class="sstssfbfacebook dashicons dashicons-facebook-alt" title="Follow on facebook" href="https://www.facebook.com/soursoptree" target="_blank">
			</a>
			
			<input type="checkbox" name="nevershow" id="sstssbnevershowupdatenote" value="confirm"/>
			<label for="sstssbnevershowupdatenote" class="sstssbnevershowupdatenote" title="Check and close if you want this to be removed from this site!">Never show this again!</label>
		</div>
		
		<?php
	}
	
	function admin_notify_update() {
		global $wpdb, $post;
		$nonce = "";
		if(isset($_POST['nonce'])) {
			$nonce = $_POST['nonce'];
		}
		if(!wp_verify_nonce($nonce, 'sstssfb_notify_admin_ajax_process')) {
			exit;
		}
		
		if(isset($_POST['hide'])) {
			$hide = esc_html($_POST['hide']);
			echo update_option(sanitize_key("sstssfb_notify_admin_hide"), $hide);
			exit;
		}
		
	}
}

$hide = get_option("sstssfb_notify_admin_hide");		
if($hide != "confirm") {
	new sstssfbStayUpate();
}

?>