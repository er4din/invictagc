<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Display_Name
 * @subpackage Profilegrid_Display_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Display_Name
 * @subpackage Profilegrid_Display_Name/public
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Display_Name_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $profilegrid_display_name    The ID of this plugin.
	 */
	private $profilegrid_display_name;

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
	 * @param      string    $profilegrid_display_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_display_name, $version ) {

		$this->profilegrid_display_name = $profilegrid_display_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profilegrid_Display_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Display_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->profilegrid_display_name, plugin_dir_url( __FILE__ ) . 'css/profilegrid-display-name-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profilegrid_Display_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Display_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
                wp_enqueue_script( $this->profilegrid_display_name, plugin_dir_url( __FILE__ ) . 'js/profilegrid-display-name-public.js', array( 'jquery' ), $this->version, false );
             
	}
        
        public function profile_magic_get_display_name_style($uid)
        {
            $dbhandler = new PM_DBhandler;
            $pmrequest = new PM_request;
            $group = $pmrequest->profile_magic_get_user_field_value($uid,'pm_group');
            $gid = $pmrequest->pg_get_primary_group_id($group);
            $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
            if(isset($options['display_name']) && $options['display_name']==1)
            {
                $type = $options['pm_display_name_style'];
            }
            else
            {
               $type = $dbhandler->get_global_option_value('pm_display_name_style',1);
            }
            
            
            switch($type)
                {
                    case 0:
                         $style = '';
                        break;
                    case 1:
                         $style = 'text-transform:capitalize';
                        break;
                    case 2:
                        $style = 'text-transform:uppercase';
                        break;
                    case 3:
                        $style = 'text-transform:lowercase';
                        break;
                    case 4:
                        $style = 'text-decoration: underline';
                        break;
                    case 5:
                        $style = 'font-weight:bold';
                        break;
                }
                return $style;
            
        }
        public function profile_magic_filter_display_name_fun($display_name,$uid)
        {
            $dbhandler = new PM_DBhandler;
            $pmrequest = new PM_request;
            $firstname = '';
            $lastname='';
            $name = '';
            $style = $this->profile_magic_get_display_name_style($uid);
            $prefix_style = $dbhandler->get_global_option_value('pm_enable_display_name_style_prefix_suffix','0');
            if($dbhandler->get_global_option_value('pm_enable_display_name','0')==1)
            {
                            
                $firstname = $pmrequest->profile_magic_get_user_field_value($uid,'first_name');
                $lastname = $pmrequest->profile_magic_get_user_field_value($uid,'last_name');
                
                $prefix='';
                $postfix='';
                
                $type = $dbhandler->get_global_option_value('pm_display_name_pattern',1);
                if($dbhandler->get_global_option_value('pm_enable_prefix','0')==1)
                    $prefix = $dbhandler->get_global_option_value('pm_set_prefix','');
                if($dbhandler->get_global_option_value('pm_enable_postfix','0')==1 )
                    $postfix = $dbhandler->get_global_option_value('pm_set_postfix','');
//                 return $postfix;
                
                $user_groups = maybe_unserialize($pmrequest->profile_magic_get_user_field_value($uid,'pm_group'));
                $group = $pmrequest->pg_filter_users_group_ids($user_groups);
                $gid = $pmrequest->pg_get_primary_group_id($group);
                $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
                
                if(isset($options['display_name']) && $options['display_name']==1)
                {
                    $type = $options['display_name_pattern'];
                    if(isset($options['enable_prefix']) && $options['enable_prefix']==1)
                    {
                      $prefix = $options['set_prefix'];
                    }
                    else
                    {
                        $prefix = '';
                    }

                    if(isset($options['enable_postfix']) && $options['enable_postfix']==1)
                    {
                      $postfix = $options['set_postfix'];
                    }
                    else
                    {
                        $postfix = '';
                    }
                }
                switch($type)
                {
                    case 1:
                        if(!empty($firstname) && !empty($lastname))
                        {
                          $name = $firstname.' '.$lastname;
                        }
                        break;
                    case 2:
                        if(!empty($firstname) && !empty($lastname))
                        {
                          $name = $lastname.', '.$firstname;      
                        }
                        break;
                    case 3:
                        if(!empty($firstname) && !empty($lastname))
                        {
                           $name = substr($firstname,0,1).'. '.$lastname;
                        }
                        break;
                    case 4:
                        if(!empty($firstname) && !empty($lastname))
                        {
                           $name = $firstname.' '.substr($lastname,0,1).'.';
                        }
                        break;
                     case 5:
                        if(!empty($firstname) && !empty($lastname))
                        {
                           $name = substr($firstname,0,1).'. '.substr($lastname,0,1).'.';
                        }
                        break;
                    case 6:
                        $nickname = $pmrequest->profile_magic_get_user_field_value($uid,'nickname');
                        if(isset($nickname) && $nickname!='')
                        {
                            $name = $nickname;
                        }
                        break;
                    case 7:
                        $name = $pmrequest->profile_magic_get_user_field_value($uid,'user_login');
                        break;
                    case 8:
                        $name = $pmrequest->profile_magic_get_user_field_value($uid,'user_email');
                        break;
                    
                    default:
                        $name = $display_name;
                        break;
                }
                if($name=='')
                {
                    return '<span style="'.$style.'">'.$display_name.'</span>';
                }
                else
                {
                    if ($prefix_style==1)
                    {
                    if($prefix!='') $prefix = $prefix.' ';
                    if($postfix!='') $postfix = ' '.$postfix;
                    
                    //{
                        return '<span style="'.$style.'">'.$prefix.$name.$postfix.'</span>';
                    }
                    else
                    {
                         return '<span style="'.$style.'">'.$name .'</span>';
                    }
                   // }
                  //  else
                   // {
                  //      return $prefix.'<span style="'.$style.'">'.$name.'</span>'.$postfix;
                   // }
                }
            }
            else
            {
               return $display_name; 
            }
            
       }
        
}
