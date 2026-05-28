<?php

/**
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

function janey_press_vertical_list_recent_posts_slideshow($args, $section_title) {

	?>

	<div class="vertical-recent-posts-section">
		
		<div class="recent-posts-head">
			
			<h4 class="title"><span><?php echo esc_html($section_title);?></span></h4>
			<div class="recent-posts-navigation vertical-recent-posts-navigation"></div>

		</div>

		<div class="vertical-recent-posts-slideshow recent-posts-slideshow">

			<?php

				$vertical_slideshow_query = new WP_Query($args);
				if( $vertical_slideshow_query->have_posts() ) :  while( $vertical_slideshow_query->have_posts() ) : $vertical_slideshow_query->the_post();
			
			?>

					<div class="recent-posts-small-article-wrapper">
					
						<div class="recent-posts-small-article">

							<div class="recent-posts-content">

								<h2><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h2>
								<p class="recent-posts-date"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo get_the_date();?></p>

							</div>

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
