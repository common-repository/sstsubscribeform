<?php
if (!defined('ABSPATH')) exit;
add_action('admin_init', 'sstssfb_updategeodatabase');
function sstssfb_updategeodatabase(){
if($_SERVER['HTTP_HOST'] != "localhost") {
	$current_month = get_option("sstssfb_current_month_name");
	$first_install = get_option("sstssfb_first_installation_done");
	$currentdate = date('d');
	$date = date('Y-m-d'); 
	$currentday = date("D", strtotime($date));
	$geofile = SSTSSFB_ASSET_DIR . 'GeoIP.dat';
	if($first_install != 1 && !isset($_POST["sstssfbdlgeoip"])) {
		return;
	}
	// This assume that the admin logged in every day
	if(($currentdate <= 10 && $currentday == "Tue" && $current_month != date('m')) || !file_exists($geofile)) {
		
		update_option(sanitize_key("sstssfb_first_installation_done"), intval(1));
		
		update_option(sanitize_key("sstssfb_current_month_name"), date('m'));
		
		$filepath = "http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz";
		// $filepath = "http://localhost/GeoIP.dat.gz";
		$ch = curl_init( $filepath );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$data = curl_exec( $ch );
		curl_close( $ch );
			$upload_dir = wp_upload_dir();				
			$destination = $upload_dir['basedir'] . '/GeoIP.dat.gz';
		file_put_contents( $destination, $data );
		
			$file = $destination;
			$out_file_name = $upload_dir['basedir'] . '/GeoIP.dat';
			$tmp = gzopen($file, "rb");
			$out_file = fopen($out_file_name, 'wb');
			while(!gzeof($tmp)) {
			fwrite($out_file, gzread($tmp, 4096));
			}
			fclose($out_file);
			gzclose($tmp);
	// Start WPFilesytem	
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
					$upload_dir = wp_upload_dir();				
					$destination = $upload_dir['basedir'] . '/GeoIP.dat';
					$wp_filesystem->copy($destination, SSTSSFB_ASSET_DIR . 'GeoIP.dat', true, FS_CHMOD_FILE);
					$wp_filesystem->delete($destination);
					$wp_filesystem->delete($upload_dir['basedir'] . '/GeoIP.dat.gz');
			}
		}
 }
}
?>