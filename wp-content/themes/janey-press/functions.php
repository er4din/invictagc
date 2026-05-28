<?php

/*-----------------------------------------------------------------------------------*/
/* Enqueue scripts */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('janey_press_enqueue_scripts')) {

	function janey_press_enqueue_scripts() {

		wp_dequeue_style( 'bootstrap'); 
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css',  array(), '3.3.7');

		wp_enqueue_style( 'janey-style', get_template_directory_uri() . '/style.css',  array(), wp_get_theme()->parent()->get('Version') ); 
		wp_enqueue_style( 'janey-press-style', get_stylesheet_directory_uri() . '/style.css', array( 'janey-style'), wp_get_theme()->get('Version') );
		wp_enqueue_style( 'janey-press-slick' , get_stylesheet_directory_uri() . '/assets/css/slick.css', array( 'janey-style' ), '1.8.1' );

		wp_deregister_style('google-fonts');

        $googleFontsArgs = array(
			'family' =>    str_replace('|', '%7C', janey_press_google_font_args()),
			'subset' =>    'latin,latin-ext'
		);
        
		wp_enqueue_style('google-fonts', add_query_arg ( $googleFontsArgs, "https://fonts.googleapis.com/css" ), array(), '1.0.0' );

		wp_enqueue_script(
			'janey-press-navigation',
			get_stylesheet_directory_uri() . '/assets/js/navigation.js',
			array('jquery'),
			'1.0.0',
			TRUE
		);

		wp_enqueue_script(
			'janey-press-slick',
			get_stylesheet_directory_uri() . '/assets/js/slick.js',
			array('jquery'),
			'1.8.1',
			TRUE
		);

		wp_enqueue_script(
			'janey-press-marquee',
			get_stylesheet_directory_uri() . '/assets/js/jquery.marquee.js',
			array('jquery'),
			'2.3.4',
			TRUE
		);

		wp_enqueue_script(
			'janey-press-template',
			get_stylesheet_directory_uri() . '/assets/js/template.js',
			array(
				'jquery',
				'janey-press-slick',
				'janey-press-marquee'
			),
			'1.0.0', 
			TRUE
		);

		wp_localize_script( 'janey-press-navigation', 'accessibleNavigationScreenReaderText', array(
			'expandMain'   => __( 'Open the main menu', 'janey-press' ),
			'collapseMain' => __( 'Close the main menu', 'janey-press' ),
			'expandChild'   => __( 'expand submenu', 'janey-press' ),
			'collapseChild' => __( 'collapse submenu', 'janey-press' ),
		));

	}

	add_action( 'wp_enqueue_scripts', 'janey_press_enqueue_scripts');

}

/*-----------------------------------------------------------------------------------*/
/* Customize scripts */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('janey_press_customize_scripts')) {

	function janey_press_customize_scripts() {

		wp_enqueue_style (
			'janey-press-customizer',
			get_stylesheet_directory_uri() . '/core/admin/assets/css/customize.css',
			array(),
			''
		);

		wp_enqueue_script(
			'janey-press-google-fonts',
			get_stylesheet_directory_uri() . '/core/admin/assets/js/google-fonts.js',
			array('jquery'),
			'',
			true
		);

	}

	add_action('customize_controls_enqueue_scripts', 'janey_press_customize_scripts');

}

/*-----------------------------------------------------------------------------------*/
/* Replace hooks */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('janey_press_replace_hooks')) {

	function janey_press_replace_hooks() {

		remove_action( 'janey_thumbnail', 'janey_thumbnail_function' );
		remove_action( 'wp_enqueue_scripts', 'janey_css_custom');
		remove_filter( 'get_the_excerpt', 'janey_customize_excerpt_more' );

	}

	add_action('init','janey_press_replace_hooks');

}

/*-----------------------------------------------------------------------------------*/
/* Theme setup */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('janey_press_theme_setup')) {

	function janey_press_theme_setup() {

		load_child_theme_textdomain( 'janey-press', get_stylesheet_directory() . '/languages' );

		require_once( trailingslashit( get_stylesheet_directory() ) . 'core/admin/customize/google-fonts.php' );
		require_once( trailingslashit( get_stylesheet_directory() ) . 'core/functions/function-style.php' );
		require_once( trailingslashit( get_stylesheet_directory() ) . 'core/templates/media.php' );
		require_once( trailingslashit( get_stylesheet_directory() ) . 'core/post/related-post.php' );
		require_once( trailingslashit( get_stylesheet_directory() ) . 'core/post/recent-posts/horizontal-recent-posts.php' );
		require_once( trailingslashit( get_stylesheet_directory() ) . 'core/post/recent-posts/vertical-recent-posts.php' );
		require_once( trailingslashit( get_stylesheet_directory() ) . 'core/post/recent-posts/vertical-list-recent-posts.php' );
		require_once( trailingslashit( get_stylesheet_directory() ) . 'core/templates/related-posts.php' );

		if ( !get_theme_mod('janey_logo_text_color') )
			set_theme_mod( 'janey_logo_text_color', '#616161' );

		if ( !get_theme_mod('janey_hide_tagline') )
			set_theme_mod( 'janey_hide_tagline', true );

		if ( !get_theme_mod('janey_logo_font_size') )
			set_theme_mod( 'janey_logo_font_size', '30px' );

		if ( !get_theme_mod('janey_logo_font_weight') )
			set_theme_mod( 'janey_logo_font_weight', '600' );

		add_image_size( 'janey_press_trending_image', 140, 140, true );
		add_image_size( 'janey_press_related_post', 600, 400, true );
		add_image_size( 'janey_press_recent_post_large', 549, 410, true );
		add_image_size( 'janey_press_recent_post_small', 89, 89, true );

	}

	add_action( 'after_setup_theme', 'janey_press_theme_setup', 999);

}

