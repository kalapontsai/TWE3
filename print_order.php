<?php
/* -----------------------------------------------------------------------------------------
   $Id: print_order.php,v 1.1 2011/03/04 ELHOMEO.com
   v1.1 add customer title instead of address label   
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_shipping_address.php,v 1.14 2003/05/27); www.oscommerce.com
   (c) 2003	 nextcommerce (checkout_shipping_address.php,v 1.14 2003/08/17); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com
   (c) 2003	 twe3.01
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
  
  // include needed functions
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_get_order_data.inc.php');
  require_once(DIR_FS_INC . 'twe_get_attributes_model.inc.php');
  require_once(DIR_FS_INC . 'twe_format_price_order.inc.php');


  $smarty = new Smarty;
  
  // check if custmer is allowed to see this order!
  $order_query_check = "SELECT
  					customers_id
  					FROM ".TABLE_ORDERS."
  					WHERE orders_id='".(int)$_GET['oID']."'";
  					
  $order_check = $db->Execute($order_query_check);
  if ($_SESSION['customer_id'] == $order_check->fields['customers_id'])
  	{
  	// get order data
 	
  	include(DIR_WS_CLASSES . 'order.php');
  	$order = new order($_GET['oID']);
//  	$smarty->assign('address_label_customer',twe_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'));
    $order_title = '會員帳號:' . $order->customer['email_address'];
    $smarty->assign('order_title',$order_title);
// 判斷是否使用便利系統
   if ($order->delivery['use_exp'] == '1') {
     require_once(DIR_FS_INC . 'twe_address_exp_format.inc.php');
     $smarty->assign('address_label_shipping',twe_address_exp_format($order->delivery, 1, ' ', '<br>'));
  } else {
  	$smarty->assign('address_label_shipping',twe_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'));
  }

//  	$smarty->assign('address_label_shipping',twe_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'));
//  關閉帳單地址欄位  	$smarty->assign('address_label_payment',twe_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'));
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
        				WHERE orders_id='".(int)$_GET['oID']."' order by products_model");
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
  					WHERE orders_id='".(int)$_GET['oID']."'
  					ORDER BY sort_order ASC");

  	$order_total=array();
  	while (!$oder_total_values->EOF) {
  	
  	$order_total[]=array(
  			'TITLE' => $oder_total_values->fields['title'],
            'CLASS'=> $oder_total_values->fields['class'],
            'VALUE'=> $oder_total_values->fields['value'],
  			'TEXT' => $oder_total_values->fields['text']);
    if ($oder_total_values->fields['class']='ot_total')
	 $total=$oder_total_values->fields['value'];
	 $oder_total_values->MoveNext(); 	 
  	}

  	// assign language to template for caching
  	$smarty->assign('language', $_SESSION['language']);	
	$smarty->assign('oID',(int)$_GET['oID']);
    if ($order->info['payment_method']!='' && $order->info['payment_method']!='no_payment') {
	include(DIR_WS_LANGUAGES.$_SESSION['language'].'/modules/payment/'.$order->info['payment_method'].'.php');
// 若是銀行匯款, 顯示匯款帳號,等資料 -- 2011/4/1
        if (($order->info['payment_method'] == 'bank')||($order->info['payment_method'] == 'atm')) {
            $payment_method = constant(strtoupper('MODULE_PAYMENT_'.$order->info['payment_method'].'_TEXT_DESCRIPTION2'));
           }else{
 	    $payment_method=constant(strtoupper('MODULE_PAYMENT_'.$order->info['payment_method'].'_TEXT_TITLE'));
          }
    }

  	$smarty->assign('PAYMENT_METHOD',$payment_method);
  	$smarty->assign('DATE',twe_date_long($order->info['date_purchased']));
  	$smarty->assign('order_data', $order_data);
  	$smarty->assign('order_total', $order_total);
    $path = DIR_WS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/';
    $smarty->assign('tpl_path', $path);

  	// dont allow cache
  	$smarty->caching = false;
	
  	$smarty->display(CURRENT_TEMPLATE . '/module/print_order.html');	
	} else {
  	
  	$smarty->assign('ERROR','You are not allowed to view this order!');
  	$smarty->display(CURRENT_TEMPLATE . '/module/error_message.html');
	}
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
