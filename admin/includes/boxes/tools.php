<?php
/* --------------------------------------------------------------
   $Id: tools.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

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
  <h3><a href="#"><?php echo BOX_HEADING_TOOLS; ?></a></h3>
  <div>
<?php
 }else{
	$style = '&nbsp;&nbsp;';
 }
 if (($admin_access->fields['module_newsletter'] == '1') && (MODULE_NEWSLETTER == 'true')) echo '<a href="' . twe_href_link(FILENAME_MODULE_NEWSLETTER,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_MODULE_NEWSLETTER . '</a>'.$style;
  if (($admin_access->fields['content_manager'] == '1') && (CONTENT_MANAGER == 'true')) echo'<a href="' . twe_href_link(FILENAME_CONTENT_MANAGER,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONTENT . '</a>'.$style;
  if (($admin_access->fields['backup'] == '1') && (BACKUP == 'true')) echo '<a href="' . twe_href_link(FILENAME_BACKUP,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_BACKUP . '</a>'.$style;
  if (($admin_access->fields['banner_manager'] == '1') && (BANNER_MANAGER == 'true')) echo '<a href="' . twe_href_link(FILENAME_BANNER_MANAGER,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_BANNER_MANAGER . '</a>'.$style;
  if (($admin_access->fields['server_info'] == '1') && (SERVER_INFO == 'true')) echo '<a href="' . twe_href_link(FILENAME_SERVER_INFO,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_SERVER_INFO . '</a>'.$style;
  if (($admin_access->fields['whos_online'] == '1') && (WHOS_ONLINE == 'true')) echo '<a href="' . twe_href_link(FILENAME_WHOS_ONLINE,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_WHOS_ONLINE . '</a>'.$style;
 /* if (($admin_access->fields['mysql'] == '1') && (MYSQL == 'true')) echo '<a href="' . twe_href_link('mysql.php','selected_box=tool') . '" target="_blank" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . phpMyAdmin . '</a>'.$style;*/
if(MENU_TYPE == 'accordion'){
?>
</div>
<?php
}
?>