<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- WooTumblogPostFormat Class
-- WooTumblogPostFormat()
-- woo_tumblog_upgrade_existing_taxonomy_posts_to_post_formats()
-- woo_get_term_by()
-- woo_get_the_term_list()
-- woo_get_the_terms()
-- woo_wp_get_object_terms()
-- woo_tumblog_restrict_manage_posts()
-- woo_tumblog_posts_where()

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* WooTumblogPostFormat Class */
/*-----------------------------------------------------------------------------------*/
	
class WooTumblogPostFormat {
	
	/*-----------------------------------------------------------------------------------*/
	/* Constructor */
	/*-----------------------------------------------------------------------------------*/
	function WooTumblogPostFormat() {
		
		// Add Theme Support
		add_theme_support( 'post-formats', array( 'aside', 'image', 'audio', 'video', 'quote', 'link' ) );
									
		// Custom Taxonomy Filters
		add_action('restrict_manage_posts', array(&$this, 'woo_tumblog_restrict_manage_posts'));
		add_filter('posts_where', array(&$this, 'woo_tumblog_posts_where'));
							
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* Upgrade Existing Taxonomy Posts */
	/*-----------------------------------------------------------------------------------*/
	
	function woo_tumblog_upgrade_existing_taxonomy_posts_to_post_formats() {
		
		// Test if upgrade performed already
		if (get_option('woo_tumblog_post_formats_upgraded') == 'true') {
			return false;
		} 
		
		$tumblog_items = array(	'articles'	=> get_option('woo_articles_term_id'),
								'images' 	=> get_option('woo_images_term_id'),
								'audio' 	=> get_option('woo_audio_term_id'),
								'video' 	=> get_option('woo_video_term_id'),
								'quotes'	=> get_option('woo_quotes_term_id'),
								'links' 	=> get_option('woo_links_term_id')
								);
		
		$tumblog_posts = get_posts( array('posts_per_page' => -1) );
		
		foreach ($tumblog_posts as $tumblog_post) {
		                  
          	$post_id = $tumblog_post->ID;
 	   	              	
           	//switch between tumblog taxonomies
			$tumblog_list = $this->woo_get_the_term_list( $post_id, 'tumblog', '' , '|' , ''  );
			$tumblog_list = strip_tags($tumblog_list);
			$tumblog_array = explode('|', $tumblog_list);
			
			$tumblog_results = '';
			$sentinel = false;
			foreach ($tumblog_array as $location_item) {
	    		$tumblog_id = $this->woo_get_term_by( 'name', $location_item, 'tumblog' );
	    		if ( $tumblog_items['articles'] == $tumblog_id->term_id && !$sentinel ) {
	    			$tumblog_results = 'article';
	    			$sentinel = true;
	    		} elseif ($tumblog_items['images'] == $tumblog_id->term_id && !$sentinel ) {
	    			$tumblog_results = 'image';
	    			$sentinel = true;
	    		} elseif ($tumblog_items['audio'] == $tumblog_id->term_id && !$sentinel) {
	    			$tumblog_results = 'audio';
	    			$sentinel = true;
	    		} elseif ($tumblog_items['video'] == $tumblog_id->term_id && !$sentinel) {
	    			$tumblog_results = 'video';
	    			$sentinel = true;
	    		} elseif ($tumblog_items['quotes'] == $tumblog_id->term_id && !$sentinel) {
	    			$tumblog_results = 'quote';
	    			$sentinel = true;
	    		} elseif ($tumblog_items['links'] == $tumblog_id->term_id && !$sentinel) {
	    			$tumblog_results = 'link';
	    			$sentinel = true;
	    		} else {
	    			// Do Nothing	
	    			$tumblog_results = 'default';
	    			$sentinel = false;
	    		}	    		
	    	}    
	    	
	    	// SET POST FORMATS
	    	if ($tumblog_results == 'article') {  
    			// ARTICLE POST -->
    			set_post_format( $post_id, 'aside' );
    		} elseif ($tumblog_results == 'image') { 
    			// IMAGE POST -->
       			set_post_format( $post_id, 'image' );
       		} elseif ($tumblog_results == 'video') { 
    			// VIDEO POST -->
       			set_post_format( $post_id, 'video' );
       		} elseif ($tumblog_results == 'link') { 
    			// LINK POST -->
       			set_post_format( $post_id, 'link' );
       		} elseif ($tumblog_results == 'audio') {
    			// AUDIO POST -->
       			set_post_format( $post_id, 'audio' );
       		} elseif ($tumblog_results == 'quote') {
    			// QUOTE POST -->
       			set_post_format( $post_id, 'quote' );
	    	} else {
	    		// DO NOTHING
	    	}
	       		    	
        }
        
        return true;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* Woo Customized WordPress functions - necessary to upgrade non registered taxonomy */
	/*-----------------------------------------------------------------------------------*/
	
	// get_term_by
	function woo_get_term_by($field, $value, $taxonomy, $output = OBJECT, $filter = 'raw') {
		global $wpdb;
		
		//if ( ! taxonomy_exists($taxonomy) )
		//	return false;
		
		if ( 'slug' == $field ) {
			$field = 't.slug';
			$value = sanitize_title_for_query($value);
			if ( empty($value) )
				return false;
		} else if ( 'name' == $field ) {
			// Assume already escaped
			$value = stripslashes($value);
			$field = 't.name';
		} else {
			return get_term( (int) $value, $taxonomy, $output, $filter);
		}
		
		$term = $wpdb->get_row( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = %s AND $field = %s LIMIT 1", $taxonomy, $value) );
		if ( !$term )
			return false;
		
		wp_cache_add($term->term_id, $term, $taxonomy);
		
		$term = apply_filters('get_term', $term, $taxonomy);
		$term = apply_filters("get_$taxonomy", $term, $taxonomy);
		$term = sanitize_term($term, $taxonomy, $filter);
		
		if ( $output == OBJECT ) {
			return $term;
		} elseif ( $output == ARRAY_A ) {
			return get_object_vars($term);
		} elseif ( $output == ARRAY_N ) {
			return array_values(get_object_vars($term));
		} else {
			return $term;
		}
	}		
	
	// get_the_term_list
	function woo_get_the_term_list( $id = 0, $taxonomy, $before = '', $sep = '', $after = '' ) {
		$terms = $this->woo_get_the_terms( $id, $taxonomy );

		if ( is_wp_error( $terms ) )
			return $terms;

		if ( empty( $terms ) )
			return false;

		foreach ( $terms as $term ) {
			$link = get_term_link( $term, $taxonomy );
			if ( is_wp_error( $link ) )
				return $link;
			$term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
		}

		$term_links = apply_filters( "term_links-$taxonomy", $term_links );

		return $before . join( $sep, $term_links ) . $after;
	}
	
	// get_the_terms
	function woo_get_the_terms( $id = 0, $taxonomy ) {
		global $post;
	
	 	$id = (int) $id;
	
		if ( !$id ) {
			if ( !$post->ID )
				return false;
			else
				$id = (int) $post->ID;
		}
	
		//$terms = get_object_term_cache( $id, $taxonomy );
		//if ( false === $terms ) {
			$terms = $this->woo_wp_get_object_terms( $id, $taxonomy );
		//	wp_cache_add($id, $terms, $taxonomy . '_relationships');
		//}
	
		$terms = apply_filters( 'get_the_terms', $terms, $id, $taxonomy );
	
		if ( empty( $terms ) )
			return false;
	
		return $terms;
	}
	
	// wp_get_object_terms
	function woo_wp_get_object_terms($object_ids, $taxonomies, $args = array()) {
		global $wpdb;
	
		if ( !is_array($taxonomies) )
			$taxonomies = array($taxonomies);
	
		/*
		foreach ( (array) $taxonomies as $taxonomy ) {
			if ( ! taxonomy_exists($taxonomy) )
				return new WP_Error('invalid_taxonomy', __('Invalid Taxonomy'));
		}
		*/
	
		if ( !is_array($object_ids) )
			$object_ids = array($object_ids);
		$object_ids = array_map('intval', $object_ids);
	
		$defaults = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
		$args = wp_parse_args( $args, $defaults );
	
		$terms = array();
		if ( count($taxonomies) > 1 ) {
			foreach ( $taxonomies as $index => $taxonomy ) {
				$t = get_taxonomy($taxonomy);
				if ( isset($t->args) && is_array($t->args) && $args != array_merge($args, $t->args) ) {
					unset($taxonomies[$index]);
					$terms = array_merge($terms, wp_get_object_terms($object_ids, $taxonomy, array_merge($args, $t->args)));
				}
			}
		} else {
			$t = get_taxonomy($taxonomies[0]);
			if ( isset($t->args) && is_array($t->args) )
				$args = array_merge($args, $t->args);
		}
	
		extract($args, EXTR_SKIP);
	
		if ( 'count' == $orderby )
			$orderby = 'tt.count';
		else if ( 'name' == $orderby )
			$orderby = 't.name';
		else if ( 'slug' == $orderby )
			$orderby = 't.slug';
		else if ( 'term_group' == $orderby )
			$orderby = 't.term_group';
		else if ( 'term_order' == $orderby )
			$orderby = 'tr.term_order';
		else if ( 'none' == $orderby ) {
			$orderby = '';
			$order = '';
		} else {
			$orderby = 't.term_id';
		}
	
		// tt_ids queries can only be none or tr.term_taxonomy_id
		if ( ('tt_ids' == $fields) && !empty($orderby) )
			$orderby = 'tr.term_taxonomy_id';
	
		if ( !empty($orderby) )
			$orderby = "ORDER BY $orderby";
	
		$taxonomies = "'" . implode("', '", $taxonomies) . "'";
		$object_ids = implode(', ', $object_ids);
	
		$select_this = '';
		if ( 'all' == $fields )
			$select_this = 't.*, tt.*';
		else if ( 'ids' == $fields )
			$select_this = 't.term_id';
		else if ( 'names' == $fields )
			$select_this = 't.name';
		else if ( 'all_with_object_id' == $fields )
			$select_this = 't.*, tt.*, tr.object_id';
	
		$query = "SELECT $select_this FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ($taxonomies) AND tr.object_id IN ($object_ids) $orderby $order";
	
		if ( 'all' == $fields || 'all_with_object_id' == $fields ) {
			$terms = array_merge($terms, $wpdb->get_results($query));
			update_term_cache($terms);
		} else if ( 'ids' == $fields || 'names' == $fields ) {
			$terms = array_merge($terms, $wpdb->get_col($query));
		} else if ( 'tt_ids' == $fields ) {
			$terms = $wpdb->get_col("SELECT tr.term_taxonomy_id FROM $wpdb->term_relationships AS tr INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tr.object_id IN ($object_ids) AND tt.taxonomy IN ($taxonomies) $orderby $order");
		}
	
		if ( ! $terms )
			$terms = array();
	
		return apply_filters('wp_get_object_terms', $terms, $object_ids, $taxonomies, $args);
	}

	/*-----------------------------------------------------------------------------------*/
	/* Manage Posts Custom Filter Drop Down */
	/*-----------------------------------------------------------------------------------*/
	
	function woo_tumblog_restrict_manage_posts() {
    ?>
        
            <fieldset>
            <?php
				//Tumblogs
				$category_ID = $_GET['post_format_names'];
            	if ($category_ID > 0) {
            		//Do nothing
            	} else {
            		$category_ID = 0;
            	}
            	$dropdown_options = array	(	
            								'show_option_all'	=> __( 'View all Tumblogs', 'woothemes' ), 
            								'hide_empty' 		=> 0, 
            								'hide_if_empty'		=> 1,
            								'hierarchical' 		=> 1,
											'show_count' 		=> 0, 
											'orderby' 			=> 'name',
											'name' 				=> 'post_format_names',
											'id' 				=> 'post_format_names',
											'taxonomy' 			=> 'post_format', 
											'selected' 			=> $category_ID
											);
				$post_formats = get_terms( 'post_format' );
				
            ?>
            <select class="" name="post_format_names" id="post_format_names">
            	<option value="0"><?php _e( 'View all Tumblogs', 'woothemes' ); ?></option>
            	<?php foreach ($post_formats as $post_format_item) { $nice_tax_name = str_replace('post-format-', '', $post_format_item->name); ?>
            	<option value="<?php echo $post_format_item->term_id; ?>" <?php if ($category_ID == $post_format_item->term_id) { echo 'selected="selected"'; } ?>><?php echo ucwords( $nice_tax_name ); ?></option>
            	<?php } ?>
            </select>
            <input type="submit" name="submit" value="<?php _e( 'Filter', 'woothemes' ); ?>" class="button" />
        </fieldset>
        
    <?php
	}

	/*-----------------------------------------------------------------------------------*/
	/* Manage Posts Custom Filter Query Addon */
	/*-----------------------------------------------------------------------------------*/
	
	function woo_tumblog_posts_where($where) {
    	if( is_admin() ) {
        	global $wpdb;
        	if (isset($_GET['post_format_names'])) {
        		$tumblog_ID = $_GET['post_format_names'];
			} else {
				$tumblog_ID = 0;
			}
        	if ( ($tumblog_ID > 0) ) {

				$tumblog_tax_names =  &get_term( $tumblog_ID, 'post_format' );
				$string_post_ids = '';
 				//tumblogs
				if ($tumblog_ID > 0) {
					$tumblog_tax_name = $tumblog_tax_names->slug;
					$nice_tax_name = str_replace('post-format-', '', $tumblog_tax_name);
					$tumblog_myposts = get_posts('nopaging=true');
					foreach($tumblog_myposts as $post) {
						if (has_post_format($nice_tax_name, $post->ID)) {
							$string_post_ids .= $post->ID.',';
						}
					}
				}
			
 				$string_post_ids = chop($string_post_ids,',');
   				$where .= "AND ID IN (" . $string_post_ids . ")";
			}
    	}
    	return $where;
	}
	
}

?>