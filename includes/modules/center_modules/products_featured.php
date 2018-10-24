<?php
/* -----------------------------------------------------------------------------------------
   $Id: products_new.php,v 1.13 2005/04/23 20:39:46 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(products_new.php,v 1.25 2003/05/27); www.oscommerce.com 
   (c) 2003	 nextcommerce (products_new.php,v 1.16 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

               // create smarty elements
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
if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html', $cache_id) || !$cache) {
	$rebuild = true;  // include needed function
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_products_data_array.inc.php');
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  $group_check='';
     if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  } 
$products_new_query_raw = "SELECT * FROM
			                                         " . TABLE_PRODUCTS . " p,
			                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE
			                                         p.products_id=pd.products_id 
			                                         " . $group_check . "
			                                         " . $fsk_lock . "
													                     and p.products_quantity > 0 and p.products_featured = '1'
			                                         and p.products_status = '1' and pd.language_id = '" . (int) $_SESSION['languages_id'] . "'
			                                         order by p.products_ordered DESC";
  $products_new = $db->Execute($products_new_query_raw,MAX_DISPLAY_INDEX_FEATURED,SQL_CACHE,CACHE_LIFETIME);

$module_content='';
$module_content = array();
  if ($products_new->fields > 0) {
    while (!$products_new->EOF) {
              $module_content[] = twe_products_data_array($products_new->fields);
$products_new->MoveNext();
    }
   $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('module_content',$module_content);
  }
  }
  // set cache ID
  if (!$cache || $rebuild) {
	if (twe_not_null($module_content)) {
			//if ($rebuild)  $module_smarty->clear_cache(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html', $cache_id);
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html',$cache_id);
  $default_smarty->assign('products_featured',$module);
	}
} else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html',$cache_id);
  $default_smarty->assign('products_featured',$module);
}
?>