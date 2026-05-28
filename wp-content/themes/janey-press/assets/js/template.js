/*
 * jQuery theme functions
 * https://www.themeinprogress.com
 *
 * Copyright 2024, ThemeinProgress
 * Licensed under MIT license
 * https://opensource.org/licenses/mit-license.php
 */

jQuery.noConflict()(function($){

	"use strict";

/* ===============================================
   Newsticker
   =============================================== */

	$('.news-ticker-marquee-init').marquee({
		duration: 150000,
		pauseOnHover : true,
		startVisible: true,
		delayBeforeStart: 0,
		duplicated:true
	});

/* ===============================================
   Recent posts horizontal slideshow
   ============================================= */

	$('.horizontal-recent-posts-slideshow').not('.slick-initialized').each(function(){

		var $slider = $(this);
		var slider_transition = $slider.attr('data-transition') === 'fade';

		$slider.slick({
			centerMode: false,
			slidesToShow: 1,
			adaptiveHeight:false,
			fade: slider_transition,
			nextArrow: '<div class="next-arrow"><span class="dashicons dashicons-arrow-right-alt2"></span></div>',
			prevArrow: '<div class="prev-arrow"><span class="dashicons dashicons-arrow-left-alt2"></span></div>',
			appendArrows: $slider.closest('.horizontal-recent-posts-section').find('.horizontal-recent-posts-navigation'),
		});

	});

/* ===============================================
   Recent posts vertical carousel
   ============================================= */

	$('.vertical-recent-posts-section').each(function(){

		var $container = $(this);
		var vertical_recent_posts_slideshow = $container.children('.vertical-recent-posts-slideshow');
		var n_items = vertical_recent_posts_slideshow.hasClass('vertical-overlay-carousel') ? 4 : 6;
		var n_items_laptop = vertical_recent_posts_slideshow.hasClass('vertical-overlay-carousel') ? 4 : 6;
		var n_items_mobile = vertical_recent_posts_slideshow.hasClass('vertical-overlay-carousel') ? 2 : 3;

		vertical_recent_posts_slideshow.not('.slick-initialized').slick({
			
			slidesToShow: n_items,
			slidesToScroll: 1,
			fade: false,
			vertical: true,
			infinite: false,
			verticalSwiping: true,
			nextArrow: '<div class="next-arrow"><span class="dashicons dashicons-arrow-up-alt2"></span></div>',
			prevArrow: '<div class="prev-arrow"><span class="dashicons dashicons-arrow-down-alt2"></span></div>',
			appendArrows: $container.find('.vertical-recent-posts-navigation'),
			responsive: [
				{
					breakpoint: 1170,
					settings: {
						slidesToShow: n_items_laptop,
					}
				},
				{
					breakpoint: 993,
					settings: {
						slidesToShow: n_items_mobile,
						slidesToScroll: 1,
					}
				},
				{
					breakpoint: 601,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
					}
				}

			]
	
		});
		
	});

});
