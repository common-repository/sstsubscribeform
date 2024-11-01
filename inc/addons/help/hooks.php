<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbTourGuide {
	function __construct() {
		add_action("sstssfb_submit_div_extra", array($this, "sstssfbtourguidebutton"), 999);
		add_action("sstssfb_after_tab", array($this, "sstssfbtourguide"), 999);
	}
	
	function sstssfbtourguidebutton() {
		global $post;
	 $provider = get_post_meta($post->ID, "sstssfb_autoresponder_saved_metakey", true);
	 $provider = isset($provider['service']) ? $provider['service'] : "selectone..";	
		
		if((!isset($_GET['post']) && !isset($_GET['action'])) || $provider == "selectone..") {
		?>
		<div id="sstssfbstarttour">Quick Creation Guide</div>
		<?php
		}
	}
	
	function sstssfbtourguide() {
		?>
		<div id="sstssfbstarttour_block"></div>
		<div id="sstssfbguideopening">
			<span class="closeintroduction dashicons dashicons-no"></span>
			<h3>SoursopTree Subscribe Form Builder Guide</h3>
			<hr/>
			<span class="tourexplanation">
				This guide will walk you through the quick steps on creating your subscribe form using SoursopTree Subscribe Form Builder! <br/>
				The admin panel of this wp plugin is best suited for screen display with resolution <b>1152 x 864 pixels</b> or above! <br/>
			</span>		
			<div id="sstssfbbuttoncontainer">
				Please click the button below to continue! <br/>
				<span id="sstssfbquickguide">Continue</span>
			</div>
		</div>
		<input type="hidden" id="quickguidetourselected" value="no"/>
		<?php
	}
}
new sstssfbTourGuide();

?>