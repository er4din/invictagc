<?php

// use Hybridauth\Provider\Google;

require_once 'autoload.php';
if ( ! class_exists( 'Pg_Social_Login_Google' ) ) :

    class Pg_Social_Login_Google {
    
        public $client_id;
        public $client_secret;
        public $pm_autofill;
        public $gid;
        public $template;
        public $redirect_url;
        public $login_url;

        private function render_error($message)
        {
            echo '<div class="pg-box-row pg-box-text-center">';
            echo '<div class="pg-alert-warning pg-alert-info">' . esc_html($message) . '</div>';
            echo '</div>';
        }
	function __construct($gid,$template = '') {
            $this->load($gid,$template);
            $this->get_auth();
	}

	/***
	***	@load
	***/
	function load($gid,$template = '') {
		$dbhandler = new PM_DBhandler;
                $pmrequests = new PM_request;
		$this->client_id = $dbhandler->get_global_option_value('pm_google_client_id');
		$this->client_secret = $dbhandler->get_global_option_value('pm_google_client_secret');
                $this->pm_autofill = $dbhandler->get_global_option_value('pm_enable_autofill_connect');
               if($template == 'profile-tpl')
                {
                    $this->gid        = maybe_serialize($gid);
                    $registration_url = $pmrequests->profile_magic_get_frontend_url('pm_user_profile_page',site_url('/wp-login.php'));
                }
                else
                {
                    $this->gid        = $gid;
                    $registration_url = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                }
                $this->template = $template;
		$this->redirect_url = trailingslashit( $registration_url );
		$this->login_url		= '';
	}

	/***
	***	@Get auth
	***/
	function get_auth() {
            $pmrequests = new PM_request;
            $dbhandler = new PM_DBhandler;
            
		if ( isset($_GET['code']) && isset($_SESSION['login_provider']) && $_SESSION['login_provider'] == 'google' ) {
			try {
				$client = new Google\Client();
				$client->setAccessType('offline');
				$client->setClientId($this->client_id);
				$client->setClientSecret($this->client_secret);
				$client->setRedirectUri($this->redirect_url);
				$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
				$client->addScope("https://www.googleapis.com/auth/userinfo.email");
				$service = new Google\Service\Oauth2($client);
				$token = $client->fetchAccessTokenWithAuthCode($_GET['code']); 
				if ( isset($token['error']) ) {
					$this->render_error(sprintf(__('Google login failed: %s', 'profilegrid-social-connect'), $token['error']));
					return;
				}
				$_SESSION['gplus_token'] = $client->getAccessToken();
				if ($client->getAccessToken())  {
					$profile = $service->userinfo->get();
				}
			} catch (\Throwable $e) {
				$this->render_error(__('Google login failed. Please try again later.', 'profilegrid-social-connect'));
				return;
			}
			if ( isset( $profile ) ) {

				$profile = json_decode(json_encode($profile), true);

				// prepare the array that will be sent
				$bio['first_name'] = $profile['givenName'];
				$bio['last_name'] = $profile['familyName'];
				$bio['user_email'] = $profile['email'];
                                $bio['username'] = $profile['email'];
				// username/email exists
				$bio['email_exists'] = $profile['email'];
				$bio['username_exists'] = $profile['email'];
				// provider identifier
				$bio['_uid_google'] = $profile['id'];
				
				if ( isset( $profile['picture'] ) ) {
					$bio['pm_google_profile_photo'] = $profile['picture'] . '?sz=200';
				}
				
				$bio['pm_google_handle'] = $profile['name'];
				$bio['pm_google_link'] = $profile['link'];
				$bio['pm_google_profile_photo_min'] = $profile['picture'] . '?sz=40';
                                $bio['gid'] =  $_GET['state'];
                                
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
                                        do_action('pg_add_social_connection',$bio,'google');
                                    }
                                    else
                                    {
                                        do_action('pg_social_registration',$bio,'google');
                                    }
                                }
                                else
                                {    
                                    $stripe_enable = $dbhandler->get_global_option_value('pm_enable_stripe','0');
                                    $is_paid = $pmrequests->profile_magic_check_paid_group($bio['gid']);
                                    if($stripe_enable == 1 && $is_paid > 0)
                                    {
                                        $bio['pm_google_connected'] = 1;
                                        $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                                        $redirect_url = add_query_arg( 'gid',$bio['gid'], $redirect_url );
                                        $redirect_url = add_query_arg( 'profile',$bio, $redirect_url );
                                        wp_redirect($redirect_url);
                                    }
                                    elseif($this->pm_autofill && $this->template != 'profile-tpl')
                                    {
                                        $bio['pm_google_connected'] = 1;
                                        $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                                        $redirect_url = add_query_arg( 'gid',$bio['gid'], $redirect_url );
                                        $redirect_url = add_query_arg( 'profile',$bio, $redirect_url );
                                        wp_redirect($redirect_url);
                                    }
                                    elseif($this->template == 'profile-tpl')
                                    {
                                        do_action('pg_add_social_connection',$bio,'google');
                                    }
                                    else
                                    {
                                        do_action('pg_social_registration',$bio,'google');
                                    }
                                }
			
			}
			
		}

	}
		
	/***
	***	@get login uri
	***/
	function login_url() {
                try {
                    $client = new Google\Client();
                    $client->setClientId($this->client_id);
                    $client->setClientSecret($this->client_secret);
                    $client->setRedirectUri($this->redirect_url);
                    $client->setState($this->gid);
                    $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
                    $client->addScope("https://www.googleapis.com/auth/userinfo.email");
                    $this->login_url = $client->createAuthUrl();
		
                    return $this->login_url;
                } catch (\Throwable $e) {
                    $this->render_error(__('Google login is unavailable right now. Please try again later.', 'profilegrid-social-connect'));
                }

                $this->login_url = '';
                return $this->login_url;
		
	}
		
}
endif;
