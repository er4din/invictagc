<?php

$prime_fashion_magazine_custom_css = "";

$prime_fashion_magazine_primary_color = get_theme_mod('prime_fashion_magazine_primary_color');

/*------------------ Primary Global Color -----------*/

if ($prime_fashion_magazine_primary_color) {
  $prime_fashion_magazine_custom_css .= ':root {';
  $prime_fashion_magazine_custom_css .= '--primary-color: ' . esc_attr($prime_fashion_magazine_primary_color) . ' !important;';
  $prime_fashion_magazine_custom_css .= '} ';
}

/*-------------------- Container Width-------------------*/

$prime_fashion_magazine_theme_width = get_theme_mod( 'prime_fashion_magazine_theme_width','full-width');

if($prime_fashion_magazine_theme_width == 'full-width'){
$prime_fashion_magazine_custom_css .='body{';
	$prime_fashion_magazine_custom_css .='max-width: 100% !important;';
$prime_fashion_magazine_custom_css .='}';
$prime_fashion_magazine_custom_css .='.sticky-head{';
$prime_fashion_magazine_custom_css .='left: 0;';
$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_theme_width == 'container'){
$prime_fashion_magazine_custom_css .='body{';
	$prime_fashion_magazine_custom_css .='width: 80% !important; padding-right: 15px; padding-left: 15px;  margin-right: auto !important; margin-left: auto !important;';
$prime_fashion_magazine_custom_css .='}';
$prime_fashion_magazine_custom_css .='.sticky-head{';
$prime_fashion_magazine_custom_css .='left: 0;';
$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_theme_width == 'container-fluid'){
$prime_fashion_magazine_custom_css .='body{';
	$prime_fashion_magazine_custom_css .='width: 95% !important;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;';
$prime_fashion_magazine_custom_css .='}';
$prime_fashion_magazine_custom_css .='.sticky-head{';
$prime_fashion_magazine_custom_css .='left: 0;';
$prime_fashion_magazine_custom_css .='}';
}

/*-------------------- Single Post Alignment-------------------*/

$prime_fashion_magazine_single_post_align = get_theme_mod( 'prime_fashion_magazine_single_post_align','left-align');

if($prime_fashion_magazine_single_post_align == 'left-align'){
$prime_fashion_magazine_custom_css .='body:not(.hide-post-meta) .post{';
	$prime_fashion_magazine_custom_css .='text-align: left';
$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_single_post_align == 'right-align'){
$prime_fashion_magazine_custom_css .='body:not(.hide-post-meta) .post{';
	$prime_fashion_magazine_custom_css .='text-align: right';
$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_single_post_align == 'center-align'){
$prime_fashion_magazine_custom_css .='body:not(.hide-post-meta) .post{';
	$prime_fashion_magazine_custom_css .='text-align: center';
$prime_fashion_magazine_custom_css .='}';
}

/*-------------------- Scroll Top Alignment-------------------*/

$prime_fashion_magazine_scroll_top_alignment = get_theme_mod( 'prime_fashion_magazine_scroll_top_alignment','right-align');

if($prime_fashion_magazine_scroll_top_alignment == 'right-align'){
$prime_fashion_magazine_custom_css .='#button{';
	$prime_fashion_magazine_custom_css .='right: 5%;';
$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_scroll_top_alignment == 'center-align'){
$prime_fashion_magazine_custom_css .='#button{';
	$prime_fashion_magazine_custom_css .='right:0; left:0; margin: 0 auto;';
$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_scroll_top_alignment == 'left-align'){
$prime_fashion_magazine_custom_css .='#button{';
	$prime_fashion_magazine_custom_css .='left: 5%;';
$prime_fashion_magazine_custom_css .='}';
}

/*-------------------- Archive Page Pagination Alignment-------------------*/

$prime_fashion_magazine_archive_pagination_alignment = get_theme_mod( 'prime_fashion_magazine_archive_pagination_alignment','left-align');

if($prime_fashion_magazine_archive_pagination_alignment == 'right-align'){
$prime_fashion_magazine_custom_css .='.pagination{';
	$prime_fashion_magazine_custom_css .='justify-content: end;';
$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_archive_pagination_alignment == 'center-align'){
$prime_fashion_magazine_custom_css .='.pagination{';
	$prime_fashion_magazine_custom_css .='justify-content: center;';
$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_archive_pagination_alignment == 'left-align'){
$prime_fashion_magazine_custom_css .='.pagination{';
	$prime_fashion_magazine_custom_css .='justify-content: start;';
$prime_fashion_magazine_custom_css .='}';
}

/*-------------------- Scroll Top Responsive-------------------*/

