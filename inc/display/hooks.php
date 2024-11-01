<?php
if (!defined('ABSPATH')) exit;
require_once("do-shortcode.php");
function sstssfb_Show_Form(){
	$adtshowads = new sstssfbShowForm();
}
add_action('template_redirect', 'sstssfb_Show_Form');
?>