<?php
/* -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_confirmation.php,v 1.137 2003/05/07); www.oscommerce.com 
   (c) 2003	 nextcommerce (checkout_confirmation.php,v 1.21 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   (c) 2003 TWE-Commerce
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
  // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_calculate_tax.inc.php');
  require_once(DIR_FS_INC . 'twe_check_stock.inc.php');
  require_once(DIR_FS_INC . 'twe_display_tax_value.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_attribute_price_checkout.inc.php');
  require_once(DIR_FS_INC . 'twe_address_exp_format.inc.php');
  if (!function_exists('twe_draw_hidden_field')) {
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
   }
  // if the customer is not logged on, redirect them to the login page

  if (!isset($_SESSION['customer_id'])) {
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    twe_redirect(twe_href_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($_SESSION['cart']->cartID) && isset($_SESSION['cartID'])) {
    if ($_SESSION['cart']->cartID != $_SESSION['cartID']) {
      twe_redirect(twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!isset($_SESSION['shipping'])) {
    twe_redirect(twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

//check if display conditions on checkout page is true

  if (isset($_POST['payment'])) $_SESSION['payment'] = $_POST['payment'];

  if ($_POST['comments_added'] != '') $_SESSION['comments'] = twe_db_prepare_input($_POST['comments']);

//-- TheMedia Begin check if display conditions on checkout page is true
if (isset($_POST['cot_gv'])) $_SESSION['cot_gv'] = true;
// if conditions are not accepted, redirect the customer to the payment method selection page
  if (DISPLAY_CONDITIONS_ON_CHECKOUT == 'true') {
    if ($_POST['conditions'] == false) {
      twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_CONDITIONS_NOT_ACCEPTED), 'SSL', true, false));
    }
  }

// load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  if (isset($_SESSION['credit_covers'])) $_SESSION['payment'] = 'no_payment'; // GV Code Start/End ICW added for CREDIT CLASS
  $payment_modules = new payment($_SESSION['payment']);

  // GV Code ICW ADDED FOR CREDIT CLASS SYSTEM
  require(DIR_WS_CLASSES . 'order_total.php');
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $payment_modules->update_status();

  // GV Code Start
  $order_total_modules = new order_total;
  $order_total_modules->collect_posts();
  $order_total_modules->pre_confirmation_check();
  // GV Code End

  // GV Code line changed
  if ((is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && (!is_object($$_SESSION['payment']))  && (!isset($_SESSION['credit_covers']))) || (is_object($$_SESSION['payment']) && ($$_SESSION['payment']->enabled == false)) ) {
    twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($_SESSION['shipping']);

// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (twe_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      twe_redirect(twe_href_link(FILENAME_SHOPPING_CART));
    }
  }


  $breadcrumb->add(NAVBAR_TITLE_1_CHECKOUT_CONFIRMATION, twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_CHECKOUT_CONFIRMATION);


 require(DIR_WS_INCLUDES . 'header.php'); 
 if (SHOW_IP_LOG=='true') {
 $smarty->assign('IP_LOG','true');
 $smarty->assign('CUSTOMERS_IP',$_SERVER['REMOTE_ADDR']);
 }

// 判斷是否使用EXPRESS 
if ($order->delivery['use_exp'] == '1') {
   $smarty->assign('DELIVERY_LABEL',twe_address_exp_format($order->delivery, 1, ' ', '<br>'));
  }
  else {
   $smarty->assign('DELIVERY_LABEL',twe_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'));
  }
// 判斷是否使用EXPRESS 


 //if ($_SESSION['credit_covers']!='1') {
// disable billing label at 20110210 by kadela
// $smarty->assign('BILLING_LABEL',twe_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'));
//}
 $smarty->assign('PRODUCTS_EDIT',twe_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
 $smarty->assign('SHIPPING_ADDRESS_EDIT',twe_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'));
 $smarty->assign('BILLING_ADDRESS_EDIT',twe_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'));


  if ($_SESSION['sendto'] != false) {

    if ($order->info['shipping_method']) {
    	$smarty->assign('SHIPPING_METHOD',$order->info['shipping_method']);
    	$smarty->assign('SHIPPING_EDIT',twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

    }

  }

  if (sizeof($order->info['tax_groups']) > 1) {

  if ($_SESSION['customers_status']['customers_status_show_price_tax']== 0 && $_SESSION['customers_status']['customers_status_add_tax_ot']==1) {                   


}

  } else {

  }
$data_products = '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {


    $data_products .= '<tr>' . "\n" .
                      '<td class="main" nowrap align="left" valign="top" width="">' . $order->products[$i]['qty'] .' x '.$order->products[$i]['name']. '</td>' . "\n" .
	                  '<td class="main" align="right" valign="top">' .twe_get_products_price($order->products[$i]['id'],$price_special=1,$quantity=$order->products[$i]['qty']). '</td></tr>' . "\n" ;


    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        $data_products .= '<tr>
		<td class="main" align="left" valign="top">
		<nobr><small>&nbsp;<i> - ' 
		. $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] .'
		</i></small></td>
		<td class="main" align="right" valign="top"><i><small>'
		.twe_get_products_attribute_price_checkout($order->products[$i]['attributes'][$j]['price'],$order->products[$i]['tax'],1,$order->products[$i]['qty'],$order->products[$i]['attributes'][$j]['prefix']). 
		'</i></small></nobr></td></tr>';
      }
    }

    $data_products .= '' . "\n";

    if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 && $_SESSION['customers_status']['customers_status_add_tax_ot'] == 1) {
      if (sizeof($order->info['tax_groups']) > 1) $data_products .= '<td class="main" valign="top" align="right">' .TAX_ALL_TAX.  twe_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
    }
	 $data_products .='</tr>' . "\n";
  }
  $data_products .= '</table>';
  	$smarty->assign('PRODUCTS_BLOCK',$data_products);

    if ($order->info['payment_method']!='no_payment' && $order->info['payment_method']!='') {
        include(DIR_WS_LANGUAGES . '/' . $_SESSION['language'] . '/modules/payment/' . $order->info['payment_method'] . '.php');
      	$smarty->assign('PAYMENT_METHOD',constant(MODULE_PAYMENT_ . strtoupper($order->info['payment_method']) . _TEXT_TITLE));
    }
    	$smarty->assign('PAYMENT_EDIT',twe_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

$total_block='<table>';
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    $total_block.= $order_total_modules->output();
  }
  $total_block.='</table>';
  $smarty->assign('TOTAL_BLOCK',$total_block);


  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
    	

    

$payment_info=$confirmation['title'];
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {

$payment_info .=
	      '<table>
		<tr>
                <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                <td class="main">'. $confirmation['fields'][$i]['title'].'</td>
                <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                <td class="main">'. stripslashes($confirmation['fields'][$i]['field']).'</td>
              </tr></table>';

      }
      $smarty->assign('PAYMENT_INFORMATION',$payment_info);

    }
  }

  if (twe_not_null($order->info['comments'])) {
  $smarty->assign('ORDER_COMMENTS',nl2br(htmlspecialchars($order->info['comments'])) . twe_draw_hidden_field('comments', $order->info['comments']));

  }

  if (isset($$_SESSION['payment']->form_action_url)) {
    $form_action_url = $$_SESSION['payment']->form_action_url;
  } else {
    $form_action_url = twe_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }
  $smarty->assign('CHECKOUT_FORM',twe_draw_form('checkout_confirmation', $form_action_url, 'post'));
  $payment_button='';
  if (is_array($payment_modules->modules)) {
    $payment_button.= $payment_modules->process_button();
  }
  $smarty->assign('MODULE_BUTTONS',$payment_button);
/* PostBack前讓按鈕失效 20130409
  $smarty->assign('CHECKOUT_BUTTON', twe_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '</form>' . "\n");
*/
  $smarty->assign('CHECKOUT_BUTTON', twe_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" /><input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" /></form>' . "\n");
  

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('PAYMENT_BLOCK',$payment_block);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/checkout_confirmation.html');

  $smarty->assign('main_content',$main_content);
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>