<?php // phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- File is loaded within a function, variables are not global.
defined( 'ABSPATH' ) || die();

$action_tab = '';
if ( isset( $_GET['action'] ) && ! empty( $_GET['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Used to determine the active tab, not saved to the database.
	$action_tab = sanitize_text_field( wp_unslash( $_GET['action'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Used to determine the active tab, not saved to the database.
}

if ( 'view' === $action_tab ) {
	require_once STCFQ_PLUGIN_DIR_PATH . 'admin/inc/message/view.php';
} else {
	require_once STCFQ_PLUGIN_DIR_PATH . 'admin/inc/message/index.php';
}
