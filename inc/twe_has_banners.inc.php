<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_has_banners.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

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
  function twe_has_banners() {
 global $db; 
   $has_banners = array();
    $banners_query = "select banners_id from " . TABLE_BANNERS ." where status = '1'";
    $banners = $db->Execute($banners_query);
    if ($banners->RecordCount() > 0) {
  while (!$banners->EOF) {    
      $has_banners[] = array('BID' => $banners->fields['banners_id']);
	$banners->MoveNext();  
    } 
	}
	return $has_banners;
  }
 ?>