$prime_fashion_magazine_resp_scroll_top = get_theme_mod( 'prime_fashion_magazine_resp_scroll_top',true);
if($prime_fashion_magazine_resp_scroll_top == true && get_theme_mod( 'prime_fashion_magazine_scroll_to_top',true) != true){
	$prime_fashion_magazine_custom_css .='#button.show{';
		$prime_fashion_magazine_custom_css .='visibility:hidden !important;';
	$prime_fashion_magazine_custom_css .='} ';
}
if($prime_fashion_magazine_resp_scroll_top == true){
	$prime_fashion_magazine_custom_css .='@media screen and (max-width:575px) {';
	$prime_fashion_magazine_custom_css .='#button.show{';
		$prime_fashion_magazine_custom_css .='visibility:visible !important;';
	$prime_fashion_magazine_custom_css .='} }';
}else if($prime_fashion_magazine_resp_scroll_top == false){
	$prime_fashion_magazine_custom_css .='@media screen and (max-width:575px){';
	$prime_fashion_magazine_custom_css .='#button.show{';
		$prime_fashion_magazine_custom_css .='visibility:hidden !important;';
	$prime_fashion_magazine_custom_css .='} }';
}

/*-------------------- Preloader Responsive-------------------*/

	$prime_fashion_magazine_resp_loader = get_theme_mod('prime_fashion_magazine_resp_loader',false);
	if($prime_fashion_magazine_resp_loader == true && get_theme_mod('prime_fashion_magazine_header_preloader',false) == false){
		$prime_fashion_magazine_custom_css .='@media screen and (min-width:575px){
			.preloader{';
			$prime_fashion_magazine_custom_css .='display:none !important;';
		$prime_fashion_magazine_custom_css .='} }';
	}

	if($prime_fashion_magazine_resp_loader == false){
		$prime_fashion_magazine_custom_css .='@media screen and (max-width:575px){
			.preloader{';
			$prime_fashion_magazine_custom_css .='display:none !important;';
		$prime_fashion_magazine_custom_css .='} }';
	}

// Scroll to top button shape 

$prime_fashion_magazine_scroll_border_radius = get_theme_mod( 'prime_fashion_magazine_scroll_to_top_radius','curved-box');
if($prime_fashion_magazine_scroll_border_radius == 'box'){
	$prime_fashion_magazine_custom_css .='#button{';
		$prime_fashion_magazine_custom_css .='border-radius: 0px;';
	$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_scroll_border_radius == 'curved-box'){
	$prime_fashion_magazine_custom_css .='#button{';
		$prime_fashion_magazine_custom_css .='border-radius: 4px;';
	$prime_fashion_magazine_custom_css .='}';
}
else if($prime_fashion_magazine_scroll_border_radius == 'circle'){
	$prime_fashion_magazine_custom_css .='#button{';
		$prime_fashion_magazine_custom_css .='border-radius: 50%;';
	$prime_fashion_magazine_custom_css .='}';
}

// Footer Background Image Attachment 

$prime_fashion_magazine_footer_attachment = get_theme_mod( 'prime_fashion_magazine_background_attachment','scroll');
if($prime_fashion_magazine_footer_attachment == 'fixed'){
	$prime_fashion_magazine_custom_css .='.site-footer{';
		$prime_fashion_magazine_custom_css .='background-attachment: fixed;';
	$prime_fashion_magazine_custom_css .='}';
}elseif ($prime_fashion_magazine_footer_attachment == 'scroll'){
	$prime_fashion_magazine_custom_css .='.site-footer{';
		$prime_fashion_magazine_custom_css .='background-attachment: scroll;';
	$prime_fashion_magazine_custom_css .='}';
}

// Menu Hover Style	

$prime_fashion_magazine_menus_item = get_theme_mod( 'prime_fashion_magazine_menus_style','None');
if($prime_fashion_magazine_menus_item == 'None'){
	$prime_fashion_magazine_custom_css .='#site-navigation .menu ul li a:hover, .main-navigation .menu li a:hover{';
		$prime_fashion_magazine_custom_css .='';
	$prime_fashion_magazine_custom_css .='}';
}else if($prime_fashion_magazine_menus_item == 'Zoom In'){
	$prime_fashion_magazine_custom_css .='#site-navigation .menu ul li a:hover, .main-navigation .menu li a:hover{';
	$prime_fashion_magazine_custom_css .= 'transition: all 0.3s ease-in-out !important; transform: scale(1.2) !important;';
	$prime_fashion_magazine_custom_css .= '}';
	
	$prime_fashion_magazine_custom_css .= '.main-navigation ul ul li a:hover {';
	$prime_fashion_magazine_custom_css .= 'margin-left: 20px;';
	$prime_fashion_magazine_custom_css .= '}';
}	