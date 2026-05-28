<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.2, user-scalable=yes" />

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php

if ( function_exists('wp_body_open') ) {
	wp_body_open();
}

?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'janey-press' ); ?></a>

<?php get_template_part('template-parts/scroll','sidebar'); ?>

<div id="wrapper">

	<header id="header-wrapper" >

        <div id="header">

            <div class="head-flex">

                <div class="head-flex-logo">

                    <div id="logo">

                        <?php

                            if ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) {

                                the_custom_logo();

                            } else {

                                echo '<a href="' . esc_url(home_url('/')) . '" title="' . esc_attr(get_bloginfo('name')) . '">';

                                    echo esc_html(get_bloginfo('name'));

                                    if ( get_theme_mod('janey_hide_tagline', true) == false) :
                                        
                                        echo '<span>'. esc_html(get_bloginfo('description')) . '</span>';

                                    endif;

                                echo '</a>';

                            }

                        ?>

                    </div>

                </div>

                <div id="primary-menu-wrapper" class="head-flex-menu" >

                    <button class="menu-toggle" aria-controls="mainmenu" aria-expanded="false" type="button">
                        <span aria-hidden="true"><?php esc_html_e( 'Menu', 'janey-press' ); ?></span>
                        <span class="dashicons" aria-hidden="true"></span>
                    </button>

                    <nav class="header-menu">

                        <?php
                        
                            wp_nav_menu( array(
                            
                                'theme_location' => 'main-menu',
                                'container' => 'false'
                            
                            ));
                        
                        ?>
                    
                    </nav>

                </div>

                <div class="head-flex-hamburger-menu">

                    <a class="open-modal-sidebar" href="#modal-sidebar">
                        <i class="fa fa-bars"></i>
                    </a>

                </div>

            </div>

        </div>

	</header>