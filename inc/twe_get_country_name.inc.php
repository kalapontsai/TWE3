<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_country_name.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_country_name.inc.php,v 1.5 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  require_once(DIR_FS_INC . 'twe_get_countries.inc.php');
  function twe_get_country_name($country_id) {
    $country_array = twe_get_countries($country_id);

    return $country_array['countries_name'];
  }
 ?>
