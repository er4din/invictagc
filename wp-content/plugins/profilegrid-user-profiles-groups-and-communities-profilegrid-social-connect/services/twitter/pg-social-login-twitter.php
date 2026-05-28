<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'api/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
if ( ! class_exists( 'Pg_Social_Login_Twitter' ) ) :
class Pg_Social_Login_Twitter {
        
        public $consumer_key;
        public $consumer_secret;
        public $pm_autofill;
        public $gid;
        public $template;
	public $oauth_callback;
	public $login_url;

	private function render_error($message) {
		echo '<div class="pg-box-row pg-box-text-center">';
		echo '<div class="pg-alert-warning pg-alert-info">' . esc_html($message) . '</div>';
		echo '</div>';
	}

	private function normalize_exception_message($message) {
		$decoded = json_decode($message, true);
		if (json_last_error() === JSON_ERROR_NONE && isset($decoded['errors'][0]['message'])) {
			return $decoded['errors'][0]['message'];
		}

		return $message;
	}
	function __construct($gid,$template) {
		$this->load($gid,$template);
             $this->get_auth();
	}

	/***
	***	@load
	***/
	function load($gid,$template) {
                $dbhandler = new PM_DBhandler;
                $pmrequests = new PM_request;
		$this->consumer_key = $dbhandler->get_global_option_value('pm_twitter_consumer_key');
		$this->consumer_secret = $dbhandler->get_global_option_value('pm_twitter_consumer_secret');
                $this->pm_autofill = $dbhandler->get_global_option_value('pm_enable_autofill_connect');
                
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
                $this->template = $template;
		$this->oauth_callback =  untrailingslashit( $registration_url ). '/?provider=twitter&gid='.$this->gid;
		$this->login_url = '';
	}

