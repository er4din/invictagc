<?php

/**
 * Related posts output for Janey Press child theme.
 */
if (!function_exists('janey_press_related_posts')) {

	function janey_press_related_posts($content) {
		
		if ( 
			janey_setting('janey_press_enable_related_posts', true) == true &&
			is_single()
		) :

			global $post;
	
			$postID = $post->ID;
			
			$catsArray = array();
			$tagsArray = array();

			foreach (get_the_category($postID) as $cat) {
				$catsArray[] = $cat->term_id;
			}
		
			foreach (wp_get_post_tags($postID) as $tag) {
				$tagsArray[] = $tag->term_id;
			}
		
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 3,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'desc',
				'fields' => 'ids',
				'exclude' => array($postID),
				'tax_query' => array(
					'relation' => 'OR',
					 array(
						'taxonomy' => 'category',
						'field' => 'term_id',
						'terms' => $catsArray,
						'operator' => 'IN'
					 ),
					 array(
						'taxonomy' => 'post_tag',
						'field' => 'term_id',
						'terms' => $tagsArray,
						'operator' => 'IN'
					 )
							 
				)
			);
		
			$relatedPosts = get_posts($args);
		
			if ( count($relatedPosts) <= 0 ) {
				
				return $content;
				
			} else {
		
				$relatedHTML = '<div class="related-posts">';
		
					$relatedHTML .= '<h3>';
					$relatedHTML .= esc_html(janey_setting('janey_press_related_posts_label', __( 'You may also like', 'janey-press' )));
					$relatedHTML .= '</h3>';

					$relatedHTML .= '<div class="related-posts-grid">';

						foreach ( $relatedPosts as $related ) {
				
							$relatedHTML .= '<section>';
							$relatedHTML .= janey_press_get_related_post($related);
							$relatedHTML .= '</section>';
				
						}

					$relatedHTML .= '</div>';

				$relatedHTML .= '</div>';
		
				return $content . $relatedHTML;
		
			}

		else :

			return $content;

		endif;
		
	}

	add_filter('the_content', 'janey_press_related_posts');

}

?>