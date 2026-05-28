<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Editor style service.
 *
 * @package PressBook
 */

namespace PressBook;

/**
 * Enqueue editor style.
 */
class EditorStyle implements Serviceable {
	/**
	 * Register service features.
	 */
	public function register() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_style' ) );
	}

	/**
	 * Enqueue block editor style.
	 */
	public function enqueue_block_editor_style() {
		wp_enqueue_style( 'pressbook-block-editor-style', get_template_directory_uri() . '/inc/block-editor-style.css', array(), PRESSBOOK_VERSION );
		wp_style_add_data( 'pressbook-block-editor-style', 'rtl', 'replace' );
	}
}
