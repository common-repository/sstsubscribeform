<?php
if (!defined('ABSPATH')) exit;
class sstssfbAdminSettings {
	
	function __construct() {
		$this->sstssfb_defaultsettings();
	}

// Default Admin
	function sstssfb_defaultsettings() {
		?>
		<div id="sstssfb_tab">
			<ul>
				<?php $this->sstssfb_settings_tab(); ?>
			</ul>  
			<?php $this->sstssfb_settings_tabbox(); ?>
		<div id="sstssfb_ajax_loader">
			<div class="cssload-line">
			</div>	
		</div>
		</div>
	<?php
	do_action("sstssfb_after_tab");
	}

// Hooks	
	function sstssfb_settings_tab() {
		do_action("sstssfb_settings_tab");
	}
	function sstssfb_settings_tabbox() {
		do_action("sstssfb_settings_tabbox");
	}
	function sstssfb_design_item() {
		do_action("sstssfb_design_item");
	}
	function sstssfb_emailservice() {
		do_action("sstssfb_autoresponder");
	}
	function sstssfb_displayrules() {
		do_action("sstssfb_rules");
	}
	function sstssfb_placements() {
		do_action("sstssfb_placements");
	}
	function sstssfb_locations() {
		do_action("sstssfb_locations");
	}
}
?>