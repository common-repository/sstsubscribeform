<?php
if (!defined('ABSPATH')) exit;
class sstssfbSubscribeProcess {		
	public function __construct() {
		add_action("wp_ajax_mailsubscribe_front", array($this, "sstssfb_subscribe_process_callback"));
		add_action("wp_ajax_nopriv_mailsubscribe_front", array($this, "sstssfb_subscribe_process_callback"));		
	}
		
	public function sstssfb_subscribe_process_callback() {
		global $post, $wpdb;	
		$nonce = "";		
		if(isset($_POST['security'])) {
			$nonce = $_POST['security'];
		}		
		wp_verify_nonce($nonce, 'sstssfb_subscribe_front_ajax_process') ||	exit;
		
		do_action("sstssfb_subscribe_process");
	}	
}

new sstssfbSubscribeProcess();
?>