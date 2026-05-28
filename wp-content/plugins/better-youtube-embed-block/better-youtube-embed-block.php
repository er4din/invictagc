<?php
/**
 * Plugin Name:       Better YouTube Embed Block
 * Description:       Embed YouTube videos without slowing down your site.
 * Requires at least: 6.9
 * Requires PHP:      7.0
 * Version:           1.1.5
 * Author:            Phi Phan
 * Author URI:        https://boldblocks.net
 * Plugin URI:        https://boldblocks.net?utm_source=BYEB&utm_campaign=visit+site&utm_medium=link&utm_content=Plugin+URI
 * License:           GPL-3.0
 *
 * @package BoldBlocks
 * @copyright Copyright(c) 2022, Phi Phan
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function better_youtube_embed_block_init() {
	register_block_type(
		__DIR__ . '/build',
		[
			'render_callback' => function ( $attributes, $content, $block_instance ) {
				$url = better_youtube_embed_block_get_binding_value( 'url', $attributes, $block_instance );
				if ( is_null( $url ) ) {
					return $content;
				}

				$caption = better_youtube_embed_block_get_binding_value( 'caption', $attributes, $block_instance );
				if ( is_null( $caption ) ) {
					$block_reader = new \WP_HTML_Tag_Processor( $content );
					if ( $block_reader->next_tag( 'figcaption' ) ) {
						$caption = '';
						while ( $block_reader->next_token() ) {
							if ( '#text' === $block_reader->get_token_name() ) {
								$caption .= $block_reader->get_modifiable_text();
							}
						}
					}
				}

				return better_youtube_embed_block_render_block(
					array_merge(
						$attributes,
						[
							'url'     => $url,
							'caption' => $caption,
						]
					)
				);
			},
		]
	);
}
add_action( 'init', 'better_youtube_embed_block_init' );

/**
 * Get binding value for an attribute
 *
 * @param string   $attribute_name
 * @param array    $attributes
 * @param WP_Block $block_instance
 * @return mixed|null
 */
function better_youtube_embed_block_get_binding_value( $attribute_name, $attributes, $block_instance ) {
	$block_binding = $attributes['metadata']['bindings'][ $attribute_name ] ?? [];

	if ( ! $block_binding || ! isset( $block_binding['source'] ) || ! is_string( $block_binding['source'] ) ) {
		return null;
	}

	$block_binding_source = get_block_bindings_source( $block_binding['source'] );
	if ( null === $block_binding_source ) {
		return null;
	}

	$source_args = ! empty( $block_binding['args'] ) && is_array( $block_binding['args'] ) ? $block_binding['args'] : [];
	return $block_binding_source->get_value( $source_args, $block_instance, $attribute_name );
}

/**
 * Allow binding URL and caption from a custom field
 */
add_filter(
	'block_bindings_supported_attributes_boldblocks/youtube-block',
	function ( $attributes ) {
		$attributes = [ 'url', 'caption' ];
		return $attributes;
	}
);

/**
 * The API to render a YouTube video URL as a better youtube embed block
 *
 * @param array   $args {
 *   @param string  $url: YouTube video URL
 *   @param string  $caption: The video caption
 *   @param boolean $caption_kses: Whether to allow inline tags (a, em, strong) in the caption
 *   @param boolean $isMaxResThumbnail: Load high-resolution image or not
 *   @param string  $aspectRatio: 1, 2, 4/3, 9/16, etc.
 *   @param string  $customThumbnail: The URL of a custom image
 *   @param array   $settings: loop, rel, videoids or playlistId
 *   @param boolean $echo
 * }
 * @return string
 */
