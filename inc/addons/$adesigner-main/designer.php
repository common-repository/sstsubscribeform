<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
// custom designer components
class sstssfbDefaultDesigner extends sstssfbAdminSettings {
	
	function __construct() {		
		add_action("sstssfb_settings_tab", array($this, "sstssfb_tabbutton"), 1);
		add_action("sstssfb_settings_tabbox", array($this, "sstssfb_tabcontent"), 1);
	}
	
	function sstssfb_tabbutton() {
		?>
			<li><a href="#tab-1">Design</a></li>
		<?php
	}

	function sstssfb_tabcontent() {
		?>
			<div id="tab-1" class="tab-item">
				<div id="tab_1-secondwrapper">
					<div class="sstssfbtabheader_area">
					<div id="sstssfb_theme_header">
						<h1>THEMES</h1>	
						<span>Select the theme for your subscribe form.</span>
					</div>
					<div id="sstssfb_theme_designer">						
						<?php 
						if(has_action("sstssfb_themecustomizer")) {
							do_action("sstssfb_themecustomizer"); 
						} else {
							?>
							<!--<div id="sstssfb_themepreview" class="button">Get theme customizer</div>-->
							<?php
						}
						?>
					</div>
					<div class="clear"></div>
					</div>
				<?php $this->sstssfb_design_item(); /* anything new will be added here */ ?>
				<?php $this->sstssfb_tabcontentitem(); /* predefined designer items */ ?>
				</div>
			</div>
		<?php
	}
	
	// default designer items
	function sstssfb_tabcontentitem() {
		global $post;
		$theme = get_post_meta($post->ID, "sstssfb_save_themedata_metakey", true);
		
		?>
		<div id="sstssfb_themeslist">
			<?php
			$path = SSTSSFB_THEMEDIR;
			$themeitems = array();
			$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
			$objects = new SstSsfbSortedIterator($objects);
				foreach ($objects as $file) {
					$filename = $file->getPathname();
					if (strpos($filename, '-ex') === false && !$file->isDir()){
					   if(pathinfo($filename, PATHINFO_EXTENSION) == "jpg" || pathinfo($filename, PATHINFO_EXTENSION) == "png") {
						$themeitems[] = basename(dirname($filename)) . "/" . pathinfo($filename, PATHINFO_BASENAME);
					   }
					}
				}
				
			foreach($themeitems as $ke => $ve) {
				$dirname = pathinfo($ve, PATHINFO_DIRNAME);
				$sshot = pathinfo($ve, PATHINFO_BASENAME);
				$themefile = pathinfo($ve, PATHINFO_FILENAME);
				$id = $themefile . $ke . str_replace(" ", "", $dirname);
				$fileurl = "$dirname/$themefile";
				if($themefile == "aaa-theme") {
					$def = !isset($theme['theme']) ? "checked" : "";
				} else {
					$def = "";
				}
				?>
				<div class="themeitembox">
					<input type="radio" name="sstssfb[theme]" value="<?php echo $fileurl; ?>" class="hidden sfb_radio" id="sfb_theme<?php echo $id; ?>" <?php echo isset($theme['theme']) && $theme['theme'] == $fileurl ? "checked" : $def; ?>/>
					<label for="sfb_theme<?php echo $id; ?>" class="unselected themeitemimgbox">
						<img src="<?php echo SSTSSFB_THEMEURL . "/$ve"; ?>"/>
					</label>
				</div>
				<?php
			}
			?>
		<div class="clear"></div>
		</div>
		<?php
	}
	
}
new sstssfbDefaultDesigner();
?>