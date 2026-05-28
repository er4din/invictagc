<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package prime_fashion_magazine
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function prime_fashion_magazine_body_classes( $classes ) {
  global $prime_fashion_magazine_post;
  
    if( !is_page_template( 'template-home.php' ) ){
        $classes[] = 'inner';
        // Adds a class of group-blog to blogs with more than 1 published author.
    }

    if ( is_multi_author() ) {
        $classes[] = 'group-blog ';
    }

    // Adds a class of custom-background-image to sites with a custom background image.
    if ( get_background_image() ) {
        $classes[] = 'custom-background-image';
    }
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
        $classes[] = 'custom-background-color';
    }
    

    if( prime_fashion_magazine_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || 'product' === get_post_type() ) && ! is_active_sidebar( 'shop-sidebar' ) ){
        $classes[] = 'full-width';
    }    

    // Adds a class of hfeed to non-singular pages.
    if ( ! is_page() ) {
        $classes[] = 'hfeed ';
    }
  
    if( is_404() ||  is_search() ){
        $classes[] = 'full-width';
    }
  
    if( ! is_active_sidebar( 'right-sidebar' ) ) {
        $classes[] = 'full-width'; 
    }

    return $classes;
}
add_filter( 'body_class', 'prime_fashion_magazine_body_classes' );

 /**
 * 
 * @link http://www.altafweb.com/2011/12/remove-specific-tag-from-php-string.html
 */
function prime_fashion_magazine_strip_single( $tag, $string ){
    $string=preg_replace('/<'.$tag.'[^>]*>/i', '', $string);
    $string=preg_replace('/<\/'.$tag.'>/i', '', $string);
    return $string;
}

if ( ! function_exists( 'prime_fashion_magazine_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function prime_fashion_magazine_excerpt_more($more) {
  return is_admin() ? $more : ' &hellip; ';
}
endif;
add_filter( 'excerpt_more', 'prime_fashion_magazine_excerpt_more' );


if ( ! defined( 'PRIME_FASHION_MAGAZINE_FOOTER_URL' ) ) {
  define( 'PRIME_FASHION_MAGAZINE_FOOTER_URL', esc_url( 'https://www.themeignite.com/products/free-style-magazine-wordpress-theme', 'prime-fashion-magazine') );
}

if( ! function_exists( 'prime_fashion_magazine_footer_credit' ) ):
/**
 * Footer Credits
*/
function prime_fashion_magazine_footer_credit(){
    $prime_fashion_magazine_footer_setting = get_theme_mod( 'prime_fashion_magazine_footer_setting', true );
    if ( $prime_fashion_magazine_footer_setting ){ 
        $prime_fashion_magazine_copyright_text = get_theme_mod( 'prime_fashion_magazine_footer_copyright_text' );

        $prime_fashion_magazine_text  = '<div class="site-info"><div class="container"><span class="copyright">';
        if( $prime_fashion_magazine_copyright_text ){
            $prime_fashion_magazine_text .= wp_kses_post( $prime_fashion_magazine_copyright_text ); 
        }else{
            $prime_fashion_magazine_text .= esc_html__( '&copy; ', 'prime-fashion-magazine' ) . date_i18n( esc_html__( 'Y', 'prime-fashion-magazine' ) ); 
            $prime_fashion_magazine_text .= ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>'. esc_html__( '. All Rights Reserved.', 'prime-fashion-magazine' );
        }
        $prime_fashion_magazine_text .= '</span>';
        $prime_fashion_magazine_text .= '<span class="by"> <a href="' . esc_url( PRIME_FASHION_MAGAZINE_FOOTER_URL ) .'" rel="nofollow" target="_blank">' . PRIME_FASHION_MAGAZINE_THEME_NAME . '</a>' . esc_html__( ' By ', 'prime-fashion-magazine' ) . '' . '<a href="' . esc_url( 'https://themeignite.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Themeignite', 'prime-fashion-magazine' ) . '</a>';
        /* translators: %s: link to WordPress.org */
        $prime_fashion_magazine_text .= sprintf( esc_html__( ' Powered By %s', 'prime-fashion-magazine' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'prime-fashion-magazine' ) ) .'" target="_blank">WordPress</a>.' );
        if ( function_exists( 'the_privacy_policy_link' ) ) {
            $prime_fashion_magazine_text .= get_the_privacy_policy_link();
        }
        $prime_fashion_magazine_text .= '</span></div></div>';
        echo apply_filters( 'prime_fashion_magazine_footer_text', $prime_fashion_magazine_text );
    } 
}
add_action( 'prime_fashion_magazine_footer', 'prime_fashion_magazine_footer_credit' );
endif;
/**
 * Is Woocommerce activated
*/
if ( ! function_exists( 'prime_fashion_magazine_woocommerce_activated' ) ) {
  function prime_fashion_magazine_woocommerce_activated() {
    if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
  }
}

