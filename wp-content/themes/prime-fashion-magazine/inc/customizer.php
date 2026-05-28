<?php
/**
 * Prime Fashion Magazine Theme Customizer.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package prime_fashion_magazine
 */

if( ! function_exists( 'prime_fashion_magazine_customize_register' ) ):  
/**
 * Add postMessage support for site title and description for the Theme Customizer.F
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function prime_fashion_magazine_customize_register( $wp_customize ) {
    require get_parent_theme_file_path('/inc/controls/changeable-icon.php');

    require get_parent_theme_file_path('/inc/controls/sortable-control.php');
    
    //Register the sortable control type.
    $wp_customize->register_control_type( 'Prime_Fashion_Magazine_Control_Sortable' ); 
    
    if ( version_compare( get_bloginfo('version'),'4.9', '>=') ) {
        $wp_customize->get_section( 'static_front_page' )->title = __( 'Static Front Page', 'prime-fashion-magazine' );
    }
	
    /* Option list of all post */	
    $prime_fashion_magazine_options_posts = array();
    $prime_fashion_magazine_options_posts_obj = get_posts('posts_per_page=-1');
    $prime_fashion_magazine_options_posts[''] = esc_html__( 'Choose Post', 'prime-fashion-magazine' );
    foreach ( $prime_fashion_magazine_options_posts_obj as $prime_fashion_magazine_posts ) {
    	$prime_fashion_magazine_options_posts[$prime_fashion_magazine_posts->ID] = $prime_fashion_magazine_posts->post_title;
    }
    
    /* Option list of all categories */
    $prime_fashion_magazine_args = array(
	   'type'                     => 'post',
	   'orderby'                  => 'name',
	   'order'                    => 'ASC',
	   'hide_empty'               => 1,
	   'hierarchical'             => 1,
	   'taxonomy'                 => 'category'
    ); 
    $prime_fashion_magazine_option_categories = array();
    $prime_fashion_magazine_category_lists = get_categories( $prime_fashion_magazine_args );
    $prime_fashion_magazine_option_categories[''] = esc_html__( 'Choose Category', 'prime-fashion-magazine' );
    foreach( $prime_fashion_magazine_category_lists as $prime_fashion_magazine_category ){
        $prime_fashion_magazine_option_categories[$prime_fashion_magazine_category->term_id] = $prime_fashion_magazine_category->name;
    }
    
    /** Default Settings */    
    $wp_customize->add_panel( 
        'wp_default_panel',
         array(
            'priority' => 10,
            'capability' => 'edit_theme_options',
            'theme_supports' => '',
            'title' => esc_html__( 'Default Settings', 'prime-fashion-magazine' ),
            'description' => esc_html__( 'Default section provided by wordpress customizer.', 'prime-fashion-magazine' ),
        ) 
    );
    
    $wp_customize->get_section( 'title_tagline' )->panel                  = 'wp_default_panel';
    $wp_customize->get_section( 'colors' )->panel                         = 'wp_default_panel';
    $wp_customize->get_section( 'header_image' )->panel                   = 'wp_default_panel';
    $wp_customize->get_section( 'background_image' )->panel               = 'wp_default_panel';
    $wp_customize->get_section( 'static_front_page' )->panel              = 'wp_default_panel';
    
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    
    /** Default Settings Ends */
    
    /** Site Title control */
    $wp_customize->add_setting( 
        'header_site_title', 
        array(
            'default'           => true,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'header_site_title',
        array(
            'label'       => __( 'Show / Hide Site Title', 'prime-fashion-magazine' ),
            'section'     => 'title_tagline',
            'type'        => 'checkbox',
        )
    );

    /** Tagline control */
    $wp_customize->add_setting( 
        'header_tagline', 
        array(
            'default'           => false,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'header_tagline',
        array(
            'label'       => __( 'Show / Hide Tagline', 'prime-fashion-magazine' ),
            'section'     => 'title_tagline',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting('logo_width', array(
        'sanitize_callback' => 'absint', 
    ));

    // Add a control for logo width
    $wp_customize->add_control('logo_width', array(
        'label' => __('Logo Width', 'prime-fashion-magazine'),
        'section' => 'title_tagline',
        'type' => 'number',
        'input_attrs' => array(
            'min' => '50', 
            'max' => '500', 
            'step' => '5', 
    ),
        'default' => '100', 
    ));

    $wp_customize->add_setting( 'prime_fashion_magazine_site_title_size', array(
        'default'           => 30, // Default font size in pixels
        'sanitize_callback' => 'absint', // Sanitize the input as a positive integer
    ) );

    // Add control for site title size
    $wp_customize->add_control( 'prime_fashion_magazine_site_title_size', array(
        'type'        => 'number',
        'section'     => 'title_tagline', // You can change this section to your preferred section
        'label'       => __( 'Site Title Font Size (px)', 'prime-fashion-magazine' ),
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 100,
            'step' => 1,
        ),
    ) );

    /** Responsive Media settings */
    $wp_customize->add_section(
        'prime_fashion_magazine_responsive_media_section',
        array(
            'title' => esc_html__( 'Responsive Media Settings', 'prime-fashion-magazine' ),
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_general_settings',
        )
    );

    /** Scroll to top Responsive control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_resp_scroll_top', 
        array(
            'default' => 1,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_resp_scroll_top',
        array(
            'label'       => __( 'Show Scroll To Top', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_responsive_media_section',
            'type'        => 'checkbox',
        )
    );

        /** Scroll to top Responsive control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_resp_loader', 
        array(
            'default' => 0,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_resp_loader',
        array(
            'label'       => __( 'Show Preloader', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_responsive_media_section',
            'type'        => 'checkbox',
        )
    );

    /** Responsive Media Ends */

    //Global Color
    $wp_customize->add_section(
        'prime_fashion_magazine_global_color',
        array(
            'title' => esc_html__( 'Global Color Settings', 'prime-fashion-magazine' ),
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_general_settings',
        )
    );

    $wp_customize->add_setting('prime_fashion_magazine_primary_color', array(
        'default'           => '#00B5C0',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'prime_fashion_magazine_primary_color', array(
        'label'    => __('Theme Primary Color', 'prime-fashion-magazine'),
        'section'  => 'prime_fashion_magazine_global_color',
        'settings' => 'prime_fashion_magazine_primary_color',
    )));   

    /** Post & Pages Settings */
    $wp_customize->add_panel( 
        'prime_fashion_magazine_post_settings',
         array(
            'priority' => 12,
            'capability' => 'edit_theme_options',
            'title' => esc_html__( 'Post & Pages Settings', 'prime-fashion-magazine' ),
            'description' => esc_html__( 'Customize Post & Pages Settings', 'prime-fashion-magazine' ),
        ) 
    );

    /** Post Layouts */
    $wp_customize->add_section(
        'prime_fashion_magazine_post_layout_section',
        array(
            'title' => esc_html__( 'Post Layout Settings', 'prime-fashion-magazine' ),
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_post_settings',
        )
    );

    $wp_customize->add_setting('prime_fashion_magazine_post_layout_setting', array(
        'default'           => 'right-sidebar',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_post_layout',
    ));

    $wp_customize->add_control('prime_fashion_magazine_post_layout_setting', array(
        'label'    => __('Post Column Settings', 'prime-fashion-magazine'),
        'section'  => 'prime_fashion_magazine_post_layout_section',
        'settings' => 'prime_fashion_magazine_post_layout_setting',
        'type'     => 'select',
        'choices'  => array(        
            'right-sidebar'   => __('Right Sidebar', 'prime-fashion-magazine'),
            'left-sidebar'   => __('Left Sidebar', 'prime-fashion-magazine'),
            'one-column'   => __('One Column', 'prime-fashion-magazine'),
            'three-column'   => __('Three Columns', 'prime-fashion-magazine'),
            'four-column'   => __('Four Columns', 'prime-fashion-magazine'),
            'grid-layout'   => __('Grid Layout', 'prime-fashion-magazine')
        ),
    ));

    $wp_customize->add_setting('prime_fashion_magazine_archive_pagination_alignment',array(
        'default' => 'left-align',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_pagination_alignment'
    ));
    $wp_customize->add_control('prime_fashion_magazine_archive_pagination_alignment',array(
        'type' => 'select',
        'label' => __('Pagination Alignment','prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_post_layout_section',
        'choices' => array(
            'right-align' => __('Right Alignment','prime-fashion-magazine'),
            'center-align' => __('Center Alignment','prime-fashion-magazine'),
            'left-align' => __('Left Alignment','prime-fashion-magazine'),
        ),
    ) );

     /** Post Layouts Ends */
     
    /** Post Settings */
    $wp_customize->add_section(
        'prime_fashion_magazine_post_settings',
        array(
            'title' => esc_html__( 'Post Settings', 'prime-fashion-magazine' ),
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_post_settings',
        )
    );

    /** Post Heading control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_post_heading_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_post_heading_setting',
        array(
            'label'       => __( 'Show / Hide Post Heading', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_post_settings',
            'type'        => 'checkbox',
        )
    );

    /** Post Meta control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_post_meta_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_post_meta_setting',
        array(
            'label'       => __( 'Show / Hide Post Meta', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_post_settings',
            'type'        => 'checkbox',
        )
    );

    /** Post Image control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_post_image_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_post_image_setting',
        array(
            'label'       => __( 'Show / Hide Post Image', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_post_settings',
            'type'        => 'checkbox',
        )
    );

    /** Post Content control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_post_content_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_post_content_setting',
        array(
            'label'       => __( 'Show / Hide Post Content', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_post_settings',
            'type'        => 'checkbox',
        )
    );
    /** Post ReadMore control */
     $wp_customize->add_setting( 'prime_fashion_magazine_read_more_setting`', array(
        'default'           => true,
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'prime_fashion_magazine_read_more_setting`', array(
        'type'        => 'checkbox',
        'section'     => 'prime_fashion_magazine_post_settings', 
        'label'       => __( 'Display Read More Button', 'prime-fashion-magazine' ),
    ) );

        $wp_customize->add_setting('prime_fashion_magazine_single_post_align',array(
        'default' => 'left-align',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_single_post_align'
    ));
    $wp_customize->add_control('prime_fashion_magazine_single_post_align',array(
        'type' => 'select',
        'label' => __('Post Content Alignment','prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_post_settings',
        'choices' => array(
            'left-align' => __('Left Alignment','prime-fashion-magazine'),
            'right-align' => __('Right Alignment','prime-fashion-magazine'),
            'center-align' => __('Center Alignment','prime-fashion-magazine'),
        ),
    ) );

    $wp_customize->add_setting('prime_fashion_magazine_blog_meta_order', array(
        'default' => array('heading', 'author', 'featured-image', 'content','button'),
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_sortable',
    ));
    $wp_customize->add_control(new Prime_Fashion_Magazine_Control_Sortable($wp_customize, 'prime_fashion_magazine_blog_meta_order', array(
        'label' => esc_html__('Post Meta Ordering', 'prime-fashion-magazine'),
        'description' => __('Drag & drop post items to rearrange the ordering ( this control will not function by post format )', 'prime-fashion-magazine') ,
        'section' => 'prime_fashion_magazine_post_settings',
        'choices' => array(
            'heading' => __('heading', 'prime-fashion-magazine') ,
            'author' => __('author', 'prime-fashion-magazine') ,
            'featured-image' => __('featured-image', 'prime-fashion-magazine') ,
            'content' => __('content', 'prime-fashion-magazine') ,
            'button' => __('button', 'prime-fashion-magazine') ,
        ) ,
    )));

    /** Post Settings Ends */

     /** Single Post Settings */
    $wp_customize->add_section(
        'prime_fashion_magazine_single_post_settings',
        array(
            'title' => esc_html__( 'Single Post Settings', 'prime-fashion-magazine' ),
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_post_settings',
        )
    );

    /** Single Post Meta control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_single_post_meta_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_single_post_meta_setting',
        array(
            'label'       => __( 'Show / Hide Single Post Meta', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_single_post_settings',
            'type'        => 'checkbox',
        )
    );

    /** Single Post Content control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_single_post_content_setting', 
        array(
            'default'           => true,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_single_post_content_setting',
        array(
            'label'       => __( 'Show / Hide Single Post Content', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_single_post_settings',
            'type'        => 'checkbox',
        )
    );

    /** Single Post Settings Ends */

         // Typography Settings Section
    $wp_customize->add_section('prime_fashion_magazine_typography_settings', array(
        'title'      => esc_html__('Typography Settings', 'prime-fashion-magazine'),
        'priority'   => 30,
        'capability' => 'edit_theme_options',
        'panel' => 'prime_fashion_magazine_general_settings',
    ));

    // Array of fonts to choose from
    $font_choices = array(
        ''               => __('Select', 'prime-fashion-magazine'),
        'Arial'          => 'Arial, sans-serif',
        'Verdana'        => 'Verdana, sans-serif',
        'Helvetica'      => 'Helvetica, sans-serif',
        'Times New Roman'=> '"Times New Roman", serif',
        'Georgia'        => 'Georgia, serif',
        'Courier New'    => '"Courier New", monospace',
        'Trebuchet MS'   => '"Trebuchet MS", sans-serif',
        'Tahoma'         => 'Tahoma, sans-serif',
        'Palatino'       => '"Palatino Linotype", serif',
        'Garamond'       => 'Garamond, serif',
        'Impact'         => 'Impact, sans-serif',
        'Comic Sans MS'  => '"Comic Sans MS", cursive, sans-serif',
        'Lucida Sans'    => '"Lucida Sans Unicode", sans-serif',
        'Arial Black'    => '"Arial Black", sans-serif',
        'Gill Sans'      => '"Gill Sans", sans-serif',
        'Segoe UI'       => '"Segoe UI", sans-serif',
        'Open Sans'      => '"Open Sans", sans-serif',
        'Roboto'         => 'Roboto, sans-serif',
        'Lato'           => 'Lato, sans-serif',
        'Montserrat'     => 'Montserrat, sans-serif',
        'Libre Baskerville' => 'Libre Baskerville',
    );

    // Heading Font Setting
    $wp_customize->add_setting('prime_fashion_magazine_heading_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_choicess',
    ));
    $wp_customize->add_control('prime_fashion_magazine_heading_font_family', array(
        'type'    => 'select',
        'choices' => $font_choices,
        'label'   => __('Select Font for Heading', 'prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_typography_settings',
    ));

    // Body Font Setting
    $wp_customize->add_setting('prime_fashion_magazine_body_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_choicess',
    ));
    $wp_customize->add_control('prime_fashion_magazine_body_font_family', array(
        'type'    => 'select',
        'choices' => $font_choices,
        'label'   => __('Select Font for Body', 'prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_typography_settings',
    ));

    /** Typography Settings Section End */

    /** General Settings */
    $wp_customize->add_panel( 
        'prime_fashion_magazine_general_settings',
         array(
            'priority' => 12,
            'capability' => 'edit_theme_options',
            'title' => esc_html__( 'General Settings', 'prime-fashion-magazine' ),
            'description' => esc_html__( 'Customize General Settings', 'prime-fashion-magazine' ),
        ) 
    );

    /** Loader Settings */
    $wp_customize->add_section(
        'prime_fashion_magazine_general_settings',
        array(
            'title' => esc_html__( 'Loader Settings', 'prime-fashion-magazine' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_general_settings',

        )
    );

    /** Preloader control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_header_preloader', 
        array(
            'default' => false,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_header_preloader',
        array(
            'label'       => __( 'Show Preloader', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_general_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting('prime_fashion_magazine_theme_width',array(
        'default' => 'full-width',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_theme_width'
    ));
    $wp_customize->add_control('prime_fashion_magazine_theme_width',array(
        'type' => 'select',
        'label' => __('Theme Width Option','prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_general_settings',
        'choices' => array(
            'full-width' => __('Fullwidth','prime-fashion-magazine'),
            'container' => __('Container','prime-fashion-magazine'),
            'container-fluid' => __('Container Fluid','prime-fashion-magazine'),
        ),
    ) );

    /** Header Section Settings */
    $wp_customize->add_section(
        'prime_fashion_magazine_header_section_settings',
        array(
            'title' => esc_html__( 'Header Section Settings', 'prime-fashion-magazine' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_home_page_settings',
        )
    );

    /** Header Section control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_header_setting', 
        array(
            'default' => false,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_header_setting',
        array(
            'label'       => __( 'Show Header', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_header_section_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting('prime_fashion_magazine_addvertise_text',array(
        'default'=> '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control('prime_fashion_magazine_addvertise_text',array(
        'label' => __('Advertisement Text ','prime-fashion-magazine'),
        'section'=> 'prime_fashion_magazine_header_section_settings',
        'settings'=> 'prime_fashion_magazine_addvertise_text',
        'type'=> 'text'
    ));

    /**  Add Advertise URL*/
     $wp_customize->add_setting(
         'prime_fashion_magazine_addvertise_link',
         array( 
             'default' => '',
             'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
         )
     );
    
     $wp_customize->add_control(
         'prime_fashion_magazine_addvertise_link',
         array(
             'label' => esc_html__( 'Add Advertise URL', 'prime-fashion-magazine' ),
             'section' => 'prime_fashion_magazine_header_section_settings',
             'type' => 'url',
         )
     );

    $wp_customize->add_setting('prime_fashion_magazine_about_text',array(
        'default'=> '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control('prime_fashion_magazine_about_text',array(
        'label' => __('About Text ','prime-fashion-magazine'),
        'section'=> 'prime_fashion_magazine_header_section_settings',
        'settings'=> 'prime_fashion_magazine_about_text',
        'type'=> 'text'
    ));

    /**  Add About URL*/
     $wp_customize->add_setting(
         'prime_fashion_magazine_about_link',
         array( 
             'default' => '',
             'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
         )
     );
    
     $wp_customize->add_control(
         'prime_fashion_magazine_about_link',
         array(
             'label' => esc_html__( 'Add About URL', 'prime-fashion-magazine' ),
             'section' => 'prime_fashion_magazine_header_section_settings',
             'type' => 'url',
         )
     );

    $wp_customize->add_setting('prime_fashion_magazine_contact_text',array(
        'default'=> '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control('prime_fashion_magazine_contact_text',array(
        'label' => __('Contact Text ','prime-fashion-magazine'),
        'section'=> 'prime_fashion_magazine_header_section_settings',
        'settings'=> 'prime_fashion_magazine_contact_text',
        'type'=> 'text'
    ));

    /**  Add Contact URL*/
     $wp_customize->add_setting(
         'prime_fashion_magazine_contact_link',
         array( 
             'default' => '',
             'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
         )
     );
    
     $wp_customize->add_control(
         'prime_fashion_magazine_contact_link',
         array(
             'label' => esc_html__( 'Add Contact URL', 'prime-fashion-magazine' ),
             'section' => 'prime_fashion_magazine_header_section_settings',
             'type' => 'url',
         )
        );

        $wp_customize->add_setting('prime_fashion_magazine_bookmark_link',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control( 'prime_fashion_magazine_bookmark_link', array(
       'settings' => 'prime_fashion_magazine_bookmark_link',
       'section'   => 'prime_fashion_magazine_header_section_settings',
       'label' => __('Bookmark Link', 'prime-fashion-magazine'),
       'type'      => 'url'
    ));

    $wp_customize->add_setting('prime_fashion_magazine_subscribe_link',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control( 'prime_fashion_magazine_subscribe_link', array(
       'settings' => 'prime_fashion_magazine_subscribe_link',
       'section'   => 'prime_fashion_magazine_header_section_settings',
       'label' => __('Subscribe Link', 'prime-fashion-magazine'),
       'type'      => 'url'
    ));

    $wp_customize->add_setting('prime_fashion_magazine_admin_img',array(
        'default'   => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'prime_fashion_magazine_admin_img',array(
        'label' => __('Add Image','prime-fashion-magazine'),
        'description'   => __('Use the given image dimension (33 x 33).','prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_header_section_settings'
    )));

    $wp_customize->add_setting('prime_fashion_magazine_image_link',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control( 'prime_fashion_magazine_image_link', array(
       'settings' => 'prime_fashion_magazine_image_link',
       'section'   => 'prime_fashion_magazine_header_section_settings',
       'label' => __('Add Account Link', 'prime-fashion-magazine'),
       'type'      => 'url'
    ));

    $wp_customize->add_setting('prime_fashion_magazine_middle_header_img',array(
        'default'   => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'prime_fashion_magazine_middle_header_img',array(
        'label' => __('Middle Header Image','prime-fashion-magazine'),
        'description'   => __('Use the given image dimension (665 x 77).','prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_header_section_settings'
    )));

    $wp_customize->add_setting('prime_fashion_magazine_discount_text',array(
        'default'=> '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control('prime_fashion_magazine_discount_text',array(
        'label' => __('Discount Text ','prime-fashion-magazine'),
        'section'=> 'prime_fashion_magazine_header_section_settings',
        'settings'=> 'prime_fashion_magazine_discount_text',
        'type'=> 'text'
    ));

    $wp_customize->add_setting('prime_fashion_magazine_img_text',array(
        'default'=> '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control('prime_fashion_magazine_img_text',array(
        'label' => __('Image Text ','prime-fashion-magazine'),
        'section'=> 'prime_fashion_magazine_header_section_settings',
        'settings'=> 'prime_fashion_magazine_img_text',
        'type'=> 'text'
    ));

    $wp_customize->add_setting('prime_fashion_magazine_header_btn_text',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control( 'prime_fashion_magazine_header_btn_text', array(
       'settings' => 'prime_fashion_magazine_header_btn_text',
       'section'   => 'prime_fashion_magazine_header_section_settings',
       'label' => __('Add Button Text', 'prime-fashion-magazine'),
       'type'      => 'text'
    ));

    $wp_customize->add_setting('prime_fashion_magazine_header_btn_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'capability' => 'edit_theme_options',
    ));
    $wp_customize->add_control( 'prime_fashion_magazine_header_btn_url', array(
       'settings' => 'prime_fashion_magazine_header_btn_url',
       'section'   => 'prime_fashion_magazine_header_section_settings',
       'label' => __('Add Button URL', 'prime-fashion-magazine'),
       'type'      => 'url'
    ));

   /** Sticky Header control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_sticky_header', 
        array(
            'default' => false,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_sticky_header',
        array(
            'label'       => __( 'Show Sticky Header', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_header_section_settings',
            'type'        => 'checkbox',
        )
    );

    // Add Setting for Menu Font Weight
    $wp_customize->add_setting( 'prime_fashion_magazine_menu_font_weight', array(
        'default'           => '500',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_font_weight',
    ) );

    // Add Control for Menu Font Weight
    $wp_customize->add_control( 'prime_fashion_magazine_menu_font_weight', array(
        'label'    => __( 'Menu Font Weight', 'prime-fashion-magazine' ),
        'section'  => 'prime_fashion_magazine_header_section_settings',
        'type'     => 'select',
        'choices'  => array(
            '100' => __( '100 - Thin', 'prime-fashion-magazine' ),
            '200' => __( '200 - Extra Light', 'prime-fashion-magazine' ),
            '300' => __( '300 - Light', 'prime-fashion-magazine' ),
            '400' => __( '400 - Normal', 'prime-fashion-magazine' ),
            '500' => __( '500 - Medium', 'prime-fashion-magazine' ),
            '600' => __( '600 - Semi Bold', 'prime-fashion-magazine' ),
            '700' => __( '700 - Bold', 'prime-fashion-magazine' ),
            '800' => __( '800 - Extra Bold', 'prime-fashion-magazine' ),
            '900' => __( '900 - Black', 'prime-fashion-magazine' ),
        ),
    ) );

    // Add Setting for Menu Text Transform
    $wp_customize->add_setting( 'prime_fashion_magazine_menu_text_transform', array(
        'default'           => 'capitalize',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_text_transform',
    ) );

    // Add Control for Menu Text Transform
    $wp_customize->add_control( 'prime_fashion_magazine_menu_text_transform', array(
        'label'    => __( 'Menu Text Transform', 'prime-fashion-magazine' ),
        'section'  => 'prime_fashion_magazine_header_section_settings',
        'type'     => 'select',
        'choices'  => array(
            'none'       => __( 'None', 'prime-fashion-magazine' ),
            'capitalize' => __( 'Capitalize', 'prime-fashion-magazine' ),
            'uppercase'  => __( 'Uppercase', 'prime-fashion-magazine' ),
            'lowercase'  => __( 'Lowercase', 'prime-fashion-magazine' ),
        ),
    ) );

    // Menu Hover Style	
    $wp_customize->add_setting('prime_fashion_magazine_menus_style',array(
        'default' => '',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_choices'
	));
	$wp_customize->add_control('prime_fashion_magazine_menus_style',array(
        'type' => 'select',
		'label' => __('Menu Hover Style','prime-fashion-magazine'),
		'section' => 'prime_fashion_magazine_header_section_settings',
		'choices' => array(
         'None' => __('None','prime-fashion-magazine'),
         'Zoom In' => __('Zoom In','prime-fashion-magazine'),
      ),
	));

    /** Social Section Settings */
    $wp_customize->add_section(
        'prime_fashion_magazine_social_section_settings',
        array(
            'title' => esc_html__( 'Social Media Section Settings', 'prime-fashion-magazine' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_home_page_settings',
        )
    );

    /** Socail Section control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_social_icon_setting', 
        array(
            'default' => false,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_social_icon_setting',
        array(
            'label'       => __( 'Show Social Icon', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_social_section_settings',
            'type'        => 'checkbox',
        )
    );

    /**  Social Link 1 */
    $wp_customize->add_setting(
        'prime_fashion_magazine_social_link_1',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'prime_fashion_magazine_social_link_1',
        array(
            'label' => esc_html__( 'Add Facebook Link', 'prime-fashion-magazine' ),
            'section' => 'prime_fashion_magazine_social_section_settings',
            'type' => 'url',
        )
    );

    /**  Social Link 2 */
    $wp_customize->add_setting(
        'prime_fashion_magazine_social_link_2',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'prime_fashion_magazine_social_link_2',
        array(
            'label' => esc_html__( 'Add Twitter Link', 'prime-fashion-magazine' ),
            'section' => 'prime_fashion_magazine_social_section_settings',
            'type' => 'url',
        )
    );

    /**  Social Link 3 */
    $wp_customize->add_setting(
        'prime_fashion_magazine_social_link_3',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'prime_fashion_magazine_social_link_3',
        array(
            'label' => esc_html__( 'Add Instagram Link', 'prime-fashion-magazine' ),
            'section' => 'prime_fashion_magazine_social_section_settings',
            'type' => 'url',
        )
    );

    /**  Social Link 4 */
    $wp_customize->add_setting(
        'prime_fashion_magazine_social_link_4',
        array( 
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh'
        )
    );
    
    $wp_customize->add_control(
        'prime_fashion_magazine_social_link_4',
        array(
            'label' => esc_html__( 'Add Youtube Link', 'prime-fashion-magazine' ),
            'section' => 'prime_fashion_magazine_social_section_settings',
            'type' => 'url',
        )
    );

    /** Socail Section Settings End */

    /** Home Page Settings */
    $wp_customize->add_panel( 
        'prime_fashion_magazine_home_page_settings',
         array(
            'priority' => 9,
            'capability' => 'edit_theme_options',
            'title' => esc_html__( 'Home Page Settings', 'prime-fashion-magazine' ),
            'description' => esc_html__( 'Customize Home Page Settings', 'prime-fashion-magazine' ),
        ) 
    );

    /** Slider Section Settings */
    $wp_customize->add_section(
        'prime_fashion_magazine_slider_section_settings',
        array(
            'title' => esc_html__( 'Slider Section Settings', 'prime-fashion-magazine' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_home_page_settings',
        )
    );

    /** Slider Section control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_slider_setting', 
        array(
            'default' => false,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_slider_setting',
        array(
            'label'       => __( 'Show Slider', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_slider_section_settings',
            'type'        => 'checkbox',
        )
    );
    
    $categories = get_categories();
        $cat_posts = array();
            $i = 0;
            $cat_posts[]='Select';
        foreach($categories as $category){
            if($i==0){
            $default = $category->slug;
            $i++;
        }
        $cat_posts[$category->slug] = $category->name;
    }

    $wp_customize->add_setting(
        'prime_fashion_magazine_blog_slide_category',
        array(
            'default'   => 'select',
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_choices',
        )
    );
    $wp_customize->add_control(
        'prime_fashion_magazine_blog_slide_category',
        array(
            'type'    => 'select',
            'choices' => $cat_posts,
            'label' => __('Select Category to display Slider Post','prime-fashion-magazine'),
            'section' => 'prime_fashion_magazine_slider_section_settings',
        )
    );

    $wp_customize->add_setting(
        'prime_fashion_magazine_blog_slide_category_1',
        array(
            'default'   => 'select',
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_choices',
        )
    );
    $wp_customize->add_control(
        'prime_fashion_magazine_blog_slide_category_1',
        array(
            'type'    => 'select',
            'choices' => $cat_posts,
            'label' => __('Select Category for Post List','prime-fashion-magazine'),
            'section' => 'prime_fashion_magazine_slider_section_settings',
        )
    );

    /** Classes Section Settings */
    $wp_customize->add_section(
        'prime_fashion_magazine_classes_section_settings',
        array(
            'title' => esc_html__( 'Our Gallery Section Settings', 'prime-fashion-magazine' ),
            'priority' => 30,
            'capability' => 'edit_theme_options',
            'panel' => 'prime_fashion_magazine_home_page_settings',
        )
    );

    /** Classes Section control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_classes_setting', 
        array(
            'default' => true,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_classes_setting',
        array(
            'label'       => __( 'Show Blogs', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_classes_section_settings',
            'type'        => 'checkbox',
        )
    );

    // Section Title
    $wp_customize->add_setting(
        'prime_fashion_magazine_section_title', 
        array(
            'default'           => '',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',    
            'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_section_title', 
        array(
            'label'       => __('Section Title', 'prime-fashion-magazine'),
            'section'     => 'prime_fashion_magazine_classes_section_settings',
            'settings'    => 'prime_fashion_magazine_section_title',
            'type'        => 'text'
        )
    );

    // Section Text
    $wp_customize->add_setting(
        'prime_fashion_magazine_section_text', 
        array(
            'default'           => '',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',    
            'sanitize_callback' => 'sanitize_text_field'
        )
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_section_text', 
        array(
            'label'       => __('Section Text', 'prime-fashion-magazine'),
            'section'     => 'prime_fashion_magazine_classes_section_settings',
            'settings'    => 'prime_fashion_magazine_section_text',
            'type'        => 'text'
        )
    );

    // Post Categories
    $categories = get_categories();
    $cat_posts = array();
    $default = '';
    $cat_posts[] = 'Select';
    foreach ($categories as $category) {
        $cat_posts[$category->slug] = $category->name;
    }

    $wp_customize->add_setting(
        'prime_fashion_magazine_trending_post_slider_args_',
        array(
            'default'            => 'select',
            'sanitize_callback'  => 'prime_fashion_magazine_sanitize_choices',
        )
    );
    $wp_customize->add_control(
        'prime_fashion_magazine_trending_post_slider_args_',
        array(
            'type'     => 'select',
            'choices'  => $cat_posts,
            'label'    => __('Select Category to display Blogs Details', 'prime-fashion-magazine'),
            'section'  => 'prime_fashion_magazine_classes_section_settings',
        )
    );

    /** Home Page Settings Ends */
    
    /** Footer Section */
    $wp_customize->add_section(
        'prime_fashion_magazine_footer_section',
        array(
            'title' => __( 'Footer Settings', 'prime-fashion-magazine' ),
            'priority' => 70,
            'panel' => 'prime_fashion_magazine_home_page_settings',
        )
    );

    /** Footer Widget Columns */
    $wp_customize->add_setting('prime_fashion_magazine_footer_widget_areas', array(
        'default'           => 4,
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_choices',
    ));

    $wp_customize->add_control('prime_fashion_magazine_footer_widget_areas', array(
        'label'    => __('Footer Widget Columns', 'prime-fashion-magazine'),
        'section'  => 'prime_fashion_magazine_footer_section',
        'settings' => 'prime_fashion_magazine_footer_widget_areas',
        'type'     => 'select',
        'choices'  => array(
		   '1'     => __('One', 'prime-fashion-magazine'),
		   '2'     => __('Two', 'prime-fashion-magazine'),
		   '3'     => __('Three', 'prime-fashion-magazine'),
		   '4'     => __('Four', 'prime-fashion-magazine')
        ),
    ));

    /** Footer Copyright control */
    $wp_customize->add_setting( 
        'prime_fashion_magazine_footer_setting', 
        array(
            'default' => true,
            'sanitize_callback' => 'prime_fashion_magazine_sanitize_checkbox',
        ) 
    );

    $wp_customize->add_control(
        'prime_fashion_magazine_footer_setting',
        array(
            'label'       => __( 'Show Footer Copyright', 'prime-fashion-magazine' ),
            'section'     => 'prime_fashion_magazine_footer_section',
            'type'        => 'checkbox',
        )
    );
    
    /** Copyright Text */
    $wp_customize->add_setting(
        'prime_fashion_magazine_footer_copyright_text',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        'prime_fashion_magazine_footer_copyright_text',
        array(
            'label' => __( 'Copyright Info', 'prime-fashion-magazine' ),
            'section' => 'prime_fashion_magazine_footer_section',
            'type' => 'text',
        )
    );  
$wp_customize->add_setting('prime_fashion_magazine_footer_background_image',
        array(
        'default' => '',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint',
        )
    );


    $wp_customize->add_control(
         new WP_Customize_Cropped_Image_Control($wp_customize, 'prime_fashion_magazine_footer_background_image',
            array(
                'label' => esc_html__('Footer Background Image', 'prime-fashion-magazine'),
                /* translators: 1: image width in pixels, 2: image height in pixels */
                'description' => sprintf(esc_html__('Recommended Size %1$s px X %2$s px', 'prime-fashion-magazine'), 1024, 800),
                'section' => 'prime_fashion_magazine_footer_section',
                'width' => 1024,
                'height' => 800,
                'flex_width' => true,
                'flex_height' => true,
            )
        )
    );

    /** Footer Background Image Attachment */
    $wp_customize->add_setting('prime_fashion_magazine_background_attachment', array(
        'default'           => 'scroll',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_choices',
    ));

    $wp_customize->add_control('prime_fashion_magazine_background_attachment', array(
        'label'    => __('Footer Background Attachment', 'prime-fashion-magazine'),
        'section'  => 'prime_fashion_magazine_footer_section',
        'settings' => 'prime_fashion_magazine_background_attachment',
        'type'     => 'select',
        'choices'  => array(
            'fixed' => __('fixed','prime-fashion-magazine'),
            'scroll' => __('scroll','prime-fashion-magazine'),
        ),
    ));

    /* Footer Background Color*/
    $wp_customize->add_setting(
        'prime_fashion_magazine_footer_background_color',
        array(
            'default' => '',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'prime_fashion_magazine_footer_background_color',
            array(
                'label' => __('Footer Widget Area Background Color', 'prime-fashion-magazine'),
                'section' => 'prime_fashion_magazine_footer_section',
                'type' => 'color',
            )
        )
    );

     $wp_customize->add_setting('prime_fashion_magazine_scroll_icon',array(
        'default'   => 'fas fa-arrow-up',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control(new Prime_Fashion_Magazine_Changeable_Icon(
        $wp_customize,'prime_fashion_magazine_scroll_icon',array(
        'label' => __('Scroll Top Icon','prime-fashion-magazine'),
        'transport' => 'refresh',
        'section'   => 'prime_fashion_magazine_footer_section',
        'type'      => 'icon'
    )));

    $wp_customize->add_setting('prime_fashion_magazine_scroll_top_alignment',array(
        'default' => 'right-align',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_scroll_top_alignment'
    ));
    $wp_customize->add_control('prime_fashion_magazine_scroll_top_alignment',array(
        'type' => 'select',
        'label' => __('Scroll Top Alignment','prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_footer_section',
        'choices' => array(
            'right-align' => __('Right Alignment','prime-fashion-magazine'),
            'center-align' => __('Center Alignment','prime-fashion-magazine'),
            'left-align' => __('Left Alignment','prime-fashion-magazine'),
        ),
    ) );

    /** Scroll to top button shape */
    $wp_customize->add_setting('prime_fashion_magazine_scroll_to_top_radius', array(
        'default'           => 'curved-box',
        'sanitize_callback' => 'prime_fashion_magazine_sanitize_choices',
    ));

    $wp_customize->add_control('prime_fashion_magazine_scroll_to_top_radius', array(
        'label'    => __('Scroll Top Button Shape', 'prime-fashion-magazine'),
        'section'  => 'prime_fashion_magazine_footer_section',
        'settings' => 'prime_fashion_magazine_scroll_to_top_radius',
        'type'     => 'select',
        'choices'  => array(
            'box'        => __( 'Box', 'prime-fashion-magazine' ),
            'curved-box' => __( 'Curved Box', 'prime-fashion-magazine' ),
            'circle'     => __( 'Circle', 'prime-fashion-magazine' ),
        ),
    ));

    // 404 PAGE SETTINGS
    $wp_customize->add_section(
        'prime_fashion_magazine_404_section',
        array(
            'title' => __( '404 Page Settings', 'prime-fashion-magazine' ),
            'priority' => 70,
            'panel' => 'prime_fashion_magazine_general_settings',
        )
    );
   
    $wp_customize->add_setting('404_page_image', array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw', // Sanitize as URL
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, '404_page_image', array(
        'label' => __('404 Page Image', 'prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_404_section',
        'settings' => '404_page_image',
    )));

    $wp_customize->add_setting('404_pagefirst_header', array(
        'default' => __('404', 'prime-fashion-magazine'),
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field', // Sanitize as text field
    ));

    $wp_customize->add_control('404_pagefirst_header', array(
        'type' => 'text',
        'label' => __('Heading', 'prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_404_section',
    ));

    // Setting for 404 page header
    $wp_customize->add_setting('404_page_header', array(
        'default' => __('Sorry, that page can\'t be found!', 'prime-fashion-magazine'),
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field', // Sanitize as text field
    ));

    $wp_customize->add_control('404_page_header', array(
        'type' => 'text',
        'label' => __('Heading', 'prime-fashion-magazine'),
        'section' => 'prime_fashion_magazine_404_section',
    ));

}
add_action( 'customize_register', 'prime_fashion_magazine_customize_register' );
endif;

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function prime_fashion_magazine_customize_preview_js() {
    // Use minified libraries if SCRIPT_DEBUG is false
    $prime_fashion_magazine_build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $prime_fashion_magazine_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'prime_fashion_magazine_customizer', get_template_directory_uri() . '/js' . $prime_fashion_magazine_build . '/customizer' . $prime_fashion_magazine_suffix . '.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'prime_fashion_magazine_customize_preview_js' );