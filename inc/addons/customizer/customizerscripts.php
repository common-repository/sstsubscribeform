<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;

//http://wordpress.stackexchange.com/questions/175307/tinymce-is-not-defined-when-not-using-wp-editor
/* $js_src = includes_url('js/tinymce/') . 'tinymce.min.js';
// wp_enqueue doesn't seem to work at all
echo '<script src="' . $js_src . '" type="text/javascript"></script>'; */

class sstssfbCustomizerScripts {
	function __construct() {
		$this->sstssfb_enqueuescripts();
	}
	
	function sstssfb_enqueuescripts() {
		add_action('admin_enqueue_scripts', array($this, 'sstssfb_customizer_script_style'), 99999);
	}

	function sstssfb_customizer_script_style() {
		global $pagenow, $typenow;
		
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow ==='sstssfb_builder' && !isset($_GET['page'])) {
			
			wp_enqueue_script('jquery-ui-spinner');
			
			// JAVASCRIPT
			wp_enqueue_script( 'sstssfb_spectrum_js', SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/js/spectrum.js", array(), '1.0.0', true );
			wp_enqueue_script( 'sstssfb_displayedit_js', SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/js/jquery.display.js", array(), '1.0.0', true );
			wp_localize_script("sstssfb_displayedit_js", "CstmzrAdmin", array("SstssfbCstmzrAjax" => wp_create_nonce("sstssfb_cstmzr_ajax_process")));
			wp_enqueue_script( 'preview_tooltip_js', SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/js/preview_tooltip.js", array(), '1.0.0', true );

/* $css_src = includes_url('css/') . 'editor.css';
wp_register_style('tinymce_css', $css_src);
wp_enqueue_style('tinymce_css');	 */		
			
			//CSS					
			wp_enqueue_style('sstssfb_spectrum_css', SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/css/spectrum.css");
			wp_enqueue_style('sstssfb_style_css', SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/css/style.css");
			wp_enqueue_style('sstssfb_editor_css', SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/css/editor.css");
			/* wp_enqueue_style('sstssfb_theme_css', plugins_url("asset-ex/css/editor.css", __FILE__)); */
		}
	}
}

new sstssfbCustomizerScripts();