if( ! function_exists( 'prime_fashion_magazine_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function prime_fashion_magazine_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $prime_fashion_magazine_commenter = wp_get_current_commenter();
 
    // core functionality
    $req      = get_option( 'require_name_email' );
    $prime_fashion_magazine_aria_req = ( $req ? " aria-required='true'" : '' );
    $prime_fashion_magazine_required = ( $req ? " required" : '' );
    $prime_fashion_magazine_author   = ( $req ? __( 'Name*', 'prime-fashion-magazine' ) : __( 'Name', 'prime-fashion-magazine' ) );
    $prime_fashion_magazine_email    = ( $req ? __( 'Email*', 'prime-fashion-magazine' ) : __( 'Email', 'prime-fashion-magazine' ) );
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name', 'prime-fashion-magazine' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr( $prime_fashion_magazine_author ) . '" type="text" value="' . esc_attr( $prime_fashion_magazine_commenter['comment_author'] ) . '" size="30"' . $prime_fashion_magazine_aria_req . $prime_fashion_magazine_required . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email', 'prime-fashion-magazine' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr( $prime_fashion_magazine_email ) . '" type="text" value="' . esc_attr(  $prime_fashion_magazine_commenter['comment_author_email'] ) . '" size="30"' . $prime_fashion_magazine_aria_req . $prime_fashion_magazine_required. ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label class="screen-reader-text" for="url">' . esc_html__( 'Website', 'prime-fashion-magazine' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'prime-fashion-magazine' ) . '" type="text" value="' . esc_attr( $prime_fashion_magazine_commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'prime_fashion_magazine_change_comment_form_default_fields' );

if( ! function_exists( 'prime_fashion_magazine_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function prime_fashion_magazine_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'prime-fashion-magazine' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'prime-fashion-magazine' ) . '" cols="45" rows="8" aria-required="true" required></textarea></p>';
    
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'prime_fashion_magazine_change_comment_form_defaults' );

if( ! function_exists( 'prime_fashion_magazine_escape_text_tags' ) ) :
/**
 * Remove new line tags from string
 *
 * @param $text
 * @return string
 */
function prime_fashion_magazine_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
endif;

if( ! function_exists( 'wp_body_open' ) ) :
/**
 * Fire the wp_body_open action.
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
*/
function wp_body_open() {
    /**
     * Triggered after the opening <body> tag.
    */
    do_action( 'wp_body_open' );
}
endif;

if ( ! function_exists( 'prime_fashion_magazine_get_fallback_svg' ) ) :    
/**
 * Get Fallback SVG
*/
function prime_fashion_magazine_get_fallback_svg( $prime_fashion_magazine_post_thumbnail ) {
    if( ! $prime_fashion_magazine_post_thumbnail ){
        return;
    }
    
    $prime_fashion_magazine_image_size = prime_fashion_magazine_get_image_sizes( $prime_fashion_magazine_post_thumbnail );
     
    if( $prime_fashion_magazine_image_size ){ ?>
        <div class="svg-holder">
             <svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $prime_fashion_magazine_image_size['width'] ); ?> <?php echo esc_attr( $prime_fashion_magazine_image_size['height'] ); ?>" preserveAspectRatio="none">
                    <rect width="<?php echo esc_attr( $prime_fashion_magazine_image_size['width'] ); ?>" height="<?php echo esc_attr( $prime_fashion_magazine_image_size['height'] ); ?>" style="fill:#dedddd;"></rect>
            </svg>
        </div>
        <?php
    }
}
endif;

function prime_fashion_magazine_enqueue_google_fonts() {

    require get_template_directory() . '/inc/wptt-webfont-loader.php';

    wp_enqueue_style(
            'google-fonts-roboto',
        wptt_get_webfont_url( 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap' ),
        array(),
        '1.0'
    );

    wp_enqueue_style(
            'google-dm-sans',
        wptt_get_webfont_url( 'https://fonts.googleapis.com/css2?family=DM Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap' ),
        array(),
        '1.0'
    );



    
}
add_action( 'wp_enqueue_scripts', 'prime_fashion_magazine_enqueue_google_fonts' );


if( ! function_exists( 'prime_fashion_magazine_site_branding' ) ) :
/**
 * Site Branding
*/
function prime_fashion_magazine_site_branding(){
    $prime_fashion_magazine_logo_site_title = get_theme_mod( 'header_site_title', 1 );
    $prime_fashion_magazine_tagline = get_theme_mod( 'header_tagline', false );
    $prime_fashion_magazine_logo_width = get_theme_mod('logo_width', 100); // Retrieve the logo width setting

    ?>
    <div class="site-branding" style="max-width: <?php echo esc_attr(get_theme_mod('logo_width', '-1'))?>px;">
        <?php 
        // Check if custom logo is set and display it
        if (function_exists('has_custom_logo') && has_custom_logo()) {
            the_custom_logo();
        }
        if ($prime_fashion_magazine_logo_site_title):
             if (is_front_page()): ?>
            <h1 class="site-title" style="font-size: <?php echo esc_attr(get_theme_mod('prime_fashion_magazine_site_title_size', '30')); ?>px;">
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
          </h1>
            <?php else: ?>
                <p class="site-title" itemprop="name">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                </p>
            <?php endif; ?>
        <?php endif; 
    
        if ($prime_fashion_magazine_tagline) :
            $prime_fashion_magazine_description = get_bloginfo('description', 'display');
            if ($prime_fashion_magazine_description || is_customize_preview()) :
        ?>
                <p class="site-description" itemprop="description"><?php echo $prime_fashion_magazine_description; ?></p>
            <?php endif;
        endif;
        ?>
    </div>
    <?php
}
endif;
if( ! function_exists( 'prime_fashion_magazine_navigation' ) ) :
/**
 * Site Navigation
*/
function prime_fashion_magazine_navigation(){
    ?>
    <nav class="main-navigation" id="site-navigation"  role="navigation">
        <?php 
        wp_nav_menu( array( 
            'theme_location' => 'primary', 
            'menu_id' => 'primary-menu' 
        ) ); 
        ?>
    </nav>
    <?php
}
endif;


if( ! function_exists( 'prime_fashion_magazine_top_header' ) ) :
/**
 * Header Start
*/
function prime_fashion_magazine_top_header(){
    $prime_fashion_magazine_header_setting     = get_theme_mod( 'prime_fashion_magazine_header_setting', false );
    $prime_fashion_magazine_social_icon = get_theme_mod( 'prime_fashion_magazine_social_icon_setting', false);
    ?>
    <?php if ( $prime_fashion_magazine_header_setting ){?>
        <div class="top-header">
            <div class="container">
                <div class="row m-0">
                    <div class="col-xl-6 col-lg-8 col-md-8 align-self-center text-lg-start text-md-start time">
                            <span><i class="fa-solid fa-calendar-days"></i><?php echo date(get_option('date_format')); ?></span>
                        <?php esc_html_e( '|', 'prime-fashion-magazine' ); ?>
                            <?php if ( $prime_fashion_magazine_social_icon ){?>
                                <div class="social-links">
                                    <?php esc_html_e( 'Follow Us:', 'prime-fashion-magazine' ); ?>
                                    <?php 
                                    $prime_fashion_magazine_social_link1 = get_theme_mod( 'prime_fashion_magazine_social_link_1' );
                                    $prime_fashion_magazine_social_link2 = get_theme_mod( 'prime_fashion_magazine_social_link_2' );
                                    $prime_fashion_magazine_social_link3 = get_theme_mod( 'prime_fashion_magazine_social_link_3' );
                                    $prime_fashion_magazine_social_link4 = get_theme_mod( 'prime_fashion_magazine_social_link_4' );

                                    if ( ! empty( $prime_fashion_magazine_social_link1 ) ) {
                                        echo '<a class="social1" href="' . esc_url( $prime_fashion_magazine_social_link1 ) . '" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                                    }
                                    if ( ! empty( $prime_fashion_magazine_social_link2 ) ) {
                                        echo '<a class="social2" href="' . esc_url( $prime_fashion_magazine_social_link2 ) . '" target="_blank"><i class="fab fa-twitter"></i></a>';
                                    } 
                                    if ( ! empty( $prime_fashion_magazine_social_link3 ) ) {
                                        echo '<a class="social3" href="' . esc_url( $prime_fashion_magazine_social_link3 ) . '" target="_blank"><i class="fab fa-instagram"></i></a>';
                                    }
                                    if ( ! empty( $prime_fashion_magazine_social_link4 ) ) {
                                        echo '<a class="social4" href="' . esc_url( $prime_fashion_magazine_social_link4 ) . '" target="_blank"><i class="fab fa-youtube"></i></a>';
                                    }
                                    ?>
                                </div>
                            <?php } ?>   
                    </div>
                    <div class="col-xl-6 col-lg-4 col-md-4 col-12 text-md-center text-xl-end align-self-center px-md-0">
                        <div class="row header-links">
                            <div class="col-xl-8 col-lg-4 col-md-4 col-4 align-self-center">
                              <?php if (get_theme_mod('prime_fashion_magazine_addvertise_text') != '') { ?>
                                <p class="topbar-text mb-0">
                                  <a target="_blank" href="<?php echo esc_url(get_theme_mod('prime_fashion_magazine_addvertise_link')); ?>" class="text-decoration-none">
                                    <?php echo esc_html(get_theme_mod('prime_fashion_magazine_addvertise_text')); ?>
                                  </a>
                                </p>
                              <?php } ?>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-4 col-4 align-self-center">
                              <?php if (get_theme_mod('prime_fashion_magazine_about_text') != '') { ?>
                                <p class="topbar-text mb-0">
                                  <a target="_blank" href="<?php echo esc_url(get_theme_mod('prime_fashion_magazine_about_link')); ?>" class="text-decoration-none">
                                    <?php echo esc_html(get_theme_mod('prime_fashion_magazine_about_text')); ?>
                                  </a>
                                </p>
                              <?php } ?>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-4 col-4 align-self-center">
                              <?php if (get_theme_mod('prime_fashion_magazine_contact_text') != '') { ?>
                                <p class="topbar-text mb-0">
                                  <a target="_blank" href="<?php echo esc_url(get_theme_mod('prime_fashion_magazine_contact_link')); ?>" class="text-decoration-none">
                                    <?php echo esc_html(get_theme_mod('prime_fashion_magazine_contact_text')); ?>
                                  </a>
                                </p>
                              <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php
}
endif;
add_action( 'prime_fashion_magazine_top_header', 'prime_fashion_magazine_top_header', 20 );


if( ! function_exists( 'prime_fashion_magazine_header' ) ) :
/**
 * Header Start
*/
function prime_fashion_magazine_header(){
      $prime_fashion_magazine_header_image = get_header_image();
      $prime_fashion_magazine_sticky_header = get_theme_mod('prime_fashion_magazine_sticky_header');
    ?>
    <div id="page-site-header">
        <header id="masthead" style="background-image: url('<?php echo esc_url( $prime_fashion_magazine_header_image ); ?>');" class="site-header header-inner" role="banner">
            <div class="container">
                <div class="row m-0">
                    <div class="col-xl-3 col-lg-3 col-md-4 text-md-start align-self-center px-md-0">
                        <?php prime_fashion_magazine_site_branding(); ?>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-8 text-md-end align-self-center mid-header-img p-0">
                      <?php if (get_theme_mod('prime_fashion_magazine_middle_header_img') != '') { ?>
                        <img src="<?php echo esc_url(get_theme_mod('prime_fashion_magazine_middle_header_img')); ?>" alt="" />
                      <?php } ?>
                      <div class="row header-img-detail">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-12 discount-main-text align-self-center">
                          <?php if (get_theme_mod('prime_fashion_magazine_discount_text') != '') { ?>
                            <p class="discount-text text-uppercase mb-0"><?php echo esc_html(get_theme_mod('prime_fashion_magazine_discount_text')); ?></p>
                          <?php } ?>
                        </div>
                        <div class="col-xl-5 col-lg-5 col-md-6 col-12 img-main-text align-self-center">
                          <?php if (get_theme_mod('prime_fashion_magazine_img_text') != '') { ?>
                            <p class="img-text text-capitalize mb-0"><?php echo esc_html(get_theme_mod('prime_fashion_magazine_img_text')); ?></p>
                          <?php } ?>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-12 shop-header-btn align-self-center">
                          <?php if ( get_theme_mod('prime_fashion_magazine_header_btn_text') != "" && get_theme_mod('prime_fashion_magazine_header_btn_url') != "") { ?> 
                            <div class="shop-btn">
                              <a href="<?php echo esc_url(get_theme_mod ('prime_fashion_magazine_header_btn_url','')); ?>"><?php echo esc_html(get_theme_mod ('prime_fashion_magazine_header_btn_text','Download CV','prime-fashion-magazine')); ?></a>
                            </div>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
    <div class="nav-head" data-sticky="<?php echo $prime_fashion_magazine_sticky_header; ?>">
        <div class="container">
            <div class="row m-0">
                <div class="col-xl-7 col-lg-6 col-md-1 col-sm-12 col-12 align-self-center ps-0">
                    <?php prime_fashion_magazine_navigation(); ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 align-self-center">
                  <div class="search_box"><?php get_search_form(); ?></div>  
                </div>
                <div class="col-xl-2 col-lg-3 col-md-5 col-sm-12 col-12 align-self-center header-icons text-lg-end text-xl-end text-md-end">
                    <?php if ( get_theme_mod('prime_fashion_magazine_bookmark_link') != "") { ?>
                        <a title="<?php echo esc_attr('bookmark', 'prime-fashion-magazine'); ?>" target="_blank" href="<?php echo esc_url(get_theme_mod('prime_fashion_magazine_bookmark_link')); ?>"><i class="fa-solid fa-bookmark"></i></a> 
                      <?php } ?>
                      <?php if ( get_theme_mod('prime_fashion_magazine_subscribe_link') != "") { ?>
                        <a title="<?php echo esc_attr('Subscribe', 'prime-fashion-magazine'); ?>" target="_blank" href="<?php echo esc_url(get_theme_mod('prime_fashion_magazine_subscribe_link')); ?>"><i class="fa-solid fa-bell"></i></a> 
                      <?php } ?>
                      <?php if (get_theme_mod('prime_fashion_magazine_admin_img') != '') { ?>
                        <a target="_blank" href="<?php echo esc_url(get_theme_mod('prime_fashion_magazine_image_link')); ?>"><img src="<?php echo esc_url(get_theme_mod('prime_fashion_magazine_admin_img')); ?>" alt="" />
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
endif;
add_action( 'prime_fashion_magazine_header', 'prime_fashion_magazine_header', 20 );