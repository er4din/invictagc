<div class="uimrow" id="notification">
       <div class="uimfield">
         <?php _e( 'Display Name Pattern','profilegrid-user-display-name' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[display_name]" id="display_name" type="checkbox"  class="pm_toggle" value="1" style="display:none;"  onClick="pm_show_hide(this,'pattern_html')" <?php if(!empty($group_options) && isset($group_options['display_name']) && $group_options['display_name']==1){ echo "checked";}?>/>
         <label for="display_name"></label>
       </div>
         <div class="uimnote"><?php _e('Turn on customized display names for user profiles for this group.','profilegrid-user-display-name');?></div>
</div>

     
<div class="childfieldsrow" id="pattern_html" style=" <?php if(!empty($group_options) && isset($group_options['display_name']) && $group_options['display_name']=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
     
     <div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Name Pattern','profilegrid-user-display-name' ); ?>
       </div>
       <div class="uiminput">
           <select name="group_options[display_name_pattern]" id="display_name_pattern">
             <option value="1" <?php if(!empty($group_options) && isset($group_options['display_name_pattern']) && $group_options['display_name_pattern']==1) echo 'selected'; ?>><?php _e('FirstName LastName','profilegrid-user-display-name');?></option>
             <option value="2" <?php if(!empty($group_options) && isset($group_options['display_name_pattern']) && $group_options['display_name_pattern']==2) echo 'selected'; ?>><?php _e('LastName, FirstName','profilegrid-user-display-name');?></option>
             <option value="3" <?php if(!empty($group_options) && isset($group_options['display_name_pattern']) && $group_options['display_name_pattern']==3) echo 'selected'; ?>><?php _e('F. LastName','profilegrid-user-display-name');?></option>
             <option value="4" <?php if(!empty($group_options) && isset($group_options['display_name_pattern']) && $group_options['display_name_pattern']==4) echo 'selected'; ?>><?php _e('FirstName L.','profilegrid-user-display-name');?></option>
            <option value="5" <?php if(!empty($group_options) && isset($group_options['display_name_pattern']) && $group_options['display_name_pattern']==5) echo 'selected'; ?>><?php _e('F.L.','profilegrid-user-display-name');?></option>
            <option value="6" <?php if(!empty($group_options) && isset($group_options['display_name_pattern']) && $group_options['display_name_pattern']==6) echo 'selected'; ?>><?php _e('NickName','profilegrid-user-display-name');?></option>
            <option value="7" <?php if(!empty($group_options) && isset($group_options['display_name_pattern']) && $group_options['display_name_pattern']==7) echo 'selected'; ?>><?php _e('UserName','profilegrid-user-display-name');?></option>
            <option value="8" <?php if(!empty($group_options) && isset($group_options['display_name_pattern']) && $group_options['display_name_pattern']==8) echo 'selected'; ?>><?php _e('Email','profilegrid-user-display-name');?></option>
     
         </select>
         <div class="errortext"></div>
       </div>
         <div class="uimnote"><?php _e('Select a pattern for displaying names for this group.','profilegrid-user-display-name');?></div>

     </div>
     
         
    <div class="uimrow">
        <div class="uimfield">
          <?php _e('Display Name Style','profilegrid-user-display-name');
          ?>
        </div>
        <div class="uiminput">
          <select name="group_options[pm_display_name_style]" id="pm_display_name_style">
              <option value="0" <?php if(!empty($group_options) && isset($group_options['pm_display_name_style']) && $group_options['pm_display_name_style']==0) echo 'selected'; ?>><?php _e('Default','profilegrid-user-display-name');?></option>
              <option value="1" <?php if(!empty($group_options) && isset($group_options['pm_display_name_style']) && $group_options['pm_display_name_style']==1) echo 'selected'; ?>><?php _e('Capitalized','profilegrid-user-display-name');?></option>
              <option value="2" <?php if(!empty($group_options) && isset($group_options['pm_display_name_style']) && $group_options['pm_display_name_style']==2) echo 'selected'; ?>><?php _e('Uppercase','profilegrid-user-display-name');?></option>
              <option value="3" <?php if(!empty($group_options) && isset($group_options['pm_display_name_style']) && $group_options['pm_display_name_style']==3) echo 'selected'; ?>><?php _e('lowercase','profilegrid-user-display-name');?></option>
              <option value="4" <?php if(!empty($group_options) && isset($group_options['pm_display_name_style']) && $group_options['pm_display_name_style']==4) echo 'selected'; ?>><?php _e('Underlined','profilegrid-user-display-name');?></option>
              <option value="5" <?php if(!empty($group_options) && isset($group_options['pm_display_name_style']) && $group_options['pm_display_name_style']==5) echo 'selected'; ?>><?php _e('Bold','profilegrid-user-display-name');?></option>
          </select>
          <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e("Select a text style for the user display name on their profiles.",'profilegrid-user-display-name');?></div>
      </div>
      <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Add Prefix','profilegrid-user-display-name' ); ?>
        </div>
        <div class="uiminput">
          <input name="group_options[enable_prefix]" id="enable_prefix" type="checkbox"  class="pm_toggle" value="1" <?php if(!empty($group_options) && isset($group_options['enable_prefix']) && $group_options['enable_prefix']==1){ echo "checked";}?> style="display:none;"  onClick="pm_show_hide(this,'prefixhtml')" />
          <label for="enable_prefix"></label>
        </div>
          <div class="uimnote"><?php _e('Define a common prefix for all display names for this group.','profilegrid-user-display-name');?></div>
      </div>
      <div class="childfieldsrow" id="prefixhtml" style=" <?php if(!empty($group_options) && isset($group_options['enable_prefix']) && $group_options['enable_prefix']=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
        <div class="uimrow">
          <div class="uimfield">
            <?php _e( 'Prefix','profilegrid-user-display-name' ); ?>
          </div>
          <div class="uiminput">
            <input name="group_options[set_prefix]" id="set_prefix" type="text"  value="<?php if(!empty($group_options) && isset($group_options['set_prefix']))echo esc_attr($group_options['set_prefix']); ?>"  />
            <div class="errortext"></div>
            <div class="user_name_error"></div>
          </div>
            <div class="uimnote"><?php _e('Define prefix text.','profilegrid-user-display-name');?></div>
        </div> 
      </div>
         
         
       <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Add Suffix','profilegrid-user-display-name' ); ?>
        </div>
        <div class="uiminput">
          <input name="group_options[enable_postfix]" id="enable_postfix" type="checkbox"  class="pm_toggle" value="1" <?php if(!empty($group_options) && isset($group_options['enable_postfix']) && $group_options['enable_postfix']==1){ echo "checked";}?> style="display:none;"  onClick="pm_show_hide(this,'postfixhtml')" />
          <label for="enable_postfix"></label>
        </div>
          <div class="uimnote"><?php _e('Define a common suffix for all display names for this group.','profilegrid-user-display-name');?></div>
      </div>
      <div class="childfieldsrow" id="postfixhtml" style=" <?php if(!empty($group_options) && isset($group_options['enable_postfix']) && $group_options['enable_postfix']=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
        <div class="uimrow">
          <div class="uimfield">
            <?php _e( 'Suffix','profilegrid-user-display-name' ); ?>
          </div>
          <div class="uiminput">
            <input name="group_options[set_postfix]" id="set_postfix" type="text"  value="<?php if(!empty($group_options)&& isset($group_options['set_postfix']))echo esc_attr($group_options['set_postfix']); ?>"  />
            <div class="errortext"></div>
            <div class="user_name_error"></div>
          </div>
            <div class="uimnote"><?php _e('Define suffix text.','profilegrid-user-display-name');?></div>
        </div> 
      </div>  
     
     
     </div>