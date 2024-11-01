<?php
if (!defined('ABSPATH')) exit;
class sstssfbThemeShortcode{

	function __construct(){
		add_shortcode('sstssfb_form', array($this, 'sstssfb_themeshortcode'), 9999);
	}

	function sstssfb_themeshortcode($atts, $content = null, $tag){
		global $post, $wpdb;
		extract( shortcode_atts( array(
		'id' => '',
		'in_content' => ''
		),	$atts ) );

		$data = get_post_meta($id,"sstssfb_save_themedata_metakey", true);

		// If there are no subscribe form created
		if(!isset($data['theme']) || $data['theme'] == "") {
			return;
		}

		// DEFAULT STYLED THEME
		$theme_css = SSTSSFB_THEMEURL . $data['theme'];
		$theme = str_replace(" ", "%20", dirname($data['theme']));	
		$basename = "/displayed/". basename($theme_css) . $id;		
		$theme_css = SSTSSFB_THEMEURL . $theme . "$basename.css";
		
		$theme_html = dirname(SSTSSFB_THEMEDIR . $data['theme']) . "$basename.html";
		$theme_html = file_get_contents($theme_html);

		// Theme to use
		$theme_to_use = isset($theme_html) && trim($theme_html) != "" ? html_entity_decode($theme_html, ENT_QUOTES, "UTF-8") : "";

		// TOOLTIP POSITION
		$vtpost = get_post_meta($id, "sstssfb_validation_tooltip_position", true);
		$vtpost = isset($vtpost) && $vtpost != "" ? "data-tooltip='$vtpost'" : "";		

		// SELECTED EMAIL SERVICE
		$emailservice = get_post_meta($id, "sstssfb_autoresponder_saved_metakey", true);
		$service = isset($emailservice['service']) && $emailservice['service'] != "" ? $emailservice['service'] : "";

		// PLACEMENT
		$place = get_post_meta($id, "sstssfb_placement_data_metakey", true);
		$position = isset($place['placement']) && $place['placement'] != "" ? $place['placement'] : "";		
		$pos = $position != "" ? "data-pos='$position'" : "";
		$pos = isset($in_content) && $in_content != "" ? "" : $pos;
		
		// REDIRECTION
		$rdr = get_post_meta($id, "sstssfb_redirection_rules_metakey", true);
		$rdr_on = isset($rdr['switch']) && $rdr['switch'] == "on" ? true : false;
		$rdr_url = isset($rdr['url']) && $rdr['url'] != "" && filter_var($rdr['url'], FILTER_VALIDATE_URL) !== false ? $rdr['url'] : "";
		$redir = $rdr_on == true && $rdr_url != "" ? "data-redirect='$rdr_url'" : "";

		// Script and style sheet
		wp_enqueue_script( 'sstssfb_prefixfree_js', SSTSSFB_URL . 'js/prefixfree.min.js', array(), '1.0.0', true );
		wp_enqueue_script( 'sstssfb_front_js', SSTSSFB_URL . 'js/sstssfb-front.js', array(), '1.0.0', true );
		wp_enqueue_style( "sstssfb_frontstyle_css", SSTSSFB_URL . "css/front/style.css");
		wp_enqueue_style( "sstssfb_themes_css$id", $theme_css);
		$add_elem = "";
		// Build shortcode output
		$sstssfb_front = "<div id='sstssfb_main_wrapper$id' class='sstssfb_main_wrapper' data-id='$id' data-service='$service' $redir $vtpost $pos>";
		$sstssfb_front .= $theme_to_use;
		$sstssfb_front .= '<div class="sstssfb_loader"><div class="sstssfb_loader_inner"></div></div>';
		$sstssfb_front .= apply_filters("sstssfb_front_additional_elem", $add_elem); // add custom element such as close or minimize button
		$sstssfb_front .= '</div>';
		// end shortcode output
		$args = array($id, $service, $position);
		do_action("sstssfb_subscribeform_after", $args);
		return $sstssfb_front;
	}
}
new sstssfbThemeShortcode();
?>