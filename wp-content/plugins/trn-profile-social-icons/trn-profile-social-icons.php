<?php
/**
 * Tournamatch - Profile Social Icons
 *
 * @package     trn-profile-social-icons
 * @author      Tournamatch
 * @copyright   2023 MessyHair, LLC
 *
 * @wordpress-plugin
 * Plugin Name: Tournamatch - Profile Social Icons
 * Plugin URI: https://www.tournamatch.com/add-ons/trn-profile-social-icons
 * Description: Extend Tournamatch with the ability to add social icons to player and team profiles.
 * Version: 4.4.2
 * Author: Tournamatch
 * Author URI: https://www.tournamatch.com
 * Text Domain: trn-profile-social-icons
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

define( 'TRN_PROFILE_SOCIAL_ICONS_VERSION', '4.4.2' );
define( 'TRN_PROFILE_SOCIAL_ICONS_PATH', plugin_dir_path( __FILE__ ) );

register_activation_hook(
	__FILE__,
	function() {
		$dependencies = array(
			'tournamatch/tournamatch.php' => '4.4.0',
		);

		if ( function_exists( 'trn_verify_plugin_dependencies' ) ) {
			trn_verify_plugin_dependencies( 'Tournamatch - Profile Social Icons', $dependencies );
		} else {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error, WordPress.PHP.NoSilencedErrors.Discouraged
			@trigger_error( esc_html__( 'Please update the Tournamatch plugin before activating.', 'trn-profile-social-icons' ), E_USER_ERROR );
		}

		// Modify the players and teams table.
		global $wpdb;

		if ( ! trn_table_column_exists( $wpdb->prefix . 'trn_players_profiles', 'psi_icon_fields' ) ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
			$wpdb->query( "ALTER TABLE `{$wpdb->prefix}trn_players_profiles` ADD `psi_icon_fields` text NULL DEFAULT NULL AFTER `avatar`;" );
		}
		if ( ! trn_table_column_exists( $wpdb->prefix . 'trn_teams', 'psi_icon_fields' ) ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
			$wpdb->query( "ALTER TABLE `{$wpdb->prefix}trn_teams` ADD `psi_icon_fields` text NULL DEFAULT NULL AFTER `members`;" );
		}
	}
);

if ( ! function_exists( 'trn_get_player_icon_fields' ) ) {
	/**
	 * Returns an array of custom player icon fields.
	 *
	 * @since 4.2.0
	 *
	 * @return mixed
	 */
	function trn_get_player_icon_fields() {
		$social_fields = array(
			'facebook' => array(
				'display_name' => __( 'Facebook', 'trn-profile-social-icons' ),
				'icon'         => 'fab fa-facebook',
				'input_type'   => 'text',
			),
			'homepage' => array(
				'display_name' => __( 'Homepage', 'trn-profile-social-icons' ),
				'icon'         => 'fa fa-globe',
				'input_type'   => 'text',
			),
			'twitch'   => array(
				'display_name' => __( 'Twitch', 'trn-profile-social-icons' ),
				'icon'         => 'fab fa-twitch',
				'input_type'   => 'text',
			),
			'twitter'  => array(
				'display_name' => __( 'Twitter', 'trn-profile-social-icons' ),
				'icon'         => 'fab fa-twitter',
				'input_type'   => 'text',
			),
			'youtube'  => array(
				'display_name' => __( 'YouTube', 'trn-profile-social-icons' ),
				'icon'         => 'fab fa-youtube',
				'input_type'   => 'text',
			),
		);

		return apply_filters( 'trn_player_social_icon_fields', $social_fields );
	}
}

if ( ! function_exists( 'trn_get_team_icon_fields' ) ) {
	/**
	 * Returns an array of custom team icon fields.
	 *
	 * @since 4.2.0
	 *
	 * @return mixed
	 */
	function trn_get_team_icon_fields() {
		$social_fields = array(
			'homepage' => array(
				'display_name' => esc_html__( 'Homepage', 'trn-profile-social-icons' ),
				'icon'         => 'fa fa-globe',
				'input_type'   => 'text',
			),
		);

		return apply_filters( 'trn_team_social_icon_fields', $social_fields );
	}
}

add_filter(
	'trn_trn-edit-player-profile-form_fields',
	function( $fields, $player ) {
		$new_fields = array();

		$data = array();
		if ( isset( $player->psi_icon_fields ) && ( 0 < strlen( $player->psi_icon_fields ) ) ) {
			$data = json_decode( $player->psi_icon_fields, true );

			if ( ! is_array( $data ) ) {
				$data = array();
			}
		}

		foreach ( trn_get_player_icon_fields() as $icon_field_id => $icon_field_data ) {
			$new_fields[] = array(
				'id'    => 'psi_icon_' . $icon_field_id,
				'label' => $icon_field_data['display_name'],
				'type'  => $icon_field_data['input_type'],
				'value' => isset( $data[ $icon_field_id ] ) ? $data[ $icon_field_id ] : '',
			);
		}

		if ( ! is_array( $fields ) ) {
			$fields = array();
		}

		return trn_array_merge_after_key( $fields, 'flag', $new_fields );
	},
	10,
	2
);

