<?php

$dbhandler = new PM_DBhandler;
$pm_activator = new Profile_Magic_Activator;
$pmrequests = new PM_request;
$path = plugin_dir_url(__FILE__);
$identifier = 'CUSTOMTABS';
$id = filter_input(INPUT_GET, 'id');
$tab_meta = array();
if ($id == false || $id == NULL) {
    $id = 0;
} else {
    $row = $dbhandler->get_row($identifier, $id);
    if(!empty($row->tab_meta))
    {
        $tab_meta = maybe_unserialize($row->tab_meta);
    }
}

if (filter_input(INPUT_POST, 'submit_tab')) {
    $retrieved_nonce = filter_input(INPUT_POST, '_wpnonce');
    if (!wp_verify_nonce($retrieved_nonce, 'save_pm_add_custom_tab'))
        die('Failed security check');
    $list_id = filter_input(INPUT_POST, 'list_id');
    $exclude = array("_wpnonce", "_wp_http_referer", "submit_tab", "list_id");
    
    $post = $pmrequests->sanitize_request($_POST, $identifier, $exclude);
 
    if ($post != false) {
        foreach ($post as $key => $value) {
            if ($key=="tab_meta"):
            $tab_metaaa = maybe_unserialize($value);
            $items = explode(',', $tab_metaaa['selected_users']);
              $tab_metaaa['selected_users'] = $items ;
                $data[$key] = maybe_serialize($tab_metaaa);
            else:
                  $data[$key] = $value;
            endif;
            $arg[] = $pm_activator->get_db_table_field_type($identifier, $key);
        }
    }
   
    if ($list_id == 0) {
        $dbhandler->insert_row($identifier, $data, $arg);
    } else {
        $dbhandler->update_row($identifier, 'id', $list_id, $data, $arg, '%d');
    }

    wp_redirect('admin.php?page=pm_custom_profile_tabs');
    exit;
    
}
?>
<div class="uimagic">
    <form name="pm_add_custom_tab" id="pm_add_custom_tab" method="post">
        <!-----Dialogue Box Starts----->
        <div class="content">
<?php if ($id == 0): ?>
                <div class="uimheader">
    <?php _e('New Tab', 'profilegrid-custom-profile-tabs'); ?>
                </div>
<?php else: ?>
                <div class="uimheader">
    <?php _e('Edit Tab', 'profilegrid-custom-profile-tabs'); ?>
                </div>
            <?php endif; ?>
            <div class="uimsubheader">
                <?php
                //Show subheadings or message or notice
                ?>
            </div>
            <div class="uimrow">
                <div class="uimfield">
            <?php _e('Title of the Tab', 'profilegrid-custom-profile-tabs'); ?>
                    <sup>*</sup></div>
                <div class="uiminput pm_required">
                    <input type="text" name="tab_label" id="tab_label" value="<?php if (!empty($row)) echo esc_attr($row->tab_label); ?>" />
                    <div class="errortext"></div>
                </div>
                <div class="uimnote"><?php _e('Title of the tab as it appears on the user profile page.', 'profilegrid-custom-profile-tabs'); ?></div>
            </div>      

            <div class="uimrow">
                <div class="uimfield">
<?php _e('Fetch Content from', 'profilegrid-custom-profile-tabs'); ?>
                </div>
                <div class="uiminput pm_checkbox_required">
                    <ul class="uimradio">
                        <li>
                            <input type="radio" name="tab_content_from" id="tab_content_from1"  value="custom_posts" <?php
