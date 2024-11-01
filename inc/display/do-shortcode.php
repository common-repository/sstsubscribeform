<?php
if (!defined('ABSPATH')) exit;
require_once(dirname(plugin_dir_path(__FILE__)). "/geoip/src/geoip.inc");
require_once(dirname(plugin_dir_path(__FILE__)). "/geoip/src/geoipcity.inc");
require_once(dirname(plugin_dir_path(__FILE__)). "/geoip/src/geoipregionvars.php");

class sstssfbShowForm {
	
	private function isFromSearchEngine() {
			if(isset($_SERVER['HTTP_REFERER'])) {
			$seDomain = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
			$seDomain = preg_match("/[^\.\/]+\.[^\.\/]+$/", $seDomain, $match);
			$seDomain = preg_match("/.([^.]*)\./", $match[0], $match);
				switch($match[0]) {
					case 'google.':
					case 'yahoo.':
					case 'search.':
					case 'lycos.':
					case 'bing.':
					case 'msn.':
					case 'yandex.':
					case 'baidu.':
					case 'munax.':
					case 'blekko.':
					case 'duckduckgo.':
					case 'exalead.':
					case 'gigablast.':
					case 'qwant.':
					case 'sogou.':
					case 'youdao.':
					return true;
					break;
					default:
					return false;
				}		
		}	
	}
	
