<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_count_products_in_category.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_count_products_in_category.inc.php,v 1.3 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_count_products_in_category($category_id, $include_inactive = false) {
  global $db;   
	$products_count = 0;
	$categories_count_query = "select count(*) as total,p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1'  group by p2c.categories_id";
	$categories_count = $db->Execute($categories_count_query,'',SQL_CACHE,CACHE_LIFETIME);
  while (!$categories_count->EOF)  {
            $c_count[$categories_count->fields['categories_id']]=$categories_count->fields['total'];
		$categories_count->MoveNext();	
         }
	
	if ($c_count){
      $products_count += $c_count[$category_id];
    }
	
    $child_categories_query = "select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . $category_id . "'";
    $child_categories = $db->Execute($child_categories_query);
      if ($child_categories->RecordCount()) {
      while (!$child_categories->EOF) {	    
            $products_count += twe_count_products_in_category($child_categories->fields['categories_id'], $include_inactive);
       $child_categories->MoveNext();  
	  }
    }
    return $products_count;
  }
 ?>