if (!empty($row)) {
    if (isset($row->tab_content_from) && $row->tab_content_from == 'custom_posts') {
        echo "checked";
    }
} else {
    echo "checked";
}
?> onClick="pm_show_hide(this, 'custom_posts_html', 'custom_content_html','post_content_html')">
 							<label for="tab_content_from1"><?php _e('User Custom Posts', 'profilegrid-custom-profile-tabs'); ?></label>
                        </li>
                        <li>
                            <input type="radio" name="tab_content_from" id="tab_content_from2" value="custom_content" <?php
                            if (!empty($row) && isset($row->tab_content_from) && $row->tab_content_from == 'custom_content') {
                                echo "checked";
                            }
                            ?> onClick="pm_show_hide(this, 'custom_content_html', 'custom_posts_html','post_content_html')">
                                  <label for="tab_content_from2"><?php _e('Custom Content', 'profilegrid-custom-profile-tabs'); ?></label>
                        </li>
                        <li>
                            <input type="radio" name="tab_content_from" id="tab_content_from3"  value="post_content" <?php
                            if (!empty($row) && isset($row->tab_content_from) && $row->tab_content_from == 'post_content') {
                                echo "checked";
                            }
                            ?> onClick="pm_show_hide(this, 'post_content_html', 'custom_posts_html','custom_content_html')">
                                  
							  <label for="tab_content_from3"><?php _e('Post Content', 'profilegrid-custom-profile-tabs'); ?></label>
                        </li>
                    </ul>
                </div>
                <div class="uimnote"><?php _e('Define if you wish to fetch user created content from custom posts or use a shortcode instead.', 'profilegrid-custom-profile-tabs'); ?></div>
            </div>
            <div class="childfieldsrow" id="custom_posts_html" style=" <?php if (!empty($row)) {
                                       if (isset($row->tab_content_from) && $row->tab_content_from == 'custom_posts') {
                                           echo 'display:block;';
                                       } else {
                                           echo 'display:none;';
                                       }
                                   } else {
                                       echo 'display:block;';
                                   } ?>">  
                <div class="uimrow">
                    <div class="uimfield">
                        <?php _e('Post Type', 'profilegrid-custom-profile-tabs'); ?>
                    </div>
                    <div class="uiminput">

                            <?php
                            // Get post types
                            $args = array('public' => true);
                            $post_types = get_post_types($args, 'objects');
                            ?>

                        <select name="tab_data_type" id="tab_data_type">
                            <?php
                            foreach ($post_types as $post_type_obj):
                                if ($post_type_obj->name == 'pg_groupwalls' || $post_type_obj->name == 'profilegrid_blogs' || $post_type_obj->name == 'attachment') {
                                    continue;
                                }
                                $labels = get_post_type_labels($post_type_obj);
                                ?>
                                <option value="<?php echo esc_attr($post_type_obj->name); ?>" <?php if (!empty($row)) {
                                    selected($row->tab_data_type, $post_type_obj->name);
                                } ?>><?php echo esc_html($labels->name); ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="uimnote"><?php _e('Select custom post type from which user authored content will be fetched.', 'profilegrid-custom-profile-tabs'); ?></div>
                </div>
            </div>
            
            
             <div class="childfieldsrow" id="post_content_html" style=" <?php if (!empty($row)) {
                                       if (isset($row->tab_content_from) && $row->tab_content_from == 'post_content') {
                                           echo 'display:block;';
                                       } else {
                                           echo 'display:none;';
                                       }
                                   } else {
                                       echo 'display:none;';
                                   } ?>">  
                <div class="uimrow">
                    <div class="uimfield">
                        <?php _e('Select Post/Page', 'profilegrid-custom-profile-tabs'); ?>
                    </div>
                    <div class="uiminput">
                        <select name="tab_meta[post_id]" id="post_id" >
                            <?php  

                            $posts = new WP_Query(array('post_type' => array( 'post', 'page' ),'post_status'=>'publish','posts_per_page'=>-1)); 
                            while($posts->have_posts()) : $posts->the_post(); ?>
                            <option value="<?php echo get_the_ID(); ?>" <?php if(!empty($tab_meta)){ selected($tab_meta['post_id'],get_the_ID());}?>><?php echo get_the_title(); ?></option>
                            <?php endwhile;
                            
                            ?>
                        </select>
                           

                        
                    </div>
                    <div class="uimnote"><?php _e('Select post or page which you wish to display inside the tab.', 'profilegrid-custom-profile-tabs'); ?></div>
                </div>
            </div>

            <div class="childfieldsrow" id="custom_content_html" style=" <?php if (!empty($row)) {
                            if (isset($row->tab_content_from) && $row->tab_content_from == 'custom_content') {
                                echo 'display:block;';
                            } else {
                                echo 'display:none;';
                            }
                        } else {
                            echo 'display:none;';
                        } ?>">  
                <div class="uimrow">
                    <div class="uimfield">
                        <?php _e('Tab Content', 'profilegrid-custom-profile-tabs'); ?>
                    </div>
                    <div class="uiminput">

                        <?php
                        if (!empty($row)) {
                            $tab_content = $row->tab_content;
                        } else {
                            $tab_content = '';
                        }
                        $settings = array(
                            'wpautop' => true,
                            'media_buttons' => false,
                            'textarea_name' => 'tab_content',
                            'textarea_rows' => 20,
                            'tabindex' => '',
                            'tabfocus_elements' => ':prev,:next',
                            'editor_css' => '',
                            'editor_class' => '',
                            'teeny' => false,
                            'dfw' => false,
                            'tinymce' => true, // <-----
                            'quicktags' => true
                        );
                        wp_editor($tab_content, 'tab_content', $settings);
                        
                        ?>
                    </div>
                    <div class="uimnote"><?php _e('Paste a shortcode or static content to be displayed inside the tab.', 'profilegrid-custom-profile-tabs'); ?></div>
                </div>
            </div>

            <div class="uimrow">
                <div class="uimfield">
                                <?php _e('Privacy', 'profilegrid-custom-profile-tabs'); ?>
                </div>
                <div class="uiminput">
                    <input name="tab_meta[privacy_enable]" id="enable_tabs_privacy" type="checkbox"  class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this, 'tabs_privacy_html')" <?php if(isset($tab_meta['privacy_enable'])){ checked($tab_meta['privacy_enable'],'1');}  ?> />
                    <label for="enable_tabs_privacy"></label>
                    <div class="errortext"></div>
                </div>
                <div class="uimnote"><?php _e('Define who can see this profile tab.', 'profilegrid-custom-profile-tabs'); ?></div>
            </div>   

            <div class="childfieldsrow" id="tabs_privacy_html" style="<?php if(!empty($tab_meta)){if(isset($tab_meta['privacy_enable']) && $tab_meta['privacy_enable']==1){ echo 'display:block;';}else{echo 'display:none;';}}else{echo 'display:none;';}  ?>">  
                <div class="uimrow">
                    <div class="uimfield">
                                <?php _e('Tab Visibility', 'profilegrid-custom-profile-tabs'); ?>
                    </div>
                    <div class="uiminput" id="pg-tab-visibility">
                        <ul class="uimradio">
                            <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="1" <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '1'); } ?>>
