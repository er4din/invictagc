<?php

/**
*
* @since News Host
*
*/
if (!function_exists('news_host_frontpage_editor_post_section')):

    function news_host_frontpage_editor_post_section() {

        if(is_front_page() || is_home()) { 
            $number_of_posts = '2';
            $newsup_editor_news_category = newsup_get_option('select_editor_news_category');
            $newsup_all_posts_main = newsup_get_posts($number_of_posts, $newsup_editor_news_category);
            ?>

            <div class="col-md-3 editor-posts">               
                <?php if ($newsup_all_posts_main->have_posts()) :
                while ($newsup_all_posts_main->have_posts()) : $newsup_all_posts_main->the_post();
                global $post;
                $newsup_url = newsup_get_freatured_image_url($post->ID, 'newsup-slider-full'); ?>
                    <div class="mg-blog-post-box">
                        <div class="mg-post-thumb back-img sm" style="background-image: url('<?php echo esc_url($newsup_url); ?>');">
                            <a class="link-div" href="<?php the_permalink(); ?>"></a>
                            <?php newsup_post_categories(); ?>
                        </div>
                        <article class="small">
                            <h4 class="entry-title title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <div class="mg-blog-meta"> 
                                <?php newsup_post_meta(); ?>
                            </div>                
                        </article>
                    </div>
                <?php
                    endwhile;
                endif;
                wp_reset_postdata();
                ?>
            </div>
        <?php }
    }

endif;

add_action('news_host_action_front_page_editor_section', 'news_host_frontpage_editor_post_section', 30);

/**
 *
 * @since News Host
 *
 */
//Front Page Banner
if (!function_exists('news_host_front_page_banner_section')) :

    function news_host_front_page_banner_section() {
        if (is_front_page() || is_home()) {
            $newsup_enable_main_slider = newsup_get_option('show_main_news_section');
            $select_vertical_slider_news_category = newsup_get_option('select_vertical_slider_news_category');
            $vertical_slider_number_of_slides = newsup_get_option('vertical_slider_number_of_slides');
            $all_posts_vertical = newsup_get_posts($vertical_slider_number_of_slides, $select_vertical_slider_news_category);
            if ($newsup_enable_main_slider):  

            $main_banner_section_background_image = newsup_get_option('main_banner_section_background_image');
            $main_banner_section_background_image_url = wp_get_attachment_image_src($main_banner_section_background_image, 'full');
            if(!empty($main_banner_section_background_image)){ ?>
                <section class="mg-fea-area over" style="background-image:url('<?php echo esc_url($main_banner_section_background_image_url[0]); ?>');">
            <?php }else{ ?>
                <section class="mg-fea-area">
            <?php  } ?>
                <div class="overlay">
                    <div class="container-fluid">
                        <div class="row">                            
                            <div class="col-md-6">
                                <div id="homemain"class="homemain owl-carousel mr-bot60"> 
                                    <?php newsup_get_block('list', 'banner'); ?>
                                </div>
                            </div> 
                            <?php do_action('news_host_action_front_page_editor_section');?>
                            <?php do_action('news_host_action_banner_tabbed_posts');?>
                            
                            
                        </div>
                    </div>
                </div>
            </section>
            <!--==/ Home Slider ==-->
            <?php endif; ?>
            <!-- end slider-section -->
        <?php }
    }
endif;
add_action('news_host_action_front_page_main_section_1', 'news_host_front_page_banner_section', 40);



//Banner Tabed Section
if (!function_exists('news_host_banner_tabbed_posts')):
    /**
     *
     * @since News Host 1.0.0
     *
     */
    function news_host_banner_tabbed_posts() {
        
            $show_excerpt = 'false';
            $excerpt_length = '20';
            $number_of_posts = '5';

            $enable_categorised_tab = 'true';
            $latest_title = newsup_get_option('latest_tab_title');
            $popular_title = newsup_get_option('popular_tab_title');
            $categorised_title = newsup_get_option('trending_tab_title');
            $category = newsup_get_option('select_trending_tab_news_category');
            $tab_id = 'tan-main-banner-latest-trending-popular'
            ?>
            <div class="col-md-3 top-right-area">
                    <div id="exTab2" >
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#<?php echo esc_attr($tab_id); ?>-recent"
                               aria-controls="<?php esc_attr_e('Recent', 'news-host'); ?>">
                               <i class="fa fa-clock-o"></i><?php echo esc_html($latest_title); ?>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#<?php echo esc_attr($tab_id); ?>-popular"
                               aria-controls="<?php esc_attr_e('Popular', 'news-host'); ?>">
                                <i class="fa fa-fire"></i> <?php echo esc_html($popular_title); ?>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#<?php echo esc_attr($tab_id); ?>-categorised"
                               aria-controls="<?php esc_attr_e('Categorised', 'news-host'); ?>">
                                <i class="fa fa-bolt"></i> <?php echo esc_html($categorised_title); ?>
                            </a>
                        </li>

                    </ul>
                <div class="tab-content">
                    <div id="<?php echo esc_attr($tab_id); ?>-recent" role="tabpanel" class="tab-pane fade active show">
                        <?php
                        newsup_render_posts('recent', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>


                    <div id="<?php echo esc_attr($tab_id); ?>-popular" role="tabpanel" class="tab-pane fade">
                        <?php
                        newsup_render_posts('popular', $show_excerpt, $excerpt_length, $number_of_posts);
                        ?>
                    </div>

                    <?php if ($enable_categorised_tab == 'true'): ?>
                        <div id="<?php echo esc_attr($tab_id); ?>-categorised" role="tabpanel" class="tab-pane fade">
                            <?php
                            newsup_render_posts('categorised', $show_excerpt, $excerpt_length, $number_of_posts, $category);
                            ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
                    </div>
        <?php

    }
endif;

add_action('news_host_action_banner_tabbed_posts', 'news_host_banner_tabbed_posts', 10);