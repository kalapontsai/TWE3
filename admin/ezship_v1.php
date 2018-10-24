<?php

/* -----------------------------------------------------------------------------------------
   $Id: ezship.php,v 0.1 2015/03/25 ELHOMEO.com   
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License 
   
   v0.1 Pass data to EZSHIP server to create a shipping order.
   ---------------------------------------------------------------------------------------*/
/*   回傳測試碼
    foreach ($_POST as $key => $value) {
      echo "$key, $value <br>";
    }
     

 
//http://www.ezship.com.tw/emap/ezship_request_order_api.jsp?su_id=service@elhomeo.com&order_id=2015032701&order_status=A01&order_type=3&order_amount=100&rv_name=蔡小兵&rv_email=kadelat@gmail.com&rv_mobile=0931876507&st_code=TFM0038&rtn_url=http://shop.elhomeo.com/ezship.php
//http://shop.elhomeo.com/admin/ezship.php?order_id=1234&sn_id=46923809&order_status=S01&webPara=

//http://map.ezship.com.tw/ezship_map_web.jsp?su_ID=service@elhomeo.com&process_ID=2015032701&rtURL=http://shop.elhomeo.com/ezship.php


*/


    require('includes/application_top.php');

  // include needed functions
    require_once(DIR_FS_INC .'twe_get_order_data.inc.php');
    require_once(DIR_FS_INC .'twe_not_null.inc.php');
    include(DIR_WS_CLASSES . 'order.php');

    $smarty = new Smarty;
    $smarty->caching = false;

    if (isset($_POST['st_code']))   {
      $rtn_message = 'Ezship 體系代號:' . $_POST['st_cate'] . '<br>店號:' . $_POST['st_code'] . '<br>店名:' . $_POST['stName']. '<br>地址:' . $_POST['stAddr']. '<br>原訂單編號:' . $_POST['webPara'];
      $smarty->assign('rtn_message',$rtn_message);
//    $order = new order($_POST['processID']);
//    print_r($_REQUEST);
    }

    if (isset($_GET['order_id']))   {
      $order = new order($_GET['order_id']);
      $smarty->assign('oID',$_GET['order_id']);    
      Switch ($_GET['order_status'])
        {  case S01:
             $rtn_message = '登記完成 !!<br>訂單號碼:' . $_GET['order_id'] . '<br>店到店編號:' . $_GET['sn_id'];
             break;
           case E00:
             $rtn_message = 'E00:參數錯誤或欄位短缺';
             break;
           case E01:
             $rtn_message = 'E01:EZ帳號不存在';
             break;
           case E02:
             $rtn_message = 'E02:此EZ帳號未開通權限';
             break;
           case E03:
             $rtn_message = 'E03:帳號無可用的輕鬆袋';
             break;
           case E04:
             $rtn_message = 'E04:取件門市有誤';
             break;
           case E05:
             $rtn_message = 'E05:金額有誤';
             break;
           case E06:
             $rtn_message = 'E06:Email格式有誤';
             break;
           case E07:
             $rtn_message = 'E07:手機號碼有誤';
             break;
           case E08:
             $rtn_message = 'E08:配送方式資料有誤';
             break;
           case E09:
             $rtn_message = 'E09:取件付款與否資料有誤';
             break;
           case E10:
             $rtn_message = 'E10:收件人姓名有誤';
             break;
           case E11:
             $rtn_message = 'E11:收件地址有誤';
             break;
           case E98:
             $rtn_message = 'E98:EZ系統錯誤無法載入';
             break;
           case E99:
             $rtn_message = 'E99:系統錯誤';
             break;
           default:
             $rtn_message = '失敗 , 未知名原因!!';
       }
      $smarty->assign('rtn_message',$rtn_message);
    }

 
    if (isset($_GET['oID'])) {
     $order = new order($_GET['oID']);
     $smarty->assign('oID',$_GET['oID']);         

// 判斷是否使用便利商店  BEGIN
    $exp_query = $db->Execute("select orders_id,customers_id,
                                      delivery_name as name,
                                      delivery_fax as fax,
                                      delivery_use_exp as use_exp, 
                                      delivery_exp_type as exp_type,
                                      delivery_exp_title as exp_title,
                                      delivery_exp_number as exp_number
                                      from " . TABLE_ORDERS . " where orders_id = '" . $_GET['oID'] . "'");

    $smarty->assign('rv_name',$exp_query->fields['name']);
	  $smarty->assign('rv_mobil',$exp_query->fields['fax']);
    if ($exp_query->fields['use_exp']) {
      $cr = '<br>';
      $name = $exp_query->fields['name'];
      $fax = $exp_query->fields['fax'];
      $exp_type = twe_output_string_protected($exp_query->fields['exp_type']);
   	  $exp_title = '店名 : ' . twe_output_string_protected($exp_query->fields['exp_title']);
   	  $exp_number = '店號 : ' . twe_output_string_protected($exp_query->fields['exp_number']);
      if ($exp_type == '0') $exp_type = '未定義';
  	  if ($exp_type == '1') $exp_type = '統一超商';
      if ($exp_type == '2') $exp_type = '全家便利';
      $address_label_shipping = $exp_query->fields['name'] . $cr . $exp_query->fields['fax'] . $cr . $exp_type . $cr . $exp_title . $cr . $exp_number;
      $smarty->assign('address_label_shipping',$address_label_shipping);
      $smarty->assign('cID',$exp_query->fields['customers_id']);

      } else {
  	  $smarty->assign('address_label_shipping',twe_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'));
      $smarty->assign('cID',$order->delivery['customers_id']);

    } 
// 判斷是否使用便利商店  EOF

// get order_total data
    $oder_total_values=$db->Execute("SELECT title,text,class,value,sort_order
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

// 插入客戶意見 
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
    $total = round($total,0);
    $smarty->assign('total',$total);
    $smarty->assign('email',$order->customer['email_address']);
    $smarty->assign('comments',$order->info['comments']);
    $smarty->assign('orders_history_comment', $orders_history_comment);
$scpt ="function door()
{
  document.all.st_code.style.visibility='hidden';
  document.all.st_code_title.style.visibility='hidden';
  document.all.rv_addr.style.visibility='visible';
  document.all.rv_addr_title.style.visibility='visible';
  document.all.rv_zip.style.visibility='visible';
  document.all.rv_zip_title.style.visibility='visible';
}

function shop()
{
  document.all.st_code.style.visibility='visible';
  document.all.st_code_title.style.visibility='visible';
  document.all.rv_addr.style.visibility='hidden';
  document.all.rv_addr_title.style.visibility='hidden';
  document.all.rv_zip.style.visibility='hidden';
  document.all.rv_zip_title.style.visibility='hidden';
}";

    $smarty->assign('scpt', $scpt);
    }

    $smarty->assign('language', $_SESSION['language']);
	  $smarty->template_dir=DIR_FS_CATALOG.'templates';
	  $smarty->compile_dir=DIR_FS_CATALOG.'templates_c';
	  $smarty->config_dir=DIR_FS_CATALOG.'lang';
	  $smarty->display(CURRENT_TEMPLATE . '/admin/ezship.html');	

?>