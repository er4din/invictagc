<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$pm_default_social_avatar =get_user_meta($uid,'pm_default_social_avatar' ,true);
$template = 'profile-tpl';
echo '<div id="pg_social_wrapper">';

$this->pg_social_connect_profile_html($uid,$gid,$template);

echo '</div>';
