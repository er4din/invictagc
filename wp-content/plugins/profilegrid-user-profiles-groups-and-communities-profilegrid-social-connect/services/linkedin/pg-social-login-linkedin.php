<?php

if ( ! class_exists( 'Pg_Social_Login_LinkedIn' ) ) :
class Pg_Social_Login_LinkedIn {
  const _AUTHORIZE_URL = 'https://www.linkedin.com/uas/oauth2/authorization';

    const _TOKEN_URL = 'https://www.linkedin.com/uas/oauth2/accessToken';

    const _BASE_URL = 'https://api.linkedin.com/v2';

    // LinkedIn Application Key
    public $li_api_key;

    // LinkedIn Application Secret
    public $li_secret_key;

    // Stores Access Token
    public $access_token;

    // Stores OAuth Object
    public $oauth;

    // Stores the user redirect after login
    public $user_redirect = false;

    // Stores our LinkedIn options 
    public $li_options;
    
    public $api_key;
    public $api_secret;
    public $pm_autofill;
    public $template;
    public $gid;
    public $oauth_callback;
    public $login_url;

    private function render_error($message)
    {
        echo '<div class="pg-box-row pg-box-text-center">';
        echo '<div class="pg-alert-warning pg-alert-info">' . esc_html($message) . '</div>';
        echo '</div>';
    }

