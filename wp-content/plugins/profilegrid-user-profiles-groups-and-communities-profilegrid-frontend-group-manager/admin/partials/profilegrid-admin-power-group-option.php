<?php
$dbhandler = new PM_DBhandler;
$email_template =  $dbhandler->get_all_result('EMAIL_TMPL', array('id','tmpl_name'));
?>

<div class="uimrow" id="notification">
       <div class="uimfield">
         <?php _e( 'On Admin Assignment','' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[enable_on_admin_assignment]" id="enable_on_admin_assignment" type="checkbox"  class="pm_toggle" value="1" style="display:none;"  onClick="pm_show_hide(this,'on_admin_assignment_html')" <?php if(!empty($group_options) && isset($group_options['enable_on_admin_assignment']) && $group_options['enable_on_admin_assignment']==1){ echo "checked";}?>/>
         <label for="enable_on_admin_assignment"></label>
       </div>
         <div class="uimnote"><?php _e('Sends an email to the user who has been assigned as admin of this group.','');?></div>
</div>

     
<div class="childfieldsrow" id="on_admin_assignment_html" style=" <?php if(!empty($group_options) && isset($group_options['enable_on_admin_assignment']) && $group_options['enable_on_admin_assignment']=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
     <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'On Admin Assignment','' ); ?>
        </div>
        <div class="uiminput <?php if($id==0 || (!empty($group_options) && isset($group_options['enable_notification']) && $group_options['enable_notification']==1)){echo 'pm_select_required';}?>">
          <select name="group_options[on_admin_assignment]" id="on_admin_assignment">
            <option value=""><?php _e('Select Email Template','');?></option>
            <?php
			  foreach($email_template as $tmpl)
			  {?>
            <option value="<?php echo $tmpl->id;?>" <?php if(!empty($group_options) && isset($group_options['on_admin_assignment']) && $group_options['on_admin_assignment']==$tmpl->id) { echo 'selected'; } elseif($tmpl->tmpl_name === 'Admin Assignment'){echo 'selected';}?>><?php echo $tmpl->tmpl_name; ?></option>
            <?php }
			  ?>
          </select>
          <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Sends an email to the user who has been assigned as admin of this group.','');?></div>
      </div>
</div>

<div class="uimrow" id="notification">
       <div class="uimfield">
         <?php _e( 'On Admin Removal','' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[enable_on_admin_removal]" id="enable_on_admin_removal" type="checkbox"  class="pm_toggle" value="1" style="display:none;"  onClick="pm_show_hide(this,'enable_on_admin_removal_html')" <?php if(!empty($group_options) && isset($group_options['enable_on_admin_removal']) && $group_options['enable_on_admin_removal']==1){ echo "checked";}?>/>
         <label for="enable_on_admin_removal"></label>
       </div>
         <div class="uimnote"><?php _e('Sends an email to the user who has been removed as admin of this group.','');?></div>
</div>  

<div class="childfieldsrow" id="enable_on_admin_removal_html" style=" <?php if(!empty($group_options) && isset($group_options['enable_on_admin_removal']) && $group_options['enable_on_admin_removal']=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
<div class="uimrow">
        <div class="uimfield">
          <?php _e( 'On Admin Removal','' ); ?>
        </div>
        <div class="uiminput <?php if($id==0 || (!empty($group_options) && isset($group_options['enable_notification']) && $group_options['enable_notification']==1)){echo 'pm_select_required';}?>">
          <select name="group_options[on_admin_removal]" id="on_admin_removal">
            <option value=""><?php _e('Select Email Template','');?></option>
            <?php
			  foreach($email_template as $tmpl)
			  {?>
            <option value="<?php echo $tmpl->id;?>" <?php if(!empty($group_options) && isset($group_options['on_admin_removal']) && $group_options['on_admin_removal']==$tmpl->id){ echo 'selected'; } elseif($tmpl->tmpl_name === 'Admin Removal'){echo 'selected';}?>><?php echo $tmpl->tmpl_name; ?></option>
            <?php }
			  ?>
          </select>
          <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Sends an email to the user who has been removed as admin of this group.','');?></div>
      </div>
</div>

<div class="uimrow" id="notification">
       <div class="uimfield">
         <?php _e( 'On Admin Resetting Password','' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[enable_on_admin_reset_password]" id="enable_on_admin_reset_password" type="checkbox"  class="pm_toggle" value="1" style="display:none;"  onClick="pm_show_hide(this,'enable_on_admin_reset_password_html')" <?php if(!empty($group_options) && isset($group_options['enable_on_admin_reset_password']) && $group_options['enable_on_admin_reset_password']==1){ echo "checked";}?>/>
         <label for="enable_on_admin_reset_password"></label>
       </div>
         <div class="uimnote"><?php _e('Sends an email to the user whose password has been changed by admin of this group.','');?></div>
</div>  

<div class="childfieldsrow" id="enable_on_admin_reset_password_html" style=" <?php if(!empty($group_options) && isset($group_options['enable_on_admin_reset_password']) && $group_options['enable_on_admin_reset_password']=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
<div class="uimrow">
        <div class="uimfield">
          <?php _e( 'On Admin Resetting Password','' ); ?>
        </div>
        <div class="uiminput <?php if($id==0 || (!empty($group_options) && isset($group_options['enable_notification']) && $group_options['enable_notification']==1)){echo 'pm_select_required';}?>">
          <select name="group_options[on_admin_reset_password]" id="on_admin_reset_password">
            <option value=""><?php _e('Select Email Template','');?></option>
            <?php
			  foreach($email_template as $tmpl)
			  {?>
                          <option value="<?php echo $tmpl->id;?>" <?php if(!empty($group_options) && isset($group_options['on_admin_reset_password']) && $group_options['on_admin_reset_password']==$tmpl->id) { echo 'selected';} elseif($tmpl->tmpl_name === 'Password Reset by Admin'){echo 'selected';}?>><?php echo $tmpl->tmpl_name; ?></option>
            <?php }
			  ?>
          </select>
          <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Sends an email to the user whose password has been changed by admin of this group.','');?></div>
      </div>
</div>  