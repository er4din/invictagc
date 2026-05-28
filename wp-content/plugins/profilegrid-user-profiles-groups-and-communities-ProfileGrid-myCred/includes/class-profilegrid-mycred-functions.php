<?php
class Profilegrid_Mycred_Functions {
    
    public function update_user_balance($reference,$user_id,$log)
    {
        $dbhandler = new PM_DBhandler;
        $enable = $dbhandler->get_global_option_value('pm_enable_mycred','0');
        if($enable=='1')
        {
            $type = $dbhandler->get_global_option_value('pm_mycred_type','mycred_default');
            $limit_var = $reference.'_limit';
            $limit = $dbhandler->get_global_option_value($limit_var,'0');
            $points = $dbhandler->get_global_option_value($reference);
            $reference_count = mycred_count_ref_instances($reference, $user_id );
            if($reference_count<$limit || $limit=='0')
            {
                mycred_add($reference, $user_id,$points,$log,'','',$type);
                $meta_key= $reference.'_total_count';
                mycred_add_user_meta( $user_id, $meta_key, '', $reference_count );
            }
        }
    }
    
    // Get user's rank progress
    public function get_mycred_users_rank_progress( $users_balance,$max ) 
    {
	
	// Calculate progress. We need a percentage with 1 decimal
	$progress = number_format( ( ( $users_balance / $max ) * 100 ), 0 );

	if($progress>100)
        {
            $progress = 100;
        }
        
        echo '<div class="mycred-rank-progress pm-dbfl">';	
		echo '<div class="rank-progress-bar pg-progress" style="width:'.$progress.'%">';
		echo '</div>';	
	echo '</div>';
    }

    public function pg_get_mycred_badges($show,$user_id,$width, $height)
    {
        echo '<div class="pg-all-badges pm-dbfl">';
        if ($show == 'earned') 
        {
            mycred_display_users_badges( $user_id, $width, $height );
        }
        else
        {
            $users_badges = mycred_get_users_badges( $user_id );
            $all_badges   = mycred_get_badge_ids();

            foreach ( $all_badges as $badge_id ) {

                    

                    // User has not earned badge
                    if ( ! array_key_exists( $badge_id, $users_badges ) ) {
                        echo '<div class="pg-badge pg-badge-gray pm-difl">';
                            $badge = mycred_get_badge( $badge_id );
                            $badge->image_width  = $width;
                            $badge->image_height = $height;

                            if ( $badge->main_image !== false )
                            {
                                    echo $badge->get_image( 'main' );
                            }
                            else
                            {
                               echo '<div class="pg-default-gray-badge" title="'.$badge->title.'"></div>';
                            }
                            echo '</div>';

                    }

                    // User has earned badge
                    else {
                            echo '<div class="pg-badge pg-badge-colored pm-difl">';
                            $level = $users_badges[ $badge_id ];
                            
                            $badge = mycred_get_badge( $badge_id, $level );
                            //print_r($badge);
                            $badge->image_width  = $width;
                            $badge->image_height = $height;

                            if ( $badge->level_image !== false )
                            {
                                    echo $badge->get_image( $level );
                            }
                            else
                            {
                                echo '<div class="pg-default-gray-badge" title="'.$badge->title.'"></div>';
                            }
                            echo '</div>';

                    }

                    

            }
        }
        echo '</div>';
       
    }
    
    
}
