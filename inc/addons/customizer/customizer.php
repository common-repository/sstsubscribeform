<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
include("ajaxcustomizer-ex.php");
class sstssfbThemeCustomizer {
	function __construct() {
		add_action("sstssfb_themecustomizer", array($this, "sstssfb_customizer_ui"));
	}
	
	function sstssfb_customizer_ui() {
		global $post;
		$fonts = array("", "Arial", "Arial Black", "Arial Narrow", "Impact", "Palatino Linotype", "Tahoma", "Century Gothic", "Comic Sans Ms","Lucida Sans Unicode", "Times New Roman", "Verdana", "Copperplate Gothic Light", "Lucida Console", "Gill Sans", "Trebuchet MS", "Courier New", "Georgia");
		$tooltip = array("Top", "Bottom", "Left", "Right");
		
		$custom = get_post_meta($post->ID, "sstssfb_custom_saved_metakey", true);
		$vtpost = get_post_meta($post->ID, "sstssfb_validation_tooltip_position", true);
		$custom_checked = isset($custom['use_edited']) && $custom['use_edited'] == "on" ? "checked" : "";
		$edited = isset($custom['theme_edited']) && trim($custom['theme_edited']) != "" ? $custom['theme_edited'] : "";
		$previous = isset($custom['previous']) && trim($custom['previous']) != "" ? $custom['previous'] : "";
		$saved_exists = $edited != "" ? "" : "hidden";
		$show_load = $custom_checked == "" && $edited != "" ? "" : "hidden";
?>
<?php if($edited != "") { ?>
	<div id="sstssfb_load_savedtheme" class="button hidden">Load Saved</div>
	<div class="hidden">
		<textarea id="sstssfb_saved_themehtml"><?php echo $edited; ?></textarea>
		<input type="text" id="sstssfb_saved_themehtml_identity" name="sstssfb_custom[theme_edited_id]" value="<?php echo $custom['parent_id']; ?>"/>
		<input type="text" id="sstssfb_edited_themehtmlidentity" name="" value=""/>
		<input type="text" name="" id="sstssfb_saved_themeurl" value="<?php echo $previous; ?>"/>
		<input type="text" id="sstssfb_true_false" value=""/>
	</div>
<?php } ?>
<input type="hidden" id="sstssfb_saved_css_url" value="<?php echo SSTSSFB_THEMEURL; ?>"/>
		<input type="checkbox" class="hidden" name="sstssfb_custom[use_edited]" value="on" id="sstssfb_use_edited_theme" <?php echo $custom_checked; ?>/>
		<label class="button <?php echo $saved_exists; ?>" id="sstssfb_use_edited_label" for="sstssfb_use_edited_theme">front end = Default</label>	
		<div id="sstssfb_themedesigner" class="button">Preview and Customize</div>
		<input type="checkbox" class="hidden" value="on" id="sstssfb_load_original"/>
		<label class="button" id="sstssfb_load_original_label" for="sstssfb_load_original">load original</label>					
				<div id="sstssfb_customizerwrapper">				
				<div id="sstssfb_close_customizer" class="dashicons dashicons-no-alt"></div>
				<div id="sstssfb_costumizer_buttons">
					<div id="sstssfb_clean_marker" class="button hidden">Unselect</div>
					<div id="sstssfb_clean_style" class="button hidden">Reset Selected's Style</div>
					<div id="sstssfb_clean_all_styles" class="button">Reset All Styles</div>
					<?php do_action("sstssfb_customizer_topright"); ?>
					<div class="clear"></div>
				</div>
					<div id="sstssfb_customizerarea">
						<div id="sstssfb_csrthemewrapper" class="sstssfb_main_wrapper">
							<div id="sstssfb_theme_holder">
							</div>
							<div class="theme_cssload-line"></div>
							<?php
								$elem = "";
								echo apply_filters("sstssfb_theme_handler_after", $elem);
							?>
							<div class="clear"></div>
						</div>				
					</div>
					<div id="designcontroller">
						<span class="showtool dashicons dashicons-arrow-right-alt2"></span>
						<span class="hidetool dashicons dashicons-arrow-left-alt2"></span>
						<div id="sstssfb_data_container" class="hidden">
							<input type="hidden" name="" id="sstssfb_previous_theme" value="<?php echo $previous; ?>"/>
						</div>
					<!--SETTINGS main area WRAPPER-->
					<div id="sst-sstssfbsettings-main" class="sst-sstssfbsettings sstssfbsettingsnotfirst">
								<div id="sstssfbmainwidth-wrapper" class="sstssfb-width-wrapper">
									<label class="itemsectionlbl" for="sstssfbmainwidth-settings">Theme width:</label><br/>
									<input type="text" name="" id="sstssfbmainwidth-val" value="" class="sstssfbsettings-spinner-val"/> <span class="sstssfb_value_attr">pixels</span>
									<span class="sstssfb_after_attr"><input type="checkbox" id="sstssfb_width_auto"><label for="sstssfb_width_auto">auto fit</label></span>
								</div>								
								<hr/>
								<div id="sstssfb_tooltip-wrapper" class="sstssfb-tooltip-wrapper">
									<label class="itemsectionlbl">Validation tooltip position:</label><br/>
									<?php 
									$tips = '<option value=""></option>';
									foreach($tooltip as $v) { 
										$tips .= sprintf(
											'<option value="%1$s" %2$s>%3$s</option>',
											esc_attr(strtolower("tooltip_$v")),
											selected(strtolower("tooltip_$v"), $vtpost, false),
											esc_html($v)
										);
									}
									?>
									<select class="dsgnrselectoption" id="sstssfbvalidation-tooltip" name="tooltip">
									<?php echo $tips; ?>
									</select>
									<span class="sstssfb_preview_vt">preview tooltip</span>
									<input type="hidden" id="previous_tooltip_position" value=""/>
								</div>
								<hr/>
						 <div class="content-box">
							<div id="sstssfbmncolor-wrapper" class="sstssfb-item-wrapper">
								<div id="sstssfbmaincolor-wrapper" class="sstssfb-clr-wrapper">
									<label class="itemsectionlbl" for="sstssfbmaincolor-settings">Background:</label><br/>
									<input type="text" name="" id="sstssfbmaincolor-settings" value="" class="sstssfbsettings-input-text"/>
									<input type="text" name="" id="sstssfbmaincolor1-val" value="" class="sstssfbsettings-input-val" readonly="true"/>
								</div>
								<div id="sstssfbtitlecolor-wrapper" class="sstssfb-clr-wrapper wrpnotfirst">
									<label class="itemsectionlbl" for="sstssfbtextcolor-settings">Text color:</label><br/>
									<input type="text" name="" id="sstssfbtextcolor-settings" value="" class="sstssfbsettings-input-text"/>
									<input type="text" name="" id="sstssfbtitlecolor1-val" value="" class="sstssfbsettings-input-val" readonly="true"/>
								</div>
								<div id="sstssfbtitlealign-wrapper" class="sstssfb-clr-wrapper wrpnotfirst">
									<label class="itemsectionlbl" for="sstssfbtextalign-settings">Text align:</label><br/>
									<select class="dsgnrselectoption" id="sstssfbtextalign-settings">
										<option value="left">Left</option>
										<option value="center">Center</option>
										<option value="right">Right</option>
									</select>
								</div>
								<div id="sstssfbfontfmly-wrapper" class="sstssfb-item-wrapper wrpnotfirst">
									<div id="sstssfbfontfmly-wrapper" class="sstssfb-clr-wrapper">
										<label class="itemsectionlbl" for="sstssfbfontfmly-settings">Font family:</label>
										<select name="" class="dsgnrselectoption" id="sstssfbfontfmly-settings">
										<?php
										foreach($fonts as $k => $f) {
										?>
										<option value="<?php echo $f; ?>"><?php echo $f; ?></option>
										<?php
										}
										?>
										</select>
									</div>
								</div>
								<div id="sstssfbcontent-wrapper" class="sstssfb-item-wrapper wrpnotfirst">
									<div id="sstssfbfontfmly-wrapper" class="sstssfb-clr-wrapper">
										<label class="itemsectionlbl" for="sstssfbcontent-settings">Text:</label>
										<textarea rows="2" name="" value="" id="sstssfbcontent-settings" class="sstssfbsettings-input-val input-fwidth input-nomargin"></textarea>
									</div>
								</div>
						<div class="clear"></div>
						 </div>
					</div> <!--end title control-->
					</div>
		<div class="hidden"> <!----> 
		<textarea id="sstssfb_custom_themestyle" name="sstssfb_custom[theme_edited]"><?php echo $edited; ?></textarea>
		<input type="text" name="sstssfb_custom[previous]" id="sstssfb_themeurlto_save" value="<?php echo $previous; ?>"/>
		</div>
				</div>
			</div>
<?php		
	}
}

new sstssfbThemeCustomizer();
?>