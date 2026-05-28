 <?php
//require_once 'facebook/autoload.php';
require_once 'fb/autoload.php';

class Pg_Social_Login_Facebook 
{
    public $pm_autofill;
    public $gid;
    public $app_id;
    public $app_secret;
    public $template;
    public $required_scope;
    public $redirect_url;
    public $login_url;

    private function render_error($message)
    {
        echo '<div class="pg-box-row pg-box-text-center">';
        echo '<div class="pg-alert-warning pg-alert-info">' . esc_html($message) . '</div>';
        echo '</div>';
    }

    private function format_error_message($message)
    {
        if (stripos($message, 'cURL error 60') !== false) {
            return __('Facebook login failed due to an SSL certificate issue on the server. Please contact the site administrator to update the server CA certificates.', 'profilegrid-social-connect');
        }

        return $message;
    }
    public function __construct($gid,$template) 
    {
       $this->load($gid,$template);
         $this->get_auth();
         
         
    }
    public function load($gid,$template) 
    {
        
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $app_id = $dbhandler->get_global_option_value('pm_facebook_app_id');
        $app_secret = $dbhandler->get_global_option_value('pm_facebook_app_secret');
        $this->pm_autofill = $dbhandler->get_global_option_value('pm_enable_autofill_connect');
        if($template == 'profile-tpl')
        {
            $registration_url = $pmrequests->profile_magic_get_frontend_url('pm_user_profile_page',site_url('/wp-login.php'));
            $this->gid        = maybe_serialize($gid);
        }
        else
        {
             $registration_url = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
             $this->gid        = $gid;
        }
        
        $this->app_id             	= $app_id;
        $this->app_secret         	= $app_secret;
        $this->template                 = $template;
        $this->required_scope     	= 'public_profile, email';
        $this->redirect_url 		= trailingslashit( $registration_url );
        //$this->redirect_url 		= add_query_arg(array('facebook_auth'=> 'true','gid' =>$this->gid), $this->redirect_url);
        $this->login_url		= '';
    }
    
    public function get_auth() 
    {
        $pmrequests = new PM_request;
        $dbhandler = new PM_DBhandler;
		
        if ( isset( $_REQUEST['code'] ) && isset( $_REQUEST['state'] ) && isset($_SESSION['login_provider']) && $_SESSION['login_provider']=='facebook') 
        {
			//echo this->redirect_url;die;
            // Initialize the Facebook PHP SDK v5.
            try {
                $fb = new Facebook\Facebook([
                  'app_id'                => $this->app_id,
                  'app_secret'            => $this->app_secret,
                  'default_graph_version' => 'v20.0',
                ]);
				
                $helper = $fb->getRedirectLoginHelper();
                try {
                  $accessToken = $helper->getAccessToken();
                } catch(Facebook\Exceptions\FacebookResponseException $e) {
                    $this->render_error(sprintf(__('Facebook login failed: %s', 'profilegrid-social-connect'), $this->format_error_message($e->getMessage())));
                    return;
                } catch(Facebook\Exceptions\FacebookSDKException $e) {
                    $this->render_error(sprintf(__('Facebook login failed: %s', 'profilegrid-social-connect'), $this->format_error_message($e->getMessage())));
                    return;
                }

                if ( !isset( $accessToken ) && isset($_SESSION['facebook_access_token']) ){
                        $accessToken = $_SESSION['facebook_access_token'];
                }

                if (isset($accessToken)) {
                        $_SESSION['facebook_access_token'] = (string) $accessToken;
                        $fb->setDefaultAccessToken( $accessToken );
                        try {
                            $res = $fb->get('/me?fields=id,name,email,link');
                        } catch(Facebook\Exceptions\FacebookResponseException $e) {
                            $this->render_error(sprintf(__('Facebook login failed: %s', 'profilegrid-social-connect'), $this->format_error_message($e->getMessage())));
                            return;
                        } catch(Facebook\Exceptions\FacebookSDKException $e) {
                            $this->render_error(sprintf(__('Facebook login failed: %s', 'profilegrid-social-connect'), $this->format_error_message($e->getMessage())));
                            return;
                        }
                        $profile = $res->getGraphUser()->asArray();
                }
            } catch (\Throwable $e) {
                $this->render_error(__('Facebook login failed. Please try again later.', 'profilegrid-social-connect'));
                return;
            }

            if ( isset( $profile['name'] ) && $profile['name'] ) {
                    $name = $profile['name'];
                    $name = explode(' ', $name);
                            $bio['first_name'] = $name[0];
                            $bio['last_name'] = $name[1];

                    // prepare the array that will be sent
                    $bio['user_email'] = $profile['email'];
                    $bio['username'] = $profile['email'];
                    // username/email exists
                    $bio['email_exists'] = $profile['email'];
                    $bio['username_exists'] = $profile['email'];

                    // provider identifier
                    $bio['pm_uid_facebook'] = $profile['id'];
                    //$bio['pm_facebook_profile_photo'] = 'http://graph.facebook.com/'.$profile['id'].'/picture?width=200&height=200';
                    $bio['pm_facebook_profile_photo'] = 'http://graph.facebook.com/'.$profile['id'].'/picture?width=200&height=200&access_token='.$accessToken;
                    $bio['pm_facebook_handle'] = $profile['name'];
                    $bio['pm_facebook_link'] = $profile['link'] ?? '';
                    $bio['gid'] =  $_SESSION['facebook_gid'];
                    
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
                            do_action('pg_add_social_connection',$bio,'facebook');
                        }
                        else
                        {
                            do_action('pg_social_registration',$bio,'facebook');
                        }
                    }
                    else
                    {   
                        $stripe_enable = $dbhandler->get_global_option_value('pm_enable_stripe','0');
                        $is_paid = $pmrequests->profile_magic_check_paid_group($bio['gid']);
                        if($stripe_enable == 1 && $is_paid > 0)
                        {
                            $bio['pm_facebook_connected'] = 1;
                            $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                            $redirect_url = add_query_arg( 'gid',$bio['gid'], $redirect_url );
                            $redirect_url = add_query_arg( 'profile',$bio, $redirect_url );
                            wp_redirect($redirect_url);
                        }
                        elseif($this->pm_autofill && $this->template != 'profile-tpl')
                        {
                            $bio['pm_facebook_connected'] = 1;
                            $redirect_url  = $pmrequests->profile_magic_get_frontend_url('pm_registration_page',site_url('/wp-login.php'));
                            $redirect_url = add_query_arg( 'gid',$bio['gid'], $redirect_url );
                            $redirect_url = add_query_arg( 'profile',$bio, $redirect_url );
                            wp_redirect($redirect_url);
                        }
                        elseif($this->template == 'profile-tpl')
                        {
                            do_action('pg_add_social_connection',$bio,'facebook');
                        }
                        else
                        {
                            do_action('pg_social_registration',$bio,'facebook');
                        }
                    }
                    
                    
            }

        }

    }
		
	
    public function login_url() 
    {
        try {
            $fb = new Facebook\Facebook([
                      'app_id'                => $this->app_id,
                      'app_secret'            => $this->app_secret,
                      'default_graph_version' => 'v20.0',
            ]);
            
            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['email']; // optional
            $callback = $this->redirect_url;
			
            $this->login_url = $helper->getLoginUrl($callback, $permissions);
            return $this->login_url;
        } catch (\Throwable $e) {
            $this->render_error(__('Facebook login is unavailable right now. Please try again later.', 'profilegrid-social-connect'));
        }

        $this->login_url = '';
        return $this->login_url;
    }
		
}
