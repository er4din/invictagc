<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Display Purchases Tab','profilegrid-mycred-integration' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[mycred_purchases_tab]" id="mycred_purchases_tab" type="checkbox"  class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'mycred_max_product_display_html')" <?php if(!empty($group_options) && isset($group_options['mycred_purchases_tab']) && $group_options['mycred_purchases_tab']==1){ echo "checked";}?> />
         <label for="mycred_purchases_tab"></label>
       </div>
         <div class="uimnote"><?php _e('Displays Purchases tab in user profile page with thumbnails and names of the products purchased by the user.','profilegrid-mycred-integration');?></div>
</div>

<div class="childfieldsrow" id="mycred_max_product_display_html" style=" <?php if(!empty($group_options) && isset($group_options['mycred_purchases_tab']) && $group_options['mycred_purchases_tab']==1){echo 'display:block;';} else { echo 'display:none;';} ?>">
  <div class="uimrow">
    <div class="uimfield"><?php _e('Max Number of Products Displayed','profilegrid-mycred-integration' ); ?></div>
    <div class="uiminput">
     
      <input type="number" name="group_options[mycred_max_product]" id="mycred_max_product" value="<?php if(!empty($group_options) && isset($group_options['mycred_max_product']) && $group_options['mycred_max_product']!=''){echo $group_options['mycred_max_product'];} else{echo '10';} ?>">
      <div class="errortext"></div>
    
    </div>
    <div class="uimnote"><?php _e('Define the maximum number of products visible in "Purchases" tab of the profile.','profilegrid-mycred-integration');?></div>
  </div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Show Product Reviews Tab','profilegrid-mycred-integration' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[mycred_reviews_tab]" id="mycred_reviews_tab" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php if(!empty($group_options) && isset($group_options['mycred_reviews_tab']) && $group_options['mycred_reviews_tab']==1){ echo "checked";}?>/>
         <label for="mycred_reviews_tab"></label>
       </div>
         <div class="uimnote"><?php _e('Displays Product Reviews tab in user profile page with reviews of the products that the user has posted.','profilegrid-mycred-integration');?></div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Show Orders in User Account','profilegrid-mycred-integration' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[mycred_orders_in_account]" id="mycred_orders_in_account" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php if(!empty($group_options) && isset($group_options['mycred_orders_in_account']) && $group_options['mycred_orders_in_account']==1){ echo "checked";}?>/>
         <label for="mycred_orders_in_account"></label>
       </div>
         <div class="uimnote"><?php _e("Displays order history and status inside the 'Settings' section of user. This is only accessible to the logged in user.",'profilegrid-mycred-integration');?></div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Show Shipping Address in User Account','profilegrid-mycred-integration' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[mycred_shipping_address_in_account]" id="mycred_shipping_address_in_account" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php if(!empty($group_options) && isset($group_options['mycred_shipping_address_in_account']) && $group_options['mycred_shipping_address_in_account']==1){ echo "checked";}?>/>
         <label for="mycred_shipping_address_in_account"></label>
       </div>
         <div class="uimnote"><?php _e("Displays and allows editing of shipping address inside the 'Settings' section of user profile. This is only accessible to the logged in user.",'profilegrid-mycred-integration');?></div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Show Billing Address in User Account','profilegrid-mycred-integration' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[mycred_billing_address_in_account]" id="mycred_billing_address_in_account" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php if(!empty($group_options) && isset($group_options['mycred_billing_address_in_account']) && $group_options['mycred_billing_address_in_account']==1){ echo "checked";}?>/>
         <label for="mycred_billing_address_in_account"></label>
       </div>
         <div class="uimnote"><?php _e("Displays and allows editing of billing address inside the 'Settings' section of user. This is only accessible to the logged in user.",'profilegrid-mycred-integration');?></div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Display Purchases Count and Total Spent','profilegrid-mycred-integration' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[mycred_show_total_spent]" id="mycred_show_total_spent" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php if(!empty($group_options) && isset($group_options['mycred_show_total_spent']) && $group_options['mycred_show_total_spent']==1){ echo "checked";}?> onClick="pm_show_hide(this,'mycred_show_total_spend_html')"/>
         <label for="mycred_show_total_spent"></label>
       </div>
         <div class="uimnote"><?php _e('Displays total count of products purchased and money spent by the user on their profile headers.','profilegrid-mycred-integration');?></div>
</div>

<div class="childfieldsrow" id="mycred_show_total_spend_html" style=" <?php if(!empty($group_options) && isset($group_options['mycred_show_total_spent']) && $group_options['mycred_show_total_spent']==1){echo 'display:block;';} else { echo 'display:none;';} ?>">
  <div class="uimrow">
    <div class="uimfield"><?php _e( 'Visibility','profilegrid-mycred-integration' ); ?></div>
    <div class="uiminput">
      <select name="group_options[mycred_show_total_spent_permission]" id="mycred_show_total_spent_permission">
                    <option value="1" <?php if(!empty($group_options) && isset($group_options['mycred_show_total_spent_permission']) && $group_options['mycred_show_total_spent_permission']=='1'){ echo "selected";}?>><?php _e('Everyone','profilegrid-mycred-integration');?></option>
                    <option value="2" <?php if(!empty($group_options) && isset($group_options['mycred_show_total_spent_permission']) && $group_options['mycred_show_total_spent_permission']=='2'){ echo "selected";}?>><?php _e('Group Leader Only','profilegrid-mycred-integration');?></option>
                    <option value="3" <?php if(!empty($group_options) && isset($group_options['mycred_show_total_spent_permission']) && $group_options['mycred_show_total_spent_permission']=='3'){ echo "selected";}?>><?php _e('Group Members Only','profilegrid-mycred-integration');?></option>
                    <option value="4" <?php if(!empty($group_options) && isset($group_options['mycred_show_total_spent_permission']) && $group_options['mycred_show_total_spent_permission']=='4'){ echo "selected";}?>><?php _e('Friends Only','profilegrid-mycred-integration');?></option>
                    <option value="5" <?php if(!empty($group_options) && isset($group_options['mycred_show_total_spent_permission']) && $group_options['mycred_show_total_spent_permission']=='5'){ echo "selected";}?>><?php _e('Private','profilegrid-mycred-integration');?></option>
                </select>
      <div class="errortext"></div>
    </div>
    <div class="uimnote"><?php _e('Define who will be able to see purchase count and total spent on user profiles.','profilegrid-mycred-integration');?></div>
  </div>
</div>