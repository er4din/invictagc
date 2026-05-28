<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_User_Content
 * @subpackage Profilegrid_User_Content/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_User_Content
 * @subpackage Profilegrid_User_Content/public
 * @author     Your Name <email@example.com>
 */
class Profilegrid_User_Content_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $profilegrid_user_content    The ID of this plugin.
     */
    private $profilegrid_user_content;

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
     * @param      string    $profilegrid_user_content       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $profilegrid_user_content, $version ) {

            $this->profilegrid_user_content = $profilegrid_user_content;
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
             * defined in Profilegrid_User_Content_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Profilegrid_User_Content_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            wp_enqueue_style( $this->profilegrid_user_content, plugin_dir_url( __FILE__ ) . 'css/profilegrid-user-content-public.css', array(), $this->version, 'all' );

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
             * defined in Profilegrid_User_Content_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Profilegrid_User_Content_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
	    wp_enqueue_script('jquery');
	    wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script( $this->profilegrid_user_content, plugin_dir_url( __FILE__ ) . 'js/profilegrid-user-content-public.js', array( 'jquery' ), $this->version, true );

    }



    public function pg_custom_tab($id,$newtab,$uid,$gid,$primary_gid)
    {
        $this->enqueue_scripts();
        $this->enqueue_styles();
        $pmrequests = new PM_request;
        $dbhandler = new PM_DBhandler;
        $current_user = wp_get_current_user();
        $tabs =  $dbhandler->get_all_result('CUSTOMTABS');
        if(isset($content['id']))$uid = $content['id'];else $uid = filter_input(INPUT_GET, 'uid');
        if(isset($uid))
        {
             $uid = $pmrequests->pm_get_uid_from_profile_slug($uid);
        }
        if(!isset($uid) && is_user_logged_in()){$uid = $current_user->ID;}
        if($dbhandler->get_global_option_value('pm_enable_custom_post_tabs','0')==1 && !empty($tabs))
        {
            $i = 1;
            foreach($tabs as $tab)
            {
                if(sanitize_key($tab->tab_label).$i==$id && isset($newtab) && $newtab['status']=='1')
                {
                    $tab_meta = maybe_unserialize($tab->tab_meta);
                    if(!isset($tab_meta['privacy_code']))
                    {
                        $tab_meta['privacy_code'] = "7";
                    }
                    if($this->pm_check_tab_access_permission($tab->id, $uid, $tab_meta['privacy_code']))
                    {
                        echo '<li class="pm-profile-tab pm-pad10"><a class="pm-dbfl" href="#'.$newtab['id'].'">'. __($newtab['title'],'profilegrid-custom-profile-tabs').'</a></li>';
                    }
                }
                $i++;
            } 
        }
    }

    public function pg_custom_tab_content($id,$newtab,$uid,$gid,$primary_gid)
    {
        $this->enqueue_scripts();
        $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $current_user = wp_get_current_user();
        if(isset($content['id']))$uid = $content['id'];else $uid = filter_input(INPUT_GET, 'uid');
        if(isset($uid))
        {
             $uid = $pmrequests->pm_get_uid_from_profile_slug($uid);
        }
        if(!isset($uid) && is_user_logged_in()){$uid = $current_user->ID;}
        
        $tabs =  $dbhandler->get_all_result('CUSTOMTABS');
        if($dbhandler->get_global_option_value('pm_enable_custom_post_tabs','0')==1 && !empty($tabs))
        {
            $i=1;
            foreach($tabs as $tab)
            {
                if(sanitize_key($tab->tab_label).$i==$id && isset($newtab) && $newtab['status']=='1')
                {
                    $tab_meta = maybe_unserialize($tab->tab_meta);
                    if(!isset($tab_meta['privacy_code']))
                    {
                        $tab_meta['privacy_code'] = "7";
                    }
                    if($this->pm_check_tab_access_permission($tab->id,$uid, $tab_meta['privacy_code']))
                    {
                        echo '<div id="'.$newtab['id'].'" class="pm-dbfl pg_custom_tab_content">';
                        $this->pg_show_custom_tab_content($tab,$uid,$gid);
                        echo '</div>';
                    }
                }
                $i++;
            } 
        }
    }
    
    public function pg_show_custom_tab_content($tab,$uid,$gid)
    {
        echo '<div class="pg-custom-tab-content">';
        if($tab->tab_content_from=='custom_posts')
        {
            echo '<div class="pm-dbfl" id="pg_blog_container_for'.$tab->tab_data_type.'">';
           $this->pm_get_custom_posts_tab_content($uid,$tab->tab_data_type);
           echo '</div>';
        }
        elseif($tab->tab_content_from=='post_content')
        {
            if(!empty($tab->tab_meta))
            {
                $tab_meta = maybe_unserialize($tab->tab_meta);
                //print_r($tab_meta);
                if(isset($tab_meta['post_id']))
                {
                   $contentElementor = "";
                    $elementor_page = get_post_meta( $tab_meta['post_id'], '_elementor_edit_mode', false );
                    if ($elementor_page && class_exists("\\Elementor\\Plugin")) {
                        $post_ID = $tab_meta['post_id'];
                        $pluginElementor = \Elementor\Plugin::instance();
                        $contentElementor = $pluginElementor->frontend->get_builder_content($post_ID);
                        $my_post_content = apply_filters('the_content',$contentElementor);
                        $content = str_replace(']]>', ']]>', $my_post_content);
                    }
                    else {
                         $my_post_content = apply_filters('the_content', get_post_field('post_content', $tab_meta['post_id']));
                         $content = str_replace(']]>', ']]>', $my_post_content);
                    }


                   
                    echo $content;
                }
               
                
            }
        }
        else
        {
            $content = $this->pm_get_filter_pg_shortcodes_from_content($tab->tab_content);
            echo do_shortcode($content);
        }
        echo '</div>';
    }
    
    public function pm_get_filter_pg_shortcodes_from_content($content)
    {
        $shortcode_tags = array('PM_Registration','PM_Group','PM_Groups','PM_Profile','PM_Login','PM_Forget_Password','PM_Search','PM_Add_Blog','PM_USERS_MAP','PM_CUSTOM_GROUP');
        if (preg_match_all( '/'. get_shortcode_regex() .'/s',$content,$matches )&& array_key_exists( 2, $matches )) 
        {
            foreach ( $matches[2] as $i => $sc ) 
            {
                if (in_array($sc,$shortcode_tags))
                {
                    $now = $matches[0][$i];
                    $content = str_replace( $now,'', $content );
                }
            }
        }
        
        return $content;          
    }
    
    public function pm_get_custom_posts_tab_content($uid,$post_type,$pagenum=1,$limit=10)
    {
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $displayname = $pmrequests->pm_get_display_name($uid);
        $offset = ( $pagenum - 1 ) * $limit;
        $args = array(
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => $post_type,
        'author'	   => $uid,
        'post_status'      => 'publish',
        'posts_per_page' => -1
        );
        $total_posts = count(get_posts( $args ));
        //echo $total_posts;
        $args['posts_per_page'] = $limit;
        $args['offset']= $offset;
        $posts_array = get_posts( $args );
      // echo count($posts_array);
        $num_of_pages = ceil( $total_posts/$limit);
        //echo $num_of_pages;
        $pagination = $dbhandler->pm_get_pagination($num_of_pages,$pagenum); 
        if($pagenum<=$num_of_pages)
        {

            $path =  plugins_url( '../public/partials/images/default-featured.jpg', __FILE__ );

            $query = new WP_Query( $args );

            while ( $query->have_posts() ) : $query->the_post();
            $comments_count = wp_count_comments();

                ?>
                <div class="pm-blog-post-wrap pm-dbfl">
                    <div class="pm-blog-img-wrap pm-difl">
                        <div class="pm-blog-img pm-difl">
                            <?php if ( has_post_thumbnail() ) {
                            the_post_thumbnail('post-thumbnail');
                            } else { ?>
                            <img src="<?php echo $path;?>" alt="<?php the_title(); ?>" class="pm-user" />
                            <?php } ?>
                        </div>
                        <div class="pm-blog-status pm-difl">
                            <span class="pm-blog-time "><?php printf( _x( '%s ago', '%s = human-readable time difference','profilegrid-custom-profile-tabs' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
                            <span class="pm-blog-comment"><?php comments_number( __('no Comment','profilegrid-custom-profile-tabs'), __('1 Comment','profilegrid-custom-profile-tabs'), __('% Comments','profilegrid-custom-profile-tabs') );?></span>
                        </div>
                    </div>

                    <div class="pm-blog-desc-wrap pm-difl">
                        <div class="pm-blog-title">
                            <a href="<?php the_permalink(); ?>"><span><?php the_title();?></span></a>
                        </div>
                        <div class="pm-blog-desc">
                         <?php the_excerpt();?>
                        </div>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
            endwhile;
             if($pagenum<$num_of_pages):
            ?>
                <div class="pg-load-more-container pm-dbfl">
                    <div class="pm-loader" style="display:none;"></div>
                    <input type="hidden" id="pg_next_blog_page_for<?php echo $post_type;?>" value="<?php echo $pagenum + 1; ?>" />
                    <input type="submit" class="pm-load-more-blogs" onclick ="load_more_pg_custom_blogs_tab_content('<?php echo $uid;?>','<?php echo $post_type;?>','<?php echo $pagenum + 1; ?>')" value="<?php _e('Load More','profilegrid-custom-profile-tabs'); ?>" />
                </div>
            <?php
            endif;

        }
        else
        {

            $current_user = wp_get_current_user();
            if($uid == $current_user->ID)
            {
                echo "<div class='pg-alert-warning pg-alert-info'> ";
                 _e("You have not written any blog posts yet. Once you do, they will appear here.",'profilegrid-custom-profile-tabs');
                echo "</div>";
            }
            else
            {
                echo "<div class='pg-alert-warning pg-alert-info'>";
                 echo sprintf(__("Sorry, %s has not made any blog posts yet.",'profilegrid-custom-profile-tabs'),$displayname);
                echo "</div>";
            }

        }
    }
    
    public function pm_load_pg_blogs()
    {
        $this->pm_get_custom_posts_tab_content($_POST['uid'],$_POST['post_type'],$_POST['page'] );
        die;
    }
    
    public function pm_check_tab_access_permission($tab, $profile_id,$access_level)
	{
                $pmfriends = new PM_Friends_Functions;
                $pmrequests = new PM_request;
                $dbhandler = new PM_DBhandler;
		$current_user_id = get_current_user_id();
                $profile_user_group = $pmrequests->profile_magic_get_user_field_value($profile_id,'pm_group');
                $current_user_group = $pmrequests->profile_magic_get_user_field_value($current_user_id,'pm_group');
                $is_my_friend = $pmfriends->profile_magic_is_my_friends($profile_id,$current_user_id);
                $profile_group_leader = $this->pm_get_group_leaders($profile_user_group);
                $in_same_group = $this->pm_check_same_group($profile_user_group,$current_user_group);
                
                $gids = get_user_meta($current_user_id,'pm_group',true);
                $gid = $pmrequests->pg_filter_users_group_ids($gids);
                
                $identifier = 'CUSTOMTABS';
                $id = $tab;
                $tab_meta = array();
                if ($id == false || $id == NULL) {
                    $id = 0;
                } else {
                    $row = $dbhandler->get_row($identifier, $id);
                    if(!empty($row->tab_meta))
                    {
                        (int)$tab_meta = maybe_unserialize($row->tab_meta);
                    }
                }
              if (isset($tab_meta['privacy_enable']))
                {
                $access = false;
                }
                else
                {
                 $access = true;
                }
                
                switch($access_level)
                {                    
                    case '1':
                        if($current_user_id == $profile_id)
                        {
                            $access = true;
                        }
                        break;
                    case '2':
                        if((is_user_logged_in() && in_array($current_user_id, $profile_group_leader)) || ( $current_user_id == $profile_id))
                        {
                            $access = true;
                        }
                        break;
                    case '3':
                        //if((is_user_logged_in() && (in_array($current_user_id, $profile_group_leader)) || current_user_can('manage_options')))
                        if(is_user_logged_in() &&  current_user_can('manage_options'))
                        {
                            $access = true;
                        }
                        break;
                    case '4':
                        if(is_user_logged_in() && $in_same_group)
                        {
                            $access = true;
                        }
                        break;
                    case '5':
                        if ( is_user_logged_in() && isset($is_my_friend) && !empty($is_my_friend)) 
                        {
                            $access = true;
                        }
                        break;
                    case '6':
                        if(is_user_logged_in())
                        {
                            $access = true;
                        }
                        break;
                    case '7':
                        $access = true;
                        break;
                    case '8':         
                        if( is_user_logged_in() && in_array(get_current_user_id(), $tab_meta['selected_users']) )
                        {
                            $access = true;
                        }
                        break;
                    case '9': 
                        if(isset($tab_meta['selected_groups']) && is_array($tab_meta['selected_groups']))
                        {
                            if(is_user_logged_in() && array_intersect($gid, $tab_meta['selected_groups']) )
                            {  
                                $access = true;
                            }
                        }
                        
                        break;
                        
                    case '10': 
                        $user = new WP_User( get_current_user_id() );
                        //print_r($tab_meta['selected_user_roles']);
                        if(isset($tab_meta['selected_user_roles']) && !empty($tab_meta['selected_user_roles'])){
                        $intersect = array_intersect($user->roles,$tab_meta['selected_user_roles']);
                        }
                        else
                        {
                            $intersect = array();
                        }
                        if(is_user_logged_in() && !empty($intersect))
                        {  
                            $access = true;
                        }
                        break;    
                        
                    default:
                            $access = false;
                        break;
                }
//                if($current_user_id == $profile_id)
//                {
//                    $access = true;
//                }
                
                return $access;
        }
        
        public function pm_get_group_leadersold($group_ids)
        {
            $dbhandler = new PM_DBhandler;  
            $group_leader_ids = array();
            if(is_array($group_ids))
            {
                foreach ($group_ids as $gid) {
                    $profile_user_groupinfo = $dbhandler->get_row('GROUPS',$gid);
                    if(isset($profile_user_groupinfo) && $profile_user_groupinfo->is_group_leader!=0){
                        $profile_group_user = get_user_by( 'login',$profile_user_groupinfo->leader_username );
                        if(isset($profile_group_user->ID))
                        {
                            $group_leader_ids[]= $profile_group_user->ID;
                        }
                    }
                }
            }
            else
            {
                $gids = array($group_ids);
                foreach ($gids as $gid) {
                    $profile_user_groupinfo = $dbhandler->get_row('GROUPS',$gid);
                    if(!empty($profile_user_groupinfo) && $profile_user_groupinfo->is_group_leader!=0){
                        $profile_group_user = get_user_by( 'login',$profile_user_groupinfo->leader_username );
                        if(isset($profile_group_user->ID))
                        {
                            $group_leader_ids[]= $profile_group_user->ID;
                        }
                    }
                }
            }
            
            return $group_leader_ids;
        }
        
        public function pm_get_group_leaders($group_ids)
        {
            $dbhandler = new PM_DBhandler;  
            $group_leader_ids = array();
            if(is_array($group_ids))
            {
                foreach ($group_ids as $gid) {
                    $profile_user_groupinfo = $dbhandler->get_row('GROUPS',$gid);
                    if(isset($profile_user_groupinfo) && $profile_user_groupinfo->is_group_leader!=0)
                    {
                        $group_leaders = maybe_unserialize($dbhandler->get_value('GROUPS','group_leaders',$gid,'id'));
                        if(is_array($group_leaders))
                        {
                            foreach($group_leaders as $leader)   
                            {
                                $group_leader_ids[] = $leader;
                            }
                        }
                        else
                        {
                            $group_leader_ids[] = $group_leaders;
                        }
                    }
                    
                }
            }
            else
            {
                $gids = array($group_ids);
                foreach ($gids as $gid) {
                    $profile_user_groupinfo = $dbhandler->get_row('GROUPS',$gid);
                    if(!empty($profile_user_groupinfo) && $profile_user_groupinfo->is_group_leader!=0){
                        $group_leaders = maybe_unserialize($dbhandler->get_value('GROUPS','group_leaders',$gid,'id'));
                        if(is_array($group_leaders))
                        {
                            foreach($group_leaders as $leader)   
                            {
                                $group_leader_ids[] = $leader;
                            }
                        }
                        else
                        {
                            $group_leader_ids[] = $group_leaders;
                        }
                    }
                }
            }
            
            return $group_leader_ids;
        }
        public function pm_check_same_group($primary, $secondary)
        {
            if(is_array($primary))
            {
                $group_one = $primary;
            }
            else
            {
                $group_one = array($primary);
            }
            if(is_array($secondary))
            {
                $group_two = $secondary;
            }
            else
            {
                $group_two = array($secondary);
            }
            
            $common_groups = array_intersect($group_one, $group_two);

            if(!empty($common_groups))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
}
