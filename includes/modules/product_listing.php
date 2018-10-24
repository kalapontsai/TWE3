<?php
/* -----------------------------------------------------------------------------------------
   $Id: product_listing.php,v 1.23 2004/04/26 10:31:17 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_listing.php,v 1.42 2003/05/27); www.oscommerce.com 
   (c) 2003	 nextcommerce (product_listing.php,v 1.19 2003/08/1); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
$module_smarty= new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
global $shipping_status_image,$manufacturer_dropdown;
$result=true;
$rebuild = false;

 if (USE_CACHE=='false') {
  $cache=false;
  $module_smarty->caching = 0;
  } else {
  $module_smarty->caching = 1;
  $module_smarty->cache_lifetime=CACHE_LIFETIME;
  $module_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_GET['page'].$_GET['cPath'].$_SESSION['customers_status']['customers_status_name'].$_SESSION['currency'].$_GET['manufacturers_id'].$_GET['filter_id'].$_GET['keywords'];  
  }
 if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/product_listing/'.$category->fields['listing_template'], $cache_id) || !$cache) {
	$rebuild = true; 
  
  // include needed functions
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_get_all_get_params.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_products_data_array.inc.php');
  require_once(DIR_FS_INC . 'twe_sorting.inc.php');

  $listing_split = new splitPageResults($listing_sql, $_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
  $module_content=array();
  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing = $db->Execute($listing_split->sql_query,'',SQL_CACHE,CACHE_LIFETIME);
    while (!$listing->EOF) {
      $rows++;
               $module_content[] = twe_products_data_array($listing->fields, 'listing');

	      $listing->MoveNext();				
    }
  }

  if  ($listing_split->number_of_rows > 0) {

$navigation='
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText">'.$listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS).'</td>
    <td class="smallText" align="right">'.TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, twe_get_all_get_params(array('page', 'info', 'x', 'y'))).'</td>
  </tr>
</table>';
      if (GROUP_CHECK=='true') {
   $group_check="and c.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
    $category_query = "select
                                    cd.categories_description,
                                    cd.categories_name,
                                    c.listing_template,
                                    c.categories_image from " .
                                    TABLE_CATEGORIES . " c, " .
                                    TABLE_CATEGORIES_DESCRIPTION . " cd
                                    where c.categories_id = '" . (int)$current_category_id . "'
                                    and cd.categories_id = '" . (int)$current_category_id . "'
                                    ".$group_check."
                                    and cd.language_id = '" . (int)$_SESSION['languages_id'] . "'";

    $category = $db->Execute($category_query,'',SQL_CACHE,CACHE_LIFETIME);
    $image='';
    if ($category->fields['categories_image']!='') $image=DIR_WS_IMAGES.'categories/'.$category->fields['categories_image'];
    $module_smarty->assign('CATEGORIES_NAME',$category->fields['categories_name']);
    $module_smarty->assign('CATEGORIES_IMAGE',$image);
    $module_smarty->assign('CATEGORIES_DESCRIPTION',$category->fields['categories_description']);

} else {

// no product found
$result=false;

}
      // get default template
   if ($category->fields['listing_template']=='' or $category->fields['listing_template']=='default') {
          $files=array();
          if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_listing/')){
          while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_listing/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
        }
  $category->fields['listing_template']=$files[0]['id'];
  }

}
  if ($result!=false) {
  $module_smarty->assign('SORTING',twe_sorting('products_price','products_name','products_date_added'));  
  $module_smarty->assign('MANUFACTURER_DROPDOWN',$manufacturer_dropdown);
  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('module_content',$module_content);
  $module_smarty->assign('NAVIGATION',$navigation);
  // set cache ID
  if (!$cache || $rebuild) {
	if (twe_not_null($module_content)) {
if ($rebuild)  $module_smarty->clear_cache(CURRENT_TEMPLATE.'/module/product_listing/'.$category->fields['listing_template'],$cache_id);
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/product_listing/'.$category->fields['listing_template'],$cache_id);
	}
} else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/product_listing/'.$category->fields['listing_template'],$cache_id);
} 


   $smarty->assign('main_content',$module);
  } else {

  $error=TEXT_PRODUCT_NOT_FOUND;
  include(DIR_WS_MODULES . FILENAME_ERROR_HANDLER);
  }
?>