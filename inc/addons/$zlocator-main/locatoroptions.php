<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbLocOptions extends sstssfbAdminSettings {
	
	function __construct() {
		add_action("sstssfb_settings_tab", array($this, "sstssfb_tabbutton"), 999999);
		add_action("sstssfb_settings_tabbox", array($this, "sstssfb_tabcontent"), 999999);
	}
	
	function sstssfb_tabbutton() {
		?>
			<li><a href="#tab-3">Locations</a></li>
		<?php		
	}
	
	function sstssfb_tabcontent() {
		?>
		<div id="tab-3" class="tab-item">
			<div id="tab_3-secondwrapper">
			<div class="sstssfbtabheader_area">
			  <h1>LOCATION SETTINGS</h1>
			  <span>Select where to display this subscribe form on your website</span>
			</div>
			<?php 
				$this->sstssfb_locationsdefault(); /* predefined location items */ 
				$this->sstssfb_locations(); /* anything new will be added here */ 
			?>
			</div>
		</div>
		<?php
	}
	
	function sstssfb_locationsdefault() {
	  global $wpdb, $post;
		$location = get_post_meta($post->ID, "sstssfb_save_locdata_metakey", true);
		$svparent_all = isset($location['parent']) && $location['parent'] == "all_loc" ? "checked" : "";
		$svpar_spec = isset($location['parent']) && $location['parent'] == "spec_loc" ? "checked" : "";
					   
		$general_options = array(
							"Homepage" => array("name" => "home_too", "value" => "home_too"),
							"Archive Page" => array("name" => "archive_too", "value" => "archive_too"),
							"404 Page" => array("name" => "404_page", "value" => "404_page")
					    );
		
		$inclusions = "";
		foreach($general_options as $key => $val) {
			$value = $val['value'];
			$name = $val['name'];
			$selected = isset($location[$name]) && $location[$name] == $value ? "checked" : "";			
			$inclusions .= sprintf(
				'<li class="pages_item"><input type="checkbox" name="sstssfb_loc[%1$s]" value="%2$s" id="%3$s" class="" %4$s/><label for="%3$s"></label><label for="%3$s">%5$s</label><div class="clear"></div></li>',
				esc_attr($name),
				esc_attr($value),
				esc_attr($name),
				esc_attr($selected),
				esc_html($key)
			);
		}
		
		$hierarchy = "";
		if(!has_filter("sstssfb_location_options")) {
			$hierarchy = "<div id=\"get_specloc_addon\">
							<a class=\"get_now\" href=\"http://soursoptree.com/specific-locator-add-on-for-sst-subscribe-form-builder-plugin/\" target=\"_blank\">Get <b>Specific Locator</b> add-on</a>
							<a class=\"see_demo\" href=\"http://soursoptree.com/specific-locator-add-on-for-sst-subscribe-form-builder-plugin/\" target=\"_blank\">See the demo (video)</a>
						</div>";
		}
		
		// Date
		if(isset($location['by_date']) && is_array($location['by_date'])){
			$val_1 = "";
			$val_2 = "";
			$count = count($location['by_date']);
			for($i = 0; $i < $count; $i++) {
				if($i == 0) {
					$val_1 .= $location['by_date'][$i];
				} else {
					$val_2 .= $location['by_date'][$i];
				}
			}
		}
	  ?>	  
	  <div id="sstssfb_location_options_wrapper">	  
			<div id="wrapper_left_title" class="parent_loc_title_wrapper">
				<input type="radio" name="sstssfb_loc[parent]" value="all_loc" id="sstssfb_loc_parent_all" <?php echo $svparent_all; ?>/>
				<label for="sstssfb_loc_parent_all"></label>
				<label for="sstssfb_loc_parent_all" class="parent_location_label">ALL PAGES</label>
			</div>		
		
			<div id="wrapper_right_title" class="parent_loc_title_wrapper">
				<input type="radio" name="sstssfb_loc[parent]" value="spec_loc" id="sstssfb_loc_parent_specific" <?php echo $svpar_spec; ?>/>
				<label for="sstssfb_loc_parent_specific"></label>
				<label for="sstssfb_loc_parent_specific" class="parent_location_label">SPECIFIC PAGES</label>			
			</div>	
			<div class="clear"></div>
			<div id="wrapper_option_main">
				<span class="options_explanation">Exclude following pages:</span>
				<ul id="sstssfb_inclusions_list" class="sstssfb_pages_list general_list">
					<span class="page_item_header header_general_list">GENERAL</span>
					<?php echo $inclusions; ?>				
				</ul>
					<?php
						echo apply_filters("sstssfb_location_options", $hierarchy);
					?>
			</div>
		<div class="clear"></div>
	  </div>
	  
	<div class="date_box">
		<div class="partial_title">By Date Range</div>
		<div class="date_boxcontent">
			<span>Specify the date range in the input fields below if you want to display subscribe form on the post/ page published on specific date range. This setting will be overriden if you define page exclusion.</span>
			<label for="by_datea"><b>from: </b></label><input type="text" class="eps_input_date" id="by_datea" name="sstssfb_loc[by_date][]" value="<?php echo (isset($val_1) && $val_1 != "") ? $val_1 : null; ?>" size="15"/>
			<label for="by_dateb"><b>to:</b> </label>
			<input type="text" class="eps_input_date" id="by_dateb" name="sstssfb_loc[by_date][]" value="<?php echo (isset($val_2) && $val_2 != "") ? $val_2 : null; ?>"  size="15"/>
			<input type="button" id="clear_dates" class="button button-small" value="clear all"/>
		</div>
	</div>
		<div class="clear"></div>
		<?php		
	}
}

new sstssfbLocOptions();
?>