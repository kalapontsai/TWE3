<?php

/* -----------------------------------------------------------------------------------------
   $Id: print_order.php,v 1.1 2011/07/19 ELHOMEO.com   
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_process.php,v 1.128 2003/05/28); www.oscommerce.com
   (c) 2003	 nextcommerce (checkout_process.php,v 1.30 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   (c) 2003	 twe3.01; www.oldpa.com.tw
   Released under the GNU General Public License 
   
   v1.0 Modify the express function.
   v1.1 20120403 Add Order comments in the end of report.
   ---------------------------------------------------------------------------------------*/



  require('includes/application_top.php');

  // include needed functions

  require_once(DIR_FS_INC .'twe_get_products_price.inc.php');

  require_once(DIR_FS_INC .'twe_get_order_data.inc.php');

  require_once(DIR_FS_INC .'twe_get_attributes_model.inc.php');

  require_once(DIR_FS_INC .'twe_not_null.inc.php');

  require_once(DIR_FS_INC .'twe_format_price_order.inc.php');



  $smarty = new Smarty;



/* Twe301 原始碼, 無作用

  $order_query_check = "SELECT

  					customers_id

  					FROM ".TABLE_ORDERS."

  					WHERE orders_id='".(int)$_GET['oID']."'";

  					

  $order_check = $db->Execute($order_query_check);

*/

 // if ($_SESSION['customer_id'] == $order_check['customers_id'])

  //	{

  	// get order data

 	

  	include(DIR_WS_CLASSES . 'order.php');

  	$order = new order($_GET['oID']);

  	$smarty->assign('address_label_customer',twe_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'));

// 判斷是否使用便利商店

    $exp_query = $db->Execute("select orders_id,

                                        delivery_name as name,

                                        delivery_fax as fax,

                                        delivery_use_exp as use_exp, 

                                        delivery_exp_type as exp_type,

                                        delivery_exp_title as exp_title,

                                        delivery_exp_number as exp_number

                                        from " . TABLE_ORDERS . " where orders_id = '" . $_GET['oID'] . "'");



    $name = $exp_query->fields['name'];

    $fax = $exp_query->fields['fax'];

    if ($exp_query->fields['use_exp']) {

     $cr = '<br>';



     $exp_type = twe_output_string_protected($exp_query->fields['exp_type']);

   	 $exp_title = TEXT_EXP_TITLE . ' : ' . twe_output_string_protected($exp_query->fields['exp_title']);

   	 $exp_number = TEXT_EXP_NUMBER . ' : ' . twe_output_string_protected($exp_query->fields['exp_number']);



     if ($exp_type == '0') $exp_type = TEXT_EXP_TYPE_0;

  	 if ($exp_type == '1') $exp_type = TEXT_EXP_TYPE_1;

     if ($exp_type == '2') $exp_type = TEXT_EXP_TYPE_2;



     $address_label_shipping = $name . $cr . $fax . $cr . $exp_type . $cr . $exp_title . $cr . $exp_number;

     $smarty->assign('address_label_shipping',$address_label_shipping);

     } else {

  	 echo $smarty->assign('address_label_shipping',twe_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'));

     }  

//  	$smarty->assign('address_label_payment',twe_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'));

  	$smarty->assign('csID',$order->customer['csID']);

  	// get products data

        $order_data_values=$db->Execute("SELECT

        				products_id,

        				orders_products_id,

        				products_model,

        				products_name,

        				final_price,

        				products_quantity

        				FROM ".TABLE_ORDERS_PRODUCTS."

        				WHERE orders_id='".$_GET['oID']."' order by products_model");

        $order_data=array();

        while (!$order_data_values->EOF) {

        	$attributes_data_values=$db->Execute("SELECT

        				products_options,

        				products_options_values,

        				price_prefix,

        				options_values_price

        				FROM ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES."

        				WHERE orders_products_id='".$order_data_values->fields['orders_products_id']."'");

        	$attributes_data='';

        	$attributes_model='';

        	while (!$attributes_data_values->EOF) {

        	$attributes_data .='<br>'.$attributes_data_values->fields['products_options'].':'.$attributes_data_values->fields['products_options_values'];	

        	$attributes_model .='<br>'.twe_get_attributes_model($order_data_values->fields['products_id'],$attributes_data_values->fields['products_options_values']);

        $attributes_data_values->MoveNext();

			}

        $order_data[]=array(

        		'PRODUCTS_MODEL' => $order_data_values->fields['products_model'],

        		'PRODUCTS_NAME' => $order_data_values->fields['products_name'],

        		'PRODUCTS_ATTRIBUTES' => $attributes_data,

        		'PRODUCTS_ATTRIBUTES_MODEL' => $attributes_model,

        		'PRODUCTS_PRICE' => twe_format_price_order($order_data_values->fields['final_price'],1,$order->info['currency']),

        		'PRODUCTS_QTY' => $order_data_values->fields['products_quantity']);

      $order_data_values->MoveNext();  

		}

  	// get order_total data

  $oder_total_values=$db->Execute("SELECT

                      title,

                      text,

                      class,

                      value,

                      sort_order

  					FROM ".TABLE_ORDERS_TOTAL."

  					WHERE orders_id='".$_GET['oID']."'

  					ORDER BY sort_order ASC");



  	$order_total=array();

  	while (!$oder_total_values->EOF) {



  	$order_total[]=array(

              'TITLE' => $oder_total_values->fields['title'],

              'CLASS'=> $oder_total_values->fields['class'],

              'VALUE'=> $oder_total_values->fields['value'],

              'TEXT' => $oder_total_values->fields['text']);

    if ($oder_total_values->fields['class']='ot_total') $total=$oder_total_values->fields['value'];

  $oder_total_values->MoveNext();

  	}

// v1.1 插入客戶意見 20120403

    $orders_history = $db->Execute("Select date_added, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . $_GET['oID'] . "' and comments <> '' order by date_added");

    if ($orders_history->RecordCount()>0) {
      while (!$orders_history->EOF) {
        $orders_history_comment[] = array(
                       'date_added' => $orders_history->fields['date_added'],
                       'comments' => $orders_history->fields['comments']);
     $orders_history->MoveNext(); 
      }
	  } else
	  {
        $orders_history_comment[] = array(
                       'date_added' => '',
                       'comments' => '');
    }

// v1.1 插入客戶意見 20120403

  	// assign language to template for caching

  	$smarty->assign('language', $_SESSION['language']);

    $smarty->assign('logo_path',HTTP_SERVER  . DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');

	$smarty->assign('oID',$_GET['oID']);

	if ($order->info['payment_method']!='' && $order->info['payment_method']!='no_payment') {

	include(DIR_FS_CATALOG.'lang/'.$_SESSION['language'].'/modules/payment/'.$order->info['payment_method'].'.php');

 	$payment_method=constant(strtoupper('MODULE_PAYMENT_'.$order->info['payment_method'].'_TEXT_TITLE'));

  	$smarty->assign('PAYMENT_METHOD',$payment_method);

    }

  	$smarty->assign('DATE',twe_date_long($order->info['date_purchased']));

  	$smarty->assign('order_data', $order_data);

  	$smarty->assign('order_total', $order_total);

// v1.1 插入客戶意見 20120403
    $smarty->assign('orders_history_comment', $orders_history_comment);

  	// dont allow cache

  	$smarty->caching = false;

  	

	$smarty->template_dir=DIR_FS_CATALOG.'templates';

	$smarty->compile_dir=DIR_FS_CATALOG.'templates_c';

	$smarty->config_dir=DIR_FS_CATALOG.'lang';

	

  	$smarty->display(CURRENT_TEMPLATE . '/admin/print_order.html');	

?>