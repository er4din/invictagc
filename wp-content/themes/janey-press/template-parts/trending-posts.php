<?php

    if ( janey_setting('janey_press_enable_trending_posts', true) == true ) :

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 4,
            'ignore_sticky_posts' => true,
            'orderby' => janey_setting('janey_press_trending_posts_orderby', 'comment_count'),
            'order' => janey_setting('janey_press_trending_posts_sort_order', 'desc'),
        );

        if ( is_numeric(janey_setting('janey_press_trending_posts_category')) ) :
			$args['cat'] = janey_setting('janey_press_trending_posts_category');
		endif;

        $trending_posts = new WP_Query($args);
        $trending_posts_enabled = janey_setting('janey_press_enable_trending_posts_only_first_page_pagination', true);

        if (
            $trending_posts->have_posts() &&
            (
                (
                    $trending_posts_enabled == true && 
                    get_query_var('paged') <= 1
                ) ||
                $trending_posts_enabled == false
            )
    
        ) :

        $trending_articles_additional_css = ( janey_setting('janey_press_trending_posts_truncate_post_titles', true) == true ) ? ' trending-articles-truncate-post-titles' : '';

    ?>

        <section class="trending-articles-wrapper">

            <div class="container">
                
                <div class="row">
                    
                    <div class="col-md-12">

                        <div class="trending-articles-section">
                            
                            <?php if ( janey_setting('janey_press_enable_trending_posts_section_title', true) == true ) : ?>

                                <h2 class="trending-articles-section-title site-section-title"><?php echo esc_html(janey_setting('janey_press_trending_posts_section_title', __( 'Trending Posts', 'janey-press' ))) ;?></h2>
                            
                            <?php endif; ?>

                            <div class="trending-articles-grid <?php echo esc_attr($trending_articles_additional_css);?>">

                                <?php

                                    while ($trending_posts->have_posts()) : $trending_posts->the_post();
                                        
                                        global $post;

                                        $post_count = $trending_posts->current_post + 1;
                                        $image_layout = janey_setting('janey_press_trending_posts_images_layout', 'round');

                                ?>

                                        <div class="trending-article trending-article-<?php echo $post_count;?>">

                                            <div class="trending-article-inner <?php echo esc_attr($image_layout);?>_images">
                                                
                                                <div class="trending-article-image-link">

                                                    <a href="<?php echo esc_url(get_permalink()); ?>">

                                                        <?php

                                                            if ('' != get_the_post_thumbnail() ) : 

                                                                the_post_thumbnail('janey_press_trending_image');
                                                            
                                                            else :

                                                                $thumbnailIMG = get_stylesheet_directory_uri() . '/assets/images/placeholders/trending-posts/placeholder-140x140.jpg';
                                                                echo '<img src="' . esc_url($thumbnailIMG) . '" alt="' . esc_attr(get_the_title()) . '">';
                                                
                                                            endif;
                                                        ?>
                                                        
                                                    </a>
                                                    
                                                </div>

                                                <div class="trending-article-content">

                                                    <h3><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
                                                    <?php if ( janey_setting('janey_press_enable_trending_posts_author', true) == true ) : ?>
                                                        <p class="trending-article-author"><?php echo esc_html(janey_setting('janey_press_trending_posts_post_author_label', __( 'By', 'janey-press' ))) . '&nbsp;' . get_the_author_posts_link(); ?></p>
                                                    <?php endif; ?>
                                                    
                                                </div>

                                            </div>

                                        </div>

                                <?php

                                    endwhile;
                                    wp_reset_postdata();
                                    
                                ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

<?php

        endif;

    endif;

?>