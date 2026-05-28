<?php
/**
 * Default theme options.
 *
 * @package News Host
 */

if (!function_exists('news_host_get_default_theme_options')):

/**
 * Get default theme options
 *
 * @since 1.0.0
 *
 * @return array Default theme options.
 */
function news_host_get_default_theme_options() {

    $defaults = array();

    $defaults['select_editor_news_category'] = 0;
    $defaults['banner_left_advertisement_section_url ']='#';


	return $defaults;

}
endif;