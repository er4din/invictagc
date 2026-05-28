<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Editor service.
 *
 * @package PressBook
 */

namespace PressBook;

/**
 * Editor setup.
 */
class Editor implements Serviceable {
	/**
	 * Register service features.
	 */
	public function register() {
		add_action( 'after_setup_theme', array( $this, 'support_editor_styles' ) );

		if ( version_compare( $GLOBALS['wp_version'], '6.2.99', '<=' ) ) {
			add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		} else {
			add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_assets' ) );
		}
	}

	/**
	 * Support editor styles.
	 */
	public function support_editor_styles() {
		// Enqueue editor styles.
		add_editor_style( 'editor-style.css' );
	}

	/**
	 * Enqueue editor assets.
	 */
	public function enqueue_editor_assets() {
		if ( function_exists( '\get_current_screen' ) ) {
			$current_screen = get_current_screen();
			if ( $current_screen && in_array( $current_screen->id, array( 'widgets', 'nav-menus' ), true ) ) {
				return;
			}

			$this->enqueue_assets();
		}
	}

	/**
	 * Enqueue block assets.
	 */
	public function enqueue_block_assets() {
		if ( $this->is_block_screen() ) {
			$this->enqueue_assets();
		}
	}

	/**
	 * Enqueue assets.
	 */
	public function enqueue_assets() {
		// Enqueue fonts.
		wp_enqueue_style( 'pressbook-editor-fonts', Scripts::fonts_url(), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion

		// Add inline style for fonts in the block editor.
		$this->load_editor_fonts_css();

		// Add output of customizer settings as inline style.
		wp_add_inline_style( 'pressbook-editor-fonts', CSSRules::output_editor() );
	}

	/**
	 * Check if block editor screen, but not widgets or nav-menus screen.
	 *
	 * @return bool
	 */
	public function is_block_screen() {
		if ( function_exists( '\get_current_screen' ) ) {
			$current_screen = get_current_screen();
			if ( $current_screen ) {
				if ( \in_array( $current_screen->id, array( 'widgets', 'nav-menus' ), true ) ) {
					return false;
				}

				if ( \method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Add inline style for fonts in the block editor.
	 */
	public function load_editor_fonts_css() {
		$fonts_css = '';

		/* translators: If there are characters in your language that are not supported by Inter, translate this to 'off'. Do not translate into your own language. */
		$inter = _x( 'on', 'Inter font (in the editor): on or off', 'pressbook' );
		if ( 'off' !== $inter ) {
			$fonts_css .= ( '.editor-styles-wrapper.editor-styles-wrapper{font-family:\'Inter\', Arial, Helvetica, sans-serif;}' );
		}

		/* translators: If there are characters in your language that are not supported by Lato, translate this to 'off'. Do not translate into your own language. */
		$lato = _x( 'on', 'Lato font (in the editor): on or off', 'pressbook' );
		if ( 'off' !== $lato ) {
			$fonts_css .= ( '.editor-styles-wrapper .editor-post-title__input,.editor-styles-wrapper .editor-post-title .editor-post-title__input,.editor-styles-wrapper h1,.editor-styles-wrapper h2,.editor-styles-wrapper h3,.editor-styles-wrapper h4,.editor-styles-wrapper h5,.editor-styles-wrapper h6{font-family:\'Lato\', Helvetica, Arial, sans-serif;}' );
		}

		if ( '' !== $fonts_css ) {
			wp_add_inline_style( 'pressbook-editor-fonts', $fonts_css );
		}
	}

	/**
	 * Styles keys for the editor CSS output.
	 *
	 * @return array
	 */
	public static function styles_keys() {
		return apply_filters(
			'pressbook_default_editor_styles_keys',
			array( 'heading_font_wgt' )
		);
	}

	/**
	 * Heading Font Weight.
	 *
	 * @param string $value Color value.
	 * @return array
	 */
	public static function heading_font_wgt( $value ) {
		return array(
			'.editor-styles-wrapper .editor-post-title__input,.editor-styles-wrapper .editor-post-title .editor-post-title__input,.editor-styles-wrapper h1,.editor-styles-wrapper h2,.editor-styles-wrapper h3,.editor-styles-wrapper h4,.editor-styles-wrapper h5,.editor-styles-wrapper h6' => array(
				'font-weight' => array(
					'value' => absint( $value ),
				),
			),
		);
	}
}