	private function isMobile() {
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))		
		return true;
	}
	
	public static function sstssfb_display() {
	global $post, $wpdb;
		$show = array();		
		$gi = geoip_open(SSTSSFB_ASSET_DIR . "GeoIP.dat", GEOIP_STANDARD);
		$country = geoip_country_name_by_addr($gi, $_SERVER['REMOTE_ADDR']);
		geoip_close($gi);		
		if($country == "") $country = "All";
		
		$post_id = get_the_ID();	
		
		// current post type
		$post_type = get_post_type($post_id);
		
		// current post taxonomy names
		$taxonomy_names = get_object_taxonomies($post_type, 'objects');		
		
		$authpar = "sp_authors$post_type";
		$spostpar = "speclabel_$post_type";
		
		// published date
		$date = strtotime(get_the_date());
		
		// current post ID
		$by_id = $post_type . "__" . $post_id;
		
		// author
		$page_author = "author_" . get_the_author_meta('ID');
		$page_author = $post_type . "__" . $page_author;
		
		// Loop through sstssfb_builder post type to get the id
		$the_query = new WP_Query("post_type=sstssfb_builder&field=ids&posts_per_page=-1&meta_key=sstssfb_active_inactive_switcher");	
		if ($the_query->have_posts()) {
			while ($the_query->have_posts()){
			$the_query->the_post();
			
			// We are talking about the current page, instead of the current subscribe form
			
				// The ID
				$form_id = get_the_ID();
				$location = get_post_meta($form_id, "sstssfb_save_locdata_metakey", true);
				$rules = get_post_meta($form_id, "sstssfb_save_rulesdata_metakey", true);
				$on = get_post_meta($form_id, "sstssfb_active_inactive_switcher", true);
				$cntryarr = array();
				
				if(isset($rules['countryitem']) && !empty($rules['countryitem']) && !in_array("All", $rules['countryitem'], true)) {
					foreach($rules['countryitem'] as $key => $country){
					   if(isset($cntryarr[$country])) {
						$cntryarr[$country] == $country;
					   }
					}
				} else {
					$cntryarr[$country] = $country;
				}
				
				// Initial Value
				$show[$form_id] = $form_id;				

				// CHECK IF ANY RULE IS NOT MATCH, THEN DON'T SHOW IN THE CURRENT PAGE AND CHECK OTHER ITERATION
				/* if(!isset($on['active'])) {
					unset($show[$form_id]);
					continue;
				} */
				
				if(!isset($cntryarr[$country])) {
					unset($show[$form_id]);
					continue;
				}
				
				if(isset($rules['cookies']) && isset($_COOKIE[$form_id . "_sstssfb"])) {
					unset($show[$form_id]);
					continue;
				}
				
				if(isset($rules['sev_only']) && !$this->isFromSearchEngine()) {
					unset($show[$form_id]);
					continue;
				}
				
				if(isset($rules['exclude_mobile']) && $this->isMobile()) {
					unset($show[$form_id]);
					continue;
				}
				
				if(!isset($rules['login_also']) && is_user_logged_in()) {
					unset($show[$form_id]);
					continue;
				}
				
				// CHECK FOR PAGE EXCLUSION IF ALL OF THE CHECKS ABOVE ARE CLEARED				
											// If Parent's Value is Set To ALL LOCATIONS
				if(isset($location['parent']) && $location['parent'] == "all_loc") {					
					if(isset($location['home_too']) && (is_home() || is_front_page())) {
						unset($show[$form_id]);
						continue;
					}
					
					if(isset($location['archive_too']) && is_archive()) {
						unset($show[$form_id]);
						continue;
					}
					
					if(isset($location['404_page']) && is_404()) {
						unset($show[$form_id]);
						continue;
					}
					
					// check if current post type is excluded
					if(isset($location['deeps'][$post_type])) {
						unset($show[$form_id]);
						continue;
					}
					
					// check if current taxonomy is excluded
					foreach($taxonomy_names as $tax){
						if(isset($location['deeps'][$post_type . "__" . $tax->name])) {
							unset($show[$form_id]);
							continue 2;
						}					
							$terms = wp_get_object_terms($post_id, $tax->name);
							foreach($terms as $term){
								if(isset($location['deeps'][$post_type . "__" . $tax->name . "__" . $term->name])) {
									unset($show[$form_id]);
									continue 3;
								}
							}
					}
					
					// exclude author
					if(isset($location['deeps'][$authpar])) {
						unset($show[$form_id]);
						continue;
					}
					if(isset($location['deeps'][$page_author])) {
						unset($show[$form_id]);
						continue;
					}

					// exclude specific post/ page
					if(isset($location['deeps'][$spostpar])) {
						unset($show[$form_id]);
						continue;
					}
					if(isset($location['deeps'][$by_id])) {
						unset($show[$form_id]);
						continue;
					}
					
					// if nothing is excluded, then just show everywhere because it has been set to All Locations
					continue; 
				}
			
/* 				
				CHECK FOR INCLUSION				
				If the page is not in the list of exclusion, then we are free to determine the page to show the subscribe form 					     */

				// Specific page inclusion
				if(isset($location['parent']) && $location['parent'] == "spec_loc") {					
					if(isset($location['home_too']) && (is_home() || is_front_page())) {
						continue;
					}
					
					if(isset($location['archive_too']) && is_archive()) {
						continue;
					}
					
					if(isset($location['404_page']) && is_404()) {
						continue;
					}

					// check if current post type is excluded
					if(isset($location['deeps'][$post_type])) {
						continue;
					}
					
					// check if current taxonomy is excluded
					foreach($taxonomy_names as $tax){
						if(isset($location['deeps'][$post_type . "__" . $tax->name])) {
							continue 2;
						}					
							$terms = wp_get_object_terms($post_id, $tax->name);
							foreach($terms as $term){
								if(isset($location['deeps'][$post_type . "__" . $tax->name . "__" . $term->name])) {
									continue 3;
								}
							}
					}
					
					// exclude author
					if(isset($location['deeps'][$authpar])) {
						continue;
					}
					if(isset($location['deeps'][$page_author])) {
						continue;
					}

					// exclude specific post/ page
					if(isset($location['deeps'][$spostpar])) {
						continue;
					}
					if(isset($location['deeps'][$by_id])) {
						continue;
					}
					
					// do nothing until the next checking section is finished if nothing is set here
				}				
				
				// If no page is set, then check if it is set by date
				if((isset($location['by_date'][0]) && $location['by_date'][0] != "") && (isset($location['by_date'][1]) && $location['by_date'][1] != "")) {
					if(strtotime($location['by_date'][0]) < $date && strtotime($location['by_date'][1]) > $date) {
						continue;
					}
				}
				
				if(isset($location['by_date'][0]) && $location['by_date'][0] != "") {
					if(strtotime($location['by_date'][0]) < $date) {
					continue;							
					}
				}
				
				if(isset($location['by_date'][1]) && $location['by_date'][1] != "") {
					if(strtotime($location['by_date'][1]) > $date) {
						continue;
					}
				}
				
				// if nothing is included, then show nowhere
				unset($show[$form_id]);			
			}
			wp_reset_postdata();
		}
		return $show;
	}
}
?>