<?php
if (!defined('ABSPATH')) exit;
if(!class_exists('SSTSSFB_WP_List_Table_BACKUP')){
    require_once('libs/class-wp-list-table.php');
}
if(!class_exists('SSTSSFB_WP_Posts_List_Table')){
    require_once('libs/class-wp-posts-list-table.php');
}

require_once("editformlist.php");
require_once("listtablescripts.php");
require_once("ajax/activate_deactivate.php");
require_once("ajax/cloneform.php");
require_once("ajax/delete_form.php");
?>