<?php
/* --------------------------------------------------------------
   $Id: configuration.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

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
  <h3><a href="#"><?php echo BOX_HEADING_CONFIGURATION; ?></a></h3>
  <div>
<?php
 }else{
	$style = '&nbsp;&nbsp;';
 }
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID1 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=1&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_1 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID2 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=2&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_2 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID3 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=3&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_3 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID4 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=4&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_4 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID5 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=5&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_5 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID7 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=7&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_7 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID8 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=8&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_8 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID9 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=9&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_9 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID10 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=10&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_10 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID11 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=11&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_11 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID12 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=12&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_12 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID13 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=13&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_13 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID14 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=14&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_14 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID15 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=15&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_15 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID16 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=16&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_16 . '</a>'.$style;
  if (($admin_access->fields['configuration'] == '1') && (CONFIGURATIONGID17 == 'true')) echo '<a href="' . twe_href_link(FILENAME_CONFIGURATION, 'gID=17&selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONFIGURATION_17 . '</a>'.$style;  
  if (($admin_access->fields['orders_status'] == '1') && (ORDERS_STATUS == 'true')) echo '<a href="' . twe_href_link(FILENAME_ORDERS_STATUS, 'selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_ORDERS_STATUS . '</a>'.$style;
    if (ACTIVATE_SHIPPING_STATUS=='true') {
  if (($admin_access->fields['shipping_status'] == '1')) echo '<a href="' . twe_href_link(FILENAME_SHIPPING_STATUS, 'selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_SHIPPING_STATUS . '</a>'.$style;
  }
if (($admin_access->fields['layout_controller'] == '1') && (LAYOUT_CONTROLLER == 'true')) echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'selected_box=configuration', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_LAYOUT_CONTROLLER . '</a>'.$style;
if(MENU_TYPE == 'accordion'){
?>
</div>
<?php
}
?>