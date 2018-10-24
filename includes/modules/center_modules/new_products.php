<?php
/*
  $Id: new_products.php,v 1.1.1.1 2004/08/14 08:01:09 oldpa Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
   (c) 2003	 xt-commerce  www.xt-commerce.com

  Released under the GNU General Public License
*/

$module_smarty = new smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$module_content='';
$rebuild = false;
$module_smarty->assign('language', $_SESSION['language']);

          if (USE_CACHE=='false') {
	 	$cache=false;
		$module_smarty->caching = 0;
		$cache_id = null;
	} else {
		$cache=true;
		$module_smarty->caching = 1;
		$module_smarty->cache_lifetime = CACHE_LIFETIME;
		$module_smarty->cache_modified_check = CACHE_CHECK;
		$cache_id = $_SESSION['language'].$_SESSION['customers_status']['customers_status_name'].$_SESSION['currency'];
	}
if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/center_modules/new_products_default.html', $cache_id) || !$cache) {
	$rebuild = true;
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
    require_once(DIR_FS_INC . 'twe_products_data_array.inc.php');

 $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock= ' and p.products_fsk18!=1';
  }
  $group_check= '';
  if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
   $new_products_query = "SELECT * FROM
			                                         " . TABLE_PRODUCTS . " p,
			                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE
			                                         p.products_id=pd.products_id 
			                                         " . $group_check . "
			                                         " . $fsk_lock . "
			                                         and p.products_quantity > 0 and p.products_status = '1' and pd.language_id = '" . (int) $_SESSION['languages_id'] . "'
			                                         order by p.products_date_added DESC";
  $new_products = $db->Execute($new_products_query,MAX_DISPLAY_INDEX_NEW_PRODUCTS,SQL_CACHE,CACHE_LIFETIME);
  if ($new_products->fields > 0) {

  $row = 0;
  $module_content = array();
  while (!$new_products->EOF) {   
    $module_content[] = twe_products_data_array($new_products->fields);
       $new_products->MoveNext();
  }
  $module_smarty->assign('module_content',$module_content);
  }
}  
 
 if (!$cache || $rebuild) {
	if (twe_not_null($module_content)) {
			//if ($rebuild)  $module_smarty->clear_cache(CURRENT_TEMPLATE.'/module/center_modules/new_products_default.html', $cache_id);
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/new_products_default.html',$cache_id);
  $default_smarty->assign('new_products',$module);
	}
} else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/center_news.html',$cache_id);
  $default_smarty->assign('new_products',$module);
} 
?>