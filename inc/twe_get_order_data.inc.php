<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_order_data.inc.php,v 1.2 2011/3/11 ELHOMEO.com

   v1.2 add express field
       ------------------------------
   $Id: twe_get_order_data.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (twe_get_order_data.inc.php,v 1.1 2003/08/15); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

function twe_get_order_data($order_id) {
 global $db;
$order_query = "SELECT
  customers_name,
  customers_company,
  customers_street_address,
  customers_suburb,
  customers_city,
  customers_postcode,
  customers_state,
  customers_country,
  customers_telephone,
  customers_email_address,
  customers_address_format_id,
  delivery_name,
  delivery_company,
  delivery_street_address,
  delivery_suburb,
  delivery_city,
  delivery_postcode,
  delivery_state,
  delivery_country,
  delivery_address_format_id,
 delivery_use_exp, 
 delivery_exp_type,
 delivery_exp_title,
 delivery_exp_number,
  billing_name,
  billing_company,
  billing_street_address,
  billing_suburb,
  billing_city,
  billing_postcode,
  billing_state,
  billing_country,
  billing_address_format_id,
  payment_method,
  comments,
  date_purchased,
  orders_status,
  currency,
  currency_value
  					FROM ".TABLE_ORDERS."
  					WHERE orders_id='".$_GET['oID']."'";
  					
  $order_data= $db->Execute(
    $order_query);
  // get order status name	
 $order_status_query="SELECT
 				orders_status_name
 				FROM ".TABLE_ORDERS_STATUS."
 				WHERE orders_status_id='".$order_data->fields['orders_status']."'
 				AND language_id='".$_SESSION['languages_id']."'";
 $order_status_data=$db->Execute(
    $order_status_query); 			
 $order_data->fields['orders_status']=$order_status_data->fields['orders_status_name'];
 // get language name for payment method
 include(DIR_WS_LANGUAGES.$_SESSION['language'].'/modules/payment/'.$order_data->fields['payment_method'].'.php');
 $order_data->fields['payment_method']=constant(strtoupper('MODULE_PAYMENT_'.$order_data->fields['payment_method'].'_TEXT_TITLE'));	
  return $order_data; 
}
?>
