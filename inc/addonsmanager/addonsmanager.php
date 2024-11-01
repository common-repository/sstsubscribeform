<?php
if (!defined('ABSPATH')) exit;
if ( ! function_exists( 'wp_handle_upload' ) ) 
require_once( ABSPATH . 'wp-admin/includes/file.php' );

class sstssfbAddonsManager {

	function __construct() {
		// additional sub menu page
		add_action(
					'admin_menu',
					 array($this, 'sstssfb_addonsmanager_page')
				   );
	}
	
	function sstssfb_addonsmanager_page() { 
		add_submenu_page(
							'edit.php?post_type=sstssfb_builder',
							'Add-ons Manager',
							'Add-on',
							'manage_options',
							'sstssfb-addons_manager_page',
							 array($this, 'sstssfb_addons_manager_page')
						);
	}
	
	function sstssfb_addons_manager_page() {
		
		if ( ! current_user_can('install_plugins') )
			wp_die(__('You do not have sufficient permissions to install add-on on this site.'));		
		
		echo _e("<h1>Add-ons Manager</h1>");
		
		if(isset($_POST['installaddon'])) {
			
			$result = "No file selected!";
			
			// if uploaded file exist
			if(isset($_FILES['sstssfbaddon']['name']) && $_FILES['sstssfbaddon']['name'] != "") {
			
			// the addon file
			$addonfile = $_FILES['sstssfbaddon'];
			
			// override test_form to false
			$override = array( 'test_form' => false );
			
			// check if file is zip
			$file_type = "";
			$zip = zip_open($addonfile['tmp_name']);
				if(!is_resource($zip)) {
					echo "File should be a .zip!";
					return;
				}
			$file_type = "zip";
			zip_close($zip);
			
			// upload limit
			$maxFileSize = 64 * 1024 * 1024;				
				
				// if file is allowed and not exceed the size limit
				if($file_type == "zip" && $addonfile['size'] <= $maxFileSize) {
					
					// upload file
					$movefile = wp_handle_upload( $addonfile, $override );								
					
					// if upload success
					if ($movefile && !isset($movefile['error'])) {
					
					// get the dir path of the uploaded file
					$fpath = $movefile['file'];
					/* $basename = pathinfo($fpath, PATHINFO_FILENAME); */
					$to = SSTSSFBDIR_ADDONS;
					
					if(!isset($fpath)) {
						$result = "Something wrong when uploading the file!";
						echo "$result";
						return;
					}
					
					// Initialize WP_FILE_SYSTEM
					$access_type = get_filesystem_method();
					if($access_type !== 'direct') {
						return;
					}
					
					$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
					$creds = request_filesystem_credentials($url, '', false, false, array());
					
					// initialize the API
					if ( ! WP_Filesystem($creds) ) {
						// any problems and we exit
						return;
					}
					global $wp_filesystem;
					
					// unzip the file
					$unzip = unzip_file($fpath, $to);

						if($unzip !== true)
						{
							$result = "Cannot unzip the file!";
							echo "$result";
							return;
						}
						
					// delete the file located in upload folder
					$wp_filesystem->delete($fpath);
					
					// return the message if everything successful					
					$result = "Addon successfully installed!";
					
					// if upload not success
					} else {
						$result = "Error! Please retry!";
					}
					
				// if file type is not allowed	
				} elseif($file_type == "") {
					$result = "Wrong file type";				
				// if exceed the size limit
				} elseif($addonfile['size'] > $maxFileSize) {
					$result = "Too big";
				}
			}
			
		echo "$result";		
		// Don't show the tabs
		return;			
		}
		?>
		<div id="sstssfb_tab">
			<ul>
				<?php do_action("sstssfb_admtab_handle_before"); ?>
				<li><a href="#tab-2">Add New</a></li>
				<?php do_action("sstssfb_admtab_handle_after"); ?>
			</ul>
			<?php do_action("sstssfb_admtab_content_before"); ?>
			<div id="tab-2" class="tab-item">
				<div id="tab_2-secondwrapper">
				
					<div class="sstssfbtabheader_area">
							<h1>Add New Add-on</h1>	
							<span>Upload new add-on for SoursopTree Subscribe Form Builder.</span>
					</div>
					
					<div class="sstssfbtabcontents_area">
						<!--- Tab's contents are here! --->
						<p class="install-help">If you have add-on in a .zip format, you may install it by uploading it here.</p>
						<form method="post" enctype="multipart/form-data" action="" id="addoninstallform">
							<span class="file_info"></span>
							<input type="file" name="sstssfbaddon" id="sstssfbupload_addon" value=""/>
							<label class="button" for="sstssfbupload_addon">browse add-on file</label>
							<input type="submit" name="installaddon" value="Install" id="sstssfbstartinstall"/>
						</form>
					</div>
					
					<div class="clear"></div>
				</div>
			</div>
			<?php do_action("sstssfb_admtab_content_after"); ?>
			<div id="sstssfb_ajax_loader">
				<div class="cssload-line">
				</div>	
			</div>			
		</div>	
	<?php	
	
	}
	
}
	new sstssfbAddonsManager();
?>