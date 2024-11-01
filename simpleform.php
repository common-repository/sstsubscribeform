<?php
/*
Plugin Name: SoursopTree - Mailchimp Subscribe Form
Plugin URI: http://soursoptree.com/
<<<<<<< .mine
Description: Build beautiful and colorful Mailchimp subscribe form easily and quickly for your Wordpress blog/ website. "SoursopTree Mailchimp Subscribe Form" wp plugin will be maintained, updated and added with more features to meet your needs and specification, so do not hesitate to use it. Feel free to ask for support if there are something that doesn't meet your specification.
Version: 0.2
=======
Description: Build beautiful and colorful subscribe form easily and quickly for your Wordpress blog/ website. "SoursopTree Subscribe Form Builder" wp plugin will be maintained, updated and added with more features to meet your needs and specification, so do not hesitate to use it. Feel free to ask for support if there are something that doesn't meet your specification.
Version: 0.2
>>>>>>> .r1307428
Author: Ari Susanto
Author URI: https://twitter.com/SoursopTree
*/
if (!defined('ABSPATH')) exit;
require_once("inc/required_files.php");
function sstssfb_screen_layout_post() {
		return 1;
}
add_filter( 'get_user_option_screen_layout_sstssfb_builder', 'sstssfb_screen_layout_post' );
/* add_filter( 'get_user_option_screen_layout_sstssfb_multiemail', 'sstssfb_screen_layout_post' ); */

function sstssfbcheckwpversion() {
	global $wp_version;
	$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$url = esc_url($url);
	if(isset($_POST["sstssfbdismisvercheck"])) {
		update_option(sanitize_key("sstssfb_stop_showingvchecknotice"), intval(1));
		header("Location: $url");
		die();
	}
	
	if(!version_compare($wp_version, "4.3.0", ">=")) {
		?>
		<div class="error sstssfb_error notice" style="position: relative;"><p>It is highly recommended to update your wordpress version to at least 4.3.0 in order to use sst subscribe form plugin!</p>
			<form method="post" action="">
				<button class="notice-dismiss" type="submit" name="sstssfbdismisvercheck">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</button>
			</form>
		</div>
		<?php
	}
}
if(get_option(esc_attr("sstssfb_stop_showingvchecknotice")) != intval(1)) {
	add_action("admin_notices", "sstssfbcheckwpversion");
}

function sstssfbcheckgeoipdatabase() {
	global $wp_version;
	$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$url = esc_url($url);
	if(isset($_POST["sstssfbdismisgeocheck"])) {
		update_option(sanitize_key("sstssfb_stop_showinggeoipnotice"), intval(1));
		header("Location: $url");
		die();
	}	
		?>
		<div class="updated notice" style="position: relative;">
		<div style="padding: 6px 0 6px;"><p style="display: inline;">Please download <a href="http://www.maxmind.com" target="_blank">Maxmind</a> GEO IP database to make the geolocation feature working on this site!</p>
			<form method="post" action="" style="display:inline-block;margin-left:16px;">
				<button class="button button-small button-primary" style="display: inline-block;vertical-align: baseline;" type="submit" name="sstssfbdlgeoip">
					Download
				</button>
			</form>	
		</div>
			<form method="post" action="">
				<button class="notice-dismiss" type="submit" name="sstssfbdismisgeocheck">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</button>
			</form>
		</div>
		<?php
}

if(!file_exists(SSTSSFB_ASSET_DIR . 'GeoIP.dat') && get_option(esc_attr("sstssfb_stop_showinggeoipnotice")) != intval(1) && $_SERVER['HTTP_HOST'] != "localhost") {
	add_action("admin_notices", "sstssfbcheckgeoipdatabase");
}

function sstssfb_activation_hook_vcheck() {
	delete_option(esc_attr("sstssfb_stop_showingvchecknotice"));	
	/* INITIALIZE WPFilesytem */
	$access_type = get_filesystem_method();
	if($access_type === 'direct') {
		$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$creds = request_filesystem_credentials($url, '', false, false, array());
			// initialize the API
			if ( ! WP_Filesystem($creds) ) {
				// any problems and we exit
				return;
			}
			global $wp_filesystem;
	/* CREATE SSTSSFB_ASSETS DIRECTORY */
		if(!$wp_filesystem->is_dir(SSTSSFB_ASSET_DIR)) 
		{
			$wp_filesystem->mkdir(SSTSSFB_ASSET_DIR);
		}
		if(!$wp_filesystem->is_dir(SSTSSFBDIR_ADDONS)) 
		{
			$wp_filesystem->mkdir(SSTSSFBDIR_ADDONS);
		}
		if(!$wp_filesystem->is_dir(SSTSSFB_THEMEDIR)) 
		{
			$wp_filesystem->mkdir(SSTSSFB_THEMEDIR);
		}
	
	/* MOVE ADDITIONAL assets TO THAT (sstssfb_assets) DIRECTORY */
		// addons
		if(file_exists(SSTSSFBDIR_ADDONS_ORI))
		copy_dir(SSTSSFBDIR_ADDONS_ORI, SSTSSFBDIR_ADDONS);
		$wp_filesystem->rmdir(SSTSSFBDIR_ADDONS_ORI, true);
		// themes
		if(file_exists(SSTSSFB_THEMEDIR_ORI))
		copy_dir(SSTSSFB_THEMEDIR_ORI, SSTSSFB_THEMEDIR);
		$wp_filesystem->rmdir(SSTSSFB_THEMEDIR_ORI, true);
	}	
}
register_activation_hook(__FILE__, "sstssfb_activation_hook_vcheck");
?>