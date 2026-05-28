<?php

/**
 * Option Panel
 *
 * @package News Host
 */


function news_host_customize_register($wp_customize) {

    $newsup_default = news_host_get_default_theme_options();

    $wp_customize->remove_control('newsup_select_slider_setting');

    $wp_customize->get_setting('newsup_header_overlay_color')->default = 'rgba(10, 0, 0, 0.25)'; 

    //section title
    $wp_customize->add_setting('editior_post_section',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        new newsup_Section_Title(
            $wp_customize,
            'editior_post_section',
            array(
                'label'             => esc_html__( 'Editor Post Section', 'news-host' ),
                'section'           => 'frontpage_main_banner_section_settings',
                'priority'          => 40,
                'active_callback' => 'newsup_main_banner_section_status'
            )
        )
    );

    // Setting - drop down category for slider.
    $wp_customize->add_setting('select_editor_news_category',
        array(
            'default' => $newsup_default['select_editor_news_category'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        )
    );
    $wp_customize->add_control(new Newsup_Dropdown_Taxonomies_Control($wp_customize, 'select_editor_news_category',
        array(
            'label' => esc_html__('Category', 'news-host'),
            'description' => esc_html__('Select category for Editor 2 Post', 'news-host'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'dropdown-taxonomies',
            'taxonomy' => 'category',
            'priority' => 60,
            'active_callback' => 'newsup_main_banner_section_status'
        )
    ));
}
add_action('customize_register', 'news_host_customize_register');
