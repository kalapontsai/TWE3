<?php
/* -----------------------------------------------------------------------------------------
   $Id: shopping_cart.php,v 1.15 2005/04/25 13:58:08 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(shopping_cart.php,v 1.71 2003/02/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (shopping_cart.php,v 1.24 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contributions:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
  $cart_empty=false;
    require("includes/application_top.php");
			
  // create smarty elements
  $smarty = new Smarty;
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_array_to_string.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_image_submit.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_form.inc.php');
  require_once(DIR_FS_INC . 'twe_recalculate_price.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_separator.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_form.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_input_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_checkbox_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_selection_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_check_stock.inc.php');
  require_once(DIR_FS_INC . 'twe_check_stock_attributes.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_stock.inc.php');
  require_once(DIR_FS_INC . 'twe_remove_non_numeric.inc.php');
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
  function updateCartProducts($qtys, $ids){
         foreach($qtys as $pID => $qty){
              $_SESSION['cart']->update_quantity($pID, $qty, $ids[$pID]);
          }
          
          $json = '';
          if (isset($_GET['rType']) && $_GET['rType'] == 'ajax'){
              $json .= '{
                  "success": "true",
				  "products": "' . $_SESSION['cart']->count_contents() . '"
              }';
          }else{
              twe_redirect(twe_href_link(FILENAME_SHOPPING_CART,'','SSL'));
          }
        return $json;
      }
      
      function removeProductFromCart($productID){
          $_SESSION['cart']->remove($productID);
          
          $json = '';
          if (isset($_GET['rType']) && $_GET['rType'] == 'ajax'){
              $json .= '{
                  "success": "true",
                  "products": "' . $_SESSION['cart']->count_contents() . '"
              }';
          }else{
              twe_redirect(twe_href_link(FILENAME_SHOPPING_CART,'','SSL'));
          }
        return $json;
      }
  if (!function_exists('twe_draw_hidden_field')) {
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
   }
 
  
 
 $action = (isset($_POST['action']) ? $_POST['action'] : '');
  if (isset($_POST['updateQuantities_x'])){
      $action = 'updateQuantities';
  }
  if (twe_not_null($action)){
      switch($action){
		  
          case 'removeProduct':
              echo removeProductFromCart($_POST['pID']);
          break;
          case 'updateQuantities':
              echo updateCartProducts($_POST['qty'], $_POST['id']);
          break;
          case 'updateCartView':
		  if ($_SESSION['cart']->count_contents() > 0){              
            ob_start();
			include(DIR_WS_INCLUDES . 'buynow/cart.php');
			$listcart = ob_get_contents();
			ob_end_clean();
			echo $listcart;
	  }
          break;
          case 'getCartTotals':
    $total_content='';
   if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == '1' && $_SESSION['customers_status']['customers_status_ot_discount'] != '0.00') {
      $discount = twe_recalculate_price($_SESSION['cart']->show_total(), $_SESSION['customers_status']['customers_status_ot_discount']);
    $total_content= TEXT_CART_OT_DISCOUNT.$_SESSION['customers_status']['customers_status_ot_discount'] . ' % ' . SUB_TITLE_OT_DISCOUNT . ' -' . twe_format_price($discount, $price_special=1, $calculate_currencies=false) .'<br>';
    }

    if ($_SESSION['customers_status']['customers_status_show_price'] == '1') {
      $total_content.= SUB_TITLE_SUB_TOTAL . twe_format_price($_SESSION['cart']->show_total(), $price_special=1, $calculate_currencies=false) . '<br>';
    } else {
     $total_content.= TEXT_INFO_SHOW_PRICE_NO . '<br>';
    }
    // display only if there is an ot_discount
    if ($customer_status_value->fields['customers_status_ot_discount'] != 0) {
      $total_content.= TEXT_CART_OT_DISCOUNT . $customer_status_value->fields['customers_status_ot_discount'] . '%';
    }
		echo '<div style="float:right"><table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td>' . $total_content . '</td></tr></table></div>';
	 break;
          
      }
      twe_session_close();
      exit;
  }

  $breadcrumb->add(NAVBAR_TITLE_SHOPPING_CART, twe_href_link(FILENAME_SHOPPING_CART,'','SSL'));

 require(DIR_WS_INCLUDES . 'header.php');
 include(DIR_WS_MODULES . 'gift_cart.php'); 
  if ($_SESSION['cart']->count_contents() > 0) {
  $smarty->assign('FORM_ACTION',twe_draw_form('cart', twe_href_link(FILENAME_SHOPPING_CART, '', $request_type)) . twe_draw_hidden_field('action', 'process'));
  $hidden_options='';
  $_SESSION['any_out_of_stock']=0;

           $checkout_cart = '';
			ob_start();
			include(DIR_WS_INCLUDES . 'buynow/cart.php');
			$checkout_cart = ob_get_contents();
			ob_end_clean();
 // if (isset($_GET['rType']) && $_GET['rType'] == 'ajax'){
    if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == '1' && $_SESSION['customers_status']['customers_status_ot_discount'] != '0.00') {
      $discount = twe_recalculate_price($_SESSION['cart']->show_total(), $_SESSION['customers_status']['customers_status_ot_discount']);
    $total_content= TEXT_CART_OT_DISCOUNT.$_SESSION['customers_status']['customers_status_ot_discount'] . ' % ' . SUB_TITLE_OT_DISCOUNT . ' -' . twe_format_price($discount, $price_special=1, $calculate_currencies=false) .'<br>';
    }

    if ($_SESSION['customers_status']['customers_status_show_price'] == '1') {
      $total_content.= SUB_TITLE_SUB_TOTAL . twe_format_price($_SESSION['cart']->show_total(), $price_special=1, $calculate_currencies=false) . '<br>';
    } else {
     $total_content.= TEXT_INFO_SHOW_PRICE_NO . '<br>';
    }
    // display only if there is an ot_discount
    if ($customer_status_value->fields['customers_status_ot_discount'] != 0) {
      $total_content.= TEXT_CART_OT_DISCOUNT . $customer_status_value->fields['customers_status_ot_discount'] . '%';
    }
 // }
		$smarty->assign('checkout_cart',$checkout_cart);
		
		$smarty->assign('cartTotal','<div style="float:right" class="cartTotals"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>' . $total_content . '</td></tr></table></div>');

		$smarty->assign('button_update_cart','<div style="float:left"><span name="updateQuantities" id="updateCartButton"></span></div>');


if ($_GET['info_message']) $smarty->assign('info_message',$_GET['info_message']);

$smarty->assign('BUTTON_CHECKOUT','<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="33%"><a href="'.twe_href_link(FILENAME_DEFAULT,'','SSL').'">'.twe_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING).'</a></td>
                <td width="33%" align="center"><div id="ajaxMessages" style="display:none;"></div><div id="cartButtonContainer">'. TITLE_CONTINUE_CHECKOUT . TEXT_CONTINUE_CHECKOUT.'</div></td>
                <td width="33%" align="right"><a href="'.twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL').'">'.twe_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT).'</a></td>
              </tr>
            </table>');
  } else {
  
  // empty cart
  $cart_empty=true;
  $smarty->assign('cart_empty',$cart_empty);
  $smarty->assign('BUTTON_CONTINUE', '<a href="'.twe_href_link(FILENAME_DEFAULT,'','SSL').'">'.twe_image_button('button_continue_shopping.gif', IMAGE_BUTTON_NEXT).'</a>');

}
if ($_GET['info_message']) $smarty->assign('info_message',$_GET['info_message']);

  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/shopping_cart.html');
  $smarty->assign('main_content',$main_content);  
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>