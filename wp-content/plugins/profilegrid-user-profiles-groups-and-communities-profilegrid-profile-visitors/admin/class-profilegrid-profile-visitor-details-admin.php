<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_profile_visitor_details
 * @subpackage Profilegrid_profile_visitor_details/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_profile_visitor_details
 * @subpackage Profilegrid_profile_visitor_details/admin
 * @author     Your Name <email@example.com>
 */
class Profilegrid_profile_visitor_details_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $Profilegrid_profile_visitor_details    The ID of this plugin.
     */
    private $Profilegrid_profile_visitor_details;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $Profilegrid_profile_visitor_details       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($Profilegrid_profile_visitor_details, $version)
    {

        $this->Profilegrid_profile_visitor_details = $Profilegrid_profile_visitor_details;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Profilegrid_profile_visitor_details_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Profilegrid_profile_visitor_details_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (class_exists('Profile_Magic')) {
            wp_enqueue_style($this->Profilegrid_profile_visitor_details, plugin_dir_url(__FILE__) . 'css/profilegrid-profile-visitor-details-admin.css', array(), $this->version, 'all');
            wp_enqueue_style('wp-color-picker');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Profilegrid_profile_visitor_details_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Profilegrid_profile_visitor_details_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (class_exists('Profile_Magic')) {
            wp_enqueue_script($this->Profilegrid_profile_visitor_details, plugin_dir_url(__FILE__) . 'js/profilegrid-profile-visitor-details-admin.js', array('jquery', 'wp-color-picker'), $this->version, true);
            wp_localize_script($this->Profilegrid_profile_visitor_details, 'pm_ajax_visitors_object', array('ajax_url' => admin_url('admin-ajax.php')));
        }
    }

    public function Profilegrid_profile_visitor_details_admin_menu()
    {
        add_submenu_page("profilegrid_visitor_admin_menu_hide", __("Visitors Settings", "profilegrid-profile-visitor-details"), __("Visitors Settings", "profilegrid-profile-visitor-details"), "manage_options", "pm_profile_visitor_details_settings", array($this, 'pm_profile_visitor_details_settings'));    }

    public function pm_profile_visitor_details_settings()
    {
        include 'partials/profilegrid-profile-visitor-details-admin-display.php';
    }

    public function Profilegrid_profile_visitor_details_add_option_setting_page()
    {
        include 'partials/profilegrid-profile-visitor-details-setting-option.php';
    }

    public function profile_magic_profile_visitor_notifications()
    {
        if (!class_exists('Profile_Magic')) {

            $this->profilegrid_profile_visitor_details_installation();
            //wp_die( "ProfileGrid Stripe won't work as unable to locate ProfileGrid plugin files." );
        }
    }

    public function profilegrid_profile_visitor_details_installation()
    {
        $pm_allow_html = new profilegrid_profile_visitor_details_allowed_html_wp_kses();
                                $allowed_html = $pm_allow_html->pm_allowed_html_wp_kses();

        $plugin_slug = 'profilegrid-user-profiles-groups-and-communities';
        $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
        $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
?>
        <div class="notice notice-success is-dismissible">
            <?php  // translators: %s is the URL to install the ProfileGrid Plugin ?>
            <p><?php echo sprintf(wp_kses(__("Profilegrid Profile Visitor Details work with ProfileGrid Plugin. You can install it  from <a href='%s'>Here</a>.", 'profilegrid-profile-visitor-details'),$allowed_html), esc_url($installUrl)); ?></p>        </div>
        <?php
        $plugin = trim(basename(plugin_dir_path(dirname(__FILE__))) . '/profilegrid-profile-visitors.php');
        deactivate_plugins($plugin);
    }



    public function activate_sitewide_plugins($blog_id)
    {
        // Switch to new website
        
        $activator = new Profilegrid_profile_visitor_details_Activator;
        switch_to_blog($blog_id);
        // Activate
        foreach (array_keys(get_site_option('active_sitewide_plugins')) as $plugin) {
            do_action('activate_'  . $plugin, false);
            do_action('activate'   . '_plugin', $plugin, false);
            $activator->activate();
        }
        // Restore current website 
        restore_current_blog();
    }

    public function profilegrid_profile_visitor_top_menu()
    {

        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        if ($dbhandler->get_global_option_value('pm_enable_profile_visitor_details') == 1) {
            $path =  plugin_dir_url(__FILE__);

            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $current_user_id = isset($id) ? $pmrequests->pm_get_uid_from_profile_slug(sanitize_text_field(wp_unslash($id))) : '';
            //print_r($current_user_id);
            $title = $dbhandler->get_global_option_value('pm_profile_visitor_details_tab_title', __('Visitors Detail', 'profilegrid-profile-visitor-details'));
        ?>
            <li class="pm-profile-nav-item"><a href="#visitor_details" onclick="profilegrid_back_to_visitor_details(<?php echo esc_js($current_user_id); ?>)"><?php echo esc_html($title); ?></a></li>
        <?php
        }
    }

    public function profilegrid_dashboard_member_profile_visitor_content()
    {
        $dbhandler = new PM_DBhandler;
        if ($dbhandler->get_global_option_value('pm_enable_profile_visitor_details') == 1) {
        ?>
            <div class="pm-user-content" id="visitor_details">

            </div>
        <?php
        }
    }
    public function profilegrid_dashboard_profile_visitor_list()
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $path =  plugin_dir_url(__FILE__);
        ob_start();    ?>
        <?php

        //   echo $_GET['id'];
        //  $current_user_id  = 
        $pagenum = filter_input(INPUT_POST, 'pagenum');
        $current_user_id = filter_input(INPUT_POST, 'id');
        $limit1 = $dbhandler->get_global_option_value('pm_visitor_per_page');
        $offset = ($pagenum - 1) * 2;

        $identifier = 'profile_visitor_details';
        $visitor_count = 0;
        $visitor_details = $dbhandler->get_all_result($identifier, 'DISTINCT visitor_id', array('uid' => $current_user_id), 'results', 0, false, 'timestamp', true);
        if (isset($visitor_details)):
            $visitor_count = count($visitor_details);
        endif;
        $count = 1;
        if ($visitor_count > 0) {
            $offset = ($pagenum - 1) * $limit1;
            $num_of_pages = ceil($visitor_count / $limit1);
            $pagination = $dbhandler->pm_get_pagination($num_of_pages, $pagenum);
            $visitor_details_page = $dbhandler->get_all_result($identifier, 'DISTINCT visitor_id', array('uid' => $current_user_id), 'results', $offset, $limit1, 'timestamp', true);
            if ($pagenum <= $num_of_pages) {
        ?>

                <div id="pm_admin_visitor_report">
                    <div class="pg-admin-visitor-count">
                        <?php esc_html_e('Total Visit Counts', 'profilegrid-profile-visitor-details'); ?> : <?php echo esc_html($this->pm_get_total_profile_visitor_count($current_user_id)); ?>

                    </div>
                    <table class="pg-visitors-table">
                        <thead>
                            <tr Class="visitors_heading">
                                <th><?php esc_html_e('No.', 'profilegrid-profile-visitor-details'); ?></th>
                                <th><?php esc_html_e('Profile Picture', 'profilegrid-profile-visitor-details'); ?></th>
                                <th><?php esc_html_e('User', 'profilegrid-profile-visitor-details'); ?></th>
                                <th><?php esc_html_e('Last Visited Date', 'profilegrid-profile-visitor-details'); ?></th>
                                <th><?php esc_html_e('Visit Count', 'profilegrid-profile-visitor-details'); ?></th>
                                <th><?php esc_html_e('Profile Link', 'profilegrid-profile-visitor-details'); ?> </th>
                                <th><?php esc_html_e('Detailed Report', 'profilegrid-profile-visitor-details'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visitor_details_page as $visitor_detail) {
                                $visitor_id = $visitor_detail->visitor_id;
                                $user_details = $dbhandler->get_all_result($identifier, '*', array('visitor_id' => $visitor_id, 'uid' => $current_user_id), 'results', $offset = 0, false, $sort_by = 'timestamp', $descending = true);
                                $profile_url = get_permalink($dbhandler->get_global_option_value('pm_user_profile_page'));
                                $slug = $pmrequests->pm_get_profile_slug_by_id($visitor_id);
                                $profile_url = add_query_arg('uid', $slug, $profile_url);
                                $pm_profile_function = new profilegrid_profile_visitor_details_controler();
                                $Profile_Picture_url = $pm_profile_function->pm_get_user_profile_picture($visitor_id);
                                $Profile_Picture = $Profile_Picture_url;

                                if ($visitor_id == 0):
                                    $visitor_name = "Guest";
                                else:
                                    $visitor_name = $pmrequests->pm_get_display_name($visitor_id, true);
                                endif;
                                $pm_allow_html = new profilegrid_profile_visitor_details_allowed_html_wp_kses();
                                $allowed_html = $pm_allow_html->pm_allowed_html_wp_kses();
                                $Profile_Picture = str_replace('<img', '<img', $Profile_Picture_url);


                            ?>
                                <tr>
                                    <td><?php echo esc_html(($limit1 * ($pagenum - 1)) + $count); ?></td>
                                    <td class="pg-visitor-profile-data"><?php echo wp_kses($Profile_Picture, $allowed_html) ?></td>
                                    <td><?php echo esc_html($visitor_name); ?></td>
                                    <td><?php echo esc_html($user_details[0]->timestamp); ?></td>
                                    <td><?php echo esc_html(count($user_details)); ?></td>
                                    <td>
                                        <?php if ($visitor_id != 0): ?>
                                            <a target="_blank" href="<?php echo esc_url($profile_url); ?>"><?php esc_html_e('View Profile', 'profilegrid-profile-visitor-details'); ?></a>
                                        <?php else: ?>
                                            <?php echo "--"; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><a class="pg-visitor-view" onclick="pm_dashboard_display_detailed_report(<?php echo esc_js($current_user_id); ?>,<?php echo esc_js($visitor_id); ?>,1);"><?php esc_html_e('View', 'profilegrid-profile-visitor-details'); ?></a>
                                </tr>

                        </tbody>
                    <?php $count++;
                            }
                    ?>
                    </table>
                    <input type="hidden" id="profile_id" value="<?php echo esc_attr($current_user_id); ?>">
                    <?php echo '<div class="pm-dashboard-visitor_details_list">' . (!empty($pagination) ? wp_kses($pagination, $allowed_html) : '') . '</div>'; ?>
                    <div class="pg-reset-counter">
                        <input type="button" class="pg-box-border pg-box-white-bg" id="reset_counter" onclick="pm_reset_visitor_counter(<?php echo esc_js($current_user_id); ?>);" value="<?php esc_html_e('Reset Counter for this user', 'profilegrid-profile-visitor-details'); ?>" />
                    </div>

                </div>
            <?php
            }
        } else {

            ?>
            <div class="pg-alert-warning pg-alert-info"><?php esc_html_e('No Details Found.', 'profilegrid-profile-visitor-details'); ?></div>
            <?php
        }
        die;
    }

    public function pm_dashboard_display_detailed_report()
    {

        $this->enqueue_scripts();
        $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $limit = $dbhandler->get_global_option_value('pm_visitor_per_page');
        $vid =  filter_input(INPUT_POST, 'vid');
        $pagenum =  filter_input(INPUT_POST, 'pagenum');
        $current_user_id = filter_input(INPUT_POST, 'id');

        $identifier = 'profile_visitor_details';
        $visitor_count = 0;
        $visitor_detailed_reports = $dbhandler->get_all_result($identifier, '*', array('uid' => $current_user_id, 'visitor_id' => $vid), 'results', 0, false, 'timestamp', true);
        //   print_r($visitor_detailed_report);

        if (count($visitor_detailed_reports) > 0):
            $offset = ($pagenum - 1) * $limit;
            $num_of_pages = ceil(count($visitor_detailed_reports) / $limit);
            $pagination = $dbhandler->pm_get_pagination($num_of_pages, $pagenum);
            $visitor_details_reports_page = $dbhandler->get_all_result($identifier, '*', array('uid' => $current_user_id, 'visitor_id' => $vid), 'results',  $offset, $limit, 'timestamp', true);

            if ($pagenum <= $num_of_pages) {

                $count = 1;
            ?>
                <div>
                    <table class="pg-visitors-table">
                        <thead>
                            <tr Class="visitors_heading">
                                <th><?php esc_html_e('No.', 'profilegrid-profile-visitor-details'); ?></th>
                                <th><?php esc_html_e('Visited Date', 'profilegrid-profile-visitor-details'); ?></th>
                                <th><?php esc_html_e('IP Address', 'profilegrid-profile-visitor-details'); ?></th>
                                <th><?php esc_html_e('Browser Details', 'profilegrid-profile-visitor-details'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visitor_details_reports_page as $visitor_detailed_report) {
                                $browser_details = array();
                                $browser_details  = maybe_unserialize($visitor_detailed_report->meta_details); ?>
                                <tr>
                                    <td><?php echo esc_html($count); ?></td>
                                    <td><?php echo esc_html($visitor_detailed_report->timestamp); ?></td>
                                    <td><?php echo esc_html($visitor_detailed_report->ip_address); ?></td>
                                    <td>
                                        <span><?php esc_html_e('Browser', 'profilegrid-profile-visitor-details'); ?>:</span><?php echo esc_html($browser_details['browser']); ?><br>
                                        <span><?php esc_html_e('OS', 'profilegrid-profile-visitor-details'); ?>:</span><?php echo esc_html($browser_details['os_platform']); ?> <br>
                                        <span><?php esc_html_e('Device', 'profilegrid-profile-visitor-details'); ?>:</span><?php echo esc_html($browser_details['device']); ?> <br>
                                        <span><?php esc_html_e('Country', 'profilegrid-profile-visitor-details'); ?>:</span><?php echo esc_html($browser_details['country']); ?>
                                    </td>
                                </tr>
                        </tbody>
                    <?php $count++;
                            }
                            $pm_allow_html = new profilegrid_profile_visitor_details_allowed_html_wp_kses();
                                $allowed_html = $pm_allow_html->pm_allowed_html_wp_kses();
                    ?>
                    </table>
                    <input type="hidden" id="visitor_id" value="<?php echo esc_attr($vid); ?>">
                    <input type="hidden" id="profile_id" value="<?php echo esc_attr($current_user_id); ?>">
                    <?php echo '<div class="pm-dashboard-visitor_details_detailed_report">' . (!empty($pagination) ? wp_kses($pagination, $allowed_html) : '') . '</div>'; ?>
                    <div class="pg-back"><a onclick="profilegrid_back_to_visitor_details(<?php echo esc_js($current_user_id) ?>,1);"><?php esc_html_e('Back', 'profilegrid-profile-visitor-details'); ?></a></div>
                </div>

<?php
            }
        endif;
        die;
    }


    public function pm_reset_visitor_counter()
    {
        $dbhandler = new PM_DBhandler;
        $identifier = 'profile_visitor_details';
        $userid =  filter_input(INPUT_POST, 'uid');
        $return = $dbhandler->remove_row($identifier, 'uid', $userid);
        echo esc_html($return);
        die;
    }

    public function pm_get_total_profile_visitor_count($uid)
    {
        $dbhandler = new PM_DBhandler;
        $identifier = 'profile_visitor_details';
        $count = $dbhandler->pm_count($identifier, array('uid' => $uid));
        return $count;
    }
    // function my_custom_cron_schedule($schedules)
    // {
    //     $schedules['every_minute'] = array(
    //         'interval' => 60, // Number of seconds
    //         'display'  => __('Every Minute')
    //     );
    //     //print($schedules);
    //     return $schedules;
    // }
    public function pm_setup_cron_job()
    {
        if (!wp_next_scheduled('cleanup_old_visit_logs')) {
            wp_schedule_event(time(), 'daily', 'cleanup_old_visit_logs');
        }
    }
    public function pm_perform_cleanup()
    {

        $retention_period = get_option('pm_visitor_logs_auto_delete', 0);
        //print($retention_period );
        echo '<br>';
        if ($retention_period <= 0) {
            //print($retention_period);
            return;
        }
        $threshold_date = gmdate('Y-m-d H:i:s', strtotime("-$retention_period days"));
        //print($threshold_date);
        global $wpdb;
        // Get the table name dynamically 
        $PM_Helper_profile_visitor_details = new PM_Helper_PROFILE_VISITOR_DETAILS;
        $table_name = $PM_Helper_profile_visitor_details->get_db_table_name('PROFILE_VISITORS_DETAILS');

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM %i WHERE `timestamp` < %s",
                $table_name,
                $threshold_date
            )
        );
    }
}