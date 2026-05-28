<?php

/**
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

function janey_press_horizontal_recent_posts_slideshow($args, $section_title, $transition = 'fade') {

	?>

	<div class="horizontal-recent-posts-section">
		
		<div class="recent-posts-head">
			
			<h4 class="title"><span><?php echo esc_html($section_title);?></span></h4>
			<div class="recent-posts-navigation horizontal-recent-posts-navigation"></div>

		</div>

		<div class="horizontal-recent-posts-slideshow recent-posts-slideshow" data-transition="<?php echo esc_attr($transition);?>">

			<?php

				$horizontal_slideshow_query = new WP_Query($args);

				if( $horizontal_slideshow_query->have_posts() ) :  while( $horizontal_slideshow_query->have_posts() ) : $horizontal_slideshow_query->the_post();

					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'janey_press_recent_post_large');
					$imgBackground = (isset($thumb[0])) ? ' style="background-image:url(' . esc_url($thumb[0]) . ')"' : '';
					$overlayPostCSSClass = (!isset($thumb[0])) ? ' overlay-article-placeholder' : '';

			?>

					<div class="recent-posts-overlay-article<?php echo esc_attr($overlayPostCSSClass);?>" <?php echo $imgBackground; ?>>

						<a class="recent-posts-image-link" href="<?php echo esc_url(get_permalink()); ?>"></a>

						<div class="recent-posts-content">

							<h2><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h2>
							<p class="author"><?php echo esc_html(janey_setting('janey_press_recent_posts_post_author_label', __( 'By', 'janey-press' ))) . '&nbsp;' . get_the_author_posts_link(); ?></p>

						</div>

					</div>
		
			<?php

				endwhile;
				wp_reset_postdata();
				endif;
		
			?>     


		</div>

	</div>

	<?php

}
	
?>
