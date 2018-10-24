<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_has_tax.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_has_product_attributes.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

// Check if product has attributes
  function twe_has_tax() {
 global $db; 
   $has_tax = false;
    $product_query = "select count(*) as count from " . TABLE_PRODUCTS ." where products_tax_class_id != '0'";
    $product_tax = $db->Execute($product_query);
    if ($product_tax->fields['count'] > 0) {
    $has_tax = true;
    } 
	return $has_tax;
  }
 ?>