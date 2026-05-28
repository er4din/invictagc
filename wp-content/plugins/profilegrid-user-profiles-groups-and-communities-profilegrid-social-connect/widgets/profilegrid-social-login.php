<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Profilegrid_Social_Login' ) ) :
    
class Profilegrid_Social_Login extends WP_Widget {
  /**
  * To create the example widget all four methods will be 
  * nested inside this single instance of the WP_Widget class.
  **/
    
    public function __construct() {
        $widget_options = array( 
          'classname' => 'profilegrid_social_login',
          'description' => __('Renders Social Login buttons allowing users to register or login on site using their social network accounts.','profilegrid-social-connect'),
        );
        parent::__construct( 'profilegrid_social_login', __('ProfileGrid Social Login','profilegrid-social-connect'), $widget_options );
    }
    
    public function widget($args, $instance) {
        
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        
            do_action('profile_magic_social_login_widget');
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New Title','profilegrid-social-connect');
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','profilegrid-social-connect'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        
        <?php
    }
}

endif;

 