<?php
/**
 * Help Panel.
 *
 * @package prime_fashion_magazine
 */

// Check demo import status
$prime_fashion_magazine_import_done = get_option( 'prime_fashion_magazine_demo_import_done' );

// Button text
$prime_fashion_magazine_button_text = $prime_fashion_magazine_import_done
    ? __( 'View Site', 'prime-fashion-magazine' )
    : __( 'Start Demo Import', 'prime-fashion-magazine' );

// Button link
if ( $prime_fashion_magazine_import_done ) {
    $prime_fashion_magazine_button_link = home_url( '/' );
} else {
    $prime_fashion_magazine_button_link = (
        wp_get_theme()->get( 'Name' ) === PRIME_FASHION_MAGAZINE_THEME_TITLE
    )
        ? admin_url( 'themes.php?page=primefashionmagazine-wizard' )
        : admin_url( 'themes.php?page=popularblogger-wizard' );
}
?>

<div id="help-panel" class="panel-left visible">
    <div class="panel-aside active">
        <div class="demo-content">
            <div class="demo-info">
                <h4><?php esc_html_e( 'DEMO CONTENT IMPORTER', 'prime-fashion-magazine' ); ?></h4>

                <p>
                    <?php esc_html_e(
                        'The Demo Content Importer helps you quickly set up your website to look exactly like the theme demo. Instead of building pages from scratch, you can import pre-designed layouts, pages, menus, images, and basic settings in just a few clicks.',
                        'prime-fashion-magazine'
                    ); ?>
                </p>

                <a class="button button-primary first-color"
                   style="text-transform: capitalize"
                   href="<?php echo esc_url( $prime_fashion_magazine_button_link ); ?>"
                   title="<?php echo esc_attr( $prime_fashion_magazine_button_text ); ?>"
                   <?php echo $prime_fashion_magazine_import_done ? 'target="_blank"' : ''; ?>>
                    <?php echo esc_html( $prime_fashion_magazine_button_text ); ?>
                </a>
            </div>

            <div class="demo-img">
                <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/screenshot.png' ); ?>"
                     alt="<?php esc_attr_e( 'Theme Screenshot', 'prime-fashion-magazine' ); ?>" />
            </div>
        </div>
    </div>

    <div class="panel-aside" >
        <h4><?php esc_html_e( 'USEFUL LINKS', 'prime-fashion-magazine' ); ?></h4>
        <p><?php esc_html_e( 'Find everything you need to set up, customize, and manage your website with ease. These helpful resources are designed to guide you at every step, from installation to advanced customization.', 'prime-fashion-magazine' ); ?></p>
        <div class="useful-links">
            <a class="button button-primary second-color" href="<?php echo esc_url( PRIME_FASHION_MAGAZINE_DEMO_URL ); ?>" title="<?php esc_attr_e( 'Live Demo', 'prime-fashion-magazine' ); ?>" target="_blank">
                <?php esc_html_e( 'Live Demo', 'prime-fashion-magazine' ); ?>
            </a>
            <a class="button button-primary first-color" href="<?php echo esc_url( PRIME_FASHION_MAGAZINE_FREE_DOC_URL ); ?>" title="<?php esc_attr_e( 'Documentation', 'prime-fashion-magazine' ); ?>" target="_blank">
                <?php esc_html_e( 'Documentation', 'prime-fashion-magazine' ); ?>
            </a>
            <a class="button button-primary second-color" href="<?php echo esc_url( PRIME_FASHION_MAGAZINE_URL ); ?>" title="<?php esc_attr_e( 'Get Premium', 'prime-fashion-magazine' ); ?>" target="_blank">
                <?php esc_html_e( 'Get Premium', 'prime-fashion-magazine' ); ?>
            </a>
            <a class="button button-primary first-color" href="<?php echo esc_url( PRIME_FASHION_MAGAZINE_BUNDLE_URL ); ?>" title="<?php esc_attr_e( 'Get Bundle - 60+ Themes', 'prime-fashion-magazine' ); ?>" target="_blank">
                <?php esc_html_e( 'Get Bundle - 60+ Themes', 'prime-fashion-magazine' ); ?>
            </a>
        </div>
    </div>

    <div class="panel-aside" >
        <h4><?php esc_html_e( 'REVIEW', 'prime-fashion-magazine' ); ?></h4>
        <p><?php esc_html_e( 'If you have a moment, please consider leaving a rating and short review. It only takes a minute, and your support means a lot to us.', 'prime-fashion-magazine' ); ?></p>
        <a class="button button-primary first-color" href="<?php echo esc_url( PRIME_FASHION_MAGAZINE_REVIEW_URL ); ?>" title="<?php esc_attr_e( 'Visit the Review', 'prime-fashion-magazine' ); ?>" target="_blank">
            <?php esc_html_e( 'Leave a Review', 'prime-fashion-magazine' ); ?>
        </a>
    </div>
    
    <div class="panel-aside">
        <h4><?php esc_html_e( 'CONTACT SUPPORT', 'prime-fashion-magazine' ); ?></h4>
        <p>
            <?php esc_html_e( 'Thank you for choosing Prime Fashion Magazine! We appreciate your interest in our theme and are here to assist you with any support you may need.', 'prime-fashion-magazine' ); ?></p>
        <a class="button button-primary first-color" href="<?php echo esc_url( PRIME_FASHION_MAGAZINE_SUPPORT_URL ); ?>" title="<?php esc_attr_e( 'Visit the Support', 'prime-fashion-magazine' ); ?>" target="_blank">
            <?php esc_html_e( 'Contact Support', 'prime-fashion-magazine' ); ?>
        </a>
    </div>
</div>