/*-----------------------------------------------------------------------------------*/
/* Get categories */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('janey_press_get_categories')) {

	function janey_press_get_categories() {

		$args = array(
			'taxonomy' => 'category',
			'hide_empty' => true,
		);

		$return['all'] = esc_html__( 'All categories', 'janey-press' );

		foreach ( get_terms($args) as $cat) {
			$return[$cat->term_id] = $cat->name;
		}
		
		return $return;

	}

}

/*-----------------------------------------------------------------------------------*/
/* Customize register */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('janey_press_customize_register')) {

	function janey_press_customize_register( $wp_customize ) {

		$wp_customize->remove_setting( 'janey_logo_font_weight');
		$wp_customize->remove_control( 'janey_logo_font_weight');

		$wp_customize->remove_setting( 'janey_logo_description_font_size');
		$wp_customize->remove_control( 'janey_logo_description_font_size');

		$wp_customize->remove_setting( 'janey_logo_description_top_margin');
		$wp_customize->remove_control( 'janey_logo_description_top_margin');

		$wp_customize->remove_setting( 'janey_menu_font_weight');
		$wp_customize->remove_control( 'janey_menu_font_weight');

		$wp_customize->remove_setting( 'janey_titles_font_weight');
		$wp_customize->remove_control( 'janey_titles_font_weight');

		/* News ticker section
		   ========================================================================== */

		$wp_customize->add_section('janey_press_news_ticker', array(
			'title' => esc_html__( 'Newsticker', 'janey-press' ),
			'description' => esc_html__('From this section you can manage the news ticker','janey-press'),
			'panel' => 'general_panel',
			'priority' => 10,
		));

		/**
		 * News ticker section > Enable news ticker option
		 */

		$wp_customize->add_setting( 'janey_press_enable_news_ticker', array(
			'default' => true,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
			'transport'  => 'refresh'
		));

		$wp_customize->add_control( 'janey_press_enable_news_ticker' , array(
			'type' => 'checkbox',
			'section' => 'janey_press_news_ticker',
			'label' => esc_html__('News ticker','janey-press'),
			'description' => esc_html__('Do you want to enable the news ticker?','janey-press'),
		));

		/**
		* News ticker section > News ticker title option
		*/

		$wp_customize->add_setting( 'janey_press_news_ticker_title', array(
			'default' => esc_html__('HOT','janey-press'),
			'sanitize_callback' => 'sanitize_text_field',
		));
 
		$wp_customize->add_control( 'janey_press_news_ticker_title' , array(
			'type' => 'text',
			'section' => 'janey_press_news_ticker',
			'label' => esc_html__('News ticker title','janey-press'),
			'description' => esc_html__('Insert the title for the news ticker','janey-press'),
		));
 
	   /**
		* News ticker section > News ticker category option
		*/

		$wp_customize->add_setting( 'janey_press_news_ticker_category', array(
			'default' => 'all',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));
 
		$wp_customize->add_control( 'janey_press_news_ticker_category' , array(
			'type' => 'select',
			'section' => 'janey_press_news_ticker',
			'label' => esc_html__('News ticker category','janey-press'),
			'description' => esc_html__('Please select the category of the news ticker.','janey-press'),
			'choices'  => janey_press_get_categories(),
		));
 
		/**
		* News ticker section > News ticker order option
		 */
 
		$wp_customize->add_setting( 'janey_press_news_ticker_order', array(
			'default' => 'date',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));
 
		$wp_customize->add_control( 'janey_press_news_ticker_order' , array(
			'type' => 'select',
			'section' => 'janey_press_news_ticker',
			'label' => esc_html__('News ticker order by','janey-press'),
			'description' => esc_html__('How you want to order the articles?.','janey-press'),
			'choices'  => array (
				'title' => esc_html__( 'Post title','janey-press'),
				'rand' => esc_html__( 'Randomly','janey-press'),
				'comment_count' => esc_html__( 'Comment count','janey-press'),
				'date' => esc_html__( 'Post date','janey-press'),
			),
		));
 
		/**
		* News ticker section > News ticker sort order option
		 */
 
		$wp_customize->add_setting( 'janey_press_news_ticker_sort_order', array(
			'default' => 'desc',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));
 
		$wp_customize->add_control( 'janey_press_news_ticker_sort_order' , array(
			'type' => 'select',
			'section' => 'janey_press_news_ticker',
			'label' => esc_html__('News ticker sort order','janey-press'),
			'description' => esc_html__('Select the order of the articles.','janey-press'),
			'choices'  => array (
				'asc' => esc_html__( 'Ascending','janey-press'),
				'desc' => esc_html__( 'Descending','janey-press'),
			),
		));
 
		/**
		 * News ticker section > News ticker limit option
		 */
 
		$wp_customize->add_setting( 'janey_press_news_ticker_limit', array(
			'sanitize_callback' => 'janey_press_limit_sanitize',
			'default' => 5,
		));
 
		$wp_customize->add_control( 'janey_press_news_ticker_limit' , array(
			'type' => 'number',
			'section' => 'janey_press_news_ticker',
			'label' => esc_html__('News ticker post limit','janey-press'),
			'description' => esc_html__('Please set the max items for the news ticker.','janey-press'),
			'input_attrs' => array('min' => 1)
		));

		/* Recent posts section
		   ========================================================================== */

		$wp_customize->add_section('janey_press_recent_posts_section', array(
			'title' => esc_html__( 'Recent posts', 'janey-press' ),
			'description' => esc_html__( 'From this section you can manage the recent posts on the homepage', 'janey-press' ),
			'panel' => 'general_panel',
			'priority' => 13,
		));

		/**
		* Recent posts section > Enable recent posts option
		*/

		$wp_customize->add_setting( 'janey_press_enable_recent_posts', array(
			'default' => true,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
			'transport'  => 'refresh',
		));
		
		$wp_customize->add_control( 'janey_press_enable_recent_posts', array(
			'label' => esc_html__( 'Enable recent posts','janey-press'),
			'description' => esc_html__( 'Would you like to enable the recent posts on the homepage?', 'janey-press' ),
			'section' => 'janey_press_recent_posts_section',
			'type' => 'checkbox',
		));

		/**
		* Recent posts section > Recent posts only on the first page of pagination option
		*/

		$wp_customize->add_setting( 'janey_press_enable_recent_posts_only_first_page_pagination', array(
			'default' => true,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
			'transport'  => 'refresh',
		));
		
		$wp_customize->add_control( 'janey_press_enable_recent_posts_only_first_page_pagination', array(
			'label' => esc_html__( 'Recent posts only on the first page of pagination','janey-press'),
			'description' => esc_html__( 'Would you like to show the recent posts only on the first page of pagination on your homepage? Enable this option to display the recent posts exclusively on the first page.', 'janey-press' ),
			'section' => 'janey_press_recent_posts_section',
			'type' => 'checkbox',
		));

	   /**
	   * Recent posts section > Main column - Label option
		*/

		$wp_customize->add_setting( 'janey_press_main_column_label', array(
			'default' => esc_html__('Recent','janey-press'),
			'sanitize_callback' => 'sanitize_text_field',
		));
 
		$wp_customize->add_control( 'janey_press_main_column_label' , array(
			'type' => 'text',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Main column &raquo; Label','janey-press'),
			'description' => esc_html__('Please insert the label of the main column','janey-press'),
		));

	   /**
		* Recent posts section > Main column - Post category option
		*/

		$wp_customize->add_setting( 'janey_press_main_column_posts_category', array(
			'default' => 'all',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));
 
		$wp_customize->add_control( 'janey_press_main_column_posts_category' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Main column &raquo; Post category','janey-press'),
			'description' => esc_html__('Please select the category for recent posts in the main column.','janey-press'),
			'choices'  => janey_press_get_categories(),
		));

		/**
		* Recent posts section > Main column - Sort articles option
		*/

		$wp_customize->add_setting( 'janey_press_main_column_posts_orderby', array(
			'default' => 'date',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_main_column_posts_orderby' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Main column &raquo; Sort articles','janey-press'),
			'description' => esc_html__('How you want to order the articles in the main column?','janey-press'),
			'choices' => array (
				'ID' => esc_html__( 'ID','janey-press'),
				'author' => esc_html__( 'Author','janey-press'),
				'title' => esc_html__( 'Post title','janey-press'),
				'date' => esc_html__( 'Date','janey-press'),
				'comment_count' => esc_html__( 'Number of comments','janey-press'),
			),
		));

		/**
		* Recent posts section > Main column - Order articles option
		*/

		$wp_customize->add_setting( 'janey_press_main_column_posts_sort_order', array(
			'default' => 'desc',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_main_column_posts_sort_order' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Main column &raquo; Order articles','janey-press'),
			'description' => esc_html__('Select whether to sort articles in ascending (oldest to newest) or descending (newest to oldest) order in the main column','janey-press'),
			'choices' => array (
				'asc' => esc_html__( 'Ascending','janey-press'),
				'desc' => esc_html__( 'Descending','janey-press'),
			),
		));

		/**
		 * Recent posts section > Main column - Post limit option
		 */
 
		$wp_customize->add_setting( 'janey_press_main_column_posts_limit', array(
			'sanitize_callback' => 'janey_press_limit_sanitize',
			'default' => 5,
		));
 
		$wp_customize->add_control( 'janey_press_main_column_posts_limit' , array(
			'type' => 'number',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Main column &raquo; Post limit','janey-press'),
			'description' => esc_html__('Please set the limit of articles in the main column.','janey-press'),
			'input_attrs' => array(
				'min' => 1,
			),
		));

		/**
		* Recent posts section > Main column - Slider transition option
		*/

		$wp_customize->add_setting( 'janey_press_main_column_slider_transition', array(
			'default' => 'fade',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_main_column_slider_transition' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Main column &raquo; Slider transition','janey-press'),
			'description' => esc_html__('Select the type of transition effect for the slider in the main column','janey-press'),
			'choices' => array (
				'fade' => esc_html__( 'Fade', 'janey-press' ),
				'slide' => esc_html__( 'Slide', 'janey-press' ),
			),
		));

	   /**
	   * Recent posts section > Main column - Post author label option
		*/

		$wp_customize->add_setting( 'janey_press_recent_posts_post_author_label', array(
			'default' => esc_html__('By','janey-press'),
			'sanitize_callback' => 'sanitize_text_field',
		));
 
		$wp_customize->add_control( 'janey_press_recent_posts_post_author_label' , array(
			'type' => 'text',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Main column &raquo; Post author label','janey-press'),
			'description' => esc_html__('Insert the label of post author','janey-press'),
		));

	   /**
	   * Recent posts section > Secondary column - Label option
		*/

		$wp_customize->add_setting( 'janey_press_secondary_column_label', array(
			'default' => esc_html__('Oldest post','janey-press'),
			'sanitize_callback' => 'sanitize_text_field',
		));
 
		$wp_customize->add_control( 'janey_press_secondary_column_label' , array(
			'type' => 'text',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Secondary column &raquo; Label','janey-press'),
			'description' => esc_html__('Please insert the label of the secondary column','janey-press'),
		));

	   /**
		* Recent posts section > Secondary column - Post category option
		*/

		$wp_customize->add_setting( 'janey_press_secondary_column_posts_category', array(
			'default' => 'all',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));
 
		$wp_customize->add_control( 'janey_press_secondary_column_posts_category' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Secondary column &raquo; Post category','janey-press'),
			'description' => esc_html__('Please select the category for recent posts in the secondary column.','janey-press'),
			'choices'  => janey_press_get_categories(),
		));

		/**
		* Recent posts section > Secondary column - Sort articles option
		*/

		$wp_customize->add_setting( 'janey_press_secondary_column_posts_orderby', array(
			'default' => 'date',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_secondary_column_posts_orderby' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Secondary column &raquo; Sort articles','janey-press'),
			'description' => esc_html__('How you want to order the articles in the secondary column?','janey-press'),
			'choices' => array (
				'ID' => esc_html__( 'ID','janey-press'),
				'author' => esc_html__( 'Author','janey-press'),
				'title' => esc_html__( 'Post title','janey-press'),
				'date' => esc_html__( 'Date','janey-press'),
				'comment_count' => esc_html__( 'Number of comments','janey-press'),
			),
		));

		/**
		* Recent posts section > Secondary column - Order articles option
		*/

		$wp_customize->add_setting( 'janey_press_secondary_column_posts_sort_order', array(
			'default' => 'asc',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_secondary_column_posts_sort_order' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Secondary column &raquo; Order articles','janey-press'),
			'description' => esc_html__('Select whether to sort articles in ascending (oldest to newest) or descending (newest to oldest) order in the secondary column','janey-press'),
			'choices' => array (
				'asc' => esc_html__( 'Ascending','janey-press'),
				'desc' => esc_html__( 'Descending','janey-press'),
			),
		));

		/**
		 * Recent posts section > Secondary column - Post limit option
		 */
 
		$wp_customize->add_setting( 'janey_press_secondary_column_posts_limit', array(
			'sanitize_callback' => 'janey_press_limit_sanitize',
			'default' => 8,
		));
 
		$wp_customize->add_control( 'janey_press_secondary_column_posts_limit' , array(
			'type' => 'number',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Secondary column &raquo; Post limit','janey-press'),
			'description' => esc_html__('Please set the limit of articles in the secondary column','janey-press'),
			'input_attrs' => array(
				'min' => 1,
			),
		));

	   /**
	   * Recent posts section > Side column - Label option
		*/

		$wp_customize->add_setting( 'janey_press_side_column_label', array(
			'default' => esc_html__('Popular','janey-press'),
			'sanitize_callback' => 'sanitize_text_field',
		));
 
		$wp_customize->add_control( 'janey_press_side_column_label' , array(
			'type' => 'text',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Side column &raquo; Label','janey-press'),
			'description' => esc_html__('Please insert the label of side column','janey-press'),
		));

	   /**
		* Recent posts section > Side column - Post category option
		*/

		$wp_customize->add_setting( 'janey_press_side_column_posts_category', array(
			'default' => 'all',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));
 
		$wp_customize->add_control( 'janey_press_side_column_posts_category' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Side column &raquo; Post category','janey-press'),
			'description' => esc_html__('Please select the category for recent posts in the side column','janey-press'),
			'choices'  => janey_press_get_categories(),
		));

		/**
		* Recent posts section > Side column - Sort articles option
		*/

		$wp_customize->add_setting( 'janey_press_side_column_posts_orderby', array(
			'default' => 'comment_count',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_side_column_posts_orderby' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Side column &raquo; Sort articles','janey-press'),
			'description' => esc_html__('How you want to order the articles in the side column?','janey-press'),
			'choices' => array (
				'ID' => esc_html__( 'ID','janey-press'),
				'author' => esc_html__( 'Author','janey-press'),
				'title' => esc_html__( 'Post title','janey-press'),
				'date' => esc_html__( 'Date','janey-press'),
				'comment_count' => esc_html__( 'Number of comments','janey-press'),
			),
		));

		/**
		* Recent posts section > Side column - Order articles option
		*/

		$wp_customize->add_setting( 'janey_press_side_column_posts_sort_order', array(
			'default' => 'desc',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_side_column_posts_sort_order' , array(
			'type' => 'select',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Side column &raquo; Order articles','janey-press'),
			'description' => esc_html__('Select whether to sort articles in ascending (oldest to newest) or descending (newest to oldest) order in the side column','janey-press'),
			'choices' => array (
				'asc' => esc_html__( 'Ascending','janey-press'),
				'desc' => esc_html__( 'Descending','janey-press'),
			),
		));

		/**
		 * Recent posts section > Side column - Post limit option
		 */
 
		$wp_customize->add_setting( 'janey_press_side_column_posts_limit', array(
			'sanitize_callback' => 'janey_press_limit_sanitize',
			'default' => 8,
		));
 
		$wp_customize->add_control( 'janey_press_side_column_posts_limit' , array(
			'type' => 'number',
			'section' => 'janey_press_recent_posts_section',
			'label' => esc_html__('Side column &raquo; Post limit','janey-press'),
			'description' => esc_html__('Please set the limit of articles in the side column','janey-press'),
			'input_attrs' => array(
				'min' => 1,
			),
		));

		/* Trending posts section
		   ========================================================================== */

		$wp_customize->add_section('janey_press_trending_posts_section', array(
			'title' => esc_html__( 'Trending posts', 'janey-press' ),
			'description' => esc_html__( 'From this section you can manage the trending posts grid on the homepage.', 'janey-press' ),
			'panel' => 'general_panel',
			'priority' => 14,
		));

		/**
		* Trending posts section > Enable trending posts option
		*/

		$wp_customize->add_setting( 'janey_press_enable_trending_posts', array(
			'default' => true,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
			'transport'  => 'refresh',
		));
		
		$wp_customize->add_control( 'janey_press_enable_trending_posts', array(
			'label' => esc_html__( 'Trending Posts','janey-press'),
			'description' => esc_html__( 'Would you like to enable the trending posts on the homepage?', 'janey-press' ),
			'section' => 'janey_press_trending_posts_section',
			'type' => 'checkbox',
		));

		/**
		* Trending posts section > Trending posts only on the first page of pagination option
		*/

		$wp_customize->add_setting( 'janey_press_enable_trending_posts_only_first_page_pagination', array(
			'default' => true,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
			'transport'  => 'refresh',
		));
		
		$wp_customize->add_control( 'janey_press_enable_trending_posts_only_first_page_pagination', array(
			'label' => esc_html__( 'Trending posts only on the first page of pagination','janey-press'),
			'description' => esc_html__( 'Would you like to show the trending posts only on the first page of pagination on your homepage? Enable this option to display the trending posts exclusively on the first page.', 'janey-press' ),
			'section' => 'janey_press_trending_posts_section',
			'type' => 'checkbox',
		));

		/**
		* Trending posts section > Show trending posts section title option
		*/

		$wp_customize->add_setting( 'janey_press_enable_trending_posts_section_title', array(
			'default' => true,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
		));

		$wp_customize->add_control( 'janey_press_enable_trending_posts_section_title', array(
			'label' => esc_html__( 'Show trending posts section title','janey-press'),
			'description' => esc_html__( 'Would you like to show the trending posts section title?', 'janey-press' ),
			'section' => 'janey_press_trending_posts_section',
			'type' => 'checkbox',
		));

		/**
		* Trending posts section > Show trending posts author option
		*/

		$wp_customize->add_setting( 'janey_press_enable_trending_posts_author', array(
			'default' => true,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
		));

		$wp_customize->add_control( 'janey_press_enable_trending_posts_author', array(
			'label' => esc_html__( 'Show trending posts author','janey-press'),
			'description' => esc_html__( 'Would you like to show the author on trending posts section?', 'janey-press' ),
			'section' => 'janey_press_trending_posts_section',
			'type' => 'checkbox',
		));

		/**
		* Trending posts section > Truncate post titles option
		*/

		$wp_customize->add_setting( 'janey_press_trending_posts_truncate_post_titles', array(
			'default' => false,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
		));

		$wp_customize->add_control( 'janey_press_trending_posts_truncate_post_titles', array(
			'label' => esc_html__( 'Truncate post titles','janey-press'),
			'description' => esc_html__( 'Enable this option to truncate long post titles with ellipsis in the trending posts section.', 'janey-press' ),
			'section' => 'janey_press_trending_posts_section',
			'type' => 'checkbox',
		));

		/**
		* Trending posts section > Trending posts section title option
		*/

		$wp_customize->add_setting( 'janey_press_trending_posts_section_title', array(
			'default' => esc_html__( 'Trending Posts', 'janey-press' ),
			'sanitize_callback' => 'sanitize_text_field',
		));

		$wp_customize->add_control( 'janey_press_trending_posts_section_title' , array(
			'type' => 'text',
			'section' => 'janey_press_trending_posts_section',
			'label' => esc_html__('Trending posts section title','janey-press'),
			'description' => esc_html__( 'Insert the title of trending posts section','janey-press'),
		));

		/**
		* Trending posts section > Trending posts category option
		*/

		$wp_customize->add_setting( 'janey_press_trending_posts_category', array(
			'default' => 'all',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_trending_posts_category' , array(
			'type' => 'select',
			'section' => 'janey_press_trending_posts_section',
			'label' => esc_html__('Trending posts category','janey-press'),
			'description' => esc_html__('Please select the category of trending posts section','janey-press'),
			'choices'  => janey_press_get_categories(),
		));

		/**
		* Trending posts section > Trending posts orderby option
		*/

		$wp_customize->add_setting( 'janey_press_trending_posts_orderby', array(
			'default' => 'comment_count',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_trending_posts_orderby' , array(
			'type' => 'select',
			'section' => 'janey_press_trending_posts_section',
			'label' => esc_html__('Trending posts orderby','janey-press'),
			'description' => esc_html__('How you want to order the articles?','janey-press'),
			'choices' => array (
				'ID' => esc_html__( 'ID','janey-press'),
				'author' => esc_html__( 'Author','janey-press'),
				'title' => esc_html__( 'Post title','janey-press'),
				'date' => esc_html__( 'Date','janey-press'),
				'comment_count' => esc_html__( 'Number of comments','janey-press'),
			),
		));

		/**
		* Trending posts section > Trending posts sort order option
		*/

		$wp_customize->add_setting( 'janey_press_trending_posts_sort_order', array(
			'default' => 'desc',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_trending_posts_sort_order' , array(
			'type' => 'select',
			'section' => 'janey_press_trending_posts_section',
			'label' => esc_html__('Trending posts sort order','janey-press'),
			'description' => esc_html__('Select the order of trending posts','janey-press'),
			'choices' => array (
				'asc' => esc_html__( 'Ascending','janey-press'),
				'desc' => esc_html__( 'Descending','janey-press'),
			),
		));

		/**
		* Trending posts section > Trending posts images layout option
		*/

		$wp_customize->add_setting( 'janey_press_trending_posts_images_layout', array(
			'default' => 'round',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_trending_posts_images_layout' , array(
			'type' => 'select',
			'section' => 'janey_press_trending_posts_section',
			'label' => esc_html__('Trending posts images layout','janey-press'),
			'description' => esc_html__('Select a layout for the trending posts images.','janey-press'),
			'choices' => array (
				'square' => esc_html__( 'Square images','janey-press'),
				'round' => esc_html__( 'Round images','janey-press'),
			),
		));

		/**
		* Trending posts section > Post author label option
		*/

		$wp_customize->add_setting( 'janey_press_trending_posts_post_author_label', array(
			'default' => esc_html__( 'By', 'janey-press' ),
			'sanitize_callback' => 'sanitize_text_field',
		));

		$wp_customize->add_control( 'janey_press_trending_posts_post_author_label' , array(
			'type' => 'text',
			'section' => 'janey_press_trending_posts_section',
			'label' => esc_html__('Post author label','janey-press'),
			'description' => esc_html__( 'Insert the label of post author','janey-press'),
		));

		/**
		* Main Settings panel > General settings section > Enable the placeholder option
		*/

		$wp_customize->add_setting( 'janey_press_has_post_placeholder', array(
			'default' => true,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
			'transport'  => 'refresh'
		));

		$wp_customize->add_control( 'janey_press_has_post_placeholder', array(
			'label' => esc_html__( 'Enable the placeholder','janey-press'),
			'description' => esc_html__('Would you like to display a placeholder when the featured image is missing, on the homepage, archives, and search results?','janey-press'),
			'section' => 'settings_section',
			'type' => 'checkbox',
		));

		/**
		* Main settings panel > General settings section > Enable related posts option
		*/

		$wp_customize->add_setting( 'janey_press_enable_related_posts', array(
			'default' => true,
			'sanitize_callback' => 'janey_press_checkbox_sanize',
			'transport'  => 'refresh'
		));
		
		$wp_customize->add_control( 'janey_press_enable_related_posts', array(
			'label' => esc_html__( 'Show related posts','janey-press'),
			'section' => 'settings_section',
			'type' => 'checkbox',
		));

		/**
		* Main settings panel > General settings section > Related posts label
		 */

		$wp_customize->add_setting( 'janey_press_related_posts_label', array(
			'default' => esc_html__('You may also like','janey-press'),
			'sanitize_callback' => 'sanitize_text_field',
		));

		$wp_customize->add_control( 'janey_press_related_posts_label' , array(
			'type' => 'text',
			'section' => 'settings_section',
			'label' => esc_html__('Related posts section label','janey-press'),
			'description' => esc_html__( 'Replace the default label','janey-press'),
		));

		/**
		* Typography panel > Logo section > Font option
		*/

		$wp_customize->add_setting( 'janey_press_logo_font_family', array(
			'default' => 'Josefin+Sans:100,100italic,300,300italic,400,400italic,600,600italic,700,700italic',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_logo_font_family' , array(
			'priority' => 9,
			'type' => 'select',
			'section' => 'logo_section',
			'label' => esc_html__('Font','janey-press'),
			'description' => esc_html__('Choose a font for the logo.','janey-press'),
			'choices' => janey_press_google_fonts(),
		));

		/**
		* Typography panel > Logo section > Weight option
		*/

		$wp_customize->add_setting( 'janey_press_logo_font_weight', array(
			'default' => '700',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_logo_font_weight' , array(
			'priority' => 9,
			'type' => 'select',
			'section' => 'logo_section',
			'label' => esc_html__('Weight','janey-press'),
			'description' => esc_html__('Choose a font weight for the logo.','janey-press'),
			'choices'  => array (
				'100' => esc_html__( '100','janey-press'),
				'200' => esc_html__( '200','janey-press'),
				'300' => esc_html__( '300','janey-press'),
				'400' => esc_html__( '400','janey-press'),
				'500' => esc_html__( '500','janey-press'),
				'600' => esc_html__( '600','janey-press'),
				'700' => esc_html__( '700','janey-press'),
				'800' => esc_html__( '800','janey-press'),
				'900' => esc_html__( '900','janey-press'),
			),
		));

		/**
		* Typography panel > Logo section > Site identity font option
		*/

		$wp_customize->add_setting( 'janey_press_site_identity_font_family', array(
			'default' => 'Josefin+Sans:100,100italic,300,300italic,400,400italic,600,600italic,700,700italic',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_site_identity_font_family' , array(
			'priority' => 10,
			'type' => 'select',
			'section' => 'logo_section',
			'label' => esc_html__('Site identity font','janey-press'),
			'description' => esc_html__('Choose a font for the site identity.','janey-press'),
			'choices' => janey_press_google_fonts(),
		));

		/**
		* Typography panel > Logo section > Site identity font weight option
		*/

		$wp_customize->add_setting( 'janey_press_site_identity_font_weight', array(
			'default' => '700',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_site_identity_font_weight' , array(
			'priority' => 10,
			'type' => 'select',
			'section' => 'logo_section',
			'label' => esc_html__('Site identity font weight','janey-press'),
			'description' => esc_html__('Choose a font weight for the site identity.','janey-press'),
			'choices'  => array (
				'100' => esc_html__( '100','janey-press'),
				'200' => esc_html__( '200','janey-press'),
				'300' => esc_html__( '300','janey-press'),
				'400' => esc_html__( '400','janey-press'),
				'500' => esc_html__( '500','janey-press'),
				'600' => esc_html__( '600','janey-press'),
				'700' => esc_html__( '700','janey-press'),
				'800' => esc_html__( '800','janey-press'),
				'900' => esc_html__( '900','janey-press'),
			),
		));

		/**
		* Typography panel > Logo section > Site identity font size option
		*/

		$wp_customize->add_setting( 'janey_press_site_identity_font_size', array(
			'default' => '14px',
			'sanitize_callback' => 'janey_press_font_size_sanize',
		));

		$wp_customize->add_control( 'janey_press_site_identity_font_size' , array(
			'type' => 'text',
			'section' => 'logo_section',
			'label' => esc_html__('Site identity font size','janey-press'),
			'description' => esc_html__( 'Insert a size, for the site identity (For example, 40px) ','janey-press'),
		));

		/**
		* Typography panel > Menu section > Font option
		*/

		$wp_customize->add_setting( 'janey_press_menu_font_family', array(
			'default' => 'Josefin+Sans:100,100italic,300,300italic,400,400italic,600,600italic,700,700italic',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_menu_font_family' , array(
			'priority' => 9,
			'type' => 'select',
			'section' => 'menu_section',
			'label' => esc_html__('Font','janey-press'),
			'description' => esc_html__('Choose a font for the menu.','janey-press'),
			'choices' => janey_press_google_fonts(),
		));

		/**
		* Typography panel > Menu section > Weight option
		*/

		$wp_customize->add_setting( 'janey_press_menu_font_weight', array(
			'default' => '600',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_menu_font_weight' , array(
			'priority' => 9,
			'type' => 'select',
			'section' => 'menu_section',
			'label' => esc_html__('Weight','janey-press'),
			'description' => esc_html__('Choose a font weight for the menu.','janey-press'),
			'choices'  => array (
				'100' => esc_html__( '100','janey-press'),
				'200' => esc_html__( '200','janey-press'),
				'300' => esc_html__( '300','janey-press'),
				'400' => esc_html__( '400','janey-press'),
				'500' => esc_html__( '500','janey-press'),
				'600' => esc_html__( '600','janey-press'),
				'700' => esc_html__( '700','janey-press'),
				'800' => esc_html__( '800','janey-press'),
				'900' => esc_html__( '900','janey-press'),
			),
		));

		/**
		* Typography panel > Headlines section > Font option
		*/

		$wp_customize->add_setting( 'janey_press_headlines_font_family', array(
			'default' => 'Josefin+Sans:100,100italic,300,300italic,400,400italic,600,600italic,700,700italic',
			'sanitize_callback' => 'janey_press_select_sanitize',
		));

		$wp_customize->add_control( 'janey_press_headlines_font_family' , array(
			'priority' => 9,
			'type' => 'select',
			'section' => 'headlines_section',
			'label' => esc_html__('Headlines font','janey-press'),
			'description' => esc_html__('Choose a font for the headlines.','janey-press'),
			'choices' => janey_press_google_fonts(),
		));

		function janey_press_select_sanitize ($value, $setting) {

			global $wp_customize;

			$control = $wp_customize->get_control( $setting->id );

			if ( array_key_exists( $value, $control->choices ) ) {

				return $value;

			} else {

				return $setting->default;

			}

		}

		function janey_press_checkbox_sanize ($input) {

			return $input ? true : false;

		}

		function janey_press_font_size_sanize ($value, $setting) {

			global $wp_customize;

			$getSetting = $wp_customize->get_setting($setting->id);
			$newValue = absint(str_replace('px', '', $value));
			return ($newValue == 0 ) ? $getSetting->default : $newValue . 'px';

		}

		function janey_press_limit_sanitize($value, $setting) {

			global $wp_customize;

			$getSetting = $wp_customize->get_setting($setting->id);
			$newValue = ($value <= 0) ? $getSetting->default : absint($value);
			return $newValue;

		}

	}

	add_action( 'customize_register', 'janey_press_customize_register', 11 );

}

