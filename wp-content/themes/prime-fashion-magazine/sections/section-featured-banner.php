<?php
/**
 * Banner Section
 * 
 * @package prime_fashion_magazine
 */

$prime_fashion_magazine_slider = get_theme_mod('prime_fashion_magazine_slider_setting', false);

$prime_fashion_magazine_args_slider = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'category_name'  => get_theme_mod('prime_fashion_magazine_blog_slide_category'),
    'posts_per_page' => 3,
);

$prime_fashion_magazine_args_featured = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'category_name'  => get_theme_mod('prime_fashion_magazine_blog_slide_category_1'),
    'posts_per_page' => 10,
);
$i = 1;
if ($prime_fashion_magazine_slider) : ?>
    <div class="banner py-4 wow bounceInDown" data-wow-duration="2s">
        <div class="container sliderbox">
            <div class="row">
                <!-- Slider Section -->
                <div class="col-xl-7 col-lg-7 col-md-12 col-12">
                    <div class="owl-carousel">
                        <?php
                        $prime_fashion_magazine_slider_query = new WP_Query($prime_fashion_magazine_args_slider);
                        if ($prime_fashion_magazine_slider_query->have_posts()) :
                            while ($prime_fashion_magazine_slider_query->have_posts()) :
                                $prime_fashion_magazine_slider_query->the_post();
                                $prime_fashion_magazine_post_date = get_the_date();
                                ?>
                                <div class="banner_inner_box">
                                    <?php if (has_post_thumbnail()) : 
                                        the_post_thumbnail();
                                    else : ?>
                                        <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/slider.png'); ?>" alt="Default Banner">
                                    <?php endif; ?>
                                    <div class="banner_box">
                                        <?php 
                                        $prime_fashion_magazine_selected_category_slug = get_theme_mod('prime_fashion_magazine_blog_slide_category');
                                        $prime_fashion_magazine_post_categories = get_the_category();
                                        if (!empty($prime_fashion_magazine_post_categories)) {
                                            foreach ($prime_fashion_magazine_post_categories as $category) {
                                                if ($category->slug === $prime_fashion_magazine_selected_category_slug) {
                                                    echo '<p class="post-category">' . esc_html($category->name) . '</p>';
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                        <h3 class="my-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <div class="post-meta">
                                            <div class="post-meta-author"> 
                                                <span class="me-1"><?php esc_html_e('By', 'prime-fashion-magazine'); ?></span>
                                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html( get_the_author() );?></a>
                                            </div>
                                            <div class="post-meta-date">
                                                <i class="fa-regular fa-calendar-days"></i><?php echo esc_html($prime_fashion_magazine_post_date); ?>
                                            </div>
                                            <div class="post-meta-comments"> 
                                                <i class="fa-regular fa-message"></i><?php comments_number(); ?>
                                            </div>
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
                <!-- Featured Section -->
                <div class="col-xl-5 col-lg-5 col-md-12 col-12 feature-main-box">
                    <?php
                    $prime_fashion_magazine_featured_query = new WP_Query($prime_fashion_magazine_args_featured);
                    if ($prime_fashion_magazine_featured_query->have_posts()) :
                        while ($prime_fashion_magazine_featured_query->have_posts()) :
                            $prime_fashion_magazine_featured_query->the_post(); ?>
                            <div class="feature-box wow zoomInUp" data-wow-duration="2s">
                                <div class="row">
                                    <div class="col-xl-5 col-lg-5 col-md-5 align-self-center">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="image-blogs">
                                                <?php the_post_thumbnail(); ?>
                                            </div>
                                        <?php else : ?>
                                            <div class="image-blogs">
                                                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/slide-ban.png'); ?>" alt="Default Image">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-xl-7 col-lg-5 col-md-7 align-self-center">
                                        <div class="blog-content">
                                            <?php 
                                            $prime_fashion_magazine_latest_category = get_the_category();
                                            if (!empty($prime_fashion_magazine_latest_category)) {
                                                echo '<a class="post-categories catebox-'.$i.'" href="' . esc_url(get_category_link($prime_fashion_magazine_latest_category[0]->term_id)) . '">' . esc_html($prime_fashion_magazine_latest_category[0]->name) . '</a>';
                                            } ?>
                                            <div class="post-title">
                                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            </div>
                                            <div class="blog-date">
                                                <i class="fa-regular fa-calendar-days"></i><?php echo esc_html(get_the_date()); ?>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        <?php
                        $i++;endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