<?php _e('User', 'profilegrid-custom-profile-tabs'); ?>
                            </li>
                            <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="2"  <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '2'); } ?>>
<?php _e('User and Group Admins', 'profilegrid-custom-profile-tabs'); ?>
                            </li>
                            <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="3"  <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '3'); } ?>>
<?php _e('Only Admins', 'profilegrid-custom-profile-tabs'); ?>
                            </li>
                             <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="4" <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '4'); } ?>>
<?php _e('Group Members.', 'profilegrid-custom-profile-tabs'); ?>
                            </li>
                            <li>
                            <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="5" <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '5'); }  ?>>
<?php _e('Friends', 'profilegrid-custom-profile-tabs'); ?>
                            </li>                            
                            <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="6" <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '6'); } ?>>
<?php _e('Logged In Users', 'profilegrid-custom-profile-tabs'); ?>
                            </li>
                            <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="7" <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '7'); } ?>>
<?php _e('Public', 'profilegrid-custom-profile-tabs'); ?>
                            </li>
                            <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="8" <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '8'); } ?> onClick="pm_show_hide(this, 'tabs_privacy_users_html')">
<?php _e('Selected Users', 'profilegrid-custom-profile-tabs'); ?>
                            </li>
                            <div class="childfieldsrow" id="tabs_privacy_users_html" style="<?php if(!empty($tab_meta)){if(isset($tab_meta['privacy_code']) && $tab_meta['privacy_code']==8){ echo 'display:block;';}else{echo 'display:none;';}}else{echo 'display:none;';}  ?>">  
                                <div class="uimrow">
                                    <div class="uimfield">
                                         <?php _e('Select Users', 'profilegrid-custom-profile-tabs'); ?>
                                    </div>
                                    <!-- multiselect dropdown to select groups--> 
                                    <div class="uiminput">
<?php 
$selected_users="";
if(isset($tab_meta['selected_users']) )
{
	 $selected_users= implode(',', $tab_meta['selected_users']);
}
?>
                                        <textarea  name="tab_meta[selected_users]"><?php echo $selected_users; ?></textarea><br />
                                        <p class="description"><?php _e('Separate multiple users ID with comma(,)','profilegrid-custom-profile-tabs') ?></p>
                                    </div>
                                </div>
                            </div>   
                            <!-- end multiselect dropdown. -->
                            <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="9"  <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '9'); } ?> onClick="pm_show_hide(this, 'tabs_privacy_groups_html')">
