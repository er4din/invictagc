<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Mycred
 * @subpackage Profilegrid_Mycred/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Mycred
 * @subpackage Profilegrid_Mycred/public
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Mycred_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $profilegrid_mycred    The ID of this plugin.
     */
    private $profilegrid_mycred;

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
     * @param      string    $profilegrid_mycred       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $profilegrid_mycred, $version ) {

            $this->profilegrid_mycred = $profilegrid_mycred;
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
             * defined in Profilegrid_Mycred_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Profilegrid_Mycred_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            wp_enqueue_style( $this->profilegrid_mycred, plugin_dir_url( __FILE__ ) . 'css/profilegrid-mycred-public.css', array(), $this->version, 'all' );

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
             * defined in Profilegrid_Mycred_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Profilegrid_Mycred_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
	    wp_enqueue_script('jquery');
	    //wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script( $this->profilegrid_mycred, plugin_dir_url( __FILE__ ) . 'js/profilegrid-mycred-public.js', array( 'jquery' ), $this->version, true );

    }

    public function pm_update_mycred_points_on_update_profile_image($uid)
    {
        $dbhandler = new PM_DBhandler;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_profile_image','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award points to the user on uploading a new or changing an existing Profile Image','profilegrid-mycred-integration');
            $reference = 'pm_mycred_profile_image_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        }
    }
    
    public function pm_update_mycred_points_on_update_cover_image($uid)
    {
        $dbhandler = new PM_DBhandler;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_cover_image','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award points to the user on uploading a new or changing an existing Cover Image','profilegrid-mycred-integration');
            $reference = 'pm_mycred_cover_image_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        }
    }
    
    public function pm_update_mycred_points_on_remove_profile_image($uid)
    {
        $dbhandler = new PM_DBhandler;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_user_remove_profile_image','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award or deduct points from the user on removing his/ her profile image.','profilegrid-mycred-integration');
            $reference = 'pm_mycred_user_remove_profile_image_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        }
    }
    
    public function pm_update_mycred_points_on_remove_cover_image($uid)
    {
        $dbhandler = new PM_DBhandler;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_user_remove_cover_image','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award or deduct points from the user on removing his/ her cover image.','profilegrid-mycred-integration');
            $reference = 'pm_mycred_user_remove_cover_image_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        }
    }
    
    public function pm_update_mycred_points_on_update_user_profile($uid)
    {
        $dbhandler = new PM_DBhandler;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_update_profile','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award points to the user on filling an empty profile field or updating an existing value.','profilegrid-mycred-integration');
            $reference = 'pm_mycred_update_profile_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        }
    }
    
    public function pm_update_mycred_points_on_join_group($gid,$uid)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequest = new PM_request;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_user_approved','0');
        $type = $pmrequest->profile_magic_get_group_type($gid);
        if($enable=='1' && $type=='closed')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award points to the user when his group joining request is approved by site admin or Group Manager.','profilegrid-mycred-integration');
            $reference = 'pm_mycred_user_approved_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        }
    }
    
    public function pm_update_mycred_points_on_user_blog_post_published($ID, $post)
    {
      
        $dbhandler = new PM_DBhandler;
        $uid = $post->post_author; /* Post author ID. */
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_user_blog_post_published','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award points to the user on successfully publishing a blog post using the post submission form. Applies if the post is automatically published or approved by the Group Manager.','profilegrid-mycred-integration');
            $reference = 'pm_mycred_user_blog_post_published_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        }
    }

    public function pm_update_mycred_points_on_user_promoted_group_manager($gid,$uid)
    {
        $dbhandler = new PM_DBhandler;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_promoted_user_to_group_manager','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award points to the user when he or she is promoted to Group Manager.','profilegrid-mycred-integration');
            $reference = 'pm_mycred_promoted_user_to_group_manager_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        } 
    }
    
    public function pm_update_mycred_points_on_user_friend_request_accepted($rid,$sid)
    {
        $dbhandler = new PM_DBhandler;
        $uid = $rid;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_friend_request_approved','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award points to the user when his/ her friend request is approved by another user.','profilegrid-mycred-integration');
            $reference = 'pm_mycred_promoted_friend_request_approved_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        } 
    }
    
    public function pm_update_mycred_points_on_user_upload_group_photo()
    {
        $dbhandler = new PM_DBhandler;
        $uid = get_current_user_id();
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_upload_group_photo','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award points to the user on uploading a new Group Photo','profilegrid-mycred-integration');
            $reference = 'pm_mycred_upload_group_photo_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        } 
    }
    
    public function pm_update_mycred_points_on_user_groupwall_post_published($ID, $post)
    {
      
        $dbhandler = new PM_DBhandler;
        $uid = $post->post_author; /* Post author ID. */
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_post_on_group_wall','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award points to the user on posting on the Group Wall','profilegrid-mycred-integration');
            $reference = 'pm_mycred_post_on_group_wall_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        }
    }
    
    public function pm_update_mycred_points_on_user_leave_group($uid,$gid)
    {
        $dbhandler = new PM_DBhandler;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_user_leave_a_group','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award or deduct points from the user on leaving a Group he or she is a member of','profilegrid-mycred-integration');
            $reference = 'pm_mycred_user_leave_a_group_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        }
    }
    
    public function pm_update_mycred_points_on_user_friend_request_rejected($rid,$sid)
    {
        $dbhandler = new PM_DBhandler;
        $uid = $rid;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_friend_request_rejected','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award or deduct points from the user when his/ her friend request is rejected.','profilegrid-mycred-integration');
            $reference = 'pm_mycred_friend_request_rejected_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        } 
    }
    
    public function pm_update_mycred_points_on_user_suspended($uid)
    {
        $dbhandler = new PM_DBhandler;
        $enable = $dbhandler->get_global_option_value('pm_mycred_enable_points_user_suspended','0');
        if($enable=='1')
        {
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $log = __('Award or deduct points from the user when he/ she is suspended by site admin or Group Manager.','profilegrid-mycred-integration');
            $reference = 'pm_mycred_user_suspended_points';
            $mycred_fun->update_user_balance($reference,$uid,$log);
        } 
    }

    public function profile_magic_show_rank_and_points($uid)
    {
        $dbhandler = new PM_DBhandler;
        $enable_mycred = $dbhandler->get_global_option_value('pm_enable_mycred','0');
        $enable = $dbhandler->get_global_option_value('pm_mycred_display_rank_on_profile','0');
        if(defined('myCRED_RANKS') && $enable_mycred=='1' &&$enable=='1'):
        
        $mycred_fun = new Profilegrid_Mycred_Functions();
        
        $type = $dbhandler->get_global_option_value('pm_mycred_type','mycred_default');
        $mycred  = mycred( $type );
        
        if ( function_exists( 'mycred_get_users_rank' ) ) 
        {
            // Get rank object
            $rank = mycred_get_users_rank( $uid,$type );
            
            $current_Balance = $mycred->get_users_balance( $uid,$type );
          
            //var_dump($rank);die;
            // If the user has a rank, $rank will be an object
            if ( is_object( $rank ) ) 
            {
                $maximum = $rank->maximum;
                // Show rank title
                echo '<div class="pm-user-mycred-rank-points pm-difl">';
                echo '<div class="pm-user-mycred-rank">';
                echo '<div class="pm-user-mycred-rank-title pm-difl">';
                echo $rank->title;
                echo '</div>';
                // Show rank logo (if one exists)
                if ( $rank->has_logo )
                {
                    echo '<div class="pm-user-mycred-rank-logo pm-difl">';
                        echo $rank->get_image( 'logo' );
                        echo '</div>';
                }
                // Show total number of users with this rank
               //echo $rank->count;
               
                echo '</div>';
                echo '<div class="pm-user-mycred-points pm-dbfl">';
                echo mycred_display_users_balance( $uid,$type );
                echo '</div>';
                $mycred_fun->get_mycred_users_rank_progress($current_Balance,$maximum);
                echo '</div>';
            }

        }
        
        endif;
    }

    
    
    public function pg_show_badge_tab($id,$newtab,$uid,$gid)
    {
        if($id=='pg-mycred-badges' && isset($newtab) && $newtab['status']=='1'):
            $dbhandler = new PM_DBhandler;
            //$title = $dbhandler->get_global_option_value('pm_mycred_display_badges_tab_title','Badges');
            $title = __($newtab['title'],'profilegrid-mycred-integration');
            if( defined( 'myCRED_BADGE' ) && $dbhandler->get_global_option_value('pm_enable_mycred','0')=='1' && $dbhandler->get_global_option_value('pm_mycred_display_badges','0')=='1')
            {
                echo '<li class="pm-profile-tab pm-pad10"><a class="pm-dbfl" href="#pg-mycred-badges">'.$title.'</a></li>';
            }
        endif;
    }

    public function pg_show_badge_tab_content($id,$newtab,$uid,$gid,$primary_gid)
    {
        if($id=='pg-mycred-badges' && isset($newtab) && $newtab['status']=='1'):
            $dbhandler = new PM_DBhandler;
            $mycred_fun = new Profilegrid_Mycred_Functions();
            $width = 100; 
            $height = 100;
            $show = ($dbhandler->get_global_option_value('pm_mycred_display_all_available_badges','0') == '1' ? 'all' : 'earned');
            if(defined( 'myCRED_BADGE' ) && $dbhandler->get_global_option_value('pm_enable_mycred','0')=='1' && $dbhandler->get_global_option_value('pm_mycred_display_badges','0')=='1')
            {

                echo '<div id="pg-mycred-badges" class="pm-difl pg-profile-tab-content">';
               $mycred_fun->pg_get_mycred_badges($show,$uid,$width, $height);
                echo '</div>';
            }

        endif;

    }
    
     public function pg_points_tab($uid,$gid)
    {
        $dbhandler = new PM_DBhandler;
        $type = $dbhandler->get_global_option_value('pm_mycred_type','mycred_default');
        $point_name = mycred_get_point_type_name($type,false);
        if($dbhandler->get_global_option_value('pm_enable_mycred','0')=='1' && $dbhandler->get_global_option_value('pm_mycred_display_point_history','0')=='1')
        {
            echo '<li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" href="#pg-my-points">'.$point_name.'</a></li>';
        }

    }
    public function pg_points_tab_content($uid,$gid)
    {
       
        $dbhandler = new PM_DBhandler;
        $type = $dbhandler->get_global_option_value('pm_mycred_type','mycred_default');
        if($dbhandler->get_global_option_value('pm_enable_mycred','0')=='1' && $dbhandler->get_global_option_value('pm_mycred_display_point_history','0')=='1')
        {
            ?>
            <div id="pg-my-points" class="pm-blog-desc-wrap pm-difl pm-section-content pg-my-points">
                <input type="hidden" id="pg_mycred_uid" name="pg_mycred_uid" value="<?php echo $uid;?>" />
                <input type="hidden" id="pg_mycred_type" name="pg_mycred_type" value="<?php echo $type;?>" />
                <div id="pg-mycred-balance" class="pm-dbfl">
                <b><?php _e('Balance','profilegrid-mycred-integration'); ?></b>
                <p><?php echo mycred_display_users_balance( $uid,$type );?></p>
            </div>
                <div id="pg-mycred-log-table">    
            <?php
            echo do_shortcode('[mycred_history inlinenav="1" number="50" user_id="'.$uid.'" type="'.$type.'"]');
            echo '</div>';
             echo '</div>';
        }
    }
    
    public function pg_new_badge_awarded_notification($new_level,$uid,$badge)
    {
       
        $notification = new Profile_Magic_Notification;
        $notification->pm_added_earned_new_badge_notification($uid,$new_level,$badge);
        return $new_level;
    }
    
    public function pm_load_mycred_log()
    {
        $uid = $_GET['uid'];
        $type = $_GET['type'];
        //$paged = $_GET['page'];
        echo do_shortcode('[mycred_history inlinenav="1" number="50" user_id="'.$uid.'" type="'.$type.'"]');
        die;
    }
    
     public function profile_magic_profile_tab_link_fun($id,$newtab,$uid,$gid,$primary_gid)
    {
        if(isset($newtab) && $newtab['status']=='1'):
            switch($id)
            {
                case 'pg-mycred-badges':
                    $this->pg_show_badge_tab($id,$newtab,$uid,$primary_gid);
                    break;
                
            }
        endif;
    }
    
    public function profile_magic_profile_tab_extension_content_fun($id,$newtab,$uid,$gid,$primary_gid)
    {
        if(isset($newtab) && $newtab['status']=='1'):
            switch($id)
            {
                case 'pg-mycred-badges':
                    $this->pg_show_badge_tab_content($id,$newtab,$uid,$gid,$primary_gid);
                    break;
               
            }
        endif;
    }

}
