<?php
/* --------------------------------------------------------------
   $Id: column_left.php,v 1.13 2004/08/14  oldpa Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(column_left.php,v 1.15 2002/01/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (column_left.php,v 1.25 2003/08/19); www.nextcommerce.org
   (c) 2003	 xtcommerce (column_left.php,v 1.25 2004/04/14); www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  $admin_access_query = "select * from " . TABLE_ADMIN_ACCESS . " where customers_id = '" . $_SESSION['customer_id'] . "'";
  $admin_access = $db->Execute($admin_access_query);  
?>
 <script type="text/javascript">
$(document).ready(function(){
	$("#tabnav").tabs({ collapsible: true,cookie:true});
	
});
</script>
<div id="tabnav">
<ul>
<?php  if($_SESSION['customers_status']['customers_status_id'] == '0') {?>
<?php  if(CONFIGURATION_NOTE == 'true'){?> <li><a href="#<?php echo BOX_HEADING_CONFIGURATION ?>" class="<?php echo BOX_HEADING_CONFIGURATION?>"><?php echo BOX_HEADING_CONFIGURATION?></a></li><?php }?>
<?php  if(CATEGORIES_NOTE == 'true'){?><li><a href="#<?php echo BOX_HEADING_CATALOG?>" class="<?php echo BOX_HEADING_CATALOG?>"><?php echo BOX_HEADING_CATALOG?></a></li><?php }?>
<?php  if(MODULES_NOTE == 'true'){?><li><a href="#<?php echo BOX_HEADING_MODULES?>" class="<?php echo BOX_HEADING_MODULES?>"><?php echo BOX_HEADING_MODULES?></a></li><?php }?>
<?php  if(CUSTOMERS_NOTE == 'true'){?><li><a href="#<?php echo BOX_HEADING_CUSTOMERS?>" class="<?php echo BOX_HEADING_CUSTOMERS?>"><?php echo BOX_HEADING_CUSTOMERS?></a></li><?php }?>
<?php  if(LOCALIZATION_NOTE == 'true'){?><li><a href="#<?php echo BOX_HEADING_LOCATION_AND_TAXES?>" class="<?php echo BOX_HEADING_LOCATION_AND_TAXES?>"><?php echo BOX_HEADING_LOCATION_AND_TAXES?></a></li><?php }?>
<?php  if(REPORTS_NOTE == 'true'){?><li><a href="#<?php echo BOX_HEADING_REPORTS?>" class="<?php echo BOX_HEADING_REPORTS?>"><?php echo BOX_HEADING_REPORTS?></a></li><?php }?>
<?php  if(TOOLS_NOTE == 'true'){?><li><a href="#<?php echo BOX_HEADING_TOOLS?>" class="<?php echo BOX_HEADING_TOOLS?>"><?php echo BOX_HEADING_TOOLS?></a></li><?php }?>
<?php  if (ACTIVATE_GIFT_SYSTEM=='true'&& GV_ADMIN_NOTE == 'true'){?><li><a href="#<?php echo BOX_HEADING_GV_ADMIN?>" class="<?php echo BOX_HEADING_GV_ADMIN?>"><?php echo BOX_HEADING_GV_ADMIN?></a></li><?php }?>
<?php
  }
 ?>
 </ul>
   <div id="<?php echo BOX_HEADING_CONFIGURATION ?>"><?php require(DIR_WS_BOXES . 'configuration.php');?></div>
   <div id="<?php echo BOX_HEADING_CATALOG?>"><?php require(DIR_WS_BOXES . 'catalog.php');?></div>
   <div id="<?php echo BOX_HEADING_MODULES?>"><?php require(DIR_WS_BOXES . 'modules.php');?></div>
   <div id="<?php echo BOX_HEADING_CUSTOMERS?>"><?php require(DIR_WS_BOXES . 'customers.php');?></div>
   <div id="<?php echo BOX_HEADING_LOCATION_AND_TAXES?>"><?php require(DIR_WS_BOXES . 'localization.php');?></div>
   <div id="<?php echo BOX_HEADING_REPORTS?>"><?php require(DIR_WS_BOXES . 'reports.php');?></div>
   <div id="<?php echo BOX_HEADING_TOOLS?>"><?php require(DIR_WS_BOXES . 'tools.php');?></div>
<?php  if (ACTIVATE_GIFT_SYSTEM=='true'&& GV_ADMIN_NOTE == 'true'){?><div id="<?php echo BOX_HEADING_GV_ADMIN?>"><?php  require(DIR_WS_BOXES . 'gv_queue.php');?></div><?php }?>
 </div>