<?php _e('Selected Groups', 'profilegrid-custom-profile-tabs'); ?>
                            </li>
                            <div class="childfieldsrow" id="tabs_privacy_groups_html" style="<?php if(!empty($tab_meta)){if(isset($tab_meta['privacy_code']) && $tab_meta['privacy_code']==9){ echo 'display:block;';}else{echo 'display:none;';}}else{echo 'display:none;';}  ?>">  
                                <div class="uimrow">
                                    <div class="uimfield">
                                         <?php _e('Select Groups', 'profilegrid-custom-profile-tabs'); ?>
                                    </div>
                                    <!-- multiselect dropdown to select groups--> 
                                    <div class="uiminput">
                                        <select multiple name="tab_meta[selected_groups][]" id="pm_custom_profile_selected_groups">
                                            <?php
                                            $dbhandler = new PM_DBhandler;
                                            $groups = $dbhandler->get_all_result('GROUPS');
                                            foreach ($groups as $group) 
                                            {
                                                ?>
                                                <option value="<?php echo $group->id; ?>" <?php if(isset($tab_meta['selected_groups']) ){if(is_array($tab_meta['selected_groups'])){if(in_array($group->id, $tab_meta['selected_groups'])){echo 'selected';}}} ?> ><?php echo $group->group_name; ?></option>
                                                <?php    
                                            } ?>

                                        </select>
                                        <p class="description"><?php _e('Press ctrl or ⌘ (in Mac) while clicking to select multiple groups.','profilegrid-custom-profile-tabs') ?></p>
                                    </div>
                                </div>
                            </div>   
                            <!-- end multiselect dropdown. -->
                                              
                            <!-- end multiselect dropdown. -->
                            <li>
                                <input type="radio" onchange="get_button_id(this);" name="tab_meta[privacy_code]" id="tab_meta" value="10"  <?php if(isset($tab_meta['privacy_code'])){ checked($tab_meta['privacy_code'], '10'); } ?> onClick="pm_show_hide(this, 'tabs_privacy_user_roles_html')">
<?php _e('Specific Roles', 'profilegrid-custom-profile-tabs'); ?>
                            </li>
                            <div class="childfieldsrow" id="tabs_privacy_user_roles_html" style="<?php if(!empty($tab_meta)){if(isset($tab_meta['privacy_code']) && $tab_meta['privacy_code']==10){ echo 'display:block;';}else{echo 'display:none;';}}else{echo 'display:none;';}  ?>">  
                                <div class="uimrow">
                                    <div class="uimfield">
                                         <?php _e('Select Roles', 'profilegrid-custom-profile-tabs'); ?>
                                    </div>
                                    <!-- multiselect dropdown to select groups--> 
                                    <div class="uiminput">
                                        <select multiple name="tab_meta[selected_user_roles][]" id="pm_custom_profile_selected_user_roles">
                                            <?php
                                            $args = array(
                                                'orderby' => 'first_name',
                                                'order'   => 'ASC'
                                            );
                                       //     $users = get_users( $args );
                                            global $wp_roles;
                                            $wp_roles = new WP_Roles();
                                            $all_roles = $wp_roles->get_names();
                                            
                                            foreach ($all_roles as $key => $role) 
                                            {
                                            
                                                ?>
                                                <option value="<?php echo $key; ?>" <?php if(isset($tab_meta['selected_user_roles']) ){if(is_array($tab_meta['selected_user_roles'])){if(in_array($key, $tab_meta['selected_user_roles'])){echo 'selected';}}} ?> ><?php echo $role; ?></option>
                                                <?php    
                                            } ?>

                                        </select>
                                        <p class="description"><?php _e('Press ctrl or ⌘ (in Mac) while clicking to select multiple roles.','profilegrid-custom-profile-tabs') ?></p>
                                    </div>
                                </div>
                            </div>   
                            <!-- end multiselect dropdown. -->
                            
                            
                            
                            
                            
                            
                            
                            
                            
                        </ul>
                        <div class="errortext"></div>
                    </div>
                    <div class="uimnote"><?php // _e('Select custom post type from which user authored content will be fetched.', 'profilegrid-custom-profile-tabs'); ?></div>
                </div>
            </div>


            <div class="buttonarea"> <a href="admin.php?page=pm_custom_profile_tabs">
                    <div class="cancel">&#8592; &nbsp;
<?php _e('Cancel', 'profilegrid-custom-profile-tabs'); ?>
                    </div>
                </a>
                <input type="hidden" name="list_id" id="list_id" value="<?php echo $id; ?>" />
<?php wp_nonce_field('save_pm_add_custom_tab'); ?>
                <input type="submit" value="<?php _e('Save', 'profilegrid-custom-profile-tabs'); ?>" name="submit_tab" id="submit_tab" onclick="return add_field_validation()" />
                <div class="all_error_text" style="display:none;"></div>
            </div>
        </div>
    </form>
</div>