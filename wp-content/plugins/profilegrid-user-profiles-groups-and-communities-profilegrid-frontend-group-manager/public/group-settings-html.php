 <div id="pg_group_setting" class="pm-dbfl">
                <div class="pm-group-view">
                    <div class="pm-section pm-dbfl">
                        <svg onclick="show_pg_section_left_panel()" class="pg-left-panel-icon" fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.41 16.09l-4.58-4.59 4.58-4.59L14 5.5l-6 6 6 6z"/>
                        <path d="M0-.5h24v24H0z" fill="none"/>
                        </svg>    
                        <div class="pm-section-left-panel pm-section-nav-vertical pm-difl">
                            <ul class="dbfl">
                                <?php if($dbhandler->get_global_option_value('pm_show_group_settings_subtab_group','1')=='1'):?>
                                <li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" href="#pg-edit-group"><?php _e('Group', 'profilegrid-frontend-group-manager'); ?></a></li>
                                <?php endif; ?>
                                    <?php if($group_type !== 'open'){ ?>
                                    <li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" href="#pg-join-requests"><?php _e('Requests', 'profilegrid-frontend-group-manager'); ?><span id="pg_show_pending_post"><?php echo $pmhtmlcreator->pg_get_pending_request_count_html($gid); ?></span></a></li>
                                <?php } ?>
                                 <?php if($dbhandler->get_global_option_value('pm_show_group_settings_subtab_members','1')=='1'):?>   
                                <li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" href="#pg-edit-members"><?php _e('Members', 'profilegrid-frontend-group-manager'); ?></a></li>
                                <?php endif; ?>
                                <?php if($dbhandler->get_global_option_value('pm_show_group_settings_subtab_blog','1')=='1'):?>
                                <li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" href="#pg-edit-blog"><?php _e('Blog', 'profilegrid-frontend-group-manager'); ?><span id="pg_show_pending_post"><?php echo $pmhtmlcreator->pg_get_pending_post_count_html($gid); ?></span></a></li>
                                <?php endif; ?>
                                    <?php do_action('profile_magic_group_setting_tab', $current_user->ID, $gid); ?>
                            </ul>
                        </div>

                        <div class="pm-section-right-panel">
                            <?php if($dbhandler->get_global_option_value('pm_show_group_settings_subtab_group','1')=='1'):?>
                                <div id="pg-edit-group" class="pm-blog-desc-wrap pm-difl pm-section-content">
                                    <?php
                                    $themepath = $pm_profile_magic_public->profile_magic_get_pm_theme('edit-group-tpl');
                                    include $themepath;
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if($group_type !== 'open'){  ?>
                            <div id="pg-join-requests" class="pm-blog-desc-wrap pm-difl pm-section-content">
                                    <?php
                                    $themepath = $pm_profile_magic_public->profile_magic_get_pm_theme('join-group-requests-tpl');
                                    include $themepath;
                                    ?>
                                </div>
                            <?php } ?>  
                            <?php if($dbhandler->get_global_option_value('pm_show_group_settings_subtab_members','1')=='1'):?>   
                                <div id="pg-edit-members" class="pm-blog-desc-wrap pm-difl pm-section-content">
                                    <?php
                                    $themepath = $pm_profile_magic_public->profile_magic_get_pm_theme('edit-group-member-tpl');
                                    include $themepath;
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if($dbhandler->get_global_option_value('pm_show_group_settings_subtab_blog','1')=='1'):?>
                                <div id="pg-edit-blog" class="pm-blog-desc-wrap pm-difl pm-section-content">
                                    <?php
                                    $themepath = $pm_profile_magic_public->profile_magic_get_pm_theme('edit-group-blog-tpl');
                                    include $themepath;
                                    ?>
                                </div>
                            <?php endif; ?>
                              <?php do_action('profile_magic_group_setting_tab_content', $current_user->ID, $gid); ?>
                            </div>
                      
                    </div>
                </div>
            </div>