add_filter(
	'trn_trn-edit-team-profile-form_fields',
	function( $fields, $team ) {
		$new_fields = array();

		$data = array();
		if ( isset( $team->psi_icon_fields ) && ( 0 < strlen( $team->psi_icon_fields ) ) ) {
			$data = json_decode( $team->psi_icon_fields, true );

			if ( ! is_array( $data ) ) {
				$data = array();
			}
		}

		foreach ( trn_get_team_icon_fields() as $icon_field_id => $icon_field_data ) {
			$new_fields[] = array(
				'id'    => 'psi_icon_' . $icon_field_id,
				'label' => $icon_field_data['display_name'],
				'type'  => $icon_field_data['input_type'],
				'value' => isset( $data[ $icon_field_id ] ) ? $data[ $icon_field_id ] : '',
			);
		}

		if ( ! is_array( $fields ) ) {
			$fields = array();
		}

		return trn_array_merge_after_key( $fields, 'flag', $new_fields );
	},
	10,
	2
);

if ( ! function_exists( 'trn_psi_prepare_field_for_database' ) ) {
	/**
	 * Helper function for preparing serialized fields for the database.
	 *
	 * @since 4.4.0
	 *
	 * @param stdClass $data   Array of values to be written to the database.
	 * @param array    $fields Array of fields to be removed from $data and serialized into a single field.
	 * @param string   $prefix A string that each field in $data is prefixed with. This is truncated in the nested serialized
	 *                         return value.
	 *
	 * @return string The serialized value.
	 */
	function trn_psi_prepare_field_for_database( &$data, $fields, $prefix ) {
		$data_array = array();

		foreach ( $fields as $field_id => $field_data ) {
			$property = $prefix . $field_id;
			if ( isset( $data->$property ) ) {
				$data_array[ $field_id ] = $data->$property;
				unset( $data->$property );
			}
		}

		return wp_json_encode( $data_array );
	}
}

add_filter(
	'rest_pre_insert_players',
	function( $data, $request ) {
		$data->psi_icon_fields = trn_psi_prepare_field_for_database( $data, trn_get_player_icon_fields(), 'psi_icon_' );

		return $data;
	},
	10,
	2
);

add_filter(
	'rest_pre_insert_teams',
	function( $data, $request ) {
		$data->psi_icon_fields = trn_psi_prepare_field_for_database( $data, trn_get_team_icon_fields(), 'psi_icon_' );

		return $data;
	},
	10,
	2
);

add_action(
	'rest_api_init',
	function() {
		$icon_field_cache = array();

		$get_callback = function( $entity, $entity_id_field, $entity_field, $field_id, &$field_cache ) {
			$cache_key = $entity->$entity_id_field;
			if ( ! isset( $field_cache[ $cache_key ] ) ) {
				if ( isset( $entity->$entity_field ) && ( 0 < strlen( $entity->$entity_field ) ) ) {
					$field_cache[ $cache_key ] = json_decode( $entity->$entity_field, true );

					if ( ! is_array( $field_cache[ $cache_key ] ) ) {
						$field_cache[ $cache_key ] = array();
					}
				} else {
					$field_cache[ $cache_key ] = array();
				}
			}

			return isset( $field_cache[ $cache_key ][ $field_id ] ) ? $field_cache[ $cache_key ][ $field_id ] : '';
		};

		foreach ( trn_get_player_icon_fields() as $icon_field_id => $icon_field_data ) {
			register_rest_field(
				'players',
				'psi_icon_' . $icon_field_id,
				array(
					'schema' => array(
						/* translators: The name of a field. */
						'description' => sprintf( esc_html__( 'The %s icon field for the player.', 'trn-profile-social-icons' ), $icon_field_id ),
						'type'        => 'string',
						'trn-subtype' => 'callable',
						'trn-get'     => function( $player ) use ( $icon_field_id, &$icon_field_cache, $get_callback ) {
							return $get_callback( $player, 'user_id', 'psi_icon_fields', $icon_field_id, $icon_field_cache );
						},
						'context'     => array( 'view', 'edit', 'embed' ),
					),
				)
			);
		}

		$icon_field_cache = array();

		foreach ( trn_get_team_icon_fields() as $icon_field_id => $icon_field_data ) {
			register_rest_field(
				'teams',
				'psi_icon_' . $icon_field_id,
				array(
					'schema' => array(
						/* translators: The name of a field. */
						'description' => sprintf( esc_html__( 'The %s icon field for the team.', 'trn-profile-social-icons' ), $icon_field_id ),
						'type'        => 'string',
						'trn-subtype' => 'callable',
						'trn-get'     => function( $team ) use ( $icon_field_id, &$icon_field_cache, $get_callback ) {
							return $get_callback( $team, 'team_id', 'psi_icon_fields', $icon_field_id, $icon_field_cache );
						},
						'context'     => array( 'view', 'edit', 'embed' ),
					),
				)
			);
		}
	}
);

add_filter(
	'trn_the_player',
	function( $player ) {
		if ( isset( $player->psi_icon_fields ) ) {
			$player_icon_data = json_decode( $player->psi_icon_fields, true );

			if ( ! is_null( $player_icon_data ) ) {
				foreach ( trn_get_player_icon_fields() as $icon => $data ) {
					$key          = 'psi_icon_' . $icon;
					$player->$key = isset( $player_icon_data[ $icon ] ) ? $player_icon_data[ $icon ] : '';
				}
			}
		}
		$player->psi_icon_fields = trn_psi_prepare_field_for_database( $data, trn_get_team_icon_fields(), 'psi_icon_' );

		return $player;
	},
	10
);

add_filter(
	'trn_filter_plugin_update_list',
	function( $plugins ) {
		if ( ! is_array( $plugins ) ) {
			$plugins = array();
		}

		$plugins[] = 'trn-profile-social-icons';

		return $plugins;
	},
	10
);
