<?php
/**
 * Getting Started Page.
 *
 * @package prime_fashion_magazine
 */


if( ! function_exists( 'prime_fashion_magazine_getting_started_menu' ) ) :
/**
 * Adding Getting Started Page in admin menu
 */
function prime_fashion_magazine_getting_started_menu(){	
	add_theme_page(
		__( 'Getting Started', 'prime-fashion-magazine' ),
		__( 'Getting Started', 'prime-fashion-magazine' ),
		'manage_options',
		'prime-fashion-magazine',
		'prime_fashion_magazine_getting_started_page'
	);
}
endif;
add_action( 'admin_menu', 'prime_fashion_magazine_getting_started_menu' );

if( ! function_exists( 'prime_fashion_magazine_getting_started_admin_scripts' ) ) :
/**
 * Load Getting Started styles in the admin
 */
function prime_fashion_magazine_getting_started_admin_scripts( $hook ){
	wp_enqueue_script( 'prime-fashion-magazine', get_template_directory_uri() . '/js/getting-started.js', array( 'jquery' ), PRIME_FASHION_MAGAZINE_THEME_VERSION, true );
	// Load styles only on our page
	if( 'appearance_page_prime-fashion-magazine' != $hook ) return;

    wp_enqueue_style( 'prime-fashion-magazine', get_template_directory_uri() . '/css/getting-started.css', false, PRIME_FASHION_MAGAZINE_THEME_VERSION );
    
}
endif;
add_action( 'admin_enqueue_scripts', 'prime_fashion_magazine_getting_started_admin_scripts' );

if( ! function_exists( 'prime_fashion_magazine_getting_started_page' ) ) :
/**
 * Callback function for admin page.
*/
function prime_fashion_magazine_getting_started_page(){ ?>
	<div class="wrap getting-started">
		<h2 class="notices"></h2>
		<div class="intro-wrap">
			<div class="intro">
				<h3><?php echo esc_html( 'Getting started with', 'prime-fashion-magazine' );?> <span><?php echo esc_html( PRIME_FASHION_MAGAZINE_THEME_NAME ); ?></span> <span class="theme-name">				
					<?php esc_html_e('V','prime-fashion-magazine'); ?><?php echo esc_html( PRIME_FASHION_MAGAZINE_THEME_VERSION ); ?></span></h3>
                <span><?php ?></span>
				<h4><?php 
				/* translators: %1$s: Theme name */
				printf( esc_html__( 'You will find everything you need to get started with %1$s below.', 'prime-fashion-magazine' ), PRIME_FASHION_MAGAZINE_THEME_NAME ); ?></h4>
			</div>
		</div>

		<div class="panels">
			<ul class="inline-list">
				<li class="current">
                    <a id="help" href="javascript:void(0);">
                        <?php esc_html_e( 'Get Started', 'prime-fashion-magazine' ); ?>
                    </a>
                </li>
				<li>
                    <a id="free-pro-panel" href="javascript:void(0);">
                        <?php esc_html_e( 'Free Vs Pro', 'prime-fashion-magazine' ); ?>
                    </a>
                </li>
			</ul>
			<div id="panel" class="panel">
				<?php require get_template_directory() . '/inc/getting-started/tabs/help-panel.php'; ?>
				<?php require get_template_directory() . '/inc/getting-started/tabs/free-vs-pro-panel.php'; ?>
				<?php require get_template_directory() . '/inc/getting-started/tabs/link-panel.php'; ?>
			</div>
		</div>
	</div>
	<?php
}
endif;