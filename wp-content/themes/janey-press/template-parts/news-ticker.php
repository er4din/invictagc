<?php

    if ( janey_setting('janey_press_enable_news_ticker', true) == true ) :

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => intval(janey_setting('janey_press_news_ticker_limit', 5)),
            'orderby' => esc_attr(janey_setting('janey_press_news_ticker_order', 'date')),
            'order' => esc_attr(janey_setting('janey_press_news_ticker_sort_order', 'desc')),
        );

        if ( is_numeric(janey_setting('janey_press_news_ticker_category')) ) :
            $args['cat'] = janey_setting('janey_press_news_ticker_category');
        endif;

        $recent_posts = new WP_Query($args);

        if ($recent_posts->have_posts()) : 

    ?>

        <div class="news-ticker container">

            <div class="row">

                <div class="col-md-12">

                    <div class="news-ticker-carousel">

                        <div class="news-ticker-title"><?php echo esc_html(janey_setting('janey_press_news_ticker_title', __( 'HOT', 'janey-press' )));?></div>

                        <div class="news-ticker-carousel-inner">

                            <div class="news-ticker-marquee-init">

                                <?php

                                    while ($recent_posts->have_posts()) : $recent_posts->the_post();

                                        echo '<a href="' . esc_url(get_permalink()).'">';

                                        echo '<span class="circle-marq">';

                                        if ('' != get_the_post_thumbnail() ) : 

                                            the_post_thumbnail(array(40, 40));
                                        
                                        else :

                                            $thumbnailIMG = get_stylesheet_directory_uri() . '/assets/images/placeholders/news-ticker/placeholder-40x40.jpg';
                                            echo '<img src="' . esc_url($thumbnailIMG) . '" alt="' . esc_attr(get_the_title()) . '">';

                                        endif;

                                        echo '</span>';

                                        echo esc_html(get_the_title());

                                        echo '</a>';

                                    endwhile;
                                    wp_reset_postdata();

                                ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php

        endif;

    endif;

?>
