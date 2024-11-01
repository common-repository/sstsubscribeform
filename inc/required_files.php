<?php
if (!defined('ABSPATH')) exit;
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require("sstconfig.php");
require("libs/admin/settings.php");
require("libs/front/subscribeprocess.php");
require_once("base.php");
require_once("postlisttable/includes.php");

if(file_exists(SSTSSFB_DIR . "addonsmanager/includes.php")) {
	require_once("addonsmanager/includes.php");
}

if(file_exists(SSTSSFB_DIR . "themeuploader/includes.php")) {
	require_once("themeuploader/includes.php");
}

require_once("controls/controllerbox.php");
require_once("controls/delete.php");
require_once("save/save.php");
require_once("cookies.php");
require_once("display/do-shortcode.php");
require_once("display/shortcodes.php");
require_once("display/hooks.php");
require_once("geoip/update.php");
require_once("enqueuescritps.php");
require_once("uptodate/main.php");
require_once("uptodate/scripts.php");
require_once("helps/helps.php");
require_once("helps/scripts.php");
// AddOns Files

if(!file_exists(SSTSSFBDIR_ADDONS) || !file_exists(SSTSSFB_THEMEDIR)) return;
$path = SSTSSFBDIR_ADDONS;
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$objects = new SstSsfbSortedIterator($objects);
	foreach ($objects as $file) {
		$filename = $file->getPathname();
		if (strpos($filename, '-ex') === false && !$file->isDir() && pathinfo($filename, PATHINFO_EXTENSION) == "php"){
		   require_once $file->getPathname();
		}
	}

require_once("filters.php");
?>