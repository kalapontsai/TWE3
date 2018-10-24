<?php
/* -----------------------------------------------------------------------------------------
   $Id: checkout_success.php,v 1.8 2004/03/01 19:16:18 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_success.php,v 1.48 2003/02/17); www.oscommerce.com 
   (c) 2003	 nextcommerce (checkout_success.php,v 1.14 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contribution:

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org


   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
   // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_draw_checkbox_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_selection_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');


/* check if use ezship function  -- shop.elhomeo.com 20150324 //
  if (isset($_GET['processID']) && isset($_GET['webPara']) && isset($_SESSION['customer_id'])) {
    $processID = $_GET['processID'];
    $cID = $_GET['webPara'];
    $stCate = $_GET['stCate'];
    $stCode = $_GET['stCode'];
    $stName = $_GET['stName'];

    if (isset($_GET['stTel'])) $stTel = $_GET['stTel']; 
    $ez_date = date('Y-m-d');
    $formlink = HTTP_SERVER . DIR_WS_CATALOG . 'shop_content.php';
    
    $errmsg = null;
    $orders_id_query = $db->Execute("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . $processID . "' and customers_id = '". $cID ."'");

    if ($orders_id_query->RecordCount() < 1){
      $errmsg = '<p>超商取件指定有問題!!</p>';
      $smarty->assign('Errmsg',$errmsg);
      } else {
        $comments = "Ezship指定資料\n門市通路代號:".$stCate."\n門市代號: ".$stCode."\n門市名稱: ".$stName;
        if (isset($_GET['stAddr']) && isset($_GET['stTel'])) {
          $stAddr = $_GET['stAddr'];
          $stTel = $_GET['stTel']
          $comments = $comments . "\n門市地址:".$stAddr."\n門市電話:".$st_Tel;
          }
        $db->Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . $cID . "', '8',  ".TIMEZONE_OFFSET." , '1', '" . $comments  . "')");
      }
  }
 // check if use ezship function  -- shop.elhomeo.com 20150324 */



  // if the customer is not logged on, redirect them to the shopping cart page
  if (!isset($_SESSION['customer_id'])) {
    twe_redirect(twe_href_link(FILENAME_SHOPPING_CART,'','SSL'));
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'update')) {
    if ((DELETE_GUEST_ACCOUNT == true) && ($_SESSION['account_type']==1)){
	$db->Execute("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
    $db->Execute("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
    $db->Execute("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
    twe_session_destroy();
    twe_redirect(twe_href_link(FILENAME_DEFAULT,'','SSL'));
   }else{
    if ($_SESSION['account_type']!=1) {
	twe_redirect(twe_href_link(FILENAME_DEFAULT,'','SSL'));
    } else {
    twe_redirect(twe_href_link(FILENAME_LOGOFF,'','SSL'));
    }
   }
  }

 $breadcrumb->add(NAVBAR_TITLE_1_CHECKOUT_SUCCESS,'','SSL');
  $breadcrumb->add(NAVBAR_TITLE_2_CHECKOUT_SUCCESS);

 
    $orders_query = "select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' order by date_purchased desc limit 1";
    $orders = $db->Execute($orders_query);

 

 require(DIR_WS_INCLUDES . 'header.php');
 


 $smarty->assign('FORM_ACTION',twe_draw_form('order', twe_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')));
 $smarty->assign('BUTTON_CONTINUE',twe_image_submit('button_continue.gif', IMAGE_BUTTON_NEXT));
 $smarty->assign('BUTTON_PRINT','<img src="'.'templates/'.CURRENT_TEMPLATE.'/buttons/' . $_SESSION['language'].'/print.gif" style="cursor:hand" onClick="window.open(\''. twe_href_link(FILENAME_PRINT_ORDER,'oID='.$orders->fields['orders_id']).'\', \'popup\', \'toolbar=0, width=640, height=600\')">');

 // GV Code Start
 $gv_query="select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id='".$_SESSION['customer_id']."'";
   $gv_result=$db->Execute($gv_query);
    if ($gv_result->RecordCount()>0) {
       if ($gv_result->fields['amount'] > 0) {
            $smarty->assign('GV_SEND_LINK', twe_href_link(FILENAME_GV_SEND,'','SSL'));
            }
       }
 // GV Code End
 if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); 
  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('PAYMENT_BLOCK',$payment_block);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/checkout_success.html');

  $smarty->assign('main_content',$main_content);
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>