function better_youtube_embed_block_render_block( $args ) {
	$output = '';
	$args   = wp_parse_args(
		$args,
		[
			'url'               => '',
			'caption'           => '',
			'caption_kses'      => false,
			'isMaxResThumbnail' => false,
			'thumbnailFormat'   => 'jpg',
			'aspectRatio'       => '',
			'customThumbnail'   => '',
			'settings'          => [],
			'echo'              => false,
		]
	);

	// Allow changing the args.
	$args = apply_filters( 'better_youtube_embed_block_render_block_args', $args );

	$url      = $args['url'] ?? '';
	$video_id = '';
	if ( $url ) {
		$regex = '/(youtu.*be.*)\/(watch\?v=|embed\/|v|shorts|)(.*?((?=[&#?])|$))/';
		if ( preg_match( $regex, $url, $matches ) ) {
			$video_id = $matches[3];
		}
	}

	if ( $video_id ) {
		$video_id      = esc_attr( $video_id );
		$image_name    = $args['isMaxResThumbnail'] ? 'maxresdefault' : 'hqdefault';
		$caption       = $args['caption'] ? '<figcaption class="yb-caption">' . ( $args['caption_kses'] ? wp_kses(
			$args['caption'],
			[
				'em'     => [],
				'strong' => [],
				'a'      => [
					'href'   => true,
					'target' => true,
					'rel'    => true,
					'title'  => true,
					'class'  => true,
				],
			]
		) : esc_html( $args['caption'] ) ) . '</figcaption>' : '';
		$aspect_ratio  = $args['aspectRatio'];
		$folder        = 'webp' === $args['thumbnailFormat'] ? 'vi_webp' : 'vi';
		$extension     = 'webp' === $args['thumbnailFormat'] ? 'webp' : 'jpg';
		$thumbnail_url = $args['customThumbnail'] ? $args['customThumbnail'] : 'https://img.youtube.com/' . $folder . '/' . $video_id . '/' . $image_name . '.' . $extension;
		$style         = '';
		if ( $aspect_ratio ) {
			if ( preg_match( '/(\d+)(\/(\d+))*/', $aspect_ratio, $aspect_ratio_matches ) ) {
				$w = absint( $aspect_ratio_matches[1] );
				if ( $w ) {
					if ( absint( $aspect_ratio_matches[3] ?? 0 ) ) {
						$h          = absint( $aspect_ratio_matches[3] );
						$percentage = round( ( 1 / ( $w / $h ) ) * 100, 2 );
					} else {
						$percentage = round( ( 1 / $w ) * 100, 2 );
					}

					if ( $percentage ) {
						$style = ' style="--byeb--aspect-ratio:' . $percentage . '%;"';
					}
				}
			}
		}

		$settings = $args['settings'];
		$params   = [];
		if ( $settings['multipleMode'] ?? false ) {
			if ( 'multiple' === $settings['multipleMode'] && ( $settings['videoIds'] ?? false ) ) {
				$params['playlist'] = "{$video_id},{$settings['videoIds']}";
			} elseif ( 'playlist' === $settings['multipleMode'] && ( $settings['playlistId'] ?? false ) ) {
				$params['list'] = $settings['playlistId'];
			}
		}

		if ( 1 === ( $settings['loop'] ?? '' ) ) {
			$params['loop'] = 1;
			if ( ! ( $params['playlist'] ?? false ) && ! ( $params['list'] ?? false ) ) {
				$params['playlist'] = $video_id;
			}
		}

		if ( 0 === ( $settings['rel'] ?? '' ) ) {
			$params['rel'] = 0;
		}

		$data_params = $params ? ' data-params="' . esc_attr( wp_json_encode( $params ) ) . '"' : '';

		$output = '<figure class="wp-block-boldblocks-youtube-block"' . $style . '><div id="yb-video-' . $video_id . '" class="yb-player" data-video-id="' . $video_id . '" data-title="Play"' . $data_params . ' style="background-image:url(' . esc_attr( $thumbnail_url ) . ')"><button type="button" class="yb-btn-play"><span class="visually-hidden">Play</span></button></div>' . $caption . '</figure>';

		$block_instance = [
			'blockName'    => 'boldblocks/youtube-block',
			'attrs'        => [
				'url'               => $url,
				'isMaxResThumbnail' => intval( $args['isMaxResThumbnail'] ),
			],
			'innerHTML'    => $output,
			'innerContent' => [ $output ],
		];

		$output = ( new WP_Block( $block_instance ) )->render();
	}

	if ( $args['echo'] ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Force render the core/embed block as a better youtube embed block
 */
add_filter(
	'render_block_core/embed',
	function ( $block_content, $block ) {
		if ( 'youtube' !== ( $block['attrs']['providerNameSlug'] ?? '' ) ) {
			return $block_content;
		}

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		if ( ! apply_filters( 'byeb_speed_up_youtube_videos', defined( 'BYEB_SPEED_UP_YOUTUBE_VIDEOS' ) && BYEB_SPEED_UP_YOUTUBE_VIDEOS ) ) {
			return $block_content;
		}

		// Get the url.
		$url = $block['attrs']['url'] ?? '';
		if ( ! $url ) {
			return $block_content;
		}

		// Get the caption.
		$block_reader = new \WP_HTML_Tag_Processor( $block_content );

		$caption = '';
		if ( $block_reader->next_tag( 'figcaption' ) ) {
			while ( $block_reader->next_token() ) {
				if ( '#text' === $block_reader->get_token_name() ) {
					$caption .= $block_reader->get_modifiable_text();
				}
			}
		}

		return better_youtube_embed_block_render_block(
			[
				'url'     => $url,
				'caption' => $caption,
			]
		);
	},
	1000,
	2
);

/**
 * Allow once click to play video on unsupported browsers
 */
if ( defined( 'BYEB_FORCE_IFRAME_ON_UNSUPPORTED_BROWSERS' ) && BYEB_FORCE_IFRAME_ON_UNSUPPORTED_BROWSERS ) {
	add_filter(
		'render_block_boldblocks/youtube-block',
		function ( $block_content ) {
			$block_reader = new \WP_HTML_Tag_Processor( $block_content );
			if ( $block_reader->next_tag() ) {
				$block_reader->add_class( 'ifr-unsupported' );
			}
			return $block_reader->get_updated_html();
		},
		10,
	);
}
