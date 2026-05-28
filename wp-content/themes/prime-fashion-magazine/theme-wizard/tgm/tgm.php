<?php require get_template_directory() . '/theme-wizard/tgm/class-tgm-plugin-activation.php';

function prime_fashion_magazine_register_recommended_plugins() {
	$plugins = array(
		array(
			'name'             => __( 'Posts Like Dislike', 'prime-fashion-magazine' ),
			'slug'             => 'posts-like-dislike',
			'required'         => false,
			'force_activation' => false,
		),
		array(
			'name'             => __( 'Classic Widgets', 'prime-fashion-magazine' ),
			'slug'             => 'classic-widgets',
			'source'           => '',
			'required'         => false,
			'force_activation' => false,
		),
	);
	$config = array();
	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'prime_fashion_magazine_register_recommended_plugins' );