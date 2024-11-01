<?php
if (!defined('ABSPATH')) exit;
if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
class sstssfbEmailServices extends sstssfbAdminSettings {
	function __construct() {		
		add_action("sstssfb_settings_tab", array($this, "sstssfb_tabbutton"), 2);
		add_action("sstssfb_settings_tabbox", array($this, "sstssfb_tabcontent"), 2);
		add_action("sstssfb_subscribeform_after", array($this, "sstssfb_mailservice_enqueue_scripts"), 99999, 3);
		add_action( 'admin_notices', array($this, "notset_emailservice_notice"));
	}
	
	function sstssfb_tabbutton() {
		?>
			<li><a href="#tab-5">Email Service</a></li>
		<?php
	}

	function sstssfb_tabcontent() {
		?>
			<div id="tab-5" class="tab-item">
				<div id="tab_5-secondwrapper">
				<div class="sstssfbtabheader_area">
				<h1>EMAIL SERVICE</h1>	
				<span>Define the email service provider you want to integrate to your subscribe form.</span>
				</div>
				<?php $this->sstssfb_tabcontentitem(); /* predefined designer items */ ?>
				</div>
			</div>
		<?php
	}
	
	function sstssfb_tabcontentitem() {
		global $post, $mailservice;
		$emailservice = get_post_meta($post->ID, "sstssfb_autoresponder_saved_metakey", true);		
		$mailoptions = '';
		$service_chosen = isset($emailservice['service']) && $emailservice['service'] != "" ? $emailservice['service'] : "";
		
		foreach($mailservice as $key => $mail) {
			$mailoptions .= sprintf(			
							'<option data-id="%1$s" class="%2$s" value="%3$s" %4$s>%5$s</option>',
							esc_attr($key),
							esc_attr('msoption'),
							esc_attr(strtolower(str_replace(" ", "", $mail))),
							selected(strtolower(str_replace(" ", "", $mail)), $service_chosen, false),
							esc_html($mail)
							);
		}
		?>
		<div class="sstssfb_tabcontent_area">
		<div id="sstssfbautoresponder_options">
			<label for="sstssfb_selectservice">Autoresponder: </label>
			<select name="sstssfb_mail[service]" id="sstssfb_selectservice" class="" data-id="<?php echo $post->ID; ?>">
			<?php echo $mailoptions; ?>
			</select>
		</div>
		<p></p>
		<div id="sstssfb_autoresponder_wrapper"></div>
			<?php $this->sstssfb_emailservice(); /* anything new will be added here */ ?>		
		</div>
		<?php
	}
	
	function sstssfb_mailservice_enqueue_scripts() {
			wp_enqueue_script("sstssfb_mailservice_front_js", SSTSSFB_ADDONS . basename(dirname(__FILE__)) . "/asset-ex/emailfrontend.js", array("jquery", "jquery-ui-tooltip"), '0.1', true);
			wp_localize_script("sstssfb_mailservice_front_js", "eMailFrontend", array("ajaxurl" => admin_url("admin-ajax.php"), "SstssfbMailFrontAjax" => wp_create_nonce("sstssfb_subscribe_front_ajax_process")));
	}
	
	function notset_emailservice_notice() {
		global $post, $pagenow, $typenow;
		
		if($pagenow != 'edit.php' && ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow ==='sstssfb_builder' && !isset($_GET['page'])){
			
			 $provider = get_post_meta($post->ID, "sstssfb_autoresponder_saved_metakey", true);
			 if(empty($provider)) {
				return; 
			 }
			 $provider = isset($provider['service']) ? $provider['service'] : "selectone..";		 
			 $list = "";
			 
			 if($provider != "selectone..") {
				 $list = get_post_meta($post->ID, "sstssfb_$provider" . "_saved_metakey", true);
				 $list = isset($list['lists']) ? $list['lists'] : "Select one..";
			 }
			 
			 if($provider == "selectone.." || $list == "Select one..") {
				$class = "error sstssfb_error notice is-dismissible";
				$message = "The email service isn't set properly! Please open <i>Email Service</i> tab to set it and then activate your subscribe form!";
				$notice = "<div class='$class'>$message</div>";
				echo $notice;
			 }
			 
		}		
	}
	
}
new sstssfbEmailServices();
?>