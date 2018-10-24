<?php
/* -----------------------------------------------------------------------------------------
   2015/4/8 skip to disable product_status when stock < 1 , ELHOMEO.COM
   ------------------------------------------------------------------------
   $Id: checkout_process.php,v 1.10 2004/03/25 08:36:06 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_process.php,v 1.128 2003/05/28); www.oscommerce.com
   (c) 2003	 nextcommerce (checkout_process.php,v 1.30 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
    ----------------------------------------------------------------------------------------
   Third Party contribution:

   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
  
  // include needed functions
  require_once(DIR_FS_INC . 'twe_calculate_tax.inc.php');
  require_once(DIR_FS_INC . 'twe_address_label.inc.php');
  require_once(DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');
  require_once(DIR_FS_INC . 'changedatain.inc.php');
  
  // initialize smarty
  $smarty = new Smarty;
  $today = date('Y-m-d H:i:s');
  // if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    //$_SESSION['navigation']->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if ($_SESSION['customers_status']['customers_status_show_price'] !='1'){
    twe_redirect(twe_href_link(FILENAME_DEFAULT, '', ''));
  }

  if (!isset($_SESSION['sendto'])) {
    twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  if ( (twe_not_null(MODULE_PAYMENT_INSTALLED)) && (!isset($_SESSION['payment'])) ) {
    twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
 }

  // avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($_SESSION['cart']->cartID) && isset($_SESSION['cartID'])) {
    if ($_SESSION['cart']->cartID != $_SESSION['cartID']) {
      twe_redirect(twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

  // load selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  if (isset($_SESSION['credit_covers'])) $_SESSION['payment']=''; //ICW added for CREDIT CLASS
  $payment_modules = new payment($_SESSION['payment']);

  // load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($_SESSION['shipping']);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  // load the before_process function from the payment modules
  $payment_modules->before_process();

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

  $order_totals = $order_total_modules->process();

    // BMC CC Mod Start
  if ( strtolower(CC_ENC) == 'true' ) {
    $key = changeme;
    $plain_data = $order->info['cc_number'];
    $order->info['cc_number'] = changedatain($plain_data,$key);
  }
  // BMC CC Mod End

  if ($_SESSION['customers_status']['customers_status_ot_discount_flag']==1) {
  $discount=$_SESSION['customers_status']['customers_status_ot_discount'];
  } else {
  $discount='0.00';
  }
  
  $today = date('Y-m-d H:i:s');
  $t1 = date("Y");
  $t2 = date("md");
  $serial = 1;
  
  if ((int)(substr($today,11,2)) < 12){
    $serial = 1;
    }else{
    $serial = rand(2,3);
    }
  $ordernum =(int)($t1.$t2)*100 + $serial;
  $check_date = $db->Execute("select orders_id, date_purchased from " . TABLE_ORDERS . " order by orders_id desc limit 0,1");
  while (!$check_date->EOF) {
	if (date("Y-m-d") == substr($check_date->fields['date_purchased'],0,10)){
  $ordernum = (int)($check_date->fields['orders_id']) + 1;
  } //end if
  $check_date->MoveNext();
  }
  
  if ($_SESSION['credit_covers']!='1') {
  $sql_data_array = array('orders_id' => $ordernum,
  						            'customers_id' => $_SESSION['customer_id'],
                          'customers_name' => $order->customer['firstname'],
                          'customers_cid' => $order->customer['csID'],
                          'customers_company' => $order->customer['company'],
                          'customers_status' => $_SESSION['customers_status']['customers_status_id'],
                          'customers_status_name' => $_SESSION['customers_status']['customers_status_name'],
                          'customers_status_image' => $_SESSION['customers_status']['customers_status_image'],
                          'customers_status_discount' => $discount,
                          'customers_street_address' => $order->customer['street_address'],
                          'customers_suburb' => $order->customer['suburb'],
                          'customers_city' => $order->customer['city'],
                          'customers_postcode' => $order->customer['postcode'],
                          'customers_state' => $order->customer['state'],
                          'customers_country' => $order->customer['country']['title'],
                          'customers_telephone' => $order->customer['telephone'],
                          'customers_fax' => $order->customer['fax'],
                          'customers_email_address' => $order->customer['email_address'],
                          'customers_address_format_id' => $order->customer['format_id'],
                          'delivery_name' => $order->delivery['firstname'],
                          'delivery_company' => $order->delivery['company'],
                          'delivery_street_address' => $order->delivery['street_address'],
                          'delivery_suburb' => $order->delivery['suburb'],
                          'delivery_city' => $order->delivery['city'],
					            	  'delivery_telephone' => $order->delivery['telephone'],
			            			  'delivery_fax' => $order->delivery['fax'],
                          'delivery_postcode' => $order->delivery['postcode'],
                          'delivery_state' => $order->delivery['state'],
                          'delivery_country' => $order->delivery['country']['title'],
                          'delivery_address_format_id' => $order->delivery['format_id'],
			            			  'delivery_use_exp' => $order->delivery['use_exp'],
		            				  'delivery_exp_type' => $order->delivery['exp_type'],
                          'delivery_exp_title' => $order->delivery['exp_title'],
                          'delivery_exp_number' => $order->delivery['exp_number'],
                          'billing_name' => $order->billing['firstname'],
                          'billing_company' => $order->billing['company'],
                          'billing_street_address' => $order->billing['street_address'],
                          'billing_suburb' => $order->billing['suburb'],
                          'billing_city' => $order->billing['city'],
	            					  'billing_telephone' => $order->billing['telephone'],
		            				  'billing_fax' => $order->billing['fax'],
                          'billing_postcode' => $order->billing['postcode'],
                          'billing_state' => $order->billing['state'],
                          'billing_country' => $order->billing['country']['title'],
                          'billing_address_format_id' => $order->billing['format_id'],
                          'payment_method' => $order->info['payment_method'],
                          'payment_class' => $order->info['payment_class'],
                          'shipping_method' => $order->info['shipping_method'],
                          'shipping_class' => $order->info['shipping_class'],
                          'cc_type' => $order->info['cc_type'],
                          'cc_owner' => $order->info['cc_owner'],
                          'cc_number' => $order->info['cc_number'],
                          'cc_expires' => $order->info['cc_expires'],
 // BMC CC Mod Start
                          'cc_start' => $order->info['cc_start'],
                          'cc_cvv' => $order->info['cc_cvv'],
                          'cc_issue' => $order->info['cc_issue'],
// BMC CC Mod End
                          'date_purchased' => $today,
                          'orders_status' => $order->info['order_status'],
                          'currency' => $order->info['currency'],
                          'currency_value' => $order->info['currency_value'],
                          'customers_ip' =>  $_SERVER['REMOTE_ADDR'],
                          'language'=>$_SESSION['language'],
                          'comments' => $order->info['comments']);
   } else {
   // free gift , no paymentaddress
     $sql_data_array = array('orders_id' => $ordernum,
	 				            		'customers_id' => $_SESSION['customer_id'],
                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                          'customers_cid' => $order->customer['csID'],
                          'customers_company' => $order->customer['company'],
                          'customers_status' => $_SESSION['customers_status']['customers_status_id'],
                          'customers_status_name' => $_SESSION['customers_status']['customers_status_name'],
                          'customers_status_image' => $_SESSION['customers_status']['customers_status_image'],
                          'customers_status_discount' => $discount,
                          'customers_street_address' => $order->customer['street_address'],
                          'customers_suburb' => $order->customer['suburb'],
                          'customers_city' => $order->customer['city'],
                          'customers_postcode' => $order->customer['postcode'],
                          'customers_state' => $order->customer['state'],
                          'customers_country' => $order->customer['country']['title'],
                          'customers_telephone' => $order->customer['telephone'],
                          'customers_fax' => $order->customer['fax'],
                          'customers_email_address' => $order->customer['email_address'],
                          'customers_address_format_id' => $order->customer['format_id'],
                          'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
                          'delivery_company' => $order->delivery['company'],
                          'delivery_street_address' => $order->delivery['street_address'],
                          'delivery_suburb' => $order->delivery['suburb'],
                          'delivery_city' => $order->delivery['city'],
			            			  'delivery_telephone' => $order->delivery['telephone'],
					            	  'delivery_fax' => $order->delivery['fax'],
                          'delivery_postcode' => $order->delivery['postcode'],
                          'delivery_state' => $order->delivery['state'],
                          'delivery_country' => $order->delivery['country']['title'],
                          'delivery_address_format_id' => $order->delivery['format_id'],
	            					  'delivery_use_exp' => $order->delivery['use_exp'],
				            		  'delivery_exp_type' => $order->delivery['exp_type'],
                          'delivery_exp_title' => $order->delivery['exp_title'],
                          'delivery_exp_number' => $order->delivery['exp_number'],
                          'payment_method' => $order->info['payment_method'],
                          'payment_class' => $order->info['payment_class'],
                          'shipping_method' => $order->info['shipping_method'],
                          'shipping_class' => $order->info['shipping_class'],
                          'cc_type' => $order->info['cc_type'],
                          'cc_owner' => $order->info['cc_owner'],
                          'cc_number' => $order->info['cc_number'],
                          'cc_expires' => $order->info['cc_expires'],
                          'date_purchased' => $today,
                          'orders_status' => $order->info['order_status'],
                          'currency' => $order->info['currency'],
                          'currency_value' => $order->info['currency_value'],
                          'customers_ip' =>  $_SERVER['REMOTE_ADDR'],
                          'language'=>$_SESSION['language'],
                          'comments' => $order->info['comments']);
   }


  twe_db_perform(TABLE_ORDERS, $sql_data_array);
   $insert_id = $ordernum;  
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'],
                            'class' => $order_totals[$i]['code'],
                            'sort_order' => $order_totals[$i]['sort_order']);
    twe_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id,
                          'orders_status_id' => $order->info['order_status'],
                          'date_added' => $today,
                          'customer_notified' => $customer_notification,
                          'comments' => $order->info['comments']);
  twe_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

  // initialized for the email confirmation
  $products_ordered = '';
  $products_ordered_html = '';
  $subtotal = 0;
  $total_tax = 0;

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    // Stock Update - Joao Correia
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
        $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . twe_get_prid($order->products[$i]['id']) . "'";
        // Will work with only one option for downloadable products
        // otherwise, we have to build the query dynamically with a loop
        $products_attributes = $order->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
        }
        $stock_query = $stock_query_raw;
      } else {
        $stock_query = "select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . twe_get_prid($order->products[$i]['id']) . "'";
      }
	  $stock_values = $db->Execute($stock_query);
      if ($stock_values->RecordCount() > 0) {
        // do not decrement quantities if products_attributes_filename exists
        if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values->fields['products_attributes_filename'])) {
          $stock_left = $stock_values->fields['products_quantity'] - $order->products[$i]['qty'];
        } else {
          $stock_left = $stock_values->fields['products_quantity'];
        }
        $db->Execute("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . twe_get_prid($order->products[$i]['id']) . "'");
/*  取消下架動作 20150408 Elhomeo.com
        if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
        $db->Execute("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . twe_get_prid($order->products[$i]['id']) . "'");
        }
        */
      }
    }

    // Update products_ordered (for bestsellers list)
    $db->Execute("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . twe_get_prid($order->products[$i]['id']) . "'");

    $sql_data_array = array('orders_id' => $insert_id,
                            'products_id' => twe_get_prid($order->products[$i]['id']),
                            'products_model' => $order->products[$i]['model'],
                            'products_name' => $order->products[$i]['name'],
                            'products_price' => $order->products[$i]['price'],
                            'final_price' => $order->products[$i]['final_price'],
			                'products_tax' => $order->products[$i]['tax'],
                            'products_discount_made' => $order->products[$i]['discount_allowed'],
                            'products_quantity' => $order->products[$i]['qty'],
			                'allow_tax' => $_SESSION['customers_status']['customers_status_show_price_tax']);
			    
    twe_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = $db->Insert_ID();

    $order_total_modules->update_credit_account($i);// GV Code ICW ADDED FOR CREDIT CLASS SYSTEM
    //------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if (isset($order->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes_query = "select popt.products_options_name,
                               poval.products_options_values_name,
                               pa.options_values_price,
                               pa.price_prefix,
                               pad.products_attributes_maxdays,
                               pad.products_attributes_maxcount,
                               pad.products_attributes_filename
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "'
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                and pa.options_id = popt.products_options_id
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                and pa.options_values_id = poval.products_options_values_id
                                and popt.language_id = '" . $_SESSION['languages_id'] . "'
                                and poval.language_id = '" . $_SESSION['languages_id'] . "'";
          $attributes_values = $db->Execute($attributes_query);
        } else {
          $attributes_query = "select popt.products_options_name,
                                             poval.products_options_values_name,
                                             pa.options_values_price,
                                             pa.price_prefix
                                             from " . TABLE_PRODUCTS_OPTIONS . " popt, " .
                                             TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " .
                                             TABLE_PRODUCTS_ATTRIBUTES . " pa
                                             where pa.products_id = '" . $order->products[$i]['id'] . "'
                                             and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                             and pa.options_id = popt.products_options_id
                                             and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                             and pa.options_values_id = poval.products_options_values_id
                                             and popt.language_id = '" . $_SESSION['languages_id'] . "'
                                             and poval.language_id = '" . $_SESSION['languages_id'] . "'";
        $attributes_values = $db->Execute($attributes_query);
		}
        // update attribute stock
        $db->Execute("UPDATE ".TABLE_PRODUCTS_ATTRIBUTES." set
                               attributes_stock=attributes_stock - '".$order->products[$i]['qty']."'
                               where
                               products_id='".$order->products[$i]['id']."'
                               and options_values_id='".$order->products[$i]['attributes'][$j]['value_id']."'
                               and options_id='".$order->products[$i]['attributes'][$j]['option_id']."'
                               ");

        $sql_data_array = array('orders_id' => $insert_id,
                                'orders_products_id' => $order_products_id,
                                'products_options' => $attributes_values->fields['products_options_name'],
                                'products_options_values' => $attributes_values->fields['products_options_values_name'],
                                'options_values_price' => $attributes_values->fields['options_values_price'],
                                'price_prefix' => $attributes_values->fields['price_prefix']);
        twe_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

        if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values->fields['products_attributes_filename']) && twe_not_null($attributes_values->fields['products_attributes_filename'])) {
          $sql_data_array = array('orders_id' => $insert_id,
                                  'orders_products_id' => $order_products_id,
                                  'orders_products_filename' => $attributes_values->fields['products_attributes_filename'],
                                  'download_maxdays' => $attributes_values->fields['products_attributes_maxdays'],
                                  'download_count' => $attributes_values->fields['products_attributes_maxcount']);
          twe_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
        }
      }
    }
    //------insert customer choosen option eof ----
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += twe_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;

  }

// NEW EMAIL configuration !
  $order_totals = $order_total_modules->apply_credit();
  include('send_order.php');            


  // load the after_process function from the payment modules
  $payment_modules->after_process();

  $_SESSION['cart']->reset(true);

  // unregister session variables used during checkout
  unset($_SESSION['sendto']);
  unset($_SESSION['billto']);
  unset($_SESSION['shipping']);
  unset($_SESSION['payment']);
  unset($_SESSION['comments']);
  unset($_SESSION['last_order']);
  $last_order = $insert_id;
  //GV Code Start
  if(isset($_SESSION['credit_covers'])) unset($_SESSION['credit_covers']);
  $order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM
  // GV Code End
  
  if (!isset($mail_error)) {
      twe_redirect(twe_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
  }
  else {
      echo $mail_error;
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>