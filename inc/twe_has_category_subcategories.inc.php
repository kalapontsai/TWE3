<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_has_category_subcategories.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_has_category_subcategories.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_has_category_subcategories($category_id) {
  global $db;
    $child_category_query = "select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . $category_id . "'";
    $child_category = $db->Execute($child_category_query,'',SQL_CACHE,CACHE_LIFETIME);
    if ($child_category->fields['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }  
 ?>