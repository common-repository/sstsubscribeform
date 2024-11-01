<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbPlacement extends sstssfbAdminSettings {
	function __construct() {
		add_action("sstssfb_settings_tab", array($this, "sstssfb_tabbutton"), 3);
		add_action("sstssfb_settings_tabbox", array($this, "sstssfb_tabcontent"), 3);
		add_action("admin_enqueue_scripts", array($this, "sstssfb_scripts"), 999);
	}
	
	function sstssfb_tabbutton() {
		?>
			<li><a href="#tab-4">Position</a></li>
		<?php
	}

	function sstssfb_tabcontent() {
		$hintscontent = "";
		$hints = "<div class='hints_collections'>";		
		$hints .= apply_filters("sstssfb_position_hints", $hintscontent);
		$hints .= "</div>";
		echo $hints;
		?>
			<div id="tab-4" class="tab-item">
				<div id="tab_4-secondwrapper">
			<div class="sstssfbtabheader_area">
				<h1>Position</h1>	
				<span>Select the display position for your subscribe form.</span>
			</div>
				<?php $this->sstssfb_placements(); /* anything new will be added here */ ?>
				<?php $this->sstssfb_tabcontentitem(); /* predefined designer items */ ?>
				</div>
				<div id="sstssfb_position_hint">
					<span class="dashicons dashicons-info info"></span>
					<span class="dashicons dashicons-no-alt close"></span>
					<span class="info_text">
					</span>
				</div>
			</div>
		<?php
	}
	
	// default designer items
	function sstssfb_tabcontentitem() {
		global $post;		
		$place = get_post_meta($post->ID, "sstssfb_placement_data_metakey", true);
		
		$inputname = "placement";
		$wrapper = '';
		$placementimgdir = SSTSSFBDIR_ADDONS;
		$plcments = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($placementimgdir));
		$plcments = new SstSsfbSortedIterator($plcments);
		foreach ($plcments as $file) {
			$filename = $file->getPathname();
			if ($file->isDir()){
			   continue;
			}
			if(strpos($filename, '_pos') !== false && strpos($filename, '-ex') === false && pathinfo($filename, PATHINFO_EXTENSION) == "png") {
				
			   $file_name = basename($filename);
			   $name = pathinfo($filename, PATHINFO_FILENAME);
			   $titlename = ucwords(str_replace("-", " ", $name));
			   $selection = isset($place[$inputname]) ? $place[$inputname] : "";
			   $folder = basename(dirname($filename));
			   $url = SSTSSFB_ADDONS . "$folder/$file_name";
			   $defplace = '';
			   
			   $title = sprintf(
			   '<span class="placement_title">%1$s</span>',
			   esc_html($titlename)
			   );
			   
			   $placeshot = sprintf(
				'<img src="%1$s"/>',
				esc_url($url)
			   );
			   if($name == 'fly-in') {
				   $defplace = !is_array($place) ? "checked" : "";
				   $selection = isset($place[$inputname]) && $place[$inputname] == $name ? "checked" : $defplace;
				   $input = sprintf(
				   '<input type="radio" name="sstssfb_place[%1$s]" value="%2$s" class="sstssfb_inputplace hidden" id="sstssfb_place%2$s" %3$s/>',
				   esc_attr($inputname),
				   esc_attr($name),
				   $selection
				   );		   
			   } else {
				   $input = sprintf(
				   '<input type="radio" name="sstssfb_place[%1$s]" value="%2$s" class="sstssfb_inputplace hidden" id="sstssfb_place%2$s" %3$s/>',
				   esc_attr($inputname),
				   esc_attr($name),
				   checked($selection, $name, false)
				   );
			   }
			   $label = sprintf(
			   '<label for="sstssfb_place%1$s">%2$s</label>',
			   esc_attr($name),
			   $placeshot
			   );
			   
			   $inner = sprintf(
			   '<div class="place-title">%1$s</div><div class="place-screenshot">%2$s%3$s</div>',
			   $title,
			   $input,
			   $label
			   );
			   
			   $wrapper .= sprintf(
			   '<div class="place-item">%s<span class="dashicons dashicons-editor-help help"></span></div>',
			   $inner
			   );
			}
		}
		?>
		<div id="placement_inner" class="placement_innerclassname">
		<?php echo $wrapper; ?>
		</div>
		<?php		
	}
	
	function sstssfb_scripts() {
		global $pagenow, $typenow;
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow ==='sstssfb_builder' && !isset($_GET['page'])) {
			wp_enqueue_script( 'sstssfb_position_js', SSTSSFB_ADDONS . basename(dirname(__FILE__)) . '/js/position.js', array('jquery'), '1.0.0', true );
		}
	}
	
}
new sstssfbPlacement();
?>