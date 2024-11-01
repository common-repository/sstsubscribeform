<?php
ob_start();
if (!defined('ABSPATH')) exit;
class sstssfbcreateCookie {
	function __construct() {
		add_action('init', array($this, 'sstssfbSetcookiee'));
	}
	
	function sstssfbSetcookiee() {
		global $wpdb;
	$the_query = new WP_Query("post_type=sstssfb_builder&field=ids&posts_per_page=-1");	
		if ($the_query->have_posts()) {
			while ($the_query->have_posts()){
			$the_query->the_post();
			$popid = esc_attr(get_the_ID());
				$rules = get_post_meta($popid, "_sstssfb_display_rules", true);
				$cookie = get_post_meta($popid, '_sstssfb_cookie_rule', true);
				
				$cookie = strtotime($cookie . 'day', 0);
				$expiry = get_post_meta($popid, '_sstssfb_cookie_history', true);
				if(is_array($rules)){
				if(in_array('use_cookies', $rules) && $cookie > 0 && !isset($_COOKIE[$popid . "_sstssfb"])){
					setcookie($popid . "_sstssfb", "ShowForm", time()+$cookie);
					update_post_meta($popid, sanitize_key('_sstssfb_cookie_history'), time()+$cookie);						
				}
				}
			}
		}
	}
}
new sstssfbcreateCookie();
?>