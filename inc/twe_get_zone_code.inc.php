<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_zone_code.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_zone_code.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_get_zone_code($country_id, $zone_id, $default_zone) {
      global $db;
    $zone_query = "select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_id = '" . $zone_id . "'";
      $zone = $db->Execute(
    $zone_query);
    if ($zone->RecordCount() > 0) { 
      return $zone->fields['zone_code'];
    } else {
      return $default_zone;
    }
  }
 ?>