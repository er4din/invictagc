<?php
class profilegrid_profile_visitor_details_controler
{
    public function pm_get_user_profile_picture($visitor_id)
    {
        $user_data = get_userdata($visitor_id);

        // Check if user data exists and retrieve the email
        if ($user_data) {
            $user_email = $user_data->user_email;
    
            // Get the avatar using the user's email
            $profile_picture_url = get_avatar($user_email, 50);
            // print_r($profile_picture_url); die;
            return $profile_picture_url;
        }
    
    }

}
