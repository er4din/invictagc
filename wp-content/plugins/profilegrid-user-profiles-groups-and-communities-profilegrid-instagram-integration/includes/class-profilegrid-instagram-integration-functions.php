<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Instagram_Functions {

	/**
	 * @var int
	 */
	public $client_id;


	/**
	 * @var string
	 */
	public $client_secret;


	/**
	 * @var string
	 */
	public $callback_url;


	/**
	 * @var
	 */
	//public $login_url;


	/**
	 * @var int
	 */
	public $auth_called = 0;


	/**
	 *  init
	 * 
	 * @since  1.0.0
	 */
	function __construct($uid,$gid) {
		$this->load($uid,$gid);
                $this->get_auth($uid, $gid);
	}


	/**
	 * @param $api_data
	 *
	 * @return \Instagram
	 */
	function call_API( $api_data ) {

		return new Instagram( $api_data );
	}


	/**
	 * Prepare variables
	 * action hook: template_redirect
	 * 
	 * @since  1.0.0
	 */
	function load($uid,$gid) {
                $dbhandler = new PM_DBhandler;
                $pmrequests = new PM_request;
		$this->client_id = $dbhandler->get_global_option_value('pm_instagram_client_id','');
		$this->client_secret = $dbhandler->get_global_option_value('pm_instagram_client_secret','');
                
                 $url = $pmrequests->profile_magic_get_frontend_url('pm_user_profile_page',site_url('/wp-login.php'));
                // $url = add_query_arg( 'pg-connect-instagram','true', $url );
		 $this->callback_url =  $url;
	}


	/**
	 * Get authorization callback response
	 * action hook: template_redirect
	 * 
	 * @since  1.0.0
	 */
	function get_auth($uid,$gid) {
            $pmrequests = new PM_request;
           // $current_url= $_SERVER['REQUEST_URI'];
            // $current_url = add_query_arg( 'pg-connect-instagram','true', $current_url );
         //  echo  $this->callback_url ;
            //die;
		if ( isset( $_REQUEST['code'] ) && $this->auth_called == 0 ) {
			
			if ( $this->is_session_started() === false ) {
				session_start();
			}

			$instagram = $this->call_API( array(
				'apiKey'      => $this->client_id,
				'apiSecret'   => $this->client_secret,
				'apiCallback' => $this->callback_url
			));
			$token = false;
			if ( isset( $_SESSION['insta_access_token'] ) ) {
				$token = $_SESSION['insta_access_token'];
                                $user = $_SESSION['insta_user'];
			} else {
				$code = $_REQUEST['code'];
				$data = $instagram->getOAuthToken( $code );
                               $token1 = $data->access_token;
             $url1 = 'https://graph.instagram.com/access_token/?grant_type=ig_exchange_token&client_secret='.$this->client_secret.'&access_token='.$token1;
            $crl = curl_init();
                 curl_setopt($crl, CURLOPT_URL, $url1);
                  curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
                  curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
                  $response1 = curl_exec($crl);
                  $token_longterm = json_decode($response1);
                     $token = $token_longterm->access_token ;
				$_SESSION['insta_access_token'] = $token;
			}

			if ( ! empty( $token ) ) {
				update_user_meta( $uid, 'pg_instagram_code', $token );
				unset( $_SESSION['insta_access_token'] );
                                $url = $pmrequests->profile_magic_get_frontend_url('pm_user_profile_page',site_url('/wp-login.php'));
                               
				wp_redirect($url.'#pg_instagram_integration_tab_content' );
			}

			$this->auth_called++;
		}
                elseif(isset( $_REQUEST['code'] ))
                {
                    $url = $this->connect_url();
                    wp_redirect($url);
                }
	}


	/**
	 * Get Authorization URL
	 * @return string Login url for App authorization
	 * 
	 * @since  1.0.0
	 */
	function connect_url() {
		$instagram = $this->call_API( array(
			'apiKey'      => $this->client_id,
			'apiSecret'   => $this->client_secret,
			'apiCallback' => $this->callback_url
		));

		return $instagram->getLoginUrl();
	}


	/**
	 * Get current user's access token
	 *
	 * @param  string $metakey field meta key
	 * @param  int $user_id User ID
	 * @return string | boolean  returns token strings on success, otherwise return false when empty token
	 * 
	 * @since  1.0.0
	 */
	function get_user_token( $metakey = 'pg_instagram_code', $user_id = 0 ) {
		$token = false;

		if ( $this->is_session_started() === false ) {
			session_start();
		}

		$token = get_user_meta( $user_id, $metakey, true );

		
		if ( ! $token ) {
			$token = get_user_meta( $user_id, 'pg_instagram_code', true );
		}

		return $token;
	}


	/**
	 * Checks if session has been started
	 * @return bool
	 */
	function is_session_started() {
		if ( php_sapi_name() !== 'cli' ) {
			return session_status() === PHP_SESSION_ACTIVE ? true : false;
		}

		return false;
	}
}