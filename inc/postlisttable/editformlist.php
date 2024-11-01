<?php
if (!defined('ABSPATH')) exit;

// Extend the WP_Posts_List_Table class to remove stuff
class SSTSSFB_PostType_Table extends SSTSSFB_WP_Posts_List_Table {
	public function search_box( $text, $input_id ){ }	
	protected function view_switcher( $current_mode ) {	}	
	private function sstssfb_pagination($which){
		?>
		<div class="sstssfb_pagination_head">
		<?php 		
			$this->pagination($which); 
		?>
		</div>
		<?php
	}
	public function display() {
		$singular = $this->_args['singular'];
		$this->sstssfb_pagination('top');
		?>
				
		<div class="sstssfb_actions_blocker"></div>
		<div class='sstssfb_actions_loader'>
		   <div class='loader'></div>
		   <div class='loader1'></div>
		   <div class='loader2'></div>
		   <div class='loader3'></div>
		   <div class='loader4'></div>
		   <div class='loader5'></div>
		   <div class='loader6'></div>
		   <div class='loader7'></div>
		</div>
		
		<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
			<thead>
			<tr>
				<?php $this->print_column_headers(); ?>
			</tr>
			</thead>
			<tbody id="the-list"<?php
					if ( $singular ) {
						echo " data-wp-lists='list:$singular'";
					} ?>>
					<?php $this->display_rows_or_placeholder(); ?>
			</tbody>
		</table>		
		<?php
		$this->sstssfb_pagination('bottom');
	}
	
	public function inline_edit() {}
}

// CUSTOMIZE POST LIST TABLE
class sstssfbCustomColumns {	
	function __construct() {
		add_filter( 'manage_edit-sstssfb_builder_columns', array($this, 'sstssfb_columns_filter'),10, 1 );
		add_filter('manage_edit-sstssfb_builder_sortable_columns', array($this, 'sstssfb_unsort_columns_filter'));
		add_action('manage_sstssfb_builder_posts_custom_column', array($this, 'sstssfb_custom_content_column'), 10, 2);
		add_action('post_row_actions', array($this, 'sstssfb_remove_row_actions'), 10, 2);
		add_filter( 'views_edit-sstssfb_builder', array($this, "sstssfb_custom_list_table"));
	}

	// DEFINE CUSTOM COLUMNS
	function sstssfb_columns_filter( $columns ) {
	   unset($columns['date']);
	   unset($columns['cb']);
	   $columns['title'] = esc_html("Forms");
	   $columns['actions'] = "";
	   return $columns;
	}

	// MAKE COLUMNS UNSORTABLE
	function sstssfb_unsort_columns_filter( $columns ) {
	   unset($columns['title']);
	   return $columns;
	}

	// REMOVE ROW ACTIONS
	function sstssfb_remove_row_actions($actions, $post) {
		if("sstssfb_builder" == $post->post_type) {
			return array();
		}
		return $actions;
	}

	// COLUMN'S CONTENTS
	/*** Actions ***/
	// * - edit, activate, deactivate, clone, delete
	function sstssfb_custom_content_column( $colname, $id ) {
		 
		 if ($colname == 'statistics') {
		 
		 }
		 
		 if ($colname == 'actions') {		
			 $provider = get_post_meta($id, "sstssfb_autoresponder_saved_metakey", true);
			 $provider = isset($provider['service']) ? $provider['service'] : "selectone..";			 
			 $list = "";
			 $warning = "";
			 $activate = "<span class=\"dashicons dashicons-lock sstssfb_acdc action_icons inactive\" title=\"Click to activate!\"></span>";
			 $icons = "";
			 if($provider == "selectone..") {
				 $warning = "<span 
							class='dashicons dashicons-warning action_icons incomplete_email' 
							title='The email service for this form hasn\"t yet been set!'
							></span>";
			 }
			 
			 if($provider != "selectone..") {				 
				 $list = get_post_meta($id, "sstssfb_$provider" . "_saved_metakey", true);
				 $list = isset($list['lists']) ? $list['lists'] : "Select one..";
				 if($list == "Select one..") {
					 $warning = "<span 
								class='dashicons dashicons-warning action_icons incomplete_email' 
								title='The email service for this form hasn\"t yet been set completely!'
								></span>";
				 }
			 }
			 
			 $icons .= $warning;
			 
			 if($provider == "selectone.." || $list == "Select one..") {				 
				delete_post_meta($id, "sstssfb_active_inactive_switcher");
				update_post_meta($id, sanitize_key("sstssfb_active_default"), esc_attr("off"));	
				$activate = "<span class=\"dashicons dashicons-lock noactive action_icons\" title=\"Cannot be activated!\"></span>";
			 }
			 
			 $on_off = get_post_meta($id, "sstssfb_active_inactive_switcher", true);			 
			 if(isset($on_off['active']) && $on_off['active'] == "active") {
				$icons .= '<span class="dashicons dashicons-unlock sstssfb_acdc action_icons active" title="Click to deactivate!"></span>';
			 } else {
				$icons .= $activate; 	 
			 }
			 
 			 $url = admin_url("post.php?post=$id&action=edit");
 			 $url = esc_url($url);
			 $icons .= "<a href='$url'><span class='dashicons dashicons-admin-generic action_icons sstssfb_edit' title='Edit'></span></a>";	
			 $icons .= '<span class="dashicons dashicons-admin-page action_icons sstssfb_clone" title="Clone"></span>';
			 $icons .= '<span class="dashicons dashicons-no action_icons sstssfb_delete" title="Delete"></span>';
			 
			 $content = "";
			 $content .= "<div class='sstssfb_action_icons'>";	
			 $content .= apply_filters("sstssfb_user_action_icons", $icons);
			 $content .= "<input type='hidden' class='idnumber' value='$id'>";			 
			 $content .= '<div class="sstssfb_confirm_deletion">
						<div class="sstssfb_stacking">
						<span class="del_question_main">Delete This Form?</span>
						<span class="del_warning">
						<span class="del_warning_icon dashicons dashicons-warning"></span> This action can\'t be undone!
						</span>
						  <div class="del_decision">	
							<span class="del_confirm">Yes</span>
							<span class="del_cancel">No</span>		
						  </div>
						  </div>
						</div>';			 
			 $content .= "</div>";
			 
			 echo apply_filters("sstssfb_user_row_actions", $content);
		 }
		 
	}
	
	// Override the post talbe object
	function sstssfb_custom_list_table() {
		global $wp_list_table;
		$sstssfblisttable = new SSTSSFB_PostType_Table();		
		$sstssfblisttable->prepare_items();		
		$wp_list_table = clone $sstssfblisttable;
	}
}

new sstssfbCustomColumns();
?>