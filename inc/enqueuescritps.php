<?php
if (!defined('ABSPATH')) exit;
class sstssfbEnqScriptStyle {
	function __construct() {
		$this->sstssfb_enqueuescripts();
	}

	function sstssfb_enqueuescripts() {
		add_action('admin_enqueue_scripts', array($this, 'sstssfb_admin_script_style'), 999);
	}

	function sstssfb_admin_script_style() {
		global $pagenow, $typenow;
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && ($typenow ==='sstssfb_builder' || $typenow ==='sstssfb_multiemail')) {
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('jquery-ui-mouse');
			wp_enqueue_script('jquery-ui-tooltip');
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-droppable');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-slider');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-effect');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('iris' );
			wp_dequeue_script('autosave' );

			// JAVASCRIPT
			wp_enqueue_script( 'sstssfb_nicescroll_js', SSTSSFB_URL . '/js/admin/jquery.nicescroll.min.js', array(), '1.0.0', true );
			wp_enqueue_script( 'sstssfb_prefixfree_js', SSTSSFB_URL . '/js/prefixfree.min.js', array(), '1.0.0', true );
			wp_enqueue_script( 'sstssfb_tipsy_js', SSTSSFB_URL . '/js/jquery.tipsy.js', array(), '1.0.0', true );
			wp_enqueue_script( 'sstssfb_hierachy_js', SSTSSFB_URL . '/js/admin/hierachy.js', array(), '1.0.0', true );
			wp_enqueue_script( 'sstssfb_hopscotchmin_js', SSTSSFB_URL . '/js/admin/hopscotch.min.js', array(), '1.0.0', true );
			wp_enqueue_script( 'sstssfb_main_js', SSTSSFB_URL . '/js/admin/main.js', array(), '1.0.0', true );
			wp_localize_script("sstssfb_main_js", "EmailService", array("SstssfbEmailServiceAdmin" => wp_create_nonce("sstssfb_emailservice_admin_ajax_process")));
			
			//CSS					
			wp_enqueue_style('sstssfb_jqueryui_css', SSTSSFB_URL . "/css/jquery-ui.css");
			wp_enqueue_style('sstssfb_tipsy_css', SSTSSFB_URL . "/css/tipsy.css");
			wp_enqueue_style('sstssfb_hopscotchmin_css', SSTSSFB_URL . "/css/admin/hopscotch.min.css");
			wp_enqueue_style('sstssfb_size_css', SSTSSFB_URL . "/css/size.css");
			wp_enqueue_style('sstssfb_main_css', SSTSSFB_URL . "/css/admin/main.css");
			wp_enqueue_style('sstssfb_themelist_css', SSTSSFB_URL . "/css/admin/themelist.css");
			wp_enqueue_style('sstssfb_layouts_css', SSTSSFB_URL . "/css/admin/layouts.css");
			wp_enqueue_style('sstssfb_design_css', SSTSSFB_URL . "/css/admin/design.css");
			wp_enqueue_style('sstssfb_hierarchy_css', SSTSSFB_URL . "/css/admin/hierarchy.css");
		}

	}
}

new sstssfbEnqScriptStyle();
?>