	/***
	***	@Get auth
	***/
	function get_auth() {
		$pmrequests = new PM_request;
                 $dbhandler = new PM_DBhandler;
		if ( isset($_REQUEST['provider']) && $_REQUEST['provider'] == 'twitter' && isset($_REQUEST['oauth_token']) && isset($_REQUEST['oauth_verifier']) ) {
			try {
				$request_token['oauth_token'] = $_SESSION['tw_oauth_token'];
				$request_token['oauth_token_secret'] = $_SESSION['tw_oauth_token_secret'];

				// invalid token: abort
				if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
					$this->render_error(__('Invalid Twitter token. Please try again.', 'profilegrid-social-connect'));
					return;
				} 

				// if session already stored
				if ( isset($_SESSION['tw_access_token']) ) {
				
					//echo "$_SESSION[tw_access_token]" . $_SESSION['tw_access_token']; 
					
					// get access token
					$access_token = $_SESSION['tw_access_token'];
					$connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

				} else {
					
					$connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret, $request_token['oauth_token'], $request_token['oauth_token_secret']);
					$access_token = $connection->oauth("oauth/access_token", array( "oauth_verifier" => $_REQUEST['oauth_verifier'],"oauth_token" => $_REQUEST['oauth_token'] ));
					
					// get access token
					$_SESSION['tw_access_token'] = $access_token;
					$connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

				}
				
				$connection->setApiVersion('1.1');
				$profile = $connection->get("account/verify_credentials" ,[ 'include_email' => 'true'] );
				
				$profile = json_decode(json_encode($profile), true);
				
				if(!empty($profile) && is_array($profile) && !isset($profile['errors']))
				{
					$name = $profile['name'];
					$name = explode(' ', $name);
					
                                        
			 		// prepare the array that will be sent
					$bio['username'] = $profile['screen_name'];
					$bio['user_login'] = $profile['screen_name'];
					$bio['first_name'] = $name[0];
					$bio['last_name'] = $name[1] ?? '';
					$bio['description'] = $profile['description'];
					// username/email exists
                                        $bio['user_email'] = $profile['email'];                        
					$bio['email_exists'] = $profile['email'];
					$bio['username_exists'] = $profile['email'];
					
					// provider identifier
					$bio['_uid_twitter'] = $profile['id'];
					
					if ( isset( $profile['profile_image_url'] ) && strstr( $profile['profile_image_url'], '_normal' ) ) {
						$bio['pm_twitter_profile_photo_syn'] = str_replace('_normal','',$profile['profile_image_url']);
					}
					
					$bio['pm_twitter_handle'] = '@' . $profile['screen_name'];
					$bio['pm_twitter_link'] = 'https://twitter.com/' . $profile['screen_name'];
					$bio['pm_twitter_profile_photo'] = $bio['pm_twitter_profile_photo_syn'];
					$bio['gid'] =  $_REQUEST['gid'];
					if ( email_exists( $bio['email_exists'] ) )
						$userid = email_exists( $bio['email_exists'] );
					elseif ( username_exists( $bio['username_exists'] ) )
						$userid = username_exists( $bio['username_exists'] );
					else 
						$userid = 0;
					
					if($userid == 0)
					{
						 $args = array('meta_key' => 'user_login','meta_value' => $bio['user_login'],);
						$user_obj = get_users($args);
						if(!empty($user_obj))
							$userid = $user_obj[0]->data->ID;
						else 
							$userid = 0;
					}

							if($userid)
							{

								if($this->template == 'profile-tpl')
								{
									do_action('pg_add_social_connection',$bio,'twitter');
								}
								else
								{
									do_action('pg_social_registration',$bio,'twitter');
								}
							}
							else
							{         
                                                            
                                                         
						/*		if($this->template != 'profile-tpl')
								{
									$bio['pm_twitter_connected'] = 1;
									$redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
									$redirect_url = add_query_arg( 'gid',$bio['gid'], $redirect_url );
									$redirect_url = add_query_arg( 'profile',$bio, $redirect_url );
									wp_redirect($redirect_url);
								}
								else
								{
									do_action('pg_add_social_connection',$bio,'twitter');
								}
                                                 
                                                 */
                                                          
                        $stripe_enable = $dbhandler->get_global_option_value('pm_enable_stripe','0');
                        $is_paid = $pmrequests->profile_magic_check_paid_group($bio['gid']);
                        if($stripe_enable == 1 && $is_paid > 0)
                        {
                            $bio['pm_twitter_connected'] = 1;
                            $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                            $redirect_url = add_query_arg( 'gid',$bio['gid'], $redirect_url );
                            $redirect_url = add_query_arg( 'profile',$bio, $redirect_url );
                            wp_redirect($redirect_url);
                        }
                        elseif($this->pm_autofill && $this->template != 'profile-tpl')
                        {
                            $bio['pm_twitter_connected'] = 1;
                            $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                            $redirect_url = add_query_arg( 'gid',$bio['gid'], $redirect_url );
                            $redirect_url = add_query_arg( 'profile',$bio, $redirect_url );
                            wp_redirect($redirect_url);
                        }
                        elseif($this->template == 'profile-tpl')
                        {
                            do_action('pg_add_social_connection',$bio,'twitter');
                        }
                        else
                        {
                            do_action('pg_social_registration',$bio,'twitter');
                        }
							}
							
				}
				else
				{
					$message = __('Error connecting to Twitter. Please try again later.', 'profilegrid-social-connect');
					if (isset($profile['errors'][0]['message'])) {
						$message = sprintf(__('Twitter authentication failed: %s', 'profilegrid-social-connect'), $profile['errors'][0]['message']);
					}
					$this->render_error($message);
					return;
				}
			} catch (\Abraham\TwitterOAuth\TwitterOAuthException $e) {
				$message = $this->normalize_exception_message($e->getMessage());
				$this->render_error(sprintf(__('Twitter authentication failed: %s', 'profilegrid-social-connect'), $message));
				return;
			} catch (\Throwable $e) {
				$this->render_error(__('Twitter authentication failed. Please try again later.', 'profilegrid-social-connect'));
				return;
			}
		}
		
	}
		
	/***
	***	@get login uri
	***/
	function login_url() {

		try {
			$connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret );
			
			$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->oauth_callback ));
			
			$_SESSION['tw_oauth_token'] = isset($request_token['oauth_token']) ? $request_token['oauth_token'] : '';
			$_SESSION['tw_oauth_token_secret'] = isset($request_token['oauth_token_secret']) ? $request_token['oauth_token_secret'] : '';
			$this->login_url = $connection->url('oauth/authenticate', array('oauth_token' => isset($request_token['oauth_token']) ? $request_token['oauth_token'] : ''));
			
			return $this->login_url;
		} catch (\Abraham\TwitterOAuth\TwitterOAuthException $e) {
			$message = $this->normalize_exception_message($e->getMessage());
			$this->render_error(sprintf(__('Twitter authentication failed: %s', 'profilegrid-social-connect'), $message));
		} catch (\Throwable $e) {
			$this->render_error(__('Twitter authentication failed. Please try again later.', 'profilegrid-social-connect'));
		}

		$this->login_url = '';
		return $this->login_url;
		
	}
		
}
endif;
