<?php
if (!defined('ABSPATH')) exit;
class sstssfbBuilderBase {

	function __construct(){
		add_action( 'init', array($this, 'sstssfb_custom_post_type'), 999999);
		add_filter('post_updated_messages', array($this, 'sstssfb_updated_messages'));
		add_filter( 'gettext', array( $this, 'sstssfb_change_publish_button'), 10, 2 );
		add_action('post_edit_form_tag',array($this, 'sstssfb_edit_form_type_eps'));
		add_filter( 'enter_title_here', array($this, 'sstssfb_custom_title_fieldplaceholder'), 10, 2 );
		add_filter( 'bulk_post_updated_messages', array($this, 'sstssfb_updated_messages_filter'), 10, 2 );
	}
	
	function sstssfb_updated_messages_filter( $bulk_messages, $bulk_counts ) {

		$bulk_messages['sstssfb_builder'] = array(
			'updated'   => _n( '%s form updated.', '%s form updated.', $bulk_counts['updated'] ),
			'locked'    => _n( '%s form not updated, somebody is editing it.', '%s form not updated, somebody is editing them.', $bulk_counts['locked'] ),
			'deleted'   => _n( '%s form permanently deleted.', '%s form permanently deleted.', $bulk_counts['deleted'] ),
			'trashed'   => _n( '%s form moved to the Trash.', '%s form moved to the Trash.', $bulk_counts['trashed'] ),
			'untrashed' => _n( '%s form restored from the Trash.', '%s form restored from the Trash.', $bulk_counts['untrashed'] ),
		);

		return $bulk_messages;

	}
	
	public function sstssfb_custom_post_type() {
		$labels = array(
			'name'                => _x( 'Subscribe Form', 'Post Type General Name', 'sstssfbuilder' ),
			'singular_name'       => _x( 'Subscribe Form', 'Post Type Singular Name', 'sstssfbuilder' ),
			'menu_name'           => __( 'Subscribe Form', 'sstssfbuilder' ),
			'parent_item_colon'   => __( 'Parent Subscribe Form', 'sstssfbuilder' ),
			'all_items'           => __( 'Subscribe Forms', 'sstssfbuilder' ),
			'add_new_item'        => __( 'Create New Subscribe Form', 'sstssfbuilder' ),
			'add_new'             => __( 'New Form', 'sstssfbuilder' ),
			'edit_item'           => __( 'Edit Subscribe Form', 'sstssfbuilder' ),
			'update_item'         => __( 'Update Subscribe Form', 'sstssfbuilder' ),
			'search_items'        => __( 'Search Subscribe Form', 'sstssfbuilder' ),
			'not_found'           => __( 'No Subscribe Form', 'sstssfbuilder' ),
			'not_found_in_trash'  => __( 'No Subscribe Form in Trash', 'sstssfbuilder' ),
		);
		$args = array(
			'label'               => __( 'sstssfb_builder', 'sstssfbuilder' ),
			'description'         => __( 'Subscribe Form', 'sstssfbuilder' ),
			'labels'              => $labels,
			'supports'            => array('title'),
			'taxonomies'          => array(''),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 999999,
			'menu_icon'			  => 'dashicons-email',
			'can_export'          => true,
			'has_archive'         => false,
			'query_var' 		  => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
		);
		register_post_type( 'sstssfb_builder', $args );
	}
	
	// Custom message
	function sstssfb_updated_messages( $messages ) {
	global $pagenow, $typenow;
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow ==='sstssfb_builder' && !isset($_GET['page'])) {
			$post             = get_post();
			$post_type        = get_post_type( $post );
			$post_type_object = get_post_type_object( $post_type );
				$messages[$post_type] = array(
				0  => '', // Unused. Messages start at index 1.
				1  => __( 'Subscribe Form updated.', 'sstssfbuilder' ),
				2  => __( 'Custom field updated.', 'sstssfbuilder' ),
				3  => __( 'Custom field deleted.', 'sstssfbuilder' ),
				4  => __( 'Subscribe Form updated.', 'sstssfbuilder' ),
				// translators: %s: date and time of the revision
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Subscribe Form restored to revision from %s', 'sstssfbuilder' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => __( 'Subscribe Form created.', 'sstssfbuilder' ),
				7  => __( 'Subscribe Form saved.', 'sstssfbuilder' ),
				8  => __( 'Subscribe Form submitted.', 'sstssfbuilder' ),
				9  => sprintf(
					__( 'Subscribe Form scheduled for: <strong>%1$s</strong>.', 'sstssfbuilder' ),
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i', 'sstssfbuilder' ), strtotime( $post->post_date ) )
				),
				10 => __( 'Subscribe Form draft updated.', 'sstssfbuilder' )
				);
				
				if ( $post_type_object->publicly_queryable ) {
				$permalink = get_permalink( $post->ID );
				$view_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $permalink ), __( 'View Subscribe Form Page', 'sstssfbuilder' ) );
				$messages[ $post_type ][1] = $view_link;
				$messages[ $post_type ][6] .= $view_link;
				$messages[ $post_type ][9] .= $view_link;

				$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
				$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview Subscribe Form', 'sstssfbuilder' ) );
				$messages[ $post_type ][8]  .= $preview_link;
				$messages[ $post_type ][10] .= $preview_link;
			}
		}
		return $messages;
	}
	
	// Change Publish Subscribe Form
		function sstssfb_change_publish_button( $translation, $text ) {
		global $post, $pagenow;
		if ( 'sstssfb_builder' == get_post_type() || 'sstssfb_multiemail' == get_post_type()){
			if ( $text == '(no title)' ){
				return 'form ' . $post->ID;
			}
		if($pagenow !== "edit.php") {
			if ( $text == 'Move to Trash' ){
				return '';
			}
		}
		}
		
		if('sstssfb_builder' == get_post_type()) {			
			if ( $text == 'Publish' ){
				return 'Save Form';
			}
		} elseif('sstssfb_multiemail' == get_post_type()){	
			if ( $text == 'Publish' ){
				return 'Save Autoresponder';
			}			
		}
		return $translation;
		}
	
	// Enable form file upload
	function sstssfb_edit_form_type_eps() {
		echo ' enctype="multipart/form-data"';
	}	
	
	function sstssfb_custom_title_fieldplaceholder( $title, $post ) {
		if ( 'sstssfb_builder' == $post->post_type) {
			$title = 'Form identity';
		} elseif('sstssfb_multiemail' == $post->post_type) {
			$title = 'Autoresponder name';
		}
		return $title;
	}
	
    function sstssfb_override_mce_options($initArray) {		
	global $pagenow, $typenow;
		if (($pagenow == 'edit.php' || $pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow ==='sstssfb_builder' && !isset($_GET['page'])) {
			$opts = '*[*]';
			$initArray['paste_word_valid_elements'] = $opts;
			$initArray['valid_elements'] = $opts;
			$initArray['extended_valid_elements'] = $opts;
			return $initArray;
		}
    }
}

new sstssfbBuilderBase();
?>