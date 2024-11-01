<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbCustomizerAjax {

	function __construct() {
		add_action("wp_ajax_cstmzr_ajax", array($this, "sstssfb_customizer_ajax_callback"));
	}

	function sstssfb_customizer_ajax_callback() {
		global $wpdb, $post;
		
			$nonce = "";
			if(isset($_POST['nonce'])) {
				$nonce = $_POST['nonce'];
			}
			
			if(!wp_verify_nonce($nonce, 'sstssfb_cstmzr_ajax_process')) {
				exit;
			}

			$path = SSTSSFB_THEMEDIR;
			$theme_url = isset($_POST['themeurl']) && $_POST['themeurl'] != "" ? $_POST['themeurl'] : "";
			
			if($theme_url == "") {
				echo "URL is not set!";
				exit;
			}
			
			$css_url = "$path$theme_url.css";
			$theme_dir = "$path$theme_url.html";

			include $theme_dir;
			
			// Collecting all of the selectors written inside stylesheet
			$allselectors = '';
			$defCss = file_get_contents($css_url);
			preg_match_all('/(?ims)([a-z0-9\s\.\,\[\]\=\"\:\+\#_\-@]+)\{([^\}]*)\}/', $defCss, $defArray);		
			foreach ($defArray[0] as $i => $x) {
				$selector = trim($defArray[1][$i]);
				$allselectors .= $selector . "|";
			}
			echo "<textarea class='hidden' name='sstssfb_custom[selectors]' id='sstssfb_theme_selectors'>".$allselectors."</textarea>";		
		exit;
	}
	
}
new sstssfbCustomizerAjax();
?>