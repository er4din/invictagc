<?php 

if (!function_exists('janey_press_css_custom')) {

	function janey_press_css_custom() { 

		$css = ':root { 
			--janey-press-child-default-color: ' . esc_html(janey_press_get_skin_color('default')) . ';
			--janey-press-child-hover-color: ' . esc_html(janey_press_get_skin_color('hover')) . ';
		}';

		/* =================== BEGIN HEADER TEXT COLOR =================== */

		if (janey_setting('janey_logo_text_color')) :

			$css .= "#logo a { color:".esc_html(janey_setting('janey_logo_text_color'))."; }";
			$css .= "nav.header-menu ul li a { color:".esc_html(janey_setting('janey_logo_text_color'))."; }";
			$css .= "#logo a span { color:".esc_html(janey_setting('janey_logo_text_color'))."; }";

		endif;

		if (janey_setting('janey_hamburger_menu_icon_color')) 
			$css .= ".open-modal-sidebar i { color:".esc_html(janey_setting('janey_hamburger_menu_icon_color'))."; }";

		/* =================== END HEADER IMAGE =================== */

		/* =================== BEGIN LOGO STYLE =================== */

		if (janey_setting('janey_press_logo_font_family')) 
			$css .= "#logo a { font-family:".esc_html(janey_press_google_font_name('janey_press_logo_font_family'))."; }";

		if (janey_setting('janey_press_logo_font_weight'))
			$css .= "#logo a { font-weight:" . esc_html(janey_setting('janey_press_logo_font_weight')) . ";}"; 

		if (janey_setting('janey_logo_font_size')) 
			$css .= "#logo a { font-size:".esc_html(janey_setting('janey_logo_font_size'))."; }";

		if (janey_setting('janey_logo_text_transform'))
			$css .= "#logo a { text-transform:" . esc_html(janey_setting('janey_logo_text_transform')) . ";}"; 

		if (janey_setting('janey_press_site_identity_font_family')) 
			$css .= "#logo a span { font-family:".esc_html(janey_press_google_font_name('janey_press_site_identity_font_family'))."; }";

		if (janey_setting('janey_press_site_identity_font_weight'))
			$css .= "#logo a span { font-weight:" . esc_html(janey_setting('janey_press_site_identity_font_weight')) . ";}"; 

		if (janey_setting('janey_press_site_identity_font_size'))
			$css .= "#logo a span { font-size:" . esc_html(janey_setting('janey_press_site_identity_font_size')) . ";}"; 
			
		/* =================== END LOGO STYLE =================== */
		
		/* =================== START MAIN MENU STYLE =================== */

		if (janey_setting('janey_press_menu_font_family')) 
			$css .= "nav.header-menu ul li a, nav.header-menu ul ul li a { font-family:".esc_html(janey_press_google_font_name('janey_press_menu_font_family'))."; }";

		if (janey_setting('janey_press_menu_font_weight'))
			$css .= "nav.header-menu ul li a { font-weight:" . esc_html(janey_setting('janey_press_menu_font_weight')) . ";}";


		/* =================== END MAIN MENU STYLE =================== */

		/* =================== BEGIN MOBILE MENU STYLE =================== */
		
		if ( janey_setting('janey_menu_font_size') )  : 
			$css .= "nav#mobilemenu ul li a { font-size:".esc_html(janey_setting('janey_menu_font_size'))."; }"; 
			$css .= "nav#mobilemenu ul ul li a { font-size:" . ( str_replace("px", "", esc_html(janey_setting('janey_menu_font_size'))) - 2 ) . "px;}"; 
		endif;
		
		if (janey_setting('janey_menu_font_weight'))
			$css .= "nav#mobilemenu ul li a { font-weight:" . esc_html(janey_setting('janey_menu_font_weight')) . ";}"; 
		
		if (janey_setting('janey_menu_text_transform'))
			$css .= "nav#mobilemenu ul li a { text-transform:" . esc_html(janey_setting('janey_menu_text_transform')) . ";}"; 
		
		/* =================== END MOBILE MENU =================== */
		
		wp_add_inline_style( 'janey-press-style', $css );
		
	}

	add_action('wp_enqueue_scripts', 'janey_press_css_custom', 9999);

}

?>