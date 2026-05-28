<?php

/**
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!function_exists('janey_press_thumbnail_function')) {

	function janey_press_thumbnail_function($size) {

		global $post;
		
		$html = '';

		$section = (is_home() || janey_is_archive() || is_search()) ? 'archive' : 'single';
		$class = ( $section == 'archive' ) ? ' blog-section' : '';
		$image_link = ( $section == 'archive' ) ? '<a class="blog-article-image-link" href="' . esc_url(get_permalink()) . '"></a>' : '';
	
		if (
			(
				$section == 'archive' &&
				( 
					has_post_thumbnail() ||
					janey_setting('janey_press_has_post_placeholder', true) == true
				) 
			) ||
			(
				$section == 'single' &&
				has_post_thumbnail() 
			)
	
		) {
	
			$html  = '<div class="pin-container' . esc_attr($class) .'">';
			$html .= $image_link;
			$html .= get_the_post_thumbnail($post->ID, $size);
			$html .= '</div>';
		
		}
	
		echo $html;
	
	}

	add_action( 'janey_thumbnail', 'janey_press_thumbnail_function', 10, 1 );

}

?>