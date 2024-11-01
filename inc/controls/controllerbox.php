<?php
if (!defined('ABSPATH')) exit;
class sstssfbControllerBox {

	function __construct(){
		$this->sstssfb_tocall();
	}

	function sstssfb_tocall(){
		add_action( 'add_meta_boxes', array($this, 'sstssfb_container'));
		add_action( 'post_submitbox_start', array($this, 'sstssfb_submitdiv_extra'));
	}				
	
	// CUSTOM PUBLISH BOX
	function sstssfb_container($post_type) {	
		$post_types = array('sstssfb_builder');		
			if (in_array( $post_type, $post_types)) {
				add_meta_box(
					'controller_box'
					,__( 'Themes', 'sstssfbuilder' )
					,array( $this, 'sstssfb_baseElement' )
					,$post_type
					,'normal'
					,'low'
				);				
			}	
	}
	
	function sstssfb_baseElement($post) {
	global $post;
	wp_nonce_field( 'sstssfb_save_meta_box_data', 'sstssfb_meta_box_nonce' );
	$settings = new sstssfbAdminSettings();
	}
	
	function sstssfb_submitdiv_extra()	{
	global $post, $pagenow, $typenow;
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow ==='sstssfb_builder' && !isset($_GET['page'])) {	
		$on = get_post_meta($post->ID, "sstssfb_active_inactive_switcher", true);
		$activedef = get_post_meta($post->ID, "sstssfb_active_default", true);
		$activedef = isset($activedef) && $activedef == "off" ? "" : "checked";
			
		$deleleteurl = get_delete_post_link($post->ID, "", true);
		?>
		<div id="sstssfb_submit_div_extra">
		<div class="onoffswitch">
		<input type="checkbox" name="sstssfb_activate[active]" class="onoffswitch-checkbox" id="sstssfb_onoffswitch" value="active" <?php echo isset($on['active']) && $on['active'] == "active" ? "checked" : $activedef; ?>/>
		<label class="onoffswitch-label" for="sstssfb_onoffswitch">
		<span class="onoffswitch-inner"></span>
		<span class="onoffswitch-switch"></span>
		</label>
		</div>
		<?php do_action("sstssfb_submit_div_extra"); ?>
		<div class="clear"></div>
		<input type="hidden" id="sstssfb_delete_post_url" value="<?php echo $deleleteurl; ?>"/>
		</div>
		<?php
		}
	}
}
new sstssfbControllerBox();
?>