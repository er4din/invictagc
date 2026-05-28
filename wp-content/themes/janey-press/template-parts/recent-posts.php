<?php

    if ( janey_setting('janey_press_enable_recent_posts', true) == true ) :

        $recent_posts_enabled = janey_setting('janey_press_enable_recent_posts_only_first_page_pagination', true);

        if (
            (
                $recent_posts_enabled == true && 
                get_query_var('paged') <= 1
            ) ||
            $recent_posts_enabled == false
        ) :

    ?>

        <section class="recent-posts-section">

            <div class="container">
                
                <div class="row">
                    
                    <div class="col-md-12">

                        <div class="recent-posts-grid">

                            <div class="recent-posts-main-column">
                                
                                <?php

                                    $section_title = janey_setting('janey_press_main_column_label', esc_html__( 'Recent', 'janey-press' ));
                                    $slider_transition = janey_setting('janey_press_main_column_slider_transition', 'fade');

                                    $col_cx = array(
                                        'post_type' => 'post',
                                        'posts_per_page' => intval(janey_setting('janey_press_main_column_posts_limit', 5)),
                                        'ignore_sticky_posts' => true,
                                        'orderby' => janey_setting('janey_press_main_column_posts_orderby', 'date'),
                                        'order' => janey_setting('janey_press_main_column_posts_sort_order', 'desc'),
                                    );

                                    if ( is_numeric(janey_setting('janey_press_main_column_posts_category')) ) :
                                        $col_cx['cat'] = janey_setting('janey_press_main_column_posts_category');
                                    endif;
                            
                                    janey_press_horizontal_recent_posts_slideshow($col_cx, $section_title, $slider_transition);
                                
                                ?>

                            </div>

                            <div class="recent-posts-secondary-column">

                                <?php

                                    $section_title = janey_setting('janey_press_secondary_column_label', esc_html__( 'Oldest post', 'janey-press' ));

                                    $col_sx = array(
                                        'post_type' => 'post',
                                        'posts_per_page' => intval(janey_setting('janey_press_secondary_column_posts_limit', 8)),
                                        'ignore_sticky_posts' => true,
                                        'orderby' => janey_setting('janey_press_secondary_column_posts_orderby', 'date'),
                                        'order' => janey_setting('janey_press_secondary_column_posts_sort_order', 'asc'),
                                    );

                                    if ( is_numeric(janey_setting('janey_press_secondary_column_posts_category')) ) :
                                        $col_sx['cat'] = janey_setting('janey_press_secondary_column_posts_category');
                                    endif;
                                
                                    janey_press_vertical_recent_posts_slideshow($col_sx, $section_title);

                                ?>

                            </div>

                            <div class="recent-posts-side-column">

                                <?php

                                    $section_title = janey_setting('janey_press_side_column_label', esc_html__( 'Popular', 'janey-press' ));

                                    $col_dx = array(
                                        'post_type' => 'post',
                                        'posts_per_page' => intval(janey_setting('janey_press_side_column_posts_limit', 8)),
                                        'ignore_sticky_posts' => true,
                                        'orderby' => janey_setting('janey_press_side_column_posts_orderby', 'comment_count'),
                                        'order' => janey_setting('janey_press_side_column_posts_sort_order', 'desc'),
                                    );

                                    if ( is_numeric(janey_setting('janey_press_side_column_posts_category')) ) :
                                        $col_dx['cat'] = janey_setting('janey_press_side_column_posts_category');
                                    endif;
                                
                                    janey_press_vertical_list_recent_posts_slideshow($col_dx, $section_title);

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
