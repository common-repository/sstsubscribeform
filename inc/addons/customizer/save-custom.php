<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbCustomizeSave {

	function __construct() {
		add_action( 'save_post', array( $this, 'sstssfb_save' ) );
	}

	function sstssfb_save( $post_id ){
	global $post;
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['sstssfb_meta_box_nonce'] ) )
			return;

		$nonce = $_POST['sstssfb_meta_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'sstssfb_save_meta_box_data' ) )
			return;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		// Check the user's permissions.
		if ( 'sstssfb_builder' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return;
		}
		/* OK, its safe for us to save the data now. */	
		
		if(isset($_POST['tooltip']) && $_POST['tooltip'] != "") {
			$vtpos = esc_attr($_POST['tooltip']);
			update_post_meta(esc_attr($post_id), sanitize_key("sstssfb_validation_tooltip_position"), $vtpos);
		}
	}
}
new sstssfbCustomizeSave();
?>