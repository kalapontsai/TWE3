<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_customer_greeting.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_customer_greeting.inc.php,v 1.3 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Return a customer greeting
  function twe_customer_greeting() {

    if (isset($_SESSION['customer_first_name']) && isset($_SESSION['customer_id'])) {
      $greeting_string = sprintf(TEXT_GREETING_PERSONAL, $_SESSION['customer_first_name'], twe_href_link(FILENAME_PRODUCTS_NEW));
    } else {
      $greeting_string = sprintf(TEXT_GREETING_GUEST, twe_href_link(FILENAME_LOGIN, '', 'SSL'), twe_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    }

    return $greeting_string;
  }
 ?>