	function __construct($gid,$template = '') {
           $this->load($gid,$template);
           $this->get_auth($this->oauth_callback);

            // Set LinkedIn keys class variables - These will be used throughout the class
            $this->li_api_key = $this->api_key;
            $this->li_secret_key = $this->api_secret;

            // Require OAuth2 client to process authentications
            require_once('api/OAuth2.php');

            // Create new Oauth client
            $this->oauth = new PG_OAuth2Client($this->li_api_key, $this->li_secret_key, $this->oauth_callback);

            // Set Oauth URLs
            $this->oauth->redirect_uri = $this->oauth_callback;
            $this->oauth->authorize_url = self::_AUTHORIZE_URL;
            $this->oauth->token_url = self::_TOKEN_URL;
            $this->oauth->api_base_url = self::_BASE_URL;

            // Set user token if user is logged in
            if (get_current_user_id()) {
                $this->oauth->access_token = get_user_meta(get_current_user_id(), 'pg_linkedin_access_token', true);
            }
           
            // Start session
            if (!session_id()) {
                session_start();
                
                //Killing session if Health Check is active
                if(is_admin()) {
                    if((isset($_REQUEST['page']) && $_REQUEST['page'] === 'health-check') || (isset($_REQUEST['action']) && in_array($_REQUEST['action'], array('health-check-site-status','health-check-loopback-requests'))) || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'site-health'))) {
                        session_unset();
                        session_destroy();
                    }
                }
            }
	}

	/***
	***	@load
	***/
	function load($gid,$template = '') {
                $dbhandler = new PM_DBhandler;
                $pmrequests = new PM_request;
		$this->api_key = $dbhandler->get_global_option_value('pm_linkedin_api_key');
		$this->api_secret = $dbhandler->get_global_option_value('pm_linkedin_api_secret');
                $this->pm_autofill = $dbhandler->get_global_option_value('pm_enable_autofill_connect');
                
                $this->template = $template;

                if($template == 'profile-tpl')
                {
                    $this->gid        = maybe_serialize($gid);
                    $registration_url = $pmrequests->profile_magic_get_frontend_url('pm_user_profile_page',site_url('/wp-login.php'));
                }
                else
                {
                    $this->gid = $gid;
                     $registration_url = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                }
                
                
		$this->oauth_callback = trailingslashit( $registration_url );
		$this->login_url = '';
	}

	/***
	***	@Get auth
	***/
        
         
    
	function get_auth() {
            $pmrequests = new PM_request;
            $dbhandler = new PM_DBhandler;
            $access_token = '';
          
            $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
             $current_url = remove_query_arg(array('code','state'),$current_url);
           
            // $_SESSION['login_provider']="linkedin";
             
            
            if (isset($_REQUEST['code']) && isset($_SESSION['login_provider']) && $_SESSION['login_provider']=='linkedin') {
                try {
                    $code= isset($_REQUEST['code']) ? $_REQUEST['code'] : false;
                    // Another error occurred, create an error log entry
                    if (isset($_REQUEST['error'])) {
                        $error = $_REQUEST['error'];
                        $error_description = $_REQUEST['error_description'];
                        error_log("LinkedIn Integration Error\nError: $error\nDescription: $error_description");
                        $this->render_error(__('LinkedIn login failed. Please try again.', 'profilegrid-social-connect'));
                        return;
                    }
                  
                    if(isset($_GET['code']))
                    {
                        $response = wp_remote_post("https://www.linkedin.com/oauth/v2/accessToken", [
                            'body' => [
                                'grant_type' => 'authorization_code',
                                'code' => sanitize_text_field($_GET['code']),
                                'redirect_uri' => esc_url($this->oauth_callback),
                                'client_id' => sanitize_text_field($this->api_key),
                                'client_secret' => sanitize_text_field($this->api_secret),
                            ],
                            'headers' => [
                                'Content-Type' => 'application/x-www-form-urlencoded',
                            ],
                        ]);

                        if (is_wp_error($response)) {
                            $this->render_error(sprintf(__('LinkedIn login failed: %s', 'profilegrid-social-connect'), $response->get_error_message()));
                            return;
                        }

                        $body = wp_remote_retrieve_body($response);
                        $data = json_decode($body, true);

                        if (isset($data['access_token'])) {
                            $access_token = $data['access_token'];
                        } else {
                            $this->render_error(__('LinkedIn login failed. Missing access token.', 'profilegrid-social-connect'));
                            return;
                        }
                    }
                } catch (\Throwable $e) {
                    $this->render_error(__('LinkedIn login failed. Please try again later.', 'profilegrid-social-connect'));
                    return;
                }
           // print_r($server_output);
           // die;
                if(isset($_GET['code'])){

                    $response = wp_remote_get("https://api.linkedin.com/v2/userinfo", array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $access_token
                        )
                    ));

                    if (is_wp_error($response)) {
                        $this->render_error(sprintf(__('LinkedIn login failed: %s', 'profilegrid-social-connect'), $response->get_error_message()));
                        return;
                    }

                    $body = wp_remote_retrieve_body($response);
                    $profile = json_decode($body);

               }
            
               
               //    print_r($profile);
                //    die;
                //   echo "---------------------------";
                //   print_r($profile2);
                 //  die;
                    // prepare the array that will be sent
                    $bio['username'] = $profile->email ?? '';	
                    $bio['user_email'] = $profile->email ?? '';
                    $bio['first_name'] = $profile->given_name ?? '';
                    $bio['last_name'] = $profile->family_name ?? '';

                    // username/email exists
                    $bio['email_exists'] = $profile->email ?? '';
                    $bio['username_exists'] = $profile->email ?? '';

                    // provider identifier
                    $bio['_uid_linkedin'] = $profile->sub ?? '';

                    $bio['pm_linkedin_handle'] = ($bio['first_name'] ?? '') . ' ' . ($bio['last_name'] ?? '');
                   // $bio['pm_linkedin_link'] = $profile['public-profile-url'];
                    $bio['gid'] =   $_SESSION['facebook_gid'] ?? '';;
                   
                 
                    if (isset($profile->picture)) {
                        $bio['pm_linkedin_profile_photo'] = $profile->picture;
                    } else {
                        $bio['pm_linkedin_profile_photo'] = '';
                    }
                    
                    if ( email_exists( $bio['email_exists'] ) )
                        $userid = email_exists( $bio['email_exists'] );
                    elseif ( username_exists( $bio['username_exists'] ) )
                        $userid = username_exists( $bio['username_exists'] );
                    else 
                        $userid = 0;
                    
                    if($userid)
                    {
                        if($this->template == 'profile-tpl')
                        {
                            if(!empty($access_token)){
                            do_action('pg_add_social_connection',$bio,'linkedin');
                            }
                        }
                        else
                        {
                            do_action('pg_social_registration',$bio,'linkedin');
                        }
                    }
                    else
                    {    
                        $stripe_enable = $dbhandler->get_global_option_value('pm_enable_stripe','0');
                        $is_paid = $pmrequests->profile_magic_check_paid_group($bio['gid']);
                        if($stripe_enable == 1 && $is_paid > 0)
                        {
                            $bio['pm_linkedin_connected'] = 1;
                            $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                            $redirect_url = add_query_arg( 'gid',$bio['gid'], $redirect_url );
                            $redirect_url = add_query_arg( 'profile',$bio, $redirect_url );
                            wp_redirect($redirect_url);
                        }
                        elseif($this->pm_autofill && $this->template != 'profile-tpl')
                        {
                           // echo 'die';
                            $bio['pm_linkedin_connected'] = 1;
                            $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                            $redirect_url = add_query_arg( 'gid',$bio['gid'], $redirect_url );
                            $redirect_url = add_query_arg( 'profile',$bio, $redirect_url );
                            wp_redirect($redirect_url);
                        }
                        elseif($this->template == 'profile-tpl')
                        {
                            if(!empty($access_token)){
                            do_action('pg_add_social_connection',$bio,'linkedin');
                                }
                        }
                        else
                        {
                            do_action('pg_social_registration',$bio,'linkedin');
                        }
                    }
                
            }
        }
		
	/***
	***	@get login uri
	***/
	function login_url($redirect = false) {
	try {
		$state = wp_generate_password(12, false);
        
        //'profile', 'openid' and 'email' are default values
       
        $this->li_options['li_list_scopes'] = 'openid profile email';

        $authorize_url = $this->oauth->authorizeUrl(array('scope' => $this->li_options['li_list_scopes'],
            'state' => $state));
        
        $_SESSION['li_api_state'] = $state;
        // Store state in database in temporarily till checked back
        if (!isset($_SESSION['li_api_state'])) {
            $_SESSION['li_api_state'] = $state;
        }

        // Store redirect URL in session
        $_SESSION['li_api_redirect'] = $redirect;

        return $authorize_url;
	} catch (\Throwable $e) {
		$this->render_error(__('LinkedIn login is unavailable right now. Please try again later.', 'profilegrid-social-connect'));
	}

	return '';
		
	}
        
        public function get_auth_url($redirect = false) {
        try {
        $state = wp_generate_password(12, false);
        
        //'profile', 'openid' and 'email' are default values
       
        $this->li_options['li_list_scopes'] = array('openid' , 'profile', 'email');

        $authorize_url = $this->oauth->authorizeUrl(array('scope' => 'openid profile email',
            'state' => $state));
        
        $_SESSION['li_api_state'] = $state;
        // Store state in database in temporarily till checked back
        if (!isset($_SESSION['li_api_state'])) {
            $_SESSION['li_api_state'] = $state;
        }

        // Store redirect URL in session
        $_SESSION['li_api_redirect'] = $redirect;

        return $authorize_url;
        } catch (\Throwable $e) {
            $this->render_error(__('LinkedIn login is unavailable right now. Please try again later.', 'profilegrid-social-connect'));
        }

        return '';

    }
		
}
endif;
