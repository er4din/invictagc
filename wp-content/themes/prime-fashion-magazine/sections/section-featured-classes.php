<?php 
/**
 * Template part for displaying Featured Classes Section
 *
 * @package Prime Fashion Magazine
 */

$prime_fashion_magazine_classes = get_theme_mod( 'prime_fashion_magazine_classes_setting',true );
$prime_fashion_magazine_section_title = get_theme_mod( 'prime_fashion_magazine_section_title' );
$prime_fashion_magazine_section_text = get_theme_mod( 'prime_fashion_magazine_section_text' );
?>
<?php if ( $prime_fashion_magazine_classes ){?>
<div class="our-classes">
    <div class="container">
        <?php if ( $prime_fashion_magazine_section_title ){?>
            <h3 class="mb-0"><?php echo esc_html( $prime_fashion_magazine_section_title );?></h3>
        <?php } ?>
        <?php if ( $prime_fashion_magazine_section_text ){?>
            <p class="main-text"><?php echo esc_html( $prime_fashion_magazine_section_text );?></p>
        <?php } ?>
        <div class="owl-carousel">
                <?php 
                    $prime_fashion_magazine_catergory_name = get_theme_mod('prime_fashion_magazine_trending_post_slider_args_');
                    $args = array(
                        'post_type'           => 'post',
                        'category_name'       => $prime_fashion_magazine_catergory_name,
                        'orderby'             => 'post__in',
                        'ignore_sticky_posts' => true,
                    );?>
                    <?php
                    $loop = new WP_Query($args);
                    if ( $loop->have_posts() ) :
                        $i=1;
                        while ($loop->have_posts()) : $loop->the_post(); ?>
                            <div class="pro-box align-self-center px-0">
                                <div class="box">
                                    <?php
                                        if ( has_post_thumbnail() ) : ?>
                                            <div class="image-blog wow zoomInUp delay-3000" data-wow-duration="2s">
                                          <?php the_post_thumbnail();?>
                                          <?php 
                                            $prime_fashion_magazine_selected_category_slug = get_theme_mod('prime_fashion_magazine_trending_post_slider_args_');
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
                                          </div>
                                        <?php else:
                                          ?>
                                          <div class="image-blog">
                                            <img src="<?php echo get_stylesheet_directory_uri() . '/images/default-header.png'; ?>">
                                            <?php 
                                            $prime_fashion_magazine_selected_category_slug = get_theme_mod('prime_fashion_magazine_trending_post_slider_args_');
                                            $prime_fashion_magazine_post_categories = get_the_category();
                                            $prime_fashion_magazine_post_date = get_the_date();
                                            if (!empty($prime_fashion_magazine_post_categories)) {
                                                foreach ($prime_fashion_magazine_post_categories as $category) {
                                                    if ($category->slug === $prime_fashion_magazine_selected_category_slug) {
                                                        echo '<p class="post-category">' . esc_html($category->name) . '</p>';
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                          </div>
                                          <?php
                                        endif;
                                      ?>
                                    <div class="box-content py-3">
                                        <div class="post-meta">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-5 col-12">
                                                   <div class="post-meta-author"> 
                                                        <span class="me-2">
                                                            <?php echo get_avatar(get_the_author_meta('ID'));?>
                                                        </span>
                                                        <?php esc_html_e('By', 'prime-fashion-magazine'); ?><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                           <?php echo esc_html( get_the_author() );?>
                                                        </a>
                                                    </div> 
                                                </div>
                                                <div class="col-lg-9 col-md-7 col-12 blog-detail">
                                                    <div class="post-like">
                                                       <?php echo do_shortcode('[posts_like_dislike]');?> 
                                                    </div>
                                                    <div class="post-meta-comments"> 
                                                        <span><i class="fa-regular fa-comments me-2"></i><?php echo get_comments_number(); ?></span>
                                                    </div>
                                                    <div class="bookmark">
                                                       <i class="fas fa-bookmark"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="title mb-2 mt-2">
                                            <a href="<?php the_permalink();?>"><?php the_title();?></a>
                                        </h4>
                                        <div class="blog-date">
                                            <i class="fa-regular fa-calendar-days"></i><?php echo esc_html(get_the_date()); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; endwhile;
                    endif;
                ?>
            </div>
    </div>
</div>
<?php } ?>