/*-----------------------------------------------------------------------------------*/
/* Post class */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('janey_press_post_class')) {

	function janey_press_post_class($classes) {

		if (
			(
				( is_home() ) ||
				( janey_is_archive() ) ||
				( is_search() )
	
			) &&
			( janey_setting('janey_has_post_placeholder', true) == true)
		) :

			$classes[] = 'has-post-placeholder';

		endif;

		return $classes;

	}

	add_filter('post_class', 'janey_press_post_class');

}

/*-----------------------------------------------------------------------------------*/
/* Get skin color */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('janey_press_get_skin_color')) {

	function janey_press_get_skin_color($type = 'default') {

		$current_skin = get_theme_mod('janey_skin', 'orange');

		$colors = array(
			'cyan' => array(
				'default' => '#48c2ae',
				'hover' => '#3aa694',
			),
			'orange' => array(
				'default' => '#ff6644',
				'hover' => '#d14a2b',
			),
			'blue' => array(
				'default' => '#0090ff',
				'hover' => '#0074cc'
			),
			'red' => array(
				'default' => '#b93333',
				'hover' => '#872424',
			),
			'pink' => array(
				'default' => '#f97c8a',
				'hover' => '#f07381',
			),
			'purple' => array(
				'default' => '#c71c77',
				'hover' => '#941559',
			),
			'yellow' => array(
				'default' => '#f0b70c',
				'hover' => '#bd9009',
			),
			'green' => array(
				'default' => '#84ad37',
				'hover' => '#5d7a27',
			),
			'black' => array(
				'default' => '#333333',
				'hover' => '#8d8d8d'
			),
			'clean-yellow' => array(
				'default' => '#ffdd59',
				'hover' => '#ffd32a',
			),
			'clean-red' => array(
				'default' => '#ef5777',
				'hover' => '#f53b57',
			),
			'clean-turquoise' => array(
				'default' => '#34e7e4',
				'hover' => '#00d8d6',
			),
			'clean-green' => array(
				'default' => '#0be881',
				'hover' => '#05c46b',
			),
			'clean-blue' => array(
				'default' => '#18dcff',
				'hover' => '#17c0eb',
			),
			'clean-pink' => array(
				'default' => '#fd79a8',
				'hover' => '#e84393',
			)
		);

		$color = ( array_key_exists($current_skin, $colors) ) ? $colors[$current_skin][$type] : '#d14a2b';
		return $color;

	}

}

?>
