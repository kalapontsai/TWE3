<?php
/* -----------------------------------------------------------------------------------------
   $Id: gift_cart.php,v 1.1 2005/04/17 21:13:26 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(shopping_cart.php,v 1.32 2003/02/11); www.oscommerce.com
   (c) 2003     nextcommerce (shopping_cart.php,v 1.21 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:


   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
$gift_smarty=new Smarty;
$gift_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
      global $db;


if (ACTIVATE_GIFT_SYSTEM=='true') {
        $gift_smarty->assign('ACTIVATE_GIFT','true');
}

  if (isset($_SESSION['customer_id'])) {
                $gv_query = "select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $_SESSION['customer_id'] . "'";
                $gv_result = $db->Execute($gv_query);
                if ($gv_result->fields['amount'] > 0 ) {
                    $gift_smarty->assign('GV_AMOUNT', $currencies->format($gv_result->fields['amount']));
                    $gift_smarty->assign('GV_SEND_TO_FRIEND_LINK', '<a href="'. twe_href_link(FILENAME_GV_SEND,'','SSL') . '">');
                } else {
                $gift_smarty->assign('GV_AMOUNT',0);
                }
              }
	
               if (isset($_SESSION['gv_id'])) {
                $gv_query = "select coupon_amount, coupon_type from " . TABLE_COUPONS . " where coupon_id = '" . $_SESSION['gv_id'] . "'";
                $coupon = $db->Execute($gv_query);
		      $coupon_amount2 = $coupon->fields['coupon_amount'];
			  if($coupon->fields['coupon_type'] =='P'){
                $gift_smarty->assign('COUPON_AMOUNT2', twe_format_price($coupon_amount2, $price_special = 0, $calculate_currencies = true).'%');
              }else{
                $gift_smarty->assign('COUPON_AMOUNT2', twe_format_price($coupon_amount2, $price_special = 1, $calculate_currencies = true));
			  }
			 }
              if (isset($_SESSION['cc_id'])) {
                $gift_smarty->assign('COUPON_HELP_LINK', '<a href="javascript:popupWindow(\'' . twe_href_link(FILENAME_POPUP_COUPON_HELP, 'cID=' . $_SESSION['cc_id'],'SSL') . '\')">');
              }
  if (isset($_SESSION['customer_id'])) {
  $gift_smarty->assign('C_FLAG','true');
  }
  $gift_smarty->assign('LINK_ACCOUNT',twe_href_link(FILENAME_CREATE_ACCOUNT,'','SSL'));
  $gift_smarty->assign('FORM_ACTION',twe_href_link(FILENAME_SHOPPING_CART, 'action=check_gift','SSL'));
  $gift_smarty->assign('INPUT_CODE',twe_draw_input_field('gv_redeem_code'));
  $gift_smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_redeem.gif', IMAGE_REDEEM_GIFT));
  $gift_smarty->assign('language', $_SESSION['language']);
  $gift_smarty->caching = 0;

  $smarty->assign('MODULE_gift_cart',$gift_smarty->fetch(CURRENT_TEMPLATE.'/module/gift_cart.html'));
?>