<?php
/*
  $Id: new_products.php,v 1.1.1.1 2004/08/14 08:01:09 oldpa Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
   (c) 2003	 xt-commerce  www.xt-commerce.com

  Released under the GNU General Public License
*/
?>
<?php 
$module_smarty= new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
$module_smarty->assign('language', $_SESSION['language']);
$rebuild = false;
global $new_products_category_id;

if (USE_CACHE=='false') {
  $cache=false;
  $module_smarty->caching = 0;
  } else {
  $module_smarty->caching = 1;
  $module_smarty->cache_lifetime=CACHE_LIFETIME;
  $module_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$new_products_category_id.$_SESSION['customers_status']['customers_status_name'].$_SESSION['currency'];
  }
 if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/new_products.html', $cache_id) || !$cache) {
	$rebuild = true; 


  require_once(DIR_FS_INC . 'twe_get_products_name.inc.php');
  require_once(DIR_FS_INC . 'twe_get_short_description.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_products_data_array.inc.php');
  require_once(DIR_FS_INC . 'twe_array_merge.inc.php');

  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock= ' and p.products_fsk18!=1';
  }
  $group_check= '';
if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
   $new_products_query = "select distinct p.products_fsk18, p.products_quantity, p.products_id, p.products_image, p.products_discount_allowed, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where c.categories_status='1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id  ".$fsk_lock." ".$group_check." and p.products_status = '1' order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS;
   $new_products = $db->Execute($new_products_query,'',SQL_CACHE,CACHE_LIFETIME);
  } else {
   if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
    $new_products_query = "select distinct p.products_fsk18, p.products_quantity, p.products_id, p.products_image, p.products_discount_allowed, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where c.categories_status='1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ".$group_check." and c.parent_id = '" . $new_products_category_id . "' and p.products_status = '1' ".$fsk_lock." order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS;
    $new_products = $db->Execute($new_products_query,'',SQL_CACHE,CACHE_LIFETIME);
  }
  $module_content = array();
  $file_merge = array();
  while (!$new_products->EOF) {
    $new_products->fields['products_name'] = twe_get_products_name($new_products->fields['products_id']);
	$new_products->fields['products_short_description'] = twe_get_short_description($new_products->fields['products_id']);
	$file_merge[] = array(array('id' => $new_products->fields['products_short_description'],
                                 'text' => $new_products->fields['products_short_description']), array('id' => $new_products->fields['products_name'], 'text' => $new_products->fields['products_name']));
	$module_content[] = twe_products_data_array(twe_array_merge($new_products->fields,$file_merge));
    $new_products->MoveNext();
  }
} 
  $module_smarty->assign('module_content',$module_content);
 
   if (!$cache || $rebuild) {
if ($rebuild)  $module_smarty->clear_cache(CURRENT_TEMPLATE.'/module/new_products.html',$cache_id);
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/new_products.html',$cache_id);
} else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/new_products.html',$cache_id);
} 
  $default_smarty->assign('MODULE_new_products',$module);
?>