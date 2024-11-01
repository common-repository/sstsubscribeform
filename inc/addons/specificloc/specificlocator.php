<?php
if (!defined('ABSPATH')) exit;
class sstssfbSpecificLocations {
	function __construct() {
		add_filter("sstssfb_location_options", array($this, "specific_location_options"));
	}
	
	function specific_location_options($hierarchy) {
		global $wpdb, $post;
		/**************************************************************************************************************************************/
		/**         START POPULATE DETAILED LOCATION         *********************************************************************/
		/**************************************************************************************************************************************/		
		$args = array('public' => true);
		$post_types = get_post_types($args);		
		foreach( $post_types  as $post_type ) {
		 if($post_type == "sstssfb_builder" || $post_type == "attachment" || $post_type == "wp_adtentions") {
			continue;
		 }
		 $po = get_post_type_object($post_type);
		 $ptype = strtoupper($po->labels->name);			 
		 
		 $hierarchy .= '<ul class="post_type_item sstssfb_pages_list">';		 
		 // POST TYPE NAME
		 $saved = isset($location['deeps'][$post_type]) ? $location['deeps'][$post_type] : "";	
		 $hierarchy .= sprintf(
						'<li class="ptype_nameli">
							<span class="page_item_header post_type_header">
								<input type="checkbox" name="sstssfb_loc[deeps][%1$s]" value="%1$s" id="%1$s_hier" class="posttype_parent" %2$s/>
								<label for="%1$s_hier"></label>
								<label class="posttype_parentlabel parentlabel" for="%1$s_hier">%3$s</label>
							</span>
							<div class="openclose openclose_ptype dashicons dashicons-arrow-down-alt2"></div>
							<div class="clear"></div>
						</li>',
						esc_attr($post_type),
						checked(esc_attr($saved), esc_attr($post_type), false),
						esc_html($ptype)
					);					
		$hierarchy .= '<div class="post_type_childrens">';
		
		/***GET CATEGORY AND POSTS FOR POSTS AND CPT***/		
		
		// TAXONOMY
		 $taxonomy_names = get_object_taxonomies($post_type, 'objects');	
			 foreach($taxonomy_names as $tax) {
				 if(empty($taxonomy_names)) {
					continue;
				 }				 
				$categories = get_categories(array('taxonomy' =>$tax->name, 'hide_empty' => '0') );
				if(empty($categories)) {
				  continue;
				}
		$hierarchy .= '<div class="wrappperdiv">';			
					
				// TAXONOMY NAME
				$tname = $tax->name;
				$tlabel = strtoupper($tax->label);
				$taxval = $post_type . "__" . $tname;
				$savedtax = isset($location['deeps'][$taxval]) ? $location['deeps'][$taxval] : "";	
				$hierarchy .= sprintf(
								'<li class="tax_parentsli">
								<span class="page_item_header taxo_header">
									<input type="checkbox" class="taxonomy_parent" id="%1$s" name="sstssfb_loc[deeps][%2$s]" value="%2$s" %3$s/>
									<label for="%1$s"></label>
									<label class="taxonomy_parentlabel parentlabel" for="%1$s">%4$s</label>
								</span>
								<div class="openclose openclose_taxo dashicons dashicons-arrow-down-alt2"></div>
								<div class="clear"></div>
								</li>',
								esc_attr($tname),
								esc_attr($taxval),
								checked($savedtax, $taxval, false),
								esc_attr($tlabel)
							);							
				$hierarchy .= '<div class="taxonomy_childrens">';			
								
					// ALL CATEGORIES
					foreach($categories as $itm) {						
						// Taxonomy Items
						$itm_id = $itm->cat_ID;
						$txitmval = $post_type . "__" . $tax->name . "__" . $itm->name;
						$tximlbl = $itm->cat_name;
						$savedtxitm = isset($location['deeps'][$txitmval]) ? $location['deeps'][$txitmval] : "";
						$hierarchy .= sprintf(
										'<li>
										<input type="checkbox" class="detailed_item" id="itm_%1$s" name="sstssfb_loc[deeps][%2$s]" value="%2$s" %3$s/>
										<label for="itm_%1$s"></label>
										<label for="itm_%1$s">%4$s</label><div class="clear"></div></li>',
										esc_attr($itm_id),
										esc_attr($txitmval),
										checked($savedtxitm, $txitmval, false),
										esc_attr($tximlbl)
									);						
					}
		 $hierarchy .= '</div></div>';		 				
			 }			 

		// AUTHOR						
		$authr_args = (array) $wpdb->get_results("SELECT DISTINCT post_author, COUNT(ID) AS count FROM $wpdb->posts WHERE post_type = '$post_type' AND " . get_private_posts_cap_sql( $post_type ) . " GROUP BY post_author");		
		if(!empty($authr_args)) {
		$hierarchy .= '<div class="wrappperdiv">';				
			$valauthor = "sp_authors$post_type";
			$saved_author = isset($location['deeps'][$valauthor]) ? $location['deeps'][$valauthor] : "";
			$lblauthor = strtoupper("Specific $ptype by Author");
			$hierarchy .= sprintf(
							'<li class="tax_parentsli">
								<span class="page_item_header taxo_header">
									<input type="checkbox" id="%1$s" name="sstssfb_loc[deeps][%1$s]" value="%1$s" class="taxonomy_parent" %2$s/>
									<label for="%1$s"></label>
									<label class="taxonomy_parentlabel parentlabel" for="%1$s">%3$s</label>							
								</span>
								<div class="clear"></div>
								<div class="openclose openclose_taxo dashicons dashicons-arrow-down-alt2"></div>
							</li>',
							esc_attr($valauthor),
							checked($saved_author, $valauthor, false),
							esc_attr($lblauthor)
						);			
					$hierarchy .= '<div class="taxonomy_childrens">';			
										
			foreach ( $authr_args as $row ) {
				$authitem = $row->post_author;
				$authval = $post_type .  "__author_" . $authitem;
				$svauth = isset($location['deeps'][$authval]) ? $location['deeps'][$authval] : "";
				$authname = get_userdata($row->post_author)->display_name;
				$postnum = $row->count;
				$authlbl = "$authname ($postnum)";
				$hierarchy .= sprintf(
								'<li>
									<input type="checkbox" id="%1$s" class="detailed_item" id="%1$s" name="sstssfb_loc[deeps][%1$s]" value="%1$s" %2$s/>
									<label for="%1$s"></label>
									<label for="%1$s">%3$s</label>										
								</li>',
								esc_attr($authval),
								checked($svauth, $authval, false),
								esc_html($authlbl)
							  );
							  			
			}
		 $hierarchy .= '</div></div>';
		 		
		 wp_reset_postdata();						
		}
		$hierarchy .= '<div class="wrappperdiv">';			
			
		// ALL POSTS
		$parbase = $po->labels->name;
		$pparval = "speclabel_" . $post_type;
		$savparval = isset($location['deeps'][$pparval]) ? $location['deeps'][$pparval] : "";
		$parlbl = strtoupper("Specific $parbase");
		$hierarchy .= sprintf(
						'<li>
							<span class="page_item_header taxo_header">
							<input type="checkbox" id="%1$s" name="sstssfb_loc[deeps][%1$s]" value="%1$s" class="taxonomy_parent" %2$s/>
							<label for="%1$s"></label>
							<label class="taxonomy_parentlabel parentlabel" for="%1$s">%3$s</label>
							</span>
							<div class="openclose openclose_taxo dashicons dashicons-arrow-down-alt2"></div>
							<div class="clear"></div>
						</li>',
						esc_attr($pparval),
						checked($savparval, $pparval, false),
						esc_html($parlbl)						
					);		
		$hierarchy .= '<div class="taxonomy_childrens">';							
					
		// Page/ Post Items
		$the_query = new WP_Query("post_type=$post_type&field=ids&posts_per_page=-1");	
		if ($the_query->have_posts()) {			
			while ($the_query->have_posts()){
			$the_query->the_post();
				$fid = get_the_ID();
				$ptitle = get_the_title($fid);
				$pval = $post_type . "__" . $fid;
				$spval = isset($location['deeps'][$pval]) ? $location['deeps'][$pval] : "";
				$plabel = $ptitle != "" ? strlen($ptitle) <= 150 ? $ptitle : substr($ptitle, 0 , 150) . " ..." : "Post id $fid";
				$plabellink = "<a title='open' href='" . get_permalink($fid) . "' target='_blank' class='dashicons dashicons-controls-play visit-item'></a>";				
				$hierarchy .= sprintf(
					'<li>
						<input type="checkbox" id="item_%1$s" name="sstssfb_loc[deeps][%2$s]" value="%2$s" class="detailed_item" %3$s/>
						<label for="item_%1$s"></label>
						<label for="item_%1$s" title="%4$s" class="post_title_label">%5$s</label>%6$s							
						<div class="clear"></div>						
					</li>',
					esc_attr($fid),
					esc_attr($pval),
					checked($spval, $pval, false),
					esc_attr($ptitle),
					$plabel,
					$plabellink
				);					
			}
			wp_reset_postdata();
		}
			 $hierarchy .= '</div></div></div></ul>';
		}	
		
		/**************************************************************************************************************************************/		
		/**         	End Populate Detailed Location         	  *********************************************************************/		
		/**************************************************************************************************************************************/		
		
		return $hierarchy;
	}
}

new sstssfbSpecificLocations();
?>