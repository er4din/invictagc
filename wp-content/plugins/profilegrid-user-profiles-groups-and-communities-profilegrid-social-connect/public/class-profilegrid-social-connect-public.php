<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Social_Connect
 * @subpackage Profilegrid_Social_Connect/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Social_Connect
 * @subpackage Profilegrid_Social_Connect/public
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Social_Connect_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $profilegrid_social_connect    The ID of this plugin.
     */
    private $profilegrid_social_connect;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    private $linkedin_login_url;

    private $twitter_login_url;

    private $facebook_login_url;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $profilegrid_social_connect       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($profilegrid_social_connect, $version)
    {

        $this->profilegrid_social_connect = $profilegrid_social_connect;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Profilegrid_Social_Connect_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Profilegrid_Social_Connect_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->profilegrid_social_connect, plugin_dir_url(__FILE__) . 'css/profilegrid-social-connect-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Profilegrid_Social_Connect_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Profilegrid_Social_Connect_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->profilegrid_social_connect, plugin_dir_url(__FILE__) . 'js/profilegrid-social-connect-public.js', array('jquery'), $this->version, true);
        $error = array();
        $error['conf_disconnect'] = __('Do you really want to Disconnect?', 'profilegrid-social-connect');
        wp_localize_script($this->profilegrid_social_connect, 'pm_social_connect_error_object', $error);
    }

    public function pg_social_connect_register_shortcode()
    {
        add_shortcode('profilegrid_social_options', array($this, 'profile_magic_shortcode_user_social_connect_tab'));
    }

    public function profile_magic_shortcode_user_social_connect_tab($content)
    {
        $pmrequests = new PM_request;
        ob_start();
        $current_user = wp_get_current_user();
        $default_attributes = array('id' => $current_user->ID);
        $attributes = shortcode_atts($default_attributes, $content);
        $attributes['id'] = $current_user->ID;
        $user_exists = $pmrequests->pm_check_user_exist_by_id($attributes['id']);
        if ($attributes['id'] !== 0 && is_object($user_exists) && is_user_logged_in()):
            $gids = maybe_unserialize($pmrequests->profile_magic_get_user_field_value($attributes['id'], 'pm_group'));
            $gid = $pmrequests->pg_filter_users_group_ids($gids);
            $primary_gid = $pmrequests->pg_get_primary_group_id($gid);
            echo '<div class="pmagic"><div class="pm-group-view pg-shortcode-content">';
            $this->pg_social_connect_tab_content($attributes['id'], $primary_gid);
            echo '</div></div>';
        endif;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function pg_social_connect_tab($uid, $gid)
    {

        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $options = maybe_unserialize($dbhandler->get_value('GROUPS', 'group_options', $gid, 'id'));
        if ($dbhandler->get_global_option_value('pm_enable_social_account_tab', '0') == 1) {
            echo '<li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" href="#pg-social-connect">' . __('Social Connect', 'profilegrid-social-connect') . '</a></li>';
        }
    }

    public function pg_social_connect_tab_content($uid, $gid)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $path =  plugin_dir_url(__FILE__);
        $options = maybe_unserialize($dbhandler->get_value('GROUPS', 'group_options', $gid, 'id'));
        if ($dbhandler->get_global_option_value('pm_enable_social_account_tab', '0') == 1) {
            echo '<div id="pg-social-connect" class="pm-blog-desc-wrap pm-difl pm-section-content">';
            include 'partials/pg-social-connect-tab-content.php';
            echo '</div>';
        }
    }

    public function pg_social_connect_profile_html($uid, $gid, $template)
    {
        $dbhandler = new PM_DBhandler;
        $path = plugin_dir_path(dirname(__FILE__));
        $pmrequests = new PM_request;
        $pm_enable_facebook_connect = $dbhandler->get_global_option_value('pm_enable_facebook_connect', '0'); 
        $pm_enable_google_connect = $dbhandler->get_global_option_value('pm_enable_google_connect', '0'); 
        $pm_enable_linkedin_connect = $dbhandler->get_global_option_value('pm_enable_linkedin_connect', '0'); 
        $pm_enable_twitter_connect = $dbhandler->get_global_option_value('pm_enable_twitter_connect', '0'); 

        if( 
            empty($pm_enable_facebook_connect) && empty($pm_enable_google_connect)
            && empty($pm_enable_linkedin_connect) && empty($pm_enable_twitter_connect)
        ) {

            echo '<div class="pg-alert-warning pg-alert-info">' . __('Please enable at least one social tab to establish a connection.', 'profilegrid-user-profiles-groups-and-communities') . '</div>';
            return;
        }

        if ( $pm_enable_facebook_connect == 1) {
            if (get_user_meta($uid, 'pm_facebook_connected', true) == 1) {

                $pm_facebook_link = get_user_meta($uid, 'pm_facebook_link', true);
                $pm_facebook_handle = get_user_meta($uid, 'pm_facebook_handle', true);
                $pm_facebook_profile_photo = get_user_meta($uid, 'pm_facebook_profile_photo', true);

                if ($pm_facebook_profile_photo == '') {
                    $pm_facebook_profile_photo = $pmrequests->pm_get_user_avatar($uid);
                } else {
                    $pm_facebook_profile_photo = '<img src="' . $pm_facebook_profile_photo . '" width="50" height="50" class="user-profile-image" />';
                }

?>
                <div class="pg-social-profile-info pg-social-facebook pm-dbfl">
                    <div class="pg-social-image pm-difl">
                        <?php echo $pm_facebook_profile_photo; ?>
                    </div>
                    <div class="pg_social_block pm-difl">

                        <div class="pg-social-url pm-difl ">
                            <label><?php echo esc_html($pm_facebook_handle); ?></label>
                        </div>
                        <div class="pg-social-disconnect pm-dbfl">
                            <a id="pm_facebook_connected" onclick="pg_update_social_connection(this)"><?php _e('Disconnect', 'profilegrid-social-connect'); ?></a>
                        </div>
                    </div>
                </div>
            <?php
                // Will show profile url of the user html

            } else {

                require_once $path . 'services/facebook/pg-social-login-facebook.php';
                $pg_fb_obj = new Pg_Social_Login_Facebook($gid, $template);
                $login_url = $pg_fb_obj->login_url();
            ?>
                <?php if (!empty($login_url)) : ?>
                    <div id="pg_faceook_btn" class="pg-social-btn"><a onclick="pg_social_login_redirect('<?php echo $login_url; ?>','<?php echo $gid; ?>','facebook')"> <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M17,2V2H17V6H15C14.31,6 14,6.81 14,7.5V10H14L17,10V14H14V22H10V14H7V10H10V6A4,4 0 0,1 14,2H17Z" />
                                </svg></span><?php _e('Connect with Facebook', 'profilegrid-social-connect'); ?></a></div>
                <?php endif; ?>
            <?php
                //echo '<div id="pg_faceook_btn" class="pg-social-btn"><a href="'.$login_url.'">Connect to Facebook</a><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24"><path d="M17,2V2H17V6H15C14.31,6 14,6.81 14,7.5V10H14L17,10V14H14V22H10V14H7V10H10V6A4,4 0 0,1 14,2H17Z" /></svg></span></div>';   
            }
        }

        if ($pm_enable_google_connect == 1) {
            if (get_user_meta($uid, 'pm_google_connected', true) == 1) {

                $pm_google_link = get_user_meta($uid, 'pm_google_link', true);
                $pm_google_handle = get_user_meta($uid, 'pm_google_handle', true);
                $pm_google_profile_photo = get_user_meta($uid, 'pm_google_profile_photo', true);
                if ($pm_google_profile_photo == '') {
                    $pm_google_profile_photo = $pmrequests->pm_get_user_avatar($uid);
                } else {
                    $pm_google_profile_photo = '<img src="' . $pm_google_profile_photo . '" width="50" height="50" class="user-profile-image" />';
                }

            ?>

                <div class="pg-social-profile-info pg-social-googleplus pm-dbfl">
                    <div class="pg-social-image pm-difl">
                        <?php echo $pm_google_profile_photo; ?>
                    </div>

                    <div class="pg_social_block pm-difl">

                        <div class="pg-social-url pm-difl">
                            <label><?php echo esc_html($pm_google_handle); ?></label>
                        </div>
                        <div class="pg-social-disconnect pm-dbfl">
                            <a id="pm_google_connected" onclick="pg_update_social_connection(this)"><?php _e('Disconnect', 'profilegrid-social-connect'); ?></a>
                        </div>
                    </div>
                </div>
            <?php
                // Will show profile url of the user html


            } else {

                $pg_google_obj = new Pg_Social_Login_Google($gid, $template);
                $login_url = $pg_google_obj->login_url();
                if (!empty($login_url)) {
                    echo '<div id="pg_google_btn" class="pg-social-btn"><a onclick="pg_social_login_redirect(\'' . esc_url($login_url) . '\',\'' . esc_js((string) $gid) . '\',\'google\')"><span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)"><path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"></path><path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"></path><path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"></path><path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"></path></g></svg></span>' . __('Connect to Google', 'profilegrid-social-connect') . '</a></div>';
                }
            }
        }

        if ($pm_enable_linkedin_connect == 1) {
            $path = plugin_dir_path(dirname(__FILE__));
            if (get_user_meta($uid, 'pm_linkedin_connected', true) == 1) {

                $pm_linkedin_link = get_user_meta($uid, 'pm_linkedin_link', true);
                $pm_linkedin_handle = get_user_meta($uid, 'pm_linkedin_handle', true);
                $pm_linkedin_profile_photo = get_user_meta($uid, 'pm_linkedin_profile_photo', true);
                if ($pm_linkedin_profile_photo == '') {
                    $pm_linkedin_profile_photo = $pmrequests->pm_get_user_avatar($uid);
                } else {
                    $pm_linkedin_profile_photo = '<img src="' . $pm_linkedin_profile_photo . '" width="50" height="50" class="user-profile-image" />';
                }

            ?>
                <div class="pg-social-profile-info pg-social-linkedin pm-dbfl">
                    <div class="pg-social-image pm-difl">
                        <?php echo $pm_linkedin_profile_photo; ?>
                    </div>
                    <div class="pg_social_block pm-difl">

                        <div class="pg-social-url pm-difl">
                            <label><?php echo esc_html($pm_linkedin_handle); ?></label>
                        </div>
                        <div class="pg-social-disconnect pm-dbfl">
                            <a id="pm_linkedin_connected" onclick="pg_update_social_connection(this)"><?php _e('Disconnect', 'profilegrid-social-connect'); ?></a>

                        </div>
                    </div>

                </div>
            <?php
                // Will show profile url of the user html


            } else {
                require_once $path . 'services/linkedin/pg-social-login-linkedin.php';
                $pg_lk_obj = new Pg_Social_Login_Linkedin($gid, $template);
                $login_url = $pg_lk_obj->login_url();
            ?>
                <?php if (!empty($login_url)) : ?>
                    <div id="pg_linkedin_btn" class="pg-social-btn"><a onclick="pg_social_login_redirect('<?php echo $login_url; ?>','<?php echo $gid; ?>','linkedin')"><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M21,21H17V14.25C17,13.19 15.81,12.31 14.75,12.31C13.69,12.31 13,13.19 13,14.25V21H9V9H13V11C13.66,9.93 15.36,9.24 16.5,9.24C19,9.24 21,11.28 21,13.75V21M7,21H3V9H7V21M5,3A2,2 0 0,1 7,5A2,2 0 0,1 5,7A2,2 0 0,1 3,5A2,2 0 0,1 5,3Z" />
                                </svg></span><?php _e('Connect with Linkedin', 'profilegrid-social-connect'); ?></a></div>
                <?php endif; ?>
            <?php
            }
        }

        if ($pm_enable_twitter_connect == 1) {
            if (get_user_meta($uid, 'pm_twitter_connected', true) == 1) {

                $pm_twitter_link = get_user_meta($uid, 'pm_twitter_link', true);
                $pm_twitter_handle = get_user_meta($uid, 'pm_twitter_handle', true);
                $pm_twitter_profile_photo = get_user_meta($uid, 'pm_twitter_profile_photo', true);

                if ($pm_twitter_profile_photo == '') {
                    $pm_twitter_profile_photo = $pmrequests->pm_get_user_avatar($uid);
                } else {
                    $pm_twitter_profile_photo = '<img src="' . $pm_twitter_profile_photo . '" width="50" height="50" class="user-profile-image" />';
                }

            ?>
                <div class="pg-social-profile-info pg-social-twitter pm-dbfl">
                    <div class="pg-social-image pm-difl">
                        <?php echo $pm_twitter_profile_photo; ?>
                    </div>
                    <div class="pg_social_block pm-difl">

                        <div class="pg-social-url pm-difl">
                            <label><?php echo esc_html($pm_twitter_handle); ?></label>
                        </div>
                        <div class="pg-social-disconnect pm-dbfl">
                            <a id="pm_twitter_connected" onclick="pg_update_social_connection(this)"><?php _e('Disconnect', 'profilegrid-social-connect'); ?></a>
                        </div>
                    </div>
                </div>
            <?php
                // Will show profile url of the user html


            } else {

                require_once $path . 'services/twitter/pg-social-login-twitter.php';
                $pg_tw_obj = new Pg_Social_Login_Twitter($gid, $template);
                $login_url = $pg_tw_obj->login_url();
                if (!empty($login_url)) {
                    echo '<div id="pg_twitter_btn" class="pg-social-btn pg-social-icon-twitter"><a onclick="pg_social_login_redirect(\'' . esc_url($login_url) . '\',\'' . esc_js((string) $gid) . '\',\'twitter\')"><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 24 24"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/></svg></span>' . __('Connect to X', 'profilegrid-social-connect') . '</a></div>';
                }
            }
        }
    }

    public function pg_social_update_user_connections()
    {

        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $path = plugin_dir_path(dirname(__FILE__));
        $key = $_POST['key'];
        $value = $_POST['value'];
        $current_user = wp_get_current_user();
        $userid = $current_user->ID;

        if (is_user_logged_in()) {
            if ($value) {
                update_user_meta($userid, $key, 0);
                $pg_social_connections = array('facebook', 'google', 'twitter', 'linkedin');
                $pg_active_connections = array();
                foreach ($pg_social_connections as $connection) {
                    if (get_user_meta($userid, 'pm_' . $connection . '_connected', true) == 1) {
                        array_push($pg_active_connections, $connection);
                    }
                }
                if (!empty($pg_active_connections)) {
                    $rand_default = $pg_active_connections[0];
                    update_user_meta($userid, 'pm_default_social_avatar', $rand_default);
                } else {
                    update_user_meta($userid, 'pm_default_social_avatar', '');
                }
            }
            $redirect_url = $pmrequests->profile_magic_get_frontend_url('pm_user_profile_page', '');

            wp_send_json_success(array('redirect' => $redirect_url));
        }
        die;
    }

    public function pg_get_social_widget($gid)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        if (!is_user_logged_in()) {
            $dbhandler = new PM_DBhandler;
            $gid = $dbhandler->get_global_option_value('pm_social_default_group', '0');
            $meta_query_array = $pmrequests->pm_get_user_meta_query(array('gid' => $gid));
            $total_users_in_group = count($dbhandler->pm_get_all_users('', $meta_query_array));
            $limit = $dbhandler->get_value('GROUPS', 'group_limit', $gid);
            $is_group_limit = $dbhandler->get_value('GROUPS', 'is_group_limit', $gid);
            if ($is_group_limit == 1) {
                if ($limit > $total_users_in_group) {
                    $this->pg_social_buttons_login_html($gid);
                } else {
                    echo $message  = $dbhandler->get_value('GROUPS', 'group_limit_message', $gid);
                }
            } else {
                $this->pg_social_buttons_login_html($gid);
            }
        } else {
            ?>
            <a href="<?php echo wp_logout_url(home_url()); ?>" title="<?php _e('Logout', 'profilegrid-social-connect'); ?>"><?php _e('Logout', 'profilegrid-social-connect'); ?></a>
            <?php
        }
    }

    public function pg_get_social_buttons($template, $content)
    {
        if (!is_user_logged_in()) {
            $dbhandler = new PM_DBhandler;
            $pmrequests = new PM_request;

            $re_process = filter_input(INPUT_GET, 'action');

            if ($template != 'profile-magic-login-form') {
                $gid = filter_input(INPUT_GET, 'gid');
                if (!isset($gid)) {
                    if (isset($content['id'])) {
                        $gid = $content['id'];
                    } else {
                        $gid = $content['gid'];
                    }
                }
            } else {
                $gid = $dbhandler->get_global_option_value('pm_social_default_group', '0');
            }

            if (empty($gid) || $gid == '0') {
                $gid = $dbhandler->get_global_option_value('pm_social_default_group', '0');
            }

            $meta_query_array = $pmrequests->pm_get_user_meta_query(array('gid' => $gid));
            $total_users_in_group = count($dbhandler->pm_get_all_users('', $meta_query_array));
            $limit = $dbhandler->get_value('GROUPS', 'group_limit', $gid);
            $is_group_limit = $dbhandler->get_value('GROUPS', 'is_group_limit', $gid);
            if ($pmrequests->profile_magic_check_paid_group($gid) > 0):
                $message = apply_filters('profile_magic_check_payment_config', '');
            else:
                $message = '';
            endif;

            if (isset($message) && $message == '') {
                if (($dbhandler->get_global_option_value('pm_enable_social_on_registration', '0') == 1 || $dbhandler->get_global_option_value('pm_enable_social_on_login', '0') == 1) && $template != 'profile-magic-login-form' && !isset($_POST['reg_form_submit']) && !isset($_REQUEST['action']) && !isset($_REQUEST['profile'])) {
                    if ($is_group_limit == 1) {
                        if ($limit > $total_users_in_group) {
                            $this->pg_social_buttons_html($gid, $template);
                        }
                    } else {
                        $this->pg_social_buttons_html($gid, $template);
                    }
                }

                if ($dbhandler->get_global_option_value('pm_enable_social_on_login', '0') == 1 && $template == 'profile-magic-login-form') {

                    $this->pg_social_buttons_login_html($gid, $template);
                }
            }

            if ($template == 'profile-magic-login-form') {
                $currentURL =   get_permalink();
                $loginurl = $pmrequests->profile_magic_get_frontend_url('pm_user_login_page', site_url('/wp-login.php'));
                if ($message == 'disabled' && $currentURL == $loginurl) {

                    echo "Payment system is not configured to accept payments. Please configure at least one payment processor to show social connect option.";
                }
            }
        }
    }

    public function pg_social_buttons_login_html($gid, $template = '')
    {
        $dbhandler = new PM_DBhandler;
        $path = plugin_dir_path(dirname(__FILE__));

        echo '<div id="pg_social_wrapper" class="pg-social-connect-login-wrapper">';
        if ($dbhandler->get_global_option_value('pm_enable_facebook_connect', '0') == 1) {
            if (!class_exists('Pg_Social_Login_Facebook')) {
                require_once $path . 'services/facebook/pg-social-login-facebook.php';
                $pg_fb_obj = new Pg_Social_Login_Facebook($gid, $template);
                $login_url = $pg_fb_obj->login_url();
                $this->facebook_login_url = $login_url;
            } else {
                $pg_fb_obj = new Pg_Social_Login_Facebook($gid, $template);
                $login_url = $this->facebook_login_url;
            }

            if ($pg_fb_obj->app_id != '' && $pg_fb_obj->app_secret != '' && !empty($login_url)) {
            ?>
                <div id="pg_faceook_btn" class="pg-social-btn"><a onclick="pg_social_login_redirect('<?php echo $login_url; ?>','<?php echo $gid; ?>','facebook')"> <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M17,2V2H17V6H15C14.31,6 14,6.81 14,7.5V10H14L17,10V14H14V22H10V14H7V10H10V6A4,4 0 0,1 14,2H17Z" />
                            </svg></span><?php _e('Login with Facebook', 'profilegrid-social-connect'); ?></a></div>
            <?php
            }
        }
        if ($dbhandler->get_global_option_value('pm_enable_google_connect', '0') == 1) {
            $pg_gog_obj = new Pg_Social_Login_Google($gid, $template);
            if ($pg_gog_obj->client_id != '' && $pg_gog_obj->client_secret != '') {
                $login_url = $pg_gog_obj->login_url();
                if (!empty($login_url)) {
                    echo '<div id="pg_google_btn" class="pg-social-btn"><a onclick="pg_social_login_redirect(\'' . esc_url($login_url) . '\',\'' . esc_js((string) $gid) . '\',\'google\')"> <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)"><path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"></path><path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"></path><path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"></path><path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"></path></g></svg></span>' . __('Login with Google', 'profilegrid-social-connect') . '</a></div>';
                }
            }
        }
        if ($dbhandler->get_global_option_value('pm_enable_linkedin_connect', '0') == 1) {
            if (!class_exists('Pg_Social_Login_Linkedin')) {
                require_once $path . 'services/linkedin/pg-social-login-linkedin.php';
                $pg_linkd_obj = new Pg_Social_Login_Linkedin($gid, $template);
                $login_url = $pg_linkd_obj->login_url();
                $this->linkedin_login_url = $login_url;
            } else {
                $pg_linkd_obj = new Pg_Social_Login_Linkedin($gid, $template);
                $login_url = $this->linkedin_login_url;
            }

            if ($pg_linkd_obj->api_key != '' && $pg_linkd_obj->api_secret != '' && !empty($login_url)) {
            ?>
                <div id="pg_linkedin_btn" class="pg-social-btn"><a onclick="pg_social_login_redirect('<?php echo $login_url; ?>','<?php echo $gid; ?>','linkedin')"><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M21,21H17V14.25C17,13.19 15.81,12.31 14.75,12.31C13.69,12.31 13,13.19 13,14.25V21H9V9H13V11C13.66,9.93 15.36,9.24 16.5,9.24C19,9.24 21,11.28 21,13.75V21M7,21H3V9H7V21M5,3A2,2 0 0,1 7,5A2,2 0 0,1 5,7A2,2 0 0,1 3,5A2,2 0 0,1 5,3Z" />
                            </svg></span><?php _e('Login with Linkedin', 'profilegrid-social-connect'); ?></a></div>
                <?php
                //echo '<div id="pg_linkedin_btn" class="pg-social-btn"><a href="'.$login_url.'">'.__('Login with Linkedin','profilegrid-social-connect').'</a><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24"><path d="M21,21H17V14.25C17,13.19 15.81,12.31 14.75,12.31C13.69,12.31 13,13.19 13,14.25V21H9V9H13V11C13.66,9.93 15.36,9.24 16.5,9.24C19,9.24 21,11.28 21,13.75V21M7,21H3V9H7V21M5,3A2,2 0 0,1 7,5A2,2 0 0,1 5,7A2,2 0 0,1 3,5A2,2 0 0,1 5,3Z" /></svg></span></div>';
            }
        }

        if ($dbhandler->get_global_option_value('pm_enable_twitter_connect', '0') == 1) {
            // $pg_twt_obj = new Pg_Social_Login_Twitter($gid,$template);
            if (!class_exists('Pg_Social_Login_Twitter')) {
                require_once $path . 'services/twitter/pg-social-login-twitter.php';
                $pg_twt_obj = new Pg_Social_Login_Twitter($gid, $template);
                $login_url = $pg_twt_obj->login_url();
                $this->twitter_login_url = $login_url;
            } else {
                $pg_twt_obj = new Pg_Social_Login_Twitter($gid, $template);
                $login_url = $this->twitter_login_url;
            }

            if ($pg_twt_obj->consumer_key != '' && $pg_twt_obj->consumer_secret != '' && !empty($login_url)) {
                echo '<div id="pg_twitter_btn" class="pg-social-btn"><a href="' . $login_url . '"> <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 24 24"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/></svg></span>' . __('Login with X', 'profilegrid-social-connect') . '</a></div>';
            }
        }
        echo '</div>';
    }

    public function pg_social_buttons_html($gid, $template = '')
    {
        $dbhandler = new PM_DBhandler;
        $path = plugin_dir_path(dirname(__FILE__));

        echo '<div id="pg_social_wrapper" class="pg-social-connect-register-wrapper">';
        if ($dbhandler->get_global_option_value('pm_enable_facebook_connect', '0') == 1) {
            require_once $path . 'services/facebook/pg-social-login-facebook.php';
            $pg_fb_obj = new Pg_Social_Login_Facebook($gid, $template);
            if ($pg_fb_obj->app_id != '' && $pg_fb_obj->app_secret != '') {
                $this->facebook_login_url = $pg_fb_obj->login_url();
                if ($dbhandler->get_global_option_value('pm_enable_social_on_registration', '0') == 1):
                    if (!empty($this->facebook_login_url)) :
                ?>
                    <div id="pg_faceook_btn" class="pg-social-btn"><a onclick="pg_social_login_redirect('<?php echo $this->facebook_login_url; ?>','<?php echo $gid; ?>','facebook')"> <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M17,2V2H17V6H15C14.31,6 14,6.81 14,7.5V10H14L17,10V14H14V22H10V14H7V10H10V6A4,4 0 0,1 14,2H17Z" />
                                </svg></span><?php _e('Connect with Facebook', 'profilegrid-social-connect'); ?></a></div>
                <?php
                    endif;
                endif;
            }
        }
        if ($dbhandler->get_global_option_value('pm_enable_google_connect', '0') == 1) {
            $pg_gog_obj = new Pg_Social_Login_Google($gid, $template);
            if ($pg_gog_obj->client_id != '' && $pg_gog_obj->client_secret != '') {
                $login_url = $pg_gog_obj->login_url();
                if ($dbhandler->get_global_option_value('pm_enable_social_on_registration', '0') == 1 && !empty($login_url)):
                    echo '<div id="pg_google_btn" class="pg-social-btn"><a onclick="pg_social_login_redirect(\'' . esc_url($login_url) . '\',\'' . esc_js((string) $gid) . '\',\'google\')"><span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)"><path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"></path><path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"></path><path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"></path><path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"></path></g></svg></span>' . __('Connect with Google', 'profilegrid-social-connect') . '</a></div>';
                endif;
            }
        }
        if ($dbhandler->get_global_option_value('pm_enable_linkedin_connect', '0') == 1) {
            require_once $path . 'services/linkedin/pg-social-login-linkedin.php';
            $pg_linkd_obj = new Pg_Social_Login_Linkedin($gid, $template);
            if ($pg_linkd_obj->api_key != '' && $pg_linkd_obj->api_secret != '') {
                $this->linkedin_login_url = $pg_linkd_obj->login_url();
                if ($dbhandler->get_global_option_value('pm_enable_social_on_registration', '0') == 1 && !empty($this->linkedin_login_url)):
                ?>
                    <div id="pg_linkedin_btn" class="pg-social-btn"><a onclick="pg_social_login_redirect('<?php echo $this->linkedin_login_url; ?>','<?php echo $gid; ?>','linkedin')"><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24">
                                    <path d="M21,21H17V14.25C17,13.19 15.81,12.31 14.75,12.31C13.69,12.31 13,13.19 13,14.25V21H9V9H13V11C13.66,9.93 15.36,9.24 16.5,9.24C19,9.24 21,11.28 21,13.75V21M7,21H3V9H7V21M5,3A2,2 0 0,1 7,5A2,2 0 0,1 5,7A2,2 0 0,1 3,5A2,2 0 0,1 5,3Z" />
                                </svg></span><?php _e('Connect with Linkedin', 'profilegrid-social-connect'); ?></a></div>
            <?php
                endif;
                //echo '<div id="pg_linkedin_btn" class="pg-social-btn"><a href="'.$this->linkedin_login_url.'">'.__('Connect with Linkedin','profilegrid-social-connect').'</a><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24"><path d="M21,21H17V14.25C17,13.19 15.81,12.31 14.75,12.31C13.69,12.31 13,13.19 13,14.25V21H9V9H13V11C13.66,9.93 15.36,9.24 16.5,9.24C19,9.24 21,11.28 21,13.75V21M7,21H3V9H7V21M5,3A2,2 0 0,1 7,5A2,2 0 0,1 5,7A2,2 0 0,1 3,5A2,2 0 0,1 5,3Z" /></svg></span></div>';
            }
        }

        if ($dbhandler->get_global_option_value('pm_enable_twitter_connect', '0') == 1) {
            require_once $path . 'services/twitter/pg-social-login-twitter.php';
            $pg_twt_obj = new Pg_Social_Login_Twitter($gid, $template);
            if ($pg_twt_obj->consumer_key != '' && $pg_twt_obj->consumer_secret != '') {
                $this->twitter_login_url = $pg_twt_obj->login_url();
                if ($dbhandler->get_global_option_value('pm_enable_social_on_registration', '0') == 1 && !empty($this->twitter_login_url)):
                    echo '<div id="pg_twitter_btn" class="pg-social-btn pg-social-icon-twitter"><a href="' . $this->twitter_login_url . '"><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 24 24"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/></svg></span>' . __('Connect to X', 'profilegrid-social-connect') . '</a></div>';
                endif;
            }
        }
        echo '</div>';
    }

    public function pg_add_social_connection_process($profile, $provider)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $current_user = wp_get_current_user();
        $userid = $current_user->ID;
        if (is_user_logged_in()) {
            unset($profile['gid']);
            foreach ($profile as $key => $value) {
                update_user_meta($userid, $key, $value);
            }
            update_user_meta($userid, 'pm_' . $provider . '_connected', 1);
            update_user_meta($userid, 'pm_default_social_avatar', $provider);
            ?>
            <script type="text/javascript">
                alert('<?php _e('You are now connected', 'profilegrid-social-connect'); ?>');
                window.location.reload(true);
            </script>
<?php
        }
    }

    public function pg_social_registration_process_old($profile, $provider)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $pmemails = new PM_Emails;


        $pm_admin_notification = $dbhandler->get_global_option_value('pm_admin_notification', 0);
        $user_exists = $this->pg_user_exists($profile, $provider);
        if ($user_exists) {
            do_action("pg_blocked_user_ip", $args = array());
            do_action("pg_blocked_user_email", $args = array('username' => $profile['username']));

            $userstatus = get_user_meta($user_exists, 'rm_user_status', true);
            $user_groups = $pmrequests->profile_magic_get_user_field_value($user_exists, 'pm_group');
            if (!in_array($profile['gid'], $user_groups)) {
                // User group allotment based on group type 
                $group_type = $pmrequests->profile_magic_get_group_type($profile['gid']);
                $pmrequests->profile_magic_join_group_fun($user_exists, $profile['gid'], $group_type);
            }

            if ($userstatus == 0) {
                foreach ($profile as $key => $value) {
                    update_user_meta($user_exists, $key, $value);
                }
                update_user_meta($user_exists, 'pm_' . $provider . '_connected', 1);
                if (get_user_meta($user_exists, 'pm_user_payment_status', true) == 'pending') {
                    wp_clear_auth_cookie();

                    $login_url = $pmrequests->profile_magic_get_frontend_url('pm_user_login_page', site_url('/wp-login.php'));
                    $login_url = add_query_arg('errors', 'payment_pending', $login_url);
                    $login_url = add_query_arg('id', $user_exists, $login_url);
                    wp_redirect($login_url);
                } else {
                    wp_set_auth_cookie($user_exists, true);
                    $redirect_url = $dbhandler->get_global_option_value('pm_redirect_after_login', 0);
                    if ($redirect_url == 0) {
                        $url = home_url('wp-admin');
                    } else {
                        $url = get_permalink($redirect_url);
                    }
                    wp_redirect(esc_url_raw($url));
                }
            } else {

                $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_user_login_page', site_url('/wp-login.php'));
                $redirect_url = add_query_arg('errors', 'account_disabled', $redirect_url);
                wp_redirect($redirect_url);
            }
        } else {
            do_action("pg_blocked_user_ip", $args = array());
            do_action("pg_blocked_user_email", $args = array('username' => $profile['username']));

            $password = wp_generate_password(10, false);
            $user_role = $dbhandler->get_value('GROUPS', 'associate_role', $profile['gid'], 'id');
            $user_id = wp_create_user($profile['username'], $password, $profile['email_exists']);
            if (is_multisite()) {
                //Creates new WP user after successful registration
                if (is_numeric($user_id)) {
                    $user_id = wp_update_user(array('ID' => $user_id, 'role' => $user_role));
                    $blog_id = get_current_blog_id();
                    if (!is_user_member_of_blog($user_id, $blog_id))
                        add_user_to_blog($blog_id, $user_id, $user_role);
                }
            } else {
                if (is_numeric($user_id)) {
                    $user_id = wp_update_user(array('ID' => $user_id, 'role' => $user_role));
                }
            }

            update_user_meta($user_id, 'pm_' . $provider . '_connected', 1);


            // User group allotment based on group type 
            $group_type = $pmrequests->profile_magic_get_group_type($profile['gid']);
            $pmrequests->profile_magic_join_group_fun($user_id, $profile['gid'], $group_type);

            foreach ($profile as $key => $value) {
                update_user_meta($user_id, $key, $value);
            }

            $price = $pmrequests->profile_magic_check_paid_group($profile['gid']);
            if ($price > 0) {
                update_user_meta($user_id, 'pm_user_payment_status', 'pending');
            }

            $pm_admin_notification = $dbhandler->get_global_option_value('pm_admin_notification', 0);
            $fields =  $dbhandler->get_all_result('FIELDS', $column = '*', array('associate_group' => $profile['gid'], 'show_in_signup_form' => 1), 'results', 0, false, $sort_by = 'ordering');
            if ($pm_admin_notification == 1) {
                $exclude = array('user_avatar', 'file', 'user_pass', 'confirm_pass', 'heading', 'paragraph');
                $admin_html = $pmrequests->pm_admin_notification_message_html($profile, $profile['gid'], $fields, $exclude);
                $subject = __('New User Created', 'profilegrid-social-connect');
                $admin_message = '<p>' . __('New user created', 'profilegrid-social-connect') . '</p>' . $admin_html;
                $pmemails->pm_send_admin_notification($subject, $admin_message);
            }

            $pm_auto_approval = $dbhandler->get_global_option_value('pm_auto_approval', '0');
            if ($pm_auto_approval) {
                $send_user_activation_link = $dbhandler->get_global_option_value('pm_send_user_activation_link', 0);
                if ($send_user_activation_link == '1') {
                    update_user_meta($user_id, 'rm_user_status', '1');
                    $pmrequests->pm_update_user_activation_code($user_id);
                    $pmemails->pm_send_activation_link($user_id, $this->profile_magic);
                    $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_user_login_page', site_url('/wp-login.php'));
                    $redirect_url = add_query_arg('errors', 'need_activation', $redirect_url);
                    wp_redirect($redirect_url);
                } else {

                    $payment_status = get_user_meta($user_id, 'pm_user_payment_status', true);
                    if ($price > 0 && $payment_status == 'pending') {
                        wp_clear_auth_cookie();
                        $invoice = date("His") . rand(1234, 9632);
                        update_user_meta($user_id, 'pm_invoice', $invoice);
                        update_user_meta($user_id, 'rm_user_status', '1');
                        $login_url = $pmrequests->profile_magic_get_frontend_url('pm_user_login_page', site_url('/wp-login.php'));
                        $login_url = add_query_arg('errors', 'payment_pending', $login_url);
                        $login_url = add_query_arg('id', $user_id, $login_url);
                        wp_redirect($login_url);
                    } else {
                        update_user_meta($user_id, 'rm_user_status', '0');
                        wp_set_auth_cookie($user_id, true);
                        $redirect_url = $dbhandler->get_global_option_value('pm_redirect_after_login', 0);
                        if ($redirect_url == 0) {
                            $url = home_url('wp-admin');
                        } else {
                            $url = get_permalink($redirect_url);
                        }
                        wp_redirect(esc_url_raw($url));
                    }
                }
            } else {
                update_user_meta($user_id, 'rm_user_status', '1');
                $accnt_review_notification = $dbhandler->get_global_option_value('pm_admin_account_review_notification', 0);
                if ($pm_admin_notification == 1 && $accnt_review_notification == 1) {
                    $review_subject = $dbhandler->get_global_option_value('pm_account_review_email_subject', __('New user awaiting review', 'profilegrid-social-connect'));
                    $review_body = $dbhandler->get_global_option_value('pm_account_review_email_body', __('{{display_name}} has just registered in {{group_name}} group and waiting to be reviewed. To review this member please click the following link: {{profile_link}}', 'profilegrid-social-connect'));
                    $review_body = $pmemails->pm_filter_email_content($review_body, $user_id);
                    $pmemails->pm_send_admin_notification($review_subject, $review_body);
                }

                $payment_status = get_user_meta($user_id, 'pm_user_payment_status', true);
                if ($price > 0 && $payment_status == 'pending') {
                    wp_clear_auth_cookie();
                    $invoice = date("His") . rand(1234, 9632);
                    update_user_meta($user_id, 'pm_invoice', $invoice);
                    $login_url = $pmrequests->profile_magic_get_frontend_url('pm_user_login_page', site_url('/wp-login.php'));
                    $login_url = add_query_arg('errors', 'payment_pending', $login_url);
                    $login_url = add_query_arg('id', $user_id, $login_url);
                    wp_redirect($login_url);
                } else {
                    $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_user_login_page', site_url('/wp-login.php'));
                    $redirect_url = add_query_arg('errors', 'account_disabled', $redirect_url);
                    wp_redirect($redirect_url);
                }
            }
        }
    }

    public function pg_authenticate_and_redirect($uid)
    {
        $dbhandler = new PM_DBhandler;
        if (!is_user_logged_in()) {
            wp_set_auth_cookie($uid, true);
            $user = get_user_by('ID', $uid);
            $user_name = $user->user_login;
            do_action('wp_login', $user_name, $user);
        }
        $redirect_url = $dbhandler->get_global_option_value('pm_redirect_after_login', 0);
        if ($redirect_url == 0) {
            $url = home_url('wp-admin');
        } else {
            $url = get_permalink($redirect_url);
        }
        wp_redirect(esc_url_raw($url));
        exit;
    }

    public function pg_social_registration_process($profile, $provider)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $pmemails = new PM_Emails;
        $pm_admin_notification = $dbhandler->get_global_option_value('pm_admin_notification', 0);
        $user_exists = $this->pg_user_exists($profile, $provider);
        if (isset($profile['gid'])) {
            $gid = $profile['gid'];
        } else {
            $gid = '0';
        }

        $is_paid_group = $pmrequests->profile_magic_check_paid_group($gid);
        //$price = $pmrequests->profile_magic_check_paid_group($profile['gid']);
        if ($user_exists) {

            $userstatus = get_user_meta($user_exists, 'rm_user_status', true);
            if ($userstatus == 0) {
                $user_groups = maybe_unserialize($pmrequests->profile_magic_get_user_field_value($user_exists, 'pm_group'));
                if (!isset($user_groups) || empty($user_groups)) {
                    $user_groups = array();
                }
                $group_type = $pmrequests->profile_magic_get_group_type($gid);

                wp_set_auth_cookie($user_exists, true);

                if (!in_array($gid, $user_groups)) {

                    // User group allotment based on group type 
                    if ($is_paid_group > 0) {
                        $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page', site_url('/wp-login.php'));
                        $redirect_url = add_query_arg('gid', $gid, $redirect_url);
                        $redirect_url = add_query_arg('profile', $profile, $redirect_url);
                        wp_redirect($redirect_url);
                        exit;
                    } else {
                        $pmrequests->profile_magic_join_group_fun($user_exists, $gid, $group_type);
                    }
                }

                foreach ($profile as $key => $value) {
                    update_user_meta($user_exists, $key, $value);
                }

                if ($group_type == 'open') {
                    update_user_meta($user_exists, 'pm_' . $provider . '_connected', 1);
                    $this->pg_authenticate_and_redirect($user_exists);
                    exit;
                } else {
                    if (!in_array($gid, $user_groups)) {
                        update_user_meta($user_exists, 'pm_' . $provider . '_connected', 1);
                        $group_url  = $pmrequests->profile_magic_get_frontend_url('pm_group_page', '');
                        $group_url = add_query_arg('gid', $gid, $group_url);
                        wp_redirect($group_url);
                        exit;
                    } else {
                        update_user_meta($user_exists, 'pm_' . $provider . '_connected', 1);
                        $this->pg_authenticate_and_redirect($user_exists);
                        exit;
                    }
                }
            } else {
                $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_user_login_page', site_url('/wp-login.php'));
                $redirect_url = add_query_arg('errors', 'account_disabled', $redirect_url);
                wp_redirect($redirect_url);
                exit;
            }
        } else {
            do_action("pg_blocked_user_ip", $args = array());
            do_action("pg_blocked_user_email", $args = array('username' => $profile['username']));

            $password = wp_generate_password(10, false);
            $user_role = $dbhandler->get_value('GROUPS', 'associate_role', $profile['gid'], 'id');
            $user_id = $dbhandler->pm_add_user($profile['username'], $password, $profile['email_exists'], $user_role);
            $group_type = $pmrequests->profile_magic_get_group_type($profile['gid']);
            $pmrequests->profile_magic_join_group_fun($user_id, $profile['gid'], $group_type);
            update_user_meta($user_id, 'pm_' . $provider . '_connected', 1);

            foreach ($profile as $key => $value) {
                update_user_meta($user_id, $key, $value);
            }

            $fields =  $dbhandler->get_all_result('FIELDS', $column = '*', array('associate_group' => $profile['gid'], 'show_in_signup_form' => 1), 'results', 0, false, $sort_by = 'ordering');
            do_action('profile_magic_registration_process', $profile, array(), array(), $profile['gid'], $fields, $user_id, 'profile-magic');
            if ($is_paid_group == 0) {
                if ($group_type == 'open') {
                    $this->pg_authenticate_and_redirect($user_id);
                    exit;
                } else {
                    wp_set_auth_cookie($user_id, true);
                    $group_url  = $pmrequests->profile_magic_get_frontend_url('pm_group_page', '');
                    $group_url = add_query_arg('gid', $profile['gid'], $group_url);
                    wp_redirect($group_url);
                    exit;
                }
            }
        }
    }


    public function pg_user_exists($profile, $provider)
    {
        if (email_exists($profile['email_exists']))
            return email_exists($profile['email_exists']);
        if (username_exists($profile['username_exists']))
            return username_exists($profile['username_exists']);

        // especially for twitter
        if (isset($profile['user_login'])) {
            $args = array('meta_key' => 'user_login', 'meta_value' => $profile['user_login'],);
            $user_obj = get_users($args);
            if (!empty($user_obj)) {
                return $user_obj[0]->data->ID;
            }
        }
        return 0;
    }

    public function pg_get_social_avatar($avatar, $id_or_email, $size, $args)
    {
        if (class_exists('Profile_Magic')) {
            $path =  plugin_dir_url(__FILE__);
            $dbhandler = new PM_DBhandler;
            $pmrequests = new PM_request;
            /*
             * Gets the user_id by id or email
             */
            if (is_numeric($id_or_email)) {
                $id = (int) $id_or_email;
                $user = get_user_by('ID', $id);
            } elseif (is_object($id_or_email)) {
                if (! empty($id_or_email->user_id)) {
                    $id = (int) $id_or_email->user_id;
                    $user = get_user_by('id', $id);
                }
            } else {
                $user = get_user_by('email', $id_or_email);
            }
            /*
             * Give priority to profile image for users
             */
            if (isset($user) && !empty($user)) {
                $avatarid = $pmrequests->profile_magic_get_user_field_value($user->data->ID, 'pm_user_avatar');

                if (isset($avatarid) && $avatarid != '') {
                    return $avatar;
                }

                /*
                 * If, no users profile picture set social profile picture, if connected
                 */
                $pg_social_connections = array('facebook', 'google', 'twitter', 'linkedin');
                $pg_active_connections = array();
                foreach ($pg_social_connections as $connection) {
                    if (get_user_meta($user->data->ID, 'pm_' . $connection . '_connected', true) == 1) {
                        array_push($pg_active_connections, $connection);
                    }
                }
                /*
                 * Check if any active connection is default avatar connection
                 */

                $pm_default_social_avatar = get_user_meta($user->data->ID, 'pm_default_social_avatar', true);
                if (!empty($pg_active_connections)) {
                    if ($pm_default_social_avatar != '') {
                        if (in_array($pm_default_social_avatar, $pg_active_connections)) {
                            $pm_avatar = get_user_meta($user->data->ID, 'pm_' . $pm_default_social_avatar . '_profile_photo', true);
                            if (!empty($pm_avatar)) {
                                if ($pm_default_social_avatar == 'linkedin') {
                                    $pm_avatar = urldecode($pm_avatar);
                                }
                                return '<img src="' . $pm_avatar . '" width="' . $size . '" height="' . $size . '" class="user-profile-image" />';
                            } else {
                                $path =  plugin_dir_url(__FILE__);
                                if (is_super_admin($user->data->ID)):
                                    $pm_avatar = $path . 'partials/images/admin-default-user.jpg';
                                else:
                                    $pm_avatar = $path . 'partials/images/default-user.png';
                                endif;

                                return '<img src="' . $pm_avatar . '" width="' . $size . '" height="' . $size . '" class="user-profile-image" />';
                            }
                        }
                    } else {

                        $rand_default = $pg_active_connections[0];
                        update_user_meta($user->data->ID, 'pm_default_social_avatar', $rand_default);
                        $pm_avatar = get_user_meta($user->data->ID, 'pm_' . $rand_default . '_profile_photo', true);
                        if (!empty($pm_avatar)) {
                            return '<img src="' . $pm_avatar . '" width="' . $size . '" height="' . $size . '" class="user-profile-image" />';
                        } else {
                            $path =  plugin_dir_url(__FILE__);
                            if (is_super_admin($user->data->ID)):
                                $pm_avatar = $path . 'partials/images/admin-default-user.jpg';
                            else:
                                $pm_avatar = $path . 'partials/images/default-user.png';
                            endif;
                            return '<img src="' . $pm_avatar . '" width="' . $size . '" height="' . $size . '" class="user-profile-image" />';
                        }
                    }
                } else {
                    return $avatar;
                }
            } else {
                return $avatar;
            }
        }
    }

    public function pm_submit_user_registration_social($post, $files, $server, $gid, $fields, $user_id, $textdomain)
    {
        if (isset($post['socialaction'])) {
            foreach ($post as $key => $value) {
                update_user_meta($user_id, $key, $value);
            }
        }
    }

    public function pgStartSession()
    {
        if (!session_id() && !headers_sent()) {
            session_start();
        }

        //Killing session if Health Check is active
        if (is_admin()) {
            if ((isset($_REQUEST['page']) && $_REQUEST['page'] === 'health-check') || (isset($_REQUEST['action']) && in_array($_REQUEST['action'], array('health-check-site-status', 'health-check-loopback-requests'))) || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'site-health'))) {
                session_unset();
                session_destroy();
            }
        }
    }




    public function pgEndSession()
    {
        session_destroy();
    }

    public function pg_save_temp_login_data()
    {
        $_SESSION['login_provider'] = $_POST['provider'];
        $_SESSION['facebook_gid'] = $_POST['gid'];
        echo '1';
        die;
    }
}
