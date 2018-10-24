<?php
/* --------------------------------------------------------------
   $Id: gv_admin.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

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
  <h3><a href="#"><?php echo BOX_HEADING_GV_ADMIN; ?></a></h3>
  <div>
<?php
 }else{
	$style = '&nbsp;&nbsp;';
 }
  if (($admin_access->fields['coupon_admin'] == '1') && (COUPON_ADMIN == 'true')) echo '<a href="' . twe_href_link(FILENAME_COUPON_ADMIN, 'selected_box=gv_queue', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_COUPON_ADMIN . '</a>'.$style;
  if (($admin_access->fields['gv_queue'] == '1') && (GV_QUEUE == 'true')) echo '<a href="' . twe_href_link(FILENAME_GV_QUEUE, 'selected_box=gv_queue', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_GV_ADMIN_QUEUE . '</a>'.$style;
  if (($admin_access->fields['gv_mail'] == '1') && (GV_MAIL == 'true')) echo '<a href="' . twe_href_link(FILENAME_GV_MAIL, 'selected_box=gv_queue', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_GV_ADMIN_MAIL . '</a>'.$style;
  if (($admin_access->fields['gv_sent'] == '1') && (GV_SENT == 'true')) echo '<a href="' . twe_href_link(FILENAME_GV_SENT, 'selected_box=gv_queue', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_GV_ADMIN_SENT . '</a>'.$style;
if(MENU_TYPE == 'accordion'){
?>
</div>
<?php
}
?>