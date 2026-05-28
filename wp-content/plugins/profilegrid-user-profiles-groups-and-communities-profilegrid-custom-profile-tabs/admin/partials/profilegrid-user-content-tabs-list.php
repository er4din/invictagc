<?php
$dbhandler = new PM_DBhandler;
$path =  plugin_dir_url(__FILE__); 
$identifier = 'CUSTOMTABS';
$pagenum = filter_input(INPUT_GET, 'pagenum');
$pagenum = isset($pagenum) ? absint($pagenum) : 1;
$limit = 20; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$i = 1 + $offset;
$totallist = $dbhandler->pm_count($identifier);
$lists =  $dbhandler->get_all_result($identifier,'*',1,'results',$offset,$limit,'id');
$num_of_pages = ceil( $totallist/$limit);
$pagination = $dbhandler->pm_get_pagination($num_of_pages,$pagenum);
if(filter_input(INPUT_GET,'delete'))
{
	$selected = filter_input(INPUT_GET, 'selected', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
	foreach($selected as $lid)
	{
		$dbhandler->remove_row($identifier,'id',$lid,'%d');
	}
	wp_redirect('admin.php?page=pm_custom_profile_tabs');exit;
}

?>

<div class="pmagic"> 
  
  <!-----Operationsbar Starts----->
  <form name="custom_tabs_manager" id="custom_tabs_manager" action="" method="get">
    <input type="hidden" name="page" value="pm_custom_profile_tabs" />
    <input type="hidden" name="pagenum" value="<?php echo $pagenum;?>" />
    <div class="operationsbar">
      <div class="pmtitle">
        <?php _e('Custom Tabs List','profilegrid-custom-profile-tabs');?>
      </div>
      <div class="nav">
        <ul>
          <li><a href="admin.php?page=pm_add_custom_tab">
              <i class="fa fa-plus" aria-hidden="true"></i>
            <?php _e('New Tab','profilegrid-custom-profile-tabs');?>
            </a></li>
          <li><a>
            <input type="submit" name="delete" value="<?php _e('Delete','profilegrid-custom-profile-tabs');?>" />
            </a></li>
        </ul>
      </div>
    </div>
    <!--------Operationsbar Ends-----> 
    
    <!-------Contentarea Starts-----> 
    
    <!----Table Wrapper---->
    <?php if(isset($lists) && !empty($lists)):?>
    <div class="pmagic-table"> 
      
      <!----Sidebar---->
      
      <table class="pg-email-list">
        <tr>
          <th>&nbsp;</th>
            <th>&nbsp;</th>
          <th><?php _e('SR','profilegrid-custom-profile-tabs');?></th>
          <th><?php _e('Name','profilegrid-custom-profile-tabs');?></th>
          <th><?php _e('Action','profilegrid-custom-profile-tabs');?></th>
        </tr>
        <?php
	 	
			foreach($lists as $list)
			{
				?>
        <tr>
          <td><input type="checkbox" name="selected[]" value="<?php echo $list->id; ?>" /></td>
          <td><i class="fa fa-pencil-square-o" aria-hidden="true"></i></td>
          <td><?php echo $i;?></td>
          <td><?php echo $list->tab_label;?></td>
          <td><a href="admin.php?page=pm_add_custom_tab&id=<?php echo $list->id;?>">
<!--              <i class="fa fa-eye" aria-hidden="true"></i>-->
            <?php _e('Edit','profilegrid-custom-profile-tabs');?>
            </a></td>
        </tr>
        <?php $i++; }?>
      </table>
    </div>
    
    <?php echo $pagination;?>
    <?php else:?>
	<div class="pg-uim-notice"><?php _e("You haven't created any custom user data tabs yet. Create one now by clicking 'New Tab' above.","profilegrid-custom-profile-tabs");?></div>
	<?php endif;?>
  </form>
</div>
