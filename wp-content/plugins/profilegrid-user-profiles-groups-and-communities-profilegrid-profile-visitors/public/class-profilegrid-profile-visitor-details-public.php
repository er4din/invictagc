<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_profile_visitor_details
 * @subpackage Profilegrid_profile_visitor_details/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_profile_visitor_details
 * @subpackage Profilegrid_profile_visitor_details/public
 * @author     Your Name <email@example.com>
 */
class Profilegrid_profile_visitor_details_Public
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
     * @param      string    $Profilegrid_profile_visitor_details       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($Profilegrid_profile_visitor_details, $version)
    {

        $this->Profilegrid_profile_visitor_details = $Profilegrid_profile_visitor_details;
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
         * defined in Profilegrid_profile_visitor_details_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Profilegrid_profile_visitor_details_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->Profilegrid_profile_visitor_details, plugin_dir_url(__FILE__) . 'css/profilegrid-profile-visitor-details-public.css', array(), $this->version, 'all');
        wp_enqueue_style( 'pg-visitor-toast-css', plugin_dir_url( __FILE__ ) . 'css/jquery.toast.min.css', false, $this->version );
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
         * defined in Profilegrid_profile_visitor_details_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Profilegrid_profile_visitor_details_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        $nonce = wp_create_nonce('pm_update_tracking_nonce');
        wp_enqueue_script('jquery');
        //wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script($this->Profilegrid_profile_visitor_details, plugin_dir_url(__FILE__) . 'js/profilegrid-profile-visitor-details-public.js', array('jquery'), $this->version, true);
        wp_localize_script($this->Profilegrid_profile_visitor_details, 'pm_ajax_visitors_object', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => $nonce));
        wp_enqueue_script( 'pg-visitor-toast-js', plugin_dir_url( __FILE__ ) . 'js/jquery.toast.min.js', array( 'jquery' ), $this->version );
        wp_enqueue_script( 'pg-visitor-toast-message-js', plugin_dir_url( __FILE__ ) . 'js/toast-message.js', array( 'jquery' ), $this->version );
    }


    public function pm_update_page_visit_stats()
    {
        
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $identifier = 'profile_visitor_details';
        $pm_profile_page = $dbhandler->get_global_option_value('pm_user_profile_page', '0');
        $current_page = get_the_ID();
        // $HttpReferer = $this->pm_get_HttpReferer();
        // $HttpReferer = ( empty($HttpReferer) ? "Direct" : $HttpReferer );
        // echo $HttpReferer;
        if ($dbhandler->get_global_option_value('pm_enable_profile_visitor_details') == 1) {
            if (is_user_logged_in()) {
                $current_user = wp_get_current_user();
                $current_user_id = $current_user->ID;

                $allow_visitor_detail_to_user = $dbhandler->get_global_option_value('pm_profile_visitor_details_to_users', '0');
                $allow_user_to_stop_tracking = $dbhandler->get_global_option_value('pm_enable_user_to_opt_out', '0');
                $disable_tracking = get_user_meta($current_user_id, 'disable_tracking', true);
                if ($allow_visitor_detail_to_user == 1 && $allow_user_to_stop_tracking == 1) {
                    if ($disable_tracking == 1) {
                        return;
                    }
                }
            } else {
                $current_user_id = 0;
            }
            // filter_input(INPUT_GET, "email", FILTER_VALIDATE_EMAIL);
            //$uid = filter_input(INPUT_GET, 'uid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // print_r($uid);
            $uid = get_query_var('uid');
            if(!$uid){
                if(!empty(filter_input(INPUT_GET, 'uid'))){
                    $uid = rtrim(filter_input(INPUT_GET, 'uid'),'/\\');
                }
            }
            if(isset($uid)){
                $user_profile_id = $pmrequests->pm_get_uid_from_profile_slug($uid);
            }else {
                $user_profile_id = $current_user_id;
            }
            
            if (!empty($user_profile_id) && $user_profile_id <> $current_user_id  && $current_page = $pm_profile_page) {

                $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
                $browser_details = $this->pm_get_Browser();
                $ip_address = $this->pm_get_user_ip_address();
                $locData = $this->ip_info($ip_address);
                if (is_array($locData) && isset($locData['country'])) {
                    $country = $locData['country'];
                } else {
                    $country = esc_html__("unknown", 'profilegrid-profile-visitor-details');
                }
                $country = array('country' => $country);
                $visitor_count_time_span =     $dbhandler->get_global_option_value('pm_visitor_count_span', '10');
                $lasttime = $dbhandler->get_all_result($identifier, 'timestamp', array('uid' => $user_profile_id, 'visitor_id' => $current_user_id, 'ip_address' => $ip_address), 'var', $offset = 0, $limit = false, $sort_by = 'timestamp', $descending = true);
                $date = current_time('mysql');
                if (!empty($lasttime)):
                    $differenceTimespan = round(abs(strtotime($date) - strtotime($lasttime)) / 60, 2); //minute 
                else:
                    $differenceTimespan = 10;
                endif;

                if (!($pageWasRefreshed)  && $differenceTimespan >= $visitor_count_time_span) {
                    $meta_details = array_merge($browser_details, $country);
                    
                    $data = array('uid' => $user_profile_id, 'visitor_id' => $current_user_id, 'meta_details' => $meta_details, 'timestamp' => current_time('mysql'), 'ip_address' => $ip_address);
                    $data = $pmrequests->sanitize_request($data, $identifier);
                    $inserted = $dbhandler->insert_row($identifier, $data);
                }
            }
        }
    }


    public function pm_get_Browser()
    {
        $browserInfo = array('user_agent' => '', 'browser' => '', 'browser_version' => '', 'os_platform' => '', 'pattern' => '', 'device' => '');
        $u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
        $bname = 'Unknown';
        $ub = 'Unknown';
        $version = "";
        $platform = 'Unknown';
        $deviceType = 'Desktop';
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $u_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($u_agent, 0, 4))) {
            $deviceType = 'Mobile';
        }
        if (isset($_SERVER['HTTP_USER_AGENT']) && sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) == 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10') {
            $deviceType = 'Tablet';
        }

        if (stristr(isset($_SERVER['HTTP_USER_AGENT']) && sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])), 'Mozilla/5.0(iPad;')) {
            $deviceType = 'Tablet';
        }

        //$detect = new Mobile_Detect();

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the user agent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'IE';
            $ub = "MSIE";
        } else if (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } else if (preg_match('/Chrome/i', $u_agent) && (!preg_match('/Opera/i', $u_agent) && !preg_match('/OPR/i', $u_agent))) {
            $bname = 'Chrome';
            $ub = "Chrome";
        } else if (preg_match('/Safari/i', $u_agent) && (!preg_match('/Opera/i', $u_agent) && !preg_match('/OPR/i', $u_agent))) {
            $bname = 'Safari';
            $ub = "Safari";
        } else if (preg_match('/Opera/i', $u_agent) || preg_match('/OPR/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } else if (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        } else if ((isset($u_agent) && (strpos($u_agent, 'Trident') !== false || strpos($u_agent, 'MSIE') !== false))) {
            $bname = 'Internet Explorer';
            $ub = 'Internet Explorer';
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1] ?? '';
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'browser'      => $bname,
            'browser_version'   => $version,
            'os_platform'  => $platform,
            'pattern'   => $pattern,
            'device'    => $deviceType
        );
    }

    public function pm_get_HttpReferer()
    {
        $http_referer = (isset($_SERVER['HTTP_REFERER']) ? filter_var(wp_unslash($_SERVER['HTTP_REFERER']), FILTER_SANITIZE_URL) : '');
        return $http_referer;
    }
    public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {

            if ($deep_detect) {
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']), FILTER_VALIDATE_IP)) {
                    $ip = filter_var(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']), FILTER_VALIDATE_IP);
                } else {
                    $ip = esc_html__("Unknown", 'profilegrid-profile-visitor-details');
                }
                if (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var(wp_unslash($_SERVER['HTTP_CLIENT_IP']), FILTER_VALIDATE_IP)) {
                    $ip = filter_var(wp_unslash($_SERVER['HTTP_CLIENT_IP']), FILTER_VALIDATE_IP);
                }
            }
        }
        $purpose = str_replace(array(
            "name",
            "\n",
            "\t",
            " ",
            "-",
            "_"
        ), '', strtolower(trim($purpose)));
        $support = array(
            "country",
            "countrycode",
            "state",
            "region",
            "city",
            "location",
            "address"
        );
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America",
        );

        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        	
        	$pg_transient_key = 'profilegrid_geo_' . md5( $ip );
			
			$ipdat = get_transient( $pg_transient_key );
		    if ( false == $ipdat ) {
			        
			    

	            $api_url = "https://ipwho.is/" . $ip;

	            // Use wp_remote_get to fetch data
	            $response = wp_remote_get($api_url);
	            
	            if (is_wp_error($response)) {
	                // Handle the error
	                $error_message = $response->get_error_message();
	                // Log the error or display a message
	                // echo("API request failed: $error_message");
	                return; // or handle accordingly
	            }
	            $details = wp_remote_retrieve_body($response);
	            
	            // curl_close($ch);
	            $ipdat = json_decode($details, true);
	            if (json_last_error() !== JSON_ERROR_NONE) {
	                // Handle JSON decode error
	                // echo("JSON decode error: " . json_last_error_msg());
	                return; // or handle accordingly
	            }
	            set_transient( $pg_transient_key, $ipdat, 30 * DAY_IN_SECONDS );

            }
            if (isset($ipdat['success']) && $ipdat['success'] === true ) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => $ipdat['city'] ?? '',
                            "state"          => $ipdat['region'] ?? '',
                            "country"        => $ipdat['country'] ?? '',
                            "country_code"   => $ipdat['country_code']?? '',
                            "continent"      => isset($ipdat['continent']) ?
                            ($continents[strtoupper($ipdat['continent_code'])] ?? '') : '',
                            "continent_code" => $ipdat['continent_code'] ?? '',
                        );
                        break;
                    case "address":
                        $address = array($ipdat['country']);
                        if (!empty($ipdat['region'])) {
                            $address[] = $ipdat['region'];
                        }
                        if (!empty($ipdat['city'])) {
                            $address[] = $ipdat['city'];
                        }
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = $ipdat['city'] ?? '';
                        break;
                    case "state":
                        $output = $ipdat['region'] ?? '';
                        break;
                    case "region":
                        $output = $ipdat['region'] ?? '';
                        break;
                    case "country":
                        $output = $ipdat['country'] ?? '';
                        break;
                    case "countrycode":
                        $output = $ipdat['country_code'] ?? '';
                        break;
                }
            }
        }

        return $output;
    }
    public function pm_get_user_ip_address()
    {
        $dbhandler = new PM_DBhandler;
        
        if ($dbhandler->get_global_option_value('pm_record_visitor_ip', '0') == 1) {
        // First try to get a valid IPv4 address
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = filter_var(wp_unslash($_SERVER['HTTP_CLIENT_IP']), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = filter_var(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = filter_var(wp_unslash($_SERVER['REMOTE_ADDR']), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
    
        // If no valid IPv4 address is found, try to get an IPv6 address
        if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = filter_var(wp_unslash($_SERVER['HTTP_CLIENT_IP']), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = filter_var(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = filter_var(wp_unslash($_SERVER['REMOTE_ADDR']), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
            }
        }
        if($ip == '::1'){
            $ip = esc_html__("127.0.0.1(localhost)", 'profilegrid-profile-visitor-details');
        }

        if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP) && $ip !== '127.0.0.1(localhost)') {
            $ip = esc_html__("Unknown", 'profilegrid-profile-visitor-details');
        }
    }else{
        $ip = esc_html__("Not Recorded", 'profilegrid-profile-visitor-details');
    }

        return $ip;
    }

    public function pg_visitors_details_tab($uid, $gid)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        if ($dbhandler->get_global_option_value('pm_profile_visitor_details_to_users', '0') == 1) {
            $title = $dbhandler->get_global_option_value('pm_profile_visitor_details_tab_title', __('Visitors Detail', 'profilegrid-profile-visitor-details'));
            echo ' <li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" onclick="pm_display_visitor_list();" href="#pg_visitor_details_tab">' . esc_html($title) . '</a></li>';
        }
    }

    public function pg_visitors_details_tab_content($uid, $gid)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        if ($dbhandler->get_global_option_value('pm_profile_visitor_details_to_users', '0') == 1) {
            echo '<div id="pg_visitor_details_tab" class="pm-blog-desc-wrap pm-difl pm-section-content">';
            //  include 'partials/profilegrid-profile-visitor-details-public-display.php';
            echo '</div>';
        }
    }

    public function pm_display_detailed_report()
    {

        $this->enqueue_scripts();
        $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $limit = $dbhandler->get_global_option_value('pm_visitor_per_page');
        $vid =  filter_input(INPUT_POST, 'vid');
        $pagenum =  filter_input(INPUT_POST, 'pagenum');

        $identifier = 'profile_visitor_details';
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $current_user_id = $current_user->ID;
        }
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
                <div class="pg-visitor-container-section">
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
                    <?php echo '<div class="pm-visitor_details_detailed_report">' . (!empty($pagination) ? wp_kses($pagination, $allowed_html) : '') . '</div>'; ?>
                    <div><a onclick="pm_display_visitor_list();"><?php esc_html_e('Back', 'profilegrid-profile-visitor-details'); ?></a></div>
                </div>
            <?php
            }

        endif;
        die;
    }


    public function pm_display_visitor_list()
    {
        $this->enqueue_scripts();
        $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $pagenum =  filter_input(INPUT_POST, 'pagenum');
        $limit1 = $dbhandler->get_global_option_value('pm_visitor_per_page');
        $offset = ($pagenum - 1) * 2;

        $identifier = 'profile_visitor_details';
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $current_user_id = $current_user->ID;
        }
        $visitor_count = 0;
        $visitor_details = $dbhandler->get_all_result($identifier, 'DISTINCT visitor_id', array('uid' => $current_user_id), 'results', 0, false, 'timestamp', true);
        if (isset($visitor_details)):
            $visitor_count = count($visitor_details);
        endif;
        $allow_user_to_stop_tracking = $dbhandler->get_global_option_value('pm_enable_user_to_opt_out', '0');
        $disable_tracking = get_user_meta($current_user_id, 'disable_tracking', true);

        $uid = filter_input(INPUT_GET, 'uid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user_profile_id = isset($uid) ? $pmrequests->pm_get_uid_from_profile_slug(sanitize_text_field(wp_unslash($uid))) : $current_user_id;

        $user_groups = $pmrequests->profile_magic_get_user_field_value($user_profile_id, 'pm_group');
        if (!is_array($user_groups)) {
            $user_groups = explode(',', $user_groups);
        }
        //print_r($user_groups);
        $selected_groups = maybe_unserialize($dbhandler->get_global_option_value('pm_selected_groups', ''));
        if (!is_array($selected_groups)) {
            $selected_groups = explode(',', $selected_groups);
        }
        //print_r($selected_groups);

        $pm_Disable_Tracking = false;
        if ($allow_user_to_stop_tracking == 1) {
            if ($dbhandler->get_global_option_value('pm_enable_specific_group_opt_out') == 1) {
                $pm_Disable_Tracking = (!empty(array_intersect($user_groups, $selected_groups)));
            } else {
                $pm_Disable_Tracking = true;
            }
        }

        if ($pm_Disable_Tracking) {
            //print_r($disable_tracking);
            ?>
            <div class="pg-visitor-container-section">
            <div class="uimrow">
                <div class="uimfield">
                    <?php esc_html_e('Disable Tracking', 'profilegrid-profile-visitor-details'); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_Disable_Tracking" id="pm_Disable_Tracking" type="checkbox" class="pm_toggle" value="1" style="display:none;" <?php checked($disable_tracking, '1'); ?> />
                    <label for="pm_Disable_Tracking"></label>
                </div>
            </div>
            <?php
        } else {
            update_user_meta($current_user_id, 'disable_tracking', 0);
        }

        $count = 1;
        if ($visitor_count > 0) {
            $offset = ($pagenum - 1) * $limit1;
            $num_of_pages = ceil($visitor_count / $limit1);
            $pagination = $dbhandler->pm_get_pagination($num_of_pages, $pagenum);
            $visitor_details_page = $dbhandler->get_all_result($identifier, 'DISTINCT visitor_id', array('uid' => $current_user_id), 'results', $offset, $limit1, 'timestamp', true);
            if ($pagenum <= $num_of_pages) {

            ?>

                <div id="pm_visitor_report">
                    <div class="pm_visitor_counts">
                        <div class="pm-visitor-count-content">
                            <?php esc_html_e('Total Visit Counts', 'profilegrid-profile-visitor-details'); ?> : <span class=""><?php echo esc_html($this->pm_get_profile_visitor_count($current_user_id)); ?></span>
                        </div>
                    </div>

                    <div class="pg-visitors-container">
                        <table class="pg-visitors-table">
                            <thead>
                            <tr class="pg-table-header pm-bg">
                                
                                <th class="pg-header-item"><?php esc_html_e('No.', 'profilegrid-profile-visitor-details'); ?></th>
                                <th class="pg-header-item"><?php esc_html_e('Profile Picture', 'profilegrid-profile-visitor-details'); ?></th>
                                <th class="pg-header-item"><?php esc_html_e('User', 'profilegrid-profile-visitor-details'); ?></th>
                                <th class="pg-header-item"><?php esc_html_e('Last Visited Date', 'profilegrid-profile-visitor-details'); ?></th>
                                <th class="pg-header-item"><?php esc_html_e('Visit Count', 'profilegrid-profile-visitor-details'); ?></th>
                                <th class="pg-header-item"><?php esc_html_e('Profile Link', 'profilegrid-profile-visitor-details'); ?> </th>
                                <th class="pg-header-item"><?php esc_html_e('Detailed Report', 'profilegrid-profile-visitor-details'); ?></th>
                                
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
                                    <tr class="pg-table-row">
                                        <td class="pg-table-data"><?php echo esc_html(($limit1 * ($pagenum - 1)) + $count); ?> </td>
                                        <td class="pg-table-data pg-visitor-profile-data"><?php echo wp_kses($Profile_Picture, $allowed_html )?> </td>
                                        <td class="pg-table-data"><?php echo wp_kses_post($visitor_name); ?> </td>
                                        <td class="pg-table-data"><?php echo esc_html($user_details[0]->timestamp); ?> </td>
                                        <td class="pg-table-data"><?php echo esc_html(count($user_details)); ?> </td>
                                        <td class="pg-table-data">
                                            <?php if ($visitor_id != 0): ?>
                                                <a target="_blank" href="<?php echo esc_url($profile_url); ?>"><?php esc_html_e('View Profile', 'profilegrid-profile-visitor-details'); ?>
                                        </td>
                                    <?php else: ?>
                                        <?php echo "--"; ?>
                                    <?php endif; ?>
                                    </td>
                                    <td class="pg-table-data"><a onclick="pm_display_detailed_report(<?php echo esc_js($visitor_id); ?>,1);"><?php esc_html_e('View', 'profilegrid-profile-visitor-details'); ?></a></a>
                                    </tr>
                            </tbody>

                        <?php $count++;
                                }
                        ?>
                        </table>
                    </div>


                    <?php echo '<div class="pm-visitor_details_list">' . (!empty($pagination) ? wp_kses($pagination, $allowed_html) : '') . '</div>'; ?>
                </div>
                <?php
                if ($dbhandler->get_global_option_value('pm_enable_user_to_reset_visitors') == 1) {
                ?>
                    <!-- </div> -->
                    <div class="pg-reset-counter">
                        <input type="button" id="reset_counter" onclick="pm_reset_profile_visitor_counter(<?php echo esc_js($current_user_id); ?>);" value="<?php esc_attr_e('Reset Visitor Counter', 'profilegrid-profile-visitor-details'); ?>" />
                    </div>
                </div>
            <?php
                }
            }
        } else {

            ?>
            <div class="pg-alert-warning pg-alert-info"><?php esc_html_e('No Details Found.', 'profilegrid-profile-visitor-details'); ?></div>
<?php
        };
        die;
    }


    public function pm_reset_profile_visitor_counter()
    {
        $dbhandler = new PM_DBhandler;
        $identifier = 'profile_visitor_details';
        $userid =  filter_input(INPUT_POST, 'uid');
        $return = $dbhandler->remove_row($identifier, 'uid', $userid);
        echo esc_html($return);
        die;
    }

    public function pm_get_profile_visitor_count($uid)
    {
        $dbhandler = new PM_DBhandler;
        $identifier = 'profile_visitor_details';
        $count = $dbhandler->pm_count($identifier, array('uid' => $uid));
        return $count;
    }

    public function pm_update_tracking_status()
    {

        check_ajax_referer('pm_update_tracking_nonce', '_ajax_nonce');
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'User not logged in'));
            wp_die();
        }
        $disable_tracking = isset($_POST['disable_tracking']) ? intval($_POST['disable_tracking']) : 0;

        $user_id = get_current_user_id();

        $update_status = update_user_meta($user_id, 'disable_tracking', $disable_tracking);
        wp_send_json_success(array('message' => 'Tracking status updated'));
        wp_die();
    }
}
