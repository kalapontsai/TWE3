<?php
/* --------------------------------------------------------------
   $Id: modules.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

    TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project 
   (c) 2002-2003 osCommerce coding standards (a typical file) www.oscommerce.com
   (c) 2003     nextcommerce (start.php,1.5 2004/03/17); www.nextcommerce.org
   (c) 2003-2004 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/
if(MENU_TYPE == 'accordion'){
$style = '<br>';
?>
  <h3><a href="#"><?php echo BOX_HEADING_MODULES; ?></a></h3>
  <div>
<?php
 }else{
	$style = '&nbsp;&nbsp;';
 }
  if (($admin_access->fields['modules'] == '1') && (PAYMENT == 'true')) echo  '<a href="' . twe_href_link(FILENAME_MODULES, 'set=payment&selected_box=modules', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_PAYMENT . '</a>'.$style;
  if (($admin_access->fields['modules'] == '1') && (SHIPPING == 'true')) echo  '<a href="' . twe_href_link(FILENAME_MODULES, 'set=shipping&selected_box=modules', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_SHIPPING . '</a>'.$style;
  if (($admin_access->fields['modules'] == '1') && (ORDER_TOTAL == 'true')) echo  '<a href="' . twe_href_link(FILENAME_MODULES, 'set=ordertotal&selected_box=modules', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_ORDER_TOTAL . '</a>'.$style;
  if (($admin_access->fields['module_export'] == '1') && (MODULE_EXPORT == 'true')) echo  '<a href="' . twe_href_link(FILENAME_MODULE_EXPORT,'selected_box=modules') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_MODULE_EXPORT . '</a>'.$style;
if(MENU_TYPE == 'accordion'){
?>
</div>
<?php
}
?>