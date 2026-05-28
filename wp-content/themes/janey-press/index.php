<?php 

	get_header();
	
	get_sidebar('top');
	get_template_part('template-parts/news','ticker');
	get_template_part('template-parts/recent', 'posts'); 
	get_template_part('template-parts/trending','posts'); 
	get_sidebar('header');
	get_template_part('core/templates/featured', 'links'); 
	
	if ( 
		!janey_setting('janey_home_layout') || 
		strstr(janey_setting('janey_home_layout'), 'sidebar' )
	) {
				
		get_template_part('layouts/home', 'sidebar'); 

	} else if ( janey_setting('janey_home_layout') == 'col-md-4' ) { 

		get_template_part('layouts/home', 'masonry'); 

	} else { 
		
		get_template_part('layouts/home', 'classic');
			
	}

	get_footer(); 
	
?>
