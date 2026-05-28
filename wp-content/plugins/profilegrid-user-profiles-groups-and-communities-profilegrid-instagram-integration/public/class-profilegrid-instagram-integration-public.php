<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Instagram_Integration
 * @subpackage Profilegrid_Instagram_Integration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Instagram_Integration
 * @subpackage Profilegrid_Instagram_Integration/public
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Instagram_Integration_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $profilegrid_instagram_integration    The ID of this plugin.
     */
    private $profilegrid_instagram_integration;

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
     * @param      string    $profilegrid_instagram_integration       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $profilegrid_instagram_integration, $version ) {

            $this->profilegrid_instagram_integration = $profilegrid_instagram_integration;
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
             * defined in Profilegrid_Instagram_Integration_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Profilegrid_Instagram_Integration_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            wp_enqueue_style( $this->profilegrid_instagram_integration, plugin_dir_url( __FILE__ ) . 'css/profilegrid-instagram-integration-public.css', array(), $this->version, 'all' );

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
             * defined in Profilegrid_Instagram_Integration_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Profilegrid_Instagram_Integration_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
	    wp_enqueue_script('jquery');
	    //wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script( $this->profilegrid_instagram_integration, plugin_dir_url( __FILE__ ) . 'js/profilegrid-instagram-integration-public.js', array( 'jquery','wp-util' ), $this->version, true );
            
    }



    public function pg_instagram_integration_tab($id,$newtab,$uid,$gid)
    {
        if($id=='pg_instagram_integration_tab_content' && isset($newtab) && $newtab['status']=='1')
        {
            $dbhandler = new PM_DBhandler;
            if($dbhandler->get_global_option_value('pm_enable_instagram_integration','0')==1):
                if(is_user_logged_in())
                {
                    $has_token = get_user_meta( $uid,'pg_instagram_code', true );
                   if($uid == get_current_user_id() || $has_token):
                    ?>
                   <li class="pm-profile-tab pm-pad10"><a class="pm-dbfl" href="#pg_instagram_integration_tab_content"><?php echo _e($newtab['title'],'profilegrid-instagram-integration');?></a></li>
                  <?php         
                   endif;
                }
            endif;
        }
    }

    public function pg_show_instagram_integration_tab_content($id,$newtab,$uid,$gid,$primary_gid)
    {
        if($id=='pg_instagram_integration_tab_content' && isset($newtab) && $newtab['status']=='1')
        {
            $dbhandler = new PM_DBhandler;
            if($dbhandler->get_global_option_value('pm_enable_instagram_integration','0')==1):
                if(is_user_logged_in())
                {
                    $has_token = get_user_meta( $uid,'pg_instagram_code', true );
                     if($uid == get_current_user_id() || $has_token):

                     echo '<div id="pg_instagram_integration_tab_content" class="pm-dbfl pg-profile-tab-content">';
                     echo $this->pg_show_instagram_field($uid,$gid);
                     echo '</div>';
                     endif;
                }
            endif;
        }
    }
        
    

    public function pg_show_instagram_field( $uid, $gid ) {

		 $functions = new Instagram_Functions($uid,$gid);
		if ( $functions->is_session_started() === false ) {
			session_start();
		}

		$has_token = $functions->get_user_token('pg_instagram_code',$uid);
		if ( ! $has_token ) {
			$has_token = get_user_meta( $uid,'pg_instagram_code', true );
		}

		ob_start(); ?>

		<div class="pg-instagram-data" data-key="<?php echo $has_token; ?>">

			<?php 
			if ( $has_token ) { ?>
                    <?php if($uid == get_current_user_id()):?>
                    <div class="pg-insta-disconnect"> <a href="javascript:void(0);" class="pg-ig-photos_disconnect" onclick="pg_instagram_disconnect()">
					<?php _e( 'Disconnect', 'profilegrid-instagram-integration' ) ?>
                        </a></div>
                    <?php endif;?>
                                <div id="pg-ig-preload"></div>
				<div class="pg-clear"></div>
				<div id="pg-ig-content" class="pg-ig-content">
					<div id="pg-ig-photo-wrap" class="pg-ig-photos" data-metakey="<?php echo 'pg_instagram_code'; ?>" data-viewing="false">
						<?php echo $this->get_user_photos( $has_token, false ); ?>
					</div>
					<?php //echo $this->nav_template() ?>
					<div class="pg-clear"></div>
					<?php echo $this->get_user_details( $has_token ); ?>
					<?php //echo $this->paginate_template() ?>
				</div>
				
				<div class="pg-clear"></div>
				<input type="hidden" class="pg-ig-photos_metakey" name="pg_instagram_code" id="pg_instagram_code" value="<?php echo $has_token ?>"/>
                                <input type="hidden" class="pg-ig-photos_metakey" name="pg_instagram_uid" id="pg_instagram_uid" value="<?php echo $uid; ?>"/>
			<?php 
                        
                        } else { ?>

				<div class="pg-connect-instagram pm-dbfl">
					<div class="pg-connect-instagram-wrap">
						<div class="pg-clear"></div>
						<a href="<?php echo $functions->connect_url() ?>">
							<i class="pg-faicon-instagram"></i>
							<div class="pg-clear"></div>
                                                        <div class="pg-insta-connect-icon pm-dbfl"> 
                                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="510px" height="510px" viewBox="0 0 510 510"  xml:space="preserve">
    <g>
	<g id="post-instagram">
		<path d="M459,0H51C22.95,0,0,22.95,0,51v408c0,28.05,22.95,51,51,51h408c28.05,0,51-22.95,51-51V51C510,22.95,487.05,0,459,0z
			 M255,153c56.1,0,102,45.9,102,102c0,56.1-45.9,102-102,102c-56.1,0-102-45.9-102-102C153,198.9,198.9,153,255,153z M63.75,459
			C56.1,459,51,453.9,51,446.25V229.5h53.55C102,237.15,102,247.35,102,255c0,84.15,68.85,153,153,153c84.15,0,153-68.85,153-153
			c0-7.65,0-17.85-2.55-25.5H459v216.75c0,7.65-5.1,12.75-12.75,12.75H63.75z M459,114.75c0,7.65-5.1,12.75-12.75,12.75h-51
			c-7.65,0-12.75-5.1-12.75-12.75v-51c0-7.65,5.1-12.75,12.75-12.75h51C453.9,51,459,56.1,459,63.75V114.75z"/>
	</g>
    </g>

                                                            </svg>
                                                        </div>
                                                        <div class="pg-insta-connect pm-dbfl"><?php _e( 'Connect to Instagram','profilegrid-instagram-integration' ); ?></div>
						</a>
					</div>
				</div>

			<?php } ?>

		</div>

		<?php $output = ob_get_clean();
                
		return $output;
	}


         
        
        public function get_user_photos( $access_token, $viewing = true ) 
                {

	$url = 	'https://graph.instagram.com/me/media?fields=media_url,thumbnail_url,caption,id,media_type,timestamp,username,comments_count,like_count,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}&access_token='.$access_token ; 
       $response = wp_remote_get($url);
		if ( empty( $response['body'] ) ) {
			return '';
		}

		$photos = json_decode( $response['body']);
               $instagram_items = $photos->data;
 		if ( ! isset( $photos->data ) ) {
			return '';
		}

		ob_start();
	//$photos_count = count( $photos->data );
                $photos_count = 0 ;
                if (count($instagram_items)>0)
                {
 
  ?>
 
		<div id="pg-ig-show_photos" data-viewing="<?php echo $viewing ?>" data-photos-count="<?php echo $photos_count ?>">
                    	
                    	<div class="pg-insta-slider">
                          <?php 
                            $i = 1; 
                            
                            foreach ($instagram_items as $item ) 
                                 {
                               if ($item->media_type<>'VIDEO') 
                              {
                                 $username=  $item->username;
                             if ($item->media_type=="CAROUSEL_ALBUM")
                              {
                               foreach ($item->children->data as $item1 )
                               { 
                                   if ($item1->media_type<>'VIDEO') 
                               {
                                  $photos_count = $photos_count+1;
                                 ?>
                                   <div class="pg-insta-column">
					<a class="pg-insta-photo">
						<img class="pg-lazy" src="<?php echo $item1->media_url; ?>" data-original="<?php echo $item1->media_url; ?>" onclick="openModal();currentSlide(<?php echo $i;?>)" />
                                                
					</a>
  				</div>
                            
                            
			<?php 
                                    $i++;
                                 
                               }
                               }
                                
                               }
                               else 
                               {
                                   $photos_count = $photos_count+1;
                                   if (($item->media_type=="VIDEO"))
                                   {
                                       $img = $item->thumbnail_url;
                                   }     
                              
                                   else
                                   {
                                        $img = $item->media_url;
                                     }
                                ?>
                            
                                <div class="pg-insta-column">
					<a class="pg-insta-photo">
						<img class="pg-lazy" src="<?php echo $img ; ?>" data-original="<?php echo $img; ?>" onclick="openModal();currentSlide(<?php echo $i;?>)" />
                                                
					</a>
                                    

				</div>
                            
                            
			<?php 
                                
                                    $i++;
                               }
                                }
                                 }
                                
                                
                            
                         ?>
                        </div>
                    <?php 
                    ?>
                    
                    
                    <div id="pg-insta-modal" class="pg-insta-modal">
                        <span class="pg-insta-photo-modal-close" onclick="closeModal()">&times;</span>
                        <div class="pg-insta-modal-content">
                            <?php $i = 1; 
                             foreach ($instagram_items as $item )  {
			   if ($item->media_type<>'VIDEO') 
                               {
                             if ($item->media_type=="CAROUSEL_ALBUM")
                              {
                               foreach ($item->children->data as $item1 )
                               {  
                                   
                                    if ($item1->media_type<>'VIDEO') 
                               { ?>
                          <div class="pg-insta-photo-slides">
                            <div class="pg-insta-photo-number"><?php echo $i.' / '. $photos_count;?></div>
                            <img src="<?php echo $item1->media_url;?>" style="width:100%">
                          </div>
                            <?php 
                                    $i++;
                               }
                                }
                              }
                              else
                              {
                                 if (($item->media_type=="VIDEO"))
                                 {
                                     ?>
                            
                            <div class="pg-insta-photo-slides">
                            <div class="pg-insta-photo-number"><?php echo $i.' / '. $photos_count;?></div>
                            <iframe allow="encrypted-media" src="<?php echo $item->media_url; ?>" frameborder="0" allowfullscreen></iframe>
                          </div>
                            <?php
                                 
                                 }
                                
 else  
 {     ?>  
                             <div class="pg-insta-photo-slides">
                            <div class="pg-insta-photo-number"><?php echo $i.' / '. $photos_count;?></div>
                            <img src="<?php echo $item->media_url;?>" style="width:100%">
                          </div>
                            
                            
 <?php   }
                            $i++;    
                              }
                             
                            }
                                   
                             }
                         
                         ?>

                          <a class="pg-insta-photo-prev" onclick="plusSlides(-1)">&#10094;</a>
                          <a class="pg-insta-photo-next" onclick="plusSlides(1)">&#10095;</a>

                        
                        </div>
                    </div>
                    
                </div>
                <?php   
                
                if($photos_count>9)
                {?>        
                <div class="prevNext" align="center">
		 <button class="button-left" onclick="plusDivs(-1)">&#10094;</button>
  <button class="button-right" onclick="plusDivs(1)">&#10095;</button>
	
                </div>
                <?php
                }
                ?>
                   <div id="instagram-link"><a href="https://www.instagram.com/<?php echo $username; ?>" target="_blank">View All Photos on Instagram</a></div>
                 
                   <?php
                $output1 = ob_get_clean();
                }
                else
                {
                    
                  $output1= '<div class="pg-insta-error">No Photos to display on your Instagram profile.</div>';  ?>
                   
                    
                    <?php
                }
		return $output1;
	}
   

        
        
        
        public function nav_template() {
		ob_start(); ?>

		<div class="pg-ig-photo-navigation">
			<a href="javascript:void(0);" class="nav-left nav-show">
				<i class="pg-faicon-arrow-left"></i>
			</a>
			<a href="javascript:void(0);" class="nav-right nav-show">
				<i class="pg-faicon-arrow-right"></i>
			</a>
		</div>

		<?php $output = ob_get_clean();
		return $output;
	}


	/**
	 * @return string
	 */
	public function paginate_template() {
		ob_start(); ?>

		<div class="pg-ig-paginate">
			<span>0/0</span>
		</div>

		<?php $output = ob_get_clean();
		return $output;
	}
        
        public function get_user_details( $access_token ) {

		$response = wp_remote_get( 'https://api.instagram.com/v1/users/self/?access_token=' . $access_token );

		if ( empty( $response['body'] ) ) {
			return '';
		}

		$user = json_decode( $response['body'] );
		if ( ! isset( $user->data ) ) {
			return '';
		}

		ob_start(); ?>

		<div class="pg-ig-user-details">
                    <a href="https://instagram.com/<?php echo $user->data->username ?>/" target="_blank">
		<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 56.7 56.7" enable-background="new 0 0 56.7 56.7" xml:space="preserve">
         <g>
	<path d="M28.2,16.7c-7,0-12.8,5.7-12.8,12.8s5.7,12.8,12.8,12.8S41,36.5,41,29.5S35.2,16.7,28.2,16.7z M28.2,37.7
		c-4.5,0-8.2-3.7-8.2-8.2s3.7-8.2,8.2-8.2s8.2,3.7,8.2,8.2S32.7,37.7,28.2,37.7z"/>
	<circle cx="41.5" cy="16.4" r="2.9"/>
	<path d="M49,8.9c-2.6-2.7-6.3-4.1-10.5-4.1H17.9c-8.7,0-14.5,5.8-14.5,14.5v20.5c0,4.3,1.4,8,4.2,10.7c2.7,2.6,6.3,3.9,10.4,3.9
		h20.4c4.3,0,7.9-1.4,10.5-3.9c2.7-2.6,4.1-6.3,4.1-10.6V19.3C53,15.1,51.6,11.5,49,8.9z M48.6,39.9c0,3.1-1.1,5.6-2.9,7.3
		s-4.3,2.6-7.3,2.6H18c-3,0-5.5-0.9-7.3-2.6C8.9,45.4,8,42.9,8,39.8V19.3c0-3,0.9-5.5,2.7-7.3c1.7-1.7,4.3-2.6,7.3-2.6h20.6
		c3,0,5.5,0.9,7.3,2.7c1.7,1.8,2.7,4.3,2.7,7.2V39.9L48.6,39.9z"/>
        </g>
</svg>&nbsp;
				<span><?php _e( "Visit Instagram Profile","profilegrid-instagram-integration" ) ?></span>
			</a>
		</div>

		<?php $output = ob_get_clean();
		return $output;
	}
        
        
        
        public function pg_instagram_disconnect()
        {
            $uid = $_POST['uid'];
           
           echo delete_user_meta($uid,'pg_instagram_code');
            die;
        }
        
    public function profile_magic_profile_tab_link_fun($id,$newtab,$uid,$gid,$primary_gid)
    {
        if(isset($newtab) && $newtab['status']=='1'):
            switch($id)
            {
                case 'pg_instagram_integration_tab_content':
                    $this->pg_instagram_integration_tab($id,$newtab,$uid,$primary_gid);
                    break;
                
            }
        endif;
    }
    
    public function profile_magic_profile_tab_extension_content_fun($id,$newtab,$uid,$gid,$primary_gid)
    {
        if(isset($newtab) && $newtab['status']=='1'):
            switch($id)
            {
                case 'pg_instagram_integration_tab_content':
                    $this->pg_show_instagram_integration_tab_content($id,$newtab,$uid,$gid,$primary_gid);
                    break;
               
            }
        endif;
    }
        
}


