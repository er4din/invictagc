<?php
/**
 * Prime Fashion Magazine functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package prime_fashion_magazine
 */

$prime_fashion_magazine_theme_data = wp_get_theme();
if( ! defined( 'PRIME_FASHION_MAGAZINE_THEME_VERSION' ) ) define ( 'PRIME_FASHION_MAGAZINE_THEME_VERSION', $prime_fashion_magazine_theme_data->get( 'Version' ) );
if( ! defined( 'PRIME_FASHION_MAGAZINE_THEME_NAME' ) ) define( 'PRIME_FASHION_MAGAZINE_THEME_NAME', $prime_fashion_magazine_theme_data->get( 'Name' ) );
if( ! defined( 'PRIME_FASHION_MAGAZINE_THEME_TITLE' ) ) define ( 'PRIME_FASHION_MAGAZINE_THEME_TITLE', 'Prime Fashion Magazine' );

if ( ! function_exists( 'prime_fashion_magazine_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function prime_fashion_magazine_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'prime-fashion-magazine' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
        'status',
        'audio', 
        'chat'
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'prime_fashion_magazine_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );


	/* Custom Logo */
    add_theme_support( 'custom-logo', array(    	
    	'header-text' => array( 'site-title', 'site-description' ),
    ) );

	add_theme_support( 'woocommerce' );

    load_theme_textdomain( 'prime-fashion-magazine', get_template_directory() . '/languages' );

    /**
	 * Custom template tags for this theme.
	 */
	require get_template_directory() . '/inc/template-tags.php';

	/**
	 * Custom functions that act independently of the theme templates.
	 */
	require get_template_directory() . '/inc/extra.php';

	/**
	 * Customizer additions.
	 */
	require get_template_directory() . '/inc/customizer.php';

	/**
	 * Social Links Widget
	 */
	require get_template_directory() . '/inc/widget-social-links.php';

	/**
	 * Info Theme
	 */
	require get_template_directory() . '/inc/sanitization.php';

	/**
	 * Info Theme
	 */
	require get_template_directory() . '/inc/info.php';

	/**
	 * Getting Started
	*/
	require get_template_directory() . '/inc/getting-started/getting-started.php';


    if ( ! defined( 'PRIME_FASHION_MAGAZINE_URL' ) ) {
        define( 'PRIME_FASHION_MAGAZINE_URL', esc_url( 'https://www.themeignite.com/products/fashion-wordpress-theme', 'prime-fashion-magazine') );
    }
    if ( ! defined( 'PRIME_FASHION_MAGAZINE_FREE_DOC_URL' ) ) {
        define( 'PRIME_FASHION_MAGAZINE_FREE_DOC_URL', esc_url( 'https://demo.themeignite.com/documentation/prime-fashion-magazine-free', 'prime-fashion-magazine') );
    }
    if ( ! defined( 'PRIME_FASHION_MAGAZINE_PRO_DOC_URL' ) ) {
        define( 'PRIME_FASHION_MAGAZINE_PRO_DOC_URL', esc_url( 'https://demo.themeignite.com/documentation/prime-fashion-magazine-pro/', 'prime-fashion-magazine') );
    }
    if ( ! defined( 'PRIME_FASHION_MAGAZINE_DEMO_URL' ) ) {
        define( 'PRIME_FASHION_MAGAZINE_DEMO_URL', esc_url( 'https://demo.themeignite.com/prime-fashion-magazine/', 'prime-fashion-magazine') );
    }
    if ( ! defined( 'PRIME_FASHION_MAGAZINE_REVIEW_URL' ) ) {
        define( 'PRIME_FASHION_MAGAZINE_REVIEW_URL', esc_url( 'https://wordpress.org/support/theme/prime-fashion-magazine/reviews/#new-post', 'prime-fashion-magazine') );
    }
    if ( ! defined( 'PRIME_FASHION_MAGAZINE_SUPPORT_URL' ) ) {
        define( 'PRIME_FASHION_MAGAZINE_SUPPORT_URL', esc_url( 'https://wordpress.org/support/theme/prime-fashion-magazine/', 'prime-fashion-magazine') );
    }
    if ( ! defined( 'PRIME_FASHION_MAGAZINE_BUNDLE_URL' ) ) {
        define( 'PRIME_FASHION_MAGAZINE_BUNDLE_URL', esc_url( 'https://www.themeignite.com/products/wp-theme-bundle', 'prime-fashion-magazine') );
    }



    if ( ! defined( 'PRIME_FASHION_MAGAZINE_THEME_TITLE' ) ) {
        return;
    }

    if ( wp_get_theme()->get( 'Name' ) === PRIME_FASHION_MAGAZINE_THEME_TITLE ) {

        require get_theme_file_path( '/theme-wizard/config.php' );

        // Hook to display the notice
        add_action( 'admin_notices', 'prime_fashion_magazine_dashboard_notice' );
    }

}
endif;
add_action( 'after_setup_theme', 'prime_fashion_magazine_setup' );

	/**
	 * Implement the Custom Header feature.
	 */
	require get_template_directory() . '/inc/custom-header.php';

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $prime_fashion_magazine_content_width
 */
function prime_fashion_magazine_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'prime_fashion_magazine_content_width', 780 );
}
add_action( 'after_setup_theme', 'prime_fashion_magazine_content_width', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function prime_fashion_magazine_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Option', 'prime-fashion-magazine' ),
		'id'            => 'right-sidebar',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Two', 'prime-fashion-magazine' ),
		'id'            => 'sidebar-2',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Three', 'prime-fashion-magazine' ),
		'id'            => 'sidebar-3',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer One', 'prime-fashion-magazine' ),
		'id'            => 'footer-one',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    
    register_sidebar( array(
		'name'          => esc_html__( 'Footer Two', 'prime-fashion-magazine' ),
		'id'            => 'footer-two',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    
    register_sidebar( array(
		'name'          => esc_html__( 'Footer Three', 'prime-fashion-magazine' ),
		'id'            => 'footer-three',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Four', 'prime-fashion-magazine' ),
		'id'            => 'footer-four',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'prime_fashion_magazine_widgets_init' );

// Change number of products per row to 3
add_filter('loop_shop_columns', 'prime_fashion_magazine_loop_columns');
if (!function_exists('prime_fashion_magazine_loop_columns')) {
    function prime_fashion_magazine_loop_columns() {
        $prime_fashion_magazine_colm = 3;
        return $prime_fashion_magazine_colm;
    }
}

if( ! function_exists( 'prime_fashion_magazine_scripts' ) ) :

/**
 * Enqueue scripts and styles.
 */
function prime_fashion_magazine_scripts() {

	// Use minified libraries if SCRIPT_DEBUG is false
    $prime_fashion_magazine_build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $prime_fashion_magazine_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_style( 'bootstrap-style', get_template_directory_uri().'/css/build/bootstrap.css' );
    wp_enqueue_style( 'owl.carousel', get_template_directory_uri() . '/css/build/owl.carousel.css' );

    wp_enqueue_style( 'fontawesome-all', esc_url(get_template_directory_uri()).'/css/all.min.css');

	wp_enqueue_style( 'prime-fashion-magazine-style', get_stylesheet_uri(), array(), PRIME_FASHION_MAGAZINE_THEME_VERSION );

	wp_style_add_data('prime-fashion-magazine-style', 'rtl', 'replace');

	require get_parent_theme_file_path( '/inc/css_custom.php' );
	wp_add_inline_style( 'prime-fashion-magazine-style',$prime_fashion_magazine_custom_css );

  	wp_enqueue_script( 'prime-fashion-magazine-all', get_template_directory_uri() . '/js' . $prime_fashion_magazine_build . '/all' . $prime_fashion_magazine_suffix . '.js', array( 'jquery' ), '6.1.1', true );
  	wp_enqueue_script( 'prime-fashion-magazine-v4-shims', get_template_directory_uri() . '/js' . $prime_fashion_magazine_build . '/v4-shims' . $prime_fashion_magazine_suffix . '.js', array( 'jquery' ), '6.1.1', true );
  	wp_enqueue_script( 'prime-fashion-magazine-modal-accessibility', get_template_directory_uri() . '/js' . $prime_fashion_magazine_build . '/modal-accessibility' . $prime_fashion_magazine_suffix . '.js', array( 'jquery' ), PRIME_FASHION_MAGAZINE_THEME_VERSION, true );
	wp_enqueue_script( 'owl.carousel', get_template_directory_uri() . '/js/build/owl.carousel.js', array('jquery'), '2.6.0', true );
	wp_enqueue_script( 'prime-fashion-magazine-js', get_template_directory_uri() . '/js/build/custom.js', array('jquery'), PRIME_FASHION_MAGAZINE_THEME_VERSION, true );
	// Wow script.
	wp_enqueue_script( 'wow-jquery', get_template_directory_uri() . '/js/build/wow.js', array('jquery'),'' ,true );
	// Animate CSS
	wp_enqueue_style( 'animate-style', get_template_directory_uri() . '/css/build/animate.css' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'prime_fashion_magazine_scripts' );

if( ! function_exists( 'prime_fashion_magazine_admin_scripts' ) ) :
/**
 * Addmin scripts
*/
function prime_fashion_magazine_admin_scripts() {
	wp_enqueue_style( 'prime-fashion-magazine-admin-style',get_template_directory_uri().'/inc/css/admin.css', PRIME_FASHION_MAGAZINE_THEME_VERSION, 'screen' );
}
endif;
add_action( 'admin_enqueue_scripts', 'prime_fashion_magazine_admin_scripts' );


function prime_fashion_magazine_customize_enque_js(){
	wp_enqueue_script( 'customizer', get_template_directory_uri() . '/inc/js/customizer.js', array('jquery'), '2.6.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'prime_fashion_magazine_customize_enque_js', 0 );


if( ! function_exists( 'prime_fashion_magazine_block_editor_styles' ) ) :
/**
 * Enqueue editor styles for Gutenberg
 */
function prime_fashion_magazine_block_editor_styles() {
	// Use minified libraries if SCRIPT_DEBUG is false
	$prime_fashion_magazine_build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
	$prime_fashion_magazine_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	
	// Block styles.
	wp_enqueue_style( 'prime-fashion-magazine-block-editor-style', get_template_directory_uri() . '/css' . $prime_fashion_magazine_build . '/editor-block' . $prime_fashion_magazine_suffix . '.css' );
}
endif;
add_action( 'enqueue_block_editor_assets', 'prime_fashion_magazine_block_editor_styles' );

/**
 * Remove header text setting and control from the Customizer.
 */
function prime_fashion_magazine_remove_customizer_setting($wp_customize) {
    // Replace 'your_setting_id' with the actual ID or name of the setting you want to remove
    $wp_customize->remove_control('display_header_text');
    $wp_customize->remove_setting('display_header_text');
}
add_action('customize_register', 'prime_fashion_magazine_remove_customizer_setting');


function prime_fashion_magazine_menu_customizer_css() { ?>
    <style type="text/css">
        .main-navigation ul li a {
            font-weight: <?php echo esc_html( get_theme_mod( 'prime_fashion_magazine_menu_font_weight', 'bold' ) ); ?>;
            text-transform: <?php echo esc_html( get_theme_mod( 'prime_fashion_magazine_menu_text_transform', 'capitalize' ) ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'prime_fashion_magazine_menu_customizer_css' );

function prime_fashion_magazine_custom_blog_banner_title() {
    if (is_404()) {
        echo '<h1 class="entry-title">'. esc_html__( 'Comments are closed.', 'prime-fashion-magazine' ).'</h1>';
    } elseif (is_search()) {
        echo '<h1 class="entry-title">'. esc_html__( 'Search Result For.', 'prime-fashion-magazine' ).' ' . get_search_query() . '</h1>';
    } elseif (is_home() && !is_front_page()) {
        echo '<h1 class="entry-title">'. esc_html__( 'Blogs', 'prime-fashion-magazine' ).'</h1>';
    } elseif (function_exists('is_shop') && is_shop()) {
        echo '<h1 class="entry-title">'. esc_html__( 'Shop', 'prime-fashion-magazine' ).'</h1>';
    } elseif (is_page_template('template-homepage.php')) {
    } elseif (is_page()) {
        the_title('<h1 class="entry-title">', '</h1>');
    } elseif (is_single()) {
        the_title('<h1 class="entry-title">', '</h1>');
    } elseif (is_archive()) {
        the_archive_title('<h1 class="entry-title">', '</h1>');
    } else {
        the_archive_title('<h1 class="entry-title">', '</h1>');
    }
	prime_fashion_magazine_the_breadcrumb();
}

function prime_fashion_magazine_the_breadcrumb() {
    echo '<div class="breadcrumb justify-content-center align-items-center">';

    if (!is_home()) {
        echo '<a class="home-main align-self-center" href="' . esc_url(home_url()) . '">';
        bloginfo('name');
        echo "</a> >> ";

        if (is_category() || is_single()) {
            the_category(' >> ');
            if (is_single()) {
                echo ' >> <span class="current-breadcrumb">' . esc_html(get_the_title()) . '</span>';
            }
        } elseif (is_page()) {
            echo '<span class="current-breadcrumb">' . esc_html(get_the_title()) . '</span>';
        }
    }

    echo '</div>';
}

function prime_fashion_magazine_enqueue_google_fontss() {
    $prime_fashion_magazine_heading_font_family = get_theme_mod('prime_fashion_magazine_heading_font_family', '');
    $prime_fashion_magazine_body_font_family = get_theme_mod('prime_fashion_magazine_body_font_family', '');

    // Google Fonts URL builder
    $google_fonts = array(
        'Arial'          => '',
        'Verdana'        => '',
        'Helvetica'      => '',
        'Times New Roman'=> '',
        'Georgia'        => '',
        'Courier New'    => '',
        'Trebuchet MS'   => '',
        'Tahoma'         => '',
        'Palatino'       => '',
        'Garamond'       => '',
        'Impact'         => '',
        'Comic Sans MS'  => '',
        'Lucida Sans'    => '',
        'Arial Black'    => '',
        'Gill Sans'      => '',
        'Segoe UI'       => '',
        'Open Sans'      => 'Open+Sans:wght@400;700',
        'Roboto'         => 'Roboto:wght@400;700',
        'Lato'           => 'Lato:wght@400;700',
        'Montserrat'     => 'Montserrat:wght@400;700',
        'Libre Baskerville' => 'Libre+Baskerville:wght@400;700'
    );

    $prime_fashion_magazine_google_fonts_url = '';

    if (!empty($google_fonts[$prime_fashion_magazine_heading_font_family]) || !empty($google_fonts[$prime_fashion_magazine_body_font_family])) {
        $fonts = array();

        if (!empty($google_fonts[$prime_fashion_magazine_heading_font_family])) {
            $fonts[] = $google_fonts[$prime_fashion_magazine_heading_font_family];
        }

        if (!empty($google_fonts[$prime_fashion_magazine_body_font_family])) {
            $fonts[] = $google_fonts[$prime_fashion_magazine_body_font_family];
        }

        // Build Google Fonts URL
        $prime_fashion_magazine_google_fonts_url = add_query_arg(
            'family',
            implode('|', $fonts),
            'https://fonts.googleapis.com/css2'
        );
    }

    if ($prime_fashion_magazine_google_fonts_url) {
        wp_enqueue_style('prime-fashion-magazine-google-fonts', $prime_fashion_magazine_google_fonts_url, false);
    }
}
add_action('wp_enqueue_scripts', 'prime_fashion_magazine_enqueue_google_fontss');

/**
 * AJAX handler to dismiss Whizzie notice
 */
if ( ! function_exists( 'prime_fashion_magazine_dismiss_whizzie_notice' ) ) {
    function prime_fashion_magazine_dismiss_whizzie_notice() {

        update_user_meta(
            get_current_user_id(),
            'prime_fashion_magazine_whizzie_dismissed',
            true
        );

        wp_die();
    }
}
add_action(
    'wp_ajax_prime_fashion_magazine_dismiss_whizzie_notice',
    'prime_fashion_magazine_dismiss_whizzie_notice'
);

/**
 * Check if Whizzie notice is dismissed
 */
if ( ! function_exists( 'prime_fashion_magazine_is_whizzie_dismissed' ) ) {
    function prime_fashion_magazine_is_whizzie_dismissed() {

        return (bool) get_user_meta(
            get_current_user_id(),
            'prime_fashion_magazine_whizzie_dismissed',
            true
        );

    }
}

/**
 * Reset Whizzie notice when theme is activated
 */
add_action( 'after_switch_theme', function () {

    $users = get_users( array(
        'fields' => 'ID',
    ) );

    foreach ( $users as $user_id ) {
        delete_user_meta( $user_id, 'prime_fashion_magazine_whizzie_dismissed' );
    }

});

/**
 * Display the admin notice unless dismissed.
 */
function prime_fashion_magazine_dashboard_notice() {
    // Check if the notice is dismissed
    $dismissed = get_user_meta( get_current_user_id(), 'prime_fashion_magazine_dismissable_notice', true );

    // Display the notice only if not dismissed
    if ( ! $dismissed ) {

        // Get the theme name dynamically
        $theme_name = wp_get_theme()->get( 'Name' );
        ?>
        <div class="updated notice notice-success is-dismissible notice-get-started-class" data-notice="get-start">
            <div class="notice-details">
                <div class="notice-content">
                    <h2>
                        <?php 
                        /* translators: %s: Theme name */
                        printf(
                            esc_html__( 'Thank you for installing %s.', 'prime-fashion-magazine' ),
                            '<strong>' . esc_html( $theme_name ) . '</strong>'
                        );
                        ?>
                    </h2>
                    <p><?php echo esc_html__( 'Your journey to a powerful and stylish website begins here. Let’s get everything set up in just a few clicks!', 'prime-fashion-magazine' ); ?></p>
                    <div class="notice-btns">
                        <a class="button button-primary getstart"
                           href="<?php echo esc_url( admin_url( 'themes.php?page=prime-fashion-magazine' ) ); ?>">
                           <?php esc_html_e( 'Getting Started', 'prime-fashion-magazine' ); ?>
                        </a>                      
                        <a class="button button-primary premium"
                           target="_blank" href="<?php echo esc_url(PRIME_FASHION_MAGAZINE_URL); ?>">
                           <?php esc_html_e( 'Go To Premium', 'prime-fashion-magazine' ); ?>
                        </a>
						<a class="button button-primary demo" target="_blank" href="<?php echo esc_url(PRIME_FASHION_MAGAZINE_DEMO_URL); ?>"><?php esc_html_e('View Demo', 'prime-fashion-magazine') ?></a>
                    </div>
                </div>
                <div class="notice-img">
                    <a href="<?php echo esc_url( PRIME_FASHION_MAGAZINE_BUNDLE_URL ); ?>" target="_blank"><img src="<?php echo esc_url( get_template_directory_uri() . '/images/notice.png' ); ?>"></a>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * AJAX handler to dismiss the notice.
 */
function prime_fashion_magazine_dismissable_notice() {
    // Set user meta to indicate the notice is dismissed
    update_user_meta(get_current_user_id(), 'prime_fashion_magazine_dismissable_notice', true);
    die();
}

// Hook for the AJAX action
add_action('wp_ajax_prime_fashion_magazine_dismissable_notice', 'prime_fashion_magazine_dismissable_notice');

/**
 * Clear dismissed notice state when switching themes.
 */
function prime_fashion_magazine_switch_theme() {
    // Clear the dismissed notice state when switching themes
    delete_user_meta(get_current_user_id(), 'prime_fashion_magazine_dismissable_notice');
}

// Hook for switching themes
add_action('after_switch_theme', 'prime_fashion_magazine_switch_theme');


function prime_fashion_magazine_apply_typography() {
    // Get the font family settings from the customizer
    $prime_fashion_magazine_heading_font_family = get_theme_mod('prime_fashion_magazine_heading_font_family');
    $prime_fashion_magazine_body_font_family = get_theme_mod('prime_fashion_magazine_body_font_family');

    // Only output CSS if one or both fonts are set
    if ($prime_fashion_magazine_body_font_family || $prime_fashion_magazine_heading_font_family) {
        ?>
        <style type="text/css">
            <?php if ($prime_fashion_magazine_body_font_family): ?>
            body, a, a:active, a:hover {
                font-family: <?php echo esc_html($prime_fashion_magazine_body_font_family); ?> !important;
            }
            <?php endif; ?>

            <?php if ($prime_fashion_magazine_heading_font_family): ?>
            h1, h2, h3, h4, h5, h6 {
                font-family: <?php echo esc_html($prime_fashion_magazine_heading_font_family); ?> !important;
            }
            <?php endif; ?>
        </style>
        <?php
    }
}
add_action('wp_head', 'prime_fashion_magazine_apply_typography');