<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;

class sstssfbShortcodeAdmin {
	function __construct() {
		add_filter("sstssfb_position_hints", array($this, "sstssfb_add_hints"));
		add_action( 'sstssfb_submit_div_extra', array($this, 'sstssfb_submitdiv_extra'));
		add_action( 'admin_enqueue_scripts', array($this, 'sstssfb_shortcode_addons_scripts'));
	}
	
	function sstssfb_add_hints($hintscontent) {
		$hintscontent .= "<span class='shortcode_hints'>Select this to make your subscribe form can only being displayed using shortcode. You can display using shortcode any subscribe form which is set in other position by adding <i><b>in_content</b></i> parameter! Make sure that your form has been created! <i>Example:</i> <b>[sstssfb_form id='xx' in_content='true']</b></span>";
		return $hintscontent;
	}

	function sstssfb_submitdiv_extra()	{
		global $post, $pagenow, $typenow;
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow ==='sstssfb_builder' && !isset($_GET['page'])) {
			$id = isset($post->ID) ? $post->ID : "";
			if($id != "") {
			?>
				<div class="sstssb_shortcode_show">
					<input type="text" class="sstssb_shortcode_show_value" value="<?php echo "[sstssfb_form id='$id']"; ?>"/>
				</div>
			<?php
			}
		}
	}
	
	function sstssfb_shortcode_addons_scripts()	{
		global $post, $pagenow, $typenow;
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow ==='sstssfb_builder' && !isset($_GET['page'])) {
			wp_enqueue_script( 'sstssfb_posfront_js', SSTSSFB_ADDONS . 'shortcode_pos/asset-ex/js/admin.js', array(), '1.0.0', true );
			wp_enqueue_style( "sstssfb_posfront_css", SSTSSFB_ADDONS . "shortcode_pos/asset-ex/css/admin.css");
		}
	}
	
}
new sstssfbShortcodeAdmin();
?>