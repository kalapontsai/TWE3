<?php
/* --------------------------------------------------------------
   $Id: localization.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

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
  <h3><a href="#"><?php echo BOX_HEADING_LOCATION_AND_TAXES; ?></a></h3>
  <div>
<?php
 }else{
	$style = '&nbsp;&nbsp;';
 }
  if (($admin_access->fields['languages'] == '1') && (LANGUAGES == 'true')) echo  '<a href="' . twe_href_link(FILENAME_LANGUAGES, 'selected_box=localization', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_LANGUAGES . '</a>'.$style;
  if (($admin_access->fields['countries'] == '1') && (COUNTRIES == 'true')) echo  '<a href="' . twe_href_link(FILENAME_COUNTRIES, 'selected_box=localization', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_COUNTRIES . '</a>'.$style;
  if (($admin_access->fields['currencies'] == '1') && (CURRENCIES == 'true')) echo  '<a href="' . twe_href_link(FILENAME_CURRENCIES, 'selected_box=localization', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CURRENCIES. '</a>'.$style;
  if (($admin_access->fields['zones'] == '1') && (ZONES == 'true')) echo  '<a href="' . twe_href_link(FILENAME_ZONES, 'selected_box=localization', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_ZONES . '</a>'.$style;
  if (($admin_access->fields['geo_zones'] == '1') && (GEO_ZONES == 'true')) echo  '<a href="' . twe_href_link(FILENAME_GEO_ZONES, 'selected_box=localization', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_GEO_ZONES . '</a>'.$style;
  if (($admin_access->fields['tax_classes'] == '1') && (TAX_CLASSES == 'true')) echo  '<a href="' . twe_href_link(FILENAME_TAX_CLASSES, 'selected_box=localization', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_TAX_CLASSES . '</a>'.$style;
  if (($admin_access->fields['tax_rates'] == '1') && (TAX_RATES == 'true')) echo  '<a href="' . twe_href_link(FILENAME_TAX_RATES, 'selected_box=localization', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_TAX_RATES . '</a>'.$style;
if(MENU_TYPE == 'accordion'){
?>
</div>
<?php
}
?>