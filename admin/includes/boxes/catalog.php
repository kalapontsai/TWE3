<?php
/* --------------------------------------------------------------
   $Id: catalog.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

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
  <h3><a href="#"><?php echo BOX_HEADING_CATALOG; ?></a></h3>
  <div>
<?php
 }else{
	$style = '&nbsp;&nbsp;';
 }
   if (($admin_access->fields['categories'] == '1') && (CATEGORIES == 'true')) echo '<a href="' . twe_href_link(FILENAME_CATEGORIES, 'selected_box=catolog', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CATEGORIES . '</a>'.$style;
   if (($admin_access->fields['new_attributes'] == '1') && (NEW_ATTRIBUTES == 'true')) echo '<a href="' . twe_href_link(FILENAME_NEW_ATTRIBUTES, 'selected_box=catolog', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').''.BOX_ATTRIBUTES_MANAGER.'</a>'.$style;
   if (($admin_access->fields['products_attributes'] == '1') && (PRODUCTS_ATTRIBUTES == 'true')) echo '<a href="' . twe_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'selected_box=catolog', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_PRODUCTS_ATTRIBUTES . '</a>'.$style;
   if (($admin_access->fields['manufacturers'] == '1') && (MANUFACTURERS == 'true')) echo '<a href="' . twe_href_link(FILENAME_MANUFACTURERS, 'selected_box=catolog', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_MANUFACTURERS . '</a>'.$style;
   if (($admin_access->fields['reviews'] == '1') && (REVIEWS == 'true')) echo '<a href="' . twe_href_link(FILENAME_REVIEWS, 'selected_box=catolog', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_REVIEWS . '</a>'.$style;
   if (($admin_access->fields['specials'] == '1') && (SPECIALS == 'true')) echo '<a href="' . twe_href_link(FILENAME_SPECIALS, 'selected_box=catolog', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_SPECIALS . '</a>'.$style;
   if (($admin_access->fields['products_expected'] == '1') && (PRODUCTS_EXPECTED == 'true')) echo '<a href="' . twe_href_link(FILENAME_PRODUCTS_EXPECTED, 'selected_box=catolog', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_PRODUCTS_EXPECTED . '</a>'.$style;
   if (($admin_access->fields['news_categories'] == '1') && (NEWS_CATEGORIES == 'true')) echo '<a href="' . twe_href_link(FILENAME_NEWS_CATEGORIES, 'selected_box=catolog', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_NEWS_CATEGORIES . '</a>'.$style;
if(MENU_TYPE == 'accordion'){
?>
</div>
<?php
}
?>