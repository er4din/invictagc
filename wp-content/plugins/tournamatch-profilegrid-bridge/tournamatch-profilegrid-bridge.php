<?php
/**
 * Plugin Name: Tournamatch ProfileGrid Bridge
 * Description: Displays Tournamatch league results on ProfileGrid user profiles.
 * Version: 1.0.2
 * Author: Basit Khan
 */

if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('tm_league_results', 'tekloop_tm_league_results_shortcode');

function tekloop_tm_league_results_shortcode($atts) {
    global $wpdb;

    $atts = shortcode_atts([
        'user_id' => 0,
    ], $atts, 'tm_league_results');

    /*
     * Priority:
     * 1. Shortcode user_id, e.g. [tm_league_results user_id="1"]
     * 2. ProfileGrid public profile URL uid, e.g. /my-profile/?uid=13
     * 3. Current logged-in user
     */
    $user_id = absint($atts['user_id']);

    if (!$user_id && isset($_GET['uid'])) {
        $user_id = absint(wp_unslash($_GET['uid']));
    }

    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    if (!$user_id) {
        return '<p>Please log in to view league results.</p>';
    }

    $ladders_entries_table = $wpdb->prefix . 'trn_ladders_entries';
    $ladders_table         = $wpdb->prefix . 'trn_ladders';

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT 
                l.name AS league_name,
                le.points,
                le.wins,
                le.losses,
                le.draws,
                le.streak,
                le.best_streak,
                le.worst_streak,
                (COALESCE(le.wins, 0) + COALESCE(le.losses, 0) + COALESCE(le.draws, 0)) AS played
            FROM {$ladders_entries_table} le
            LEFT JOIN {$ladders_table} l 
                ON l.ladder_id = le.ladder_id
            WHERE le.competitor_type = %s
            AND le.competitor_id = %d
            AND (COALESCE(le.wins, 0) + COALESCE(le.losses, 0) + COALESCE(le.draws, 0)) > 0
            ORDER BY l.name ASC
            ",
            'players',
            $user_id
        )
    );

    if (empty($results)) {
        return '<div class="tm-profile-results"><h3>League Results</h3><p>No league results found for this profile.</p></div>';
    }

    ob_start();
    ?>
    <div class="tm-profile-results">
        <h3>League Results</h3>

        <table class="tm-profile-results-table">
            <thead>
                <tr>
                    <th>League</th>
                    <th>Played</th>
                    <th>Points</th>
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Draws</th>
                    <th>Streak</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?php echo esc_html($row->league_name); ?></td>
                        <td><?php echo esc_html($row->played); ?></td>
                        <td><?php echo esc_html($row->points); ?></td>
                        <td><?php echo esc_html($row->wins); ?></td>
                        <td><?php echo esc_html($row->losses); ?></td>
                        <td><?php echo esc_html($row->draws); ?></td>
                        <td><?php echo esc_html($row->streak); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <style>
        .tm-profile-results {
            margin-top: 0 !important;
            padding: 18px 20px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
        }

        .tm-profile-results h3 {
            margin-top: 0;
            margin-bottom: 12px;
        }

        .tm-profile-results p {
            margin-bottom: 0;
        }

        .tm-profile-results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .tm-profile-results-table th,
        .tm-profile-results-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .tm-profile-results-table th {
            font-weight: 600;
            background: #f5f5f5;
        }

        .pmagic + .tm-profile-results,
        .pmagic {
            margin-bottom: 0 !important;
        }
    </style>
    <?php

    return ob_get_clean();
    
    add_action('template_redirect', 'tekloop_redirect_tournamatch_player_profile');

function tekloop_redirect_tournamatch_player_profile() {
    if (is_admin() || wp_doing_ajax()) {
        return;
    }

    $request_path = isset($_SERVER['REQUEST_URI'])
        ? wp_parse_url(wp_unslash($_SERVER['REQUEST_URI']), PHP_URL_PATH)
        : '';

    $request_path = trim($request_path, '/');

    /*
     * Redirect Tournamatch player profile URLs:
     * /players/1  ->  /my-profile/?uid=1
     */
    if (preg_match('#^players/([0-9]+)/?$#', $request_path, $matches)) {
        $user_id = absint($matches[1]);

        if ($user_id && get_user_by('id', $user_id)) {
            wp_safe_redirect(home_url('/my-profile/?uid=' . $user_id), 302);
            exit;
        }
    }
}
}