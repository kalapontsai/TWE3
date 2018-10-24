<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_customers_statuses.inc.php,v 1.2 2005/04/07 19:57:46 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on Third Party contribution:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_customers_statuses.inc.php,v 1.4 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Return all customers statuses for a specified language_id and return an array(array())
// Use it to make pull_down_menu, checkbox....
  function twe_get_customers_statuses() {
global $db;
     $customers_statuses_array = array(array());
     if ($_SESSION['languages_id']=='') {
     $customers_statuses_query = "select * from " . TABLE_CUSTOMERS_STATUS . " where language_id = '1' order by customers_status_id";
     } else {
     $customers_statuses_query = "select * from " . TABLE_CUSTOMERS_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "' order by customers_status_id";
     }
    $customers_statuses = $db->Execute(
    $customers_statuses_query);
     $i=1;
     while (!$customers_statuses->EOF) {
       $i=$customers_statuses->fields['customers_status_id'];
       $customers_statuses_array[] = array('id' => $customers_statuses->fields['customers_status_id'],
                                           'text' => $customers_statuses->fields['customers_status_name'],
                                           'csa_public' => $customers_statuses->fields['customers_status_public'],
                                           'csa_show_price' => $customers_statuses->fields['customers_status_show_price'],
                                           'csa_show_price_tax' => $customers_statuses->fields['customers_status_show_price_tax'],
                                           'csa_image' => $customers_statuses->fields['customers_status_image'],
                                           'csa_discount' => $customers_statuses->fields['customers_status_discount'],
                                           'csa_ot_discount_flag' => $customers_statuses->fields['customers_status_ot_discount_flag'],
                                           'csa_ot_discount' => $customers_statuses->fields['customers_status_ot_discount'],
                                           'csa_graduated_prices' => $customers_statuses->fields['customers_status_graduated_prices'],
                                           'csa_cod_permission' => $customers_statuses->fields['customers_status_cod_permission'],
                                           'csa_cc_permission' => $customers_statuses->fields['customers_status_cc_permission'],
                                           'csa_bt_permission' => $customers_statuses->fields['customers_status_bt_permission'],
                                           );
        $customers_statuses->MoveNext();
     }
    return $customers_statuses_array;
  }
 ?>