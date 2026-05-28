<?php

/**
 * Related post item renderer for Janey Press child theme.
 */
function janey_press_get_related_post($postID) {

	$excerpt = wp_trim_words(get_the_content($postID), 10 , '...' );

	$html = '';
	
	$html .= '<a class="related-post-permalink" title="' . esc_attr(get_the_title($postID)) . '" href="' . esc_url(get_permalink($postID)) . '">';
	$html .= get_the_post_thumbnail($postID, 'janey_press_related_post');
	$html .= '</a>';
		
	$html .= '<div class="related_post_details">';
	
		$html .= '<a title="' . esc_attr(get_the_title($postID)) . '" href="' . esc_url(get_permalink($postID)) . '">';
		$html .= '<h4>' . esc_html(get_the_title($postID)) . '</h4>';
		$html .= '</a>';
		$html .= '<div class="meta-info">' . esc_html(get_the_date(false, $postID)) . '</div>';
			
	$html .= '</div>';
	
	return $html;

}
?>