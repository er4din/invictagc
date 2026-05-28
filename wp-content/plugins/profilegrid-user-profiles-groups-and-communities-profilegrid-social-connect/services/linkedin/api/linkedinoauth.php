<?php

/*
 * Abraham Williams (abraham@abrah.am) http://abrah.am
 *
 * The first PHP Library to support OAuth for Twitter's REST API.
 */

/* Load OAuth lib. You can find it at http://oauth.net */
require_once('OAuth2.php');

/**
 * OAuth class
 */
class linkedino {

  public $http_code;
  public $url;
  public $host = "https://api.linkedin.com/v2";
  public $timeout = 30;
  public $connecttimeout = 30; 
  public $ssl_verifypeer = false;
  public $format = 'xml';
  public $decode_xml = true;
  public $http_info;
  public $useragent = 'LinkedInOAuth v0.1.0';
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

  
  
    public function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
        $pmrequests = new PM_request;
        $this->li_api_key = $consumer_key;
        $this->li_secret_key = $consumer_secret;

        $this->oauth = new PG_OAuth2Client($this->li_api_key, $this->li_secret_key);

        // Set Oauth URLs
        $this->oauth->redirect_uri = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
        //$this->oauth->redirect_uri = wp_login_url() . '?action=pkli_login';
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
    
    
    
    public function get_auth_url($redirect = false) {
         
        $state = wp_generate_password(12, false);
        
        //'r_liteprofile' and 'r_emailaddress' are default values
       
        $this->li_options['li_list_scopes'] = array('id','first-name','last-name','email-address', 'public-profile-url', 'picture-url','picture-urls::(original)' );

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

    }



  /**
   * Get a request_token from Twitter
   *
   * @returns a key/value array containing oauth_token and oauth_token_secret
   */
  function getRequestToken($oauth_callback = NULL) {
    $parameters = array();
    if (!empty($oauth_callback)) {
      $parameters['oauth_callback'] = $oauth_callback;
    } 
    $request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
    $token = OAuthUtil::parse_parameters($request);
	if ( isset( $token['oauth_token'] ) )
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * Get the authorize URL
   *
   * @returns a string
   */
  function getAuthorizeURL($token, $sign_in_with_twitter = TRUE) {
    if (is_array($token)) {
      $token = $token['oauth_token'];
    }
    if (empty($sign_in_with_twitter)) {
      return $this->authorizeURL() . "?oauth_token={$token}";
    } else {
       return $this->authorizeURL() . "?oauth_token={$token}";
    }
  }

  /**
   * Exchange request token and secret for an access token and
   * secret, to sign API calls.
   *
   * @returns array("oauth_token" => "the-access-token",
   *                "oauth_token_secret" => "the-access-secret",
   *                "user_id" => "9436992",
   *                "screen_name" => "abraham")
   */
  function getAccessToken($oauth_verifier = FALSE) {
    $parameters = array();
    if (!empty($oauth_verifier)) {
      $parameters['oauth_verifier'] = $oauth_verifier;
    }
    $request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * One time exchange of username and password for access token and secret.
   *
   * @returns array("oauth_token" => "the-access-token",
   *                "oauth_token_secret" => "the-access-secret",
   *                "user_id" => "9436992",
   *                "screen_name" => "abraham",
   *                "x_auth_expires" => "0")
   */  
  function getXAuthToken($username, $password) {
    $parameters = array();
    $parameters['x_auth_username'] = $username;
    $parameters['x_auth_password'] = $password;
    $parameters['x_auth_mode'] = 'client_auth';
    $request = $this->oAuthRequest($this->accessTokenURL(), 'POST', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * GET wrapper for oAuthRequest.
   */
  function get($url, $selectors = array(), $parameters = array()) { // added $selectors to handle linkedin's... strange field selectors
  	$url .= ':(';
  	foreach( $selectors as $selector ) {
  		$url .= $selector . ',';
  	}
  	$url .= ')';
    $response = $this->oAuthRequest($url, 'GET', $parameters);
    return $response;
  }
  
  /**
   * POST wrapper for oAuthRequest.
   */
  function post($url, $parameters = array()) {
    $response = $this->oAuthRequest($url, 'POST', $parameters);
    if ($this->format === 'xml' && $this->decode_xml) {
      return new SimpleXMLElement($response);
    }
    return $response;
  }

  /**
   * DELETE wrapper for oAuthReqeust.
   */
  function delete($url, $parameters = array()) {
    $response = $this->oAuthRequest($url, 'DELETE', $parameters);
    if ($this->format === 'xml' && $this->decode_xml) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * Format and sign an OAuth / API request
   */
  function oAuthRequest($url, $method, $parameters) {
    if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
      $url = "{$this->host}{$url}";
    }
       
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
    $request->sign_request($this->sha1_method, $this->consumer, $this->token);
        
    switch ($method) {
    case 'GET':
      return $this->http($request->to_url(), 'GET');
    default:
      return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
    }
  }

  /**
   * Make an HTTP request
   *
   * @return API results
   */
  function http($url, $method, $postfields = NULL) {
    $this->http_info = array();
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
    curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
    curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
    curl_setopt($ci, CURLOPT_HEADER, FALSE);

    switch ($method) {
      case 'POST':
        curl_setopt($ci, CURLOPT_POST, TRUE);
        if (!empty($postfields)) {
          curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        break;
      case 'DELETE':
        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($postfields)) {
          $url = "{$url}?{$postfields}";
        }
    }

    curl_setopt($ci, CURLOPT_URL, $url);
    $response = curl_exec($ci);
    $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
    $this->url = $url;
    curl_close ($ci);
    return $response;
  }

  /**
   * Get the header info to store.
   */
  function getHeader($ch, $header) {
    $i = strpos($header, ':');
    if (!empty($i)) {
      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
      $value = trim(substr($header, $i + 2));
      $this->http_header[$key] = $value;
    }
    return strlen($header);
  }
}
