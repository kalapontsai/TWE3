<?php

/* ----------------------------------------
   $Id: payment_notice.php,v 1.1 2011/09/05 ELHOMEO.com
   -------------------------------------------------------------------------

   v0.1 payment_notice.php by Kadela 20110106
   v0.2 modify mail function and add Express '店到店' text area. 20110118
   v0.3 integrate in to shop centent center.
   v0.4 disable EXPRESS field that move to address book.
   v0.5 requery customer firstname for e-mail header.

   v0.6 change POST to GET for data tranfer form PO mail
   v0.7 add payment amount data
   
   v0.8 send record into database table orders_status_history

   v0.9 change mail sender to dummy account from elhomeo.com

   v1.0 clear data once send mail success

   v1.1 separate email to admin and customer
   
   v1.2 modify sender and attendee for Gmail forwarder issue.
   
   v1.3 change $acc label
   
   v1.4 check href include _GET value as bookmark

   v1.5 revise line 172 auto redirect to home page will SSL
   
   test po number :   2011032201 / elsa@elhomeo.com
   http://shop.elhomeo.com/shop_content.php?coID=8&c_ID=elsa%40elhomeo.com&o_ID=2011032201&paydate=2015-12-25&b_code=111&account=feeffefe+f&amount=111&memo=test&submit=Send&keywords=%E8%AB%8B%E8%BC%B8%E5%85%A5%E9%97%9C%E9%8D%B5%E5%AD%97

-----------------------------------------*/

  require_once(DIR_WS_CLASSES.'class.phpmailer.php');

  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');

  require_once(DIR_FS_INC . 'twe_validate_email.inc.php');

$currentdate = date('Y-m-d');

if ($_GET['c_ID']) $cID = $_GET['c_ID'];

if ($_GET['o_ID']) $oID = $_GET['o_ID'];

if ($_GET['b_code']) $bank = $_GET['b_code'];

if ($_GET['account']) $acc = $_GET['account'];

if ($_GET['amount']) $amt = $_GET['amount'];

if ($_GET['memo']) $memo = $_GET['memo'];

if ($_GET['paydate']) {

  $paydate = $_GET['paydate'];

  }else { $paydate = (date('Y-m-d'));}

$formlink = HTTP_SERVER . DIR_WS_CATALOG . 'shop_content.php';

if (isset($_GET['submit']) ) { // 客戶送出資料的處理

	$errmsg = null;

//找出客戶的名字

	$firstname_query = "select customers_firstname, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $cID . "' LIMIT 1";

  $firstname_query_result = $db->Execute($firstname_query);

  if ($firstname_query_result->RecordCount() < 1) {

    $errmsg = '<p>這個會員帳號不存在 !</p>';

    }

    else {

    $firstname = $firstname_query_result->fields['customers_firstname'];

    $orders_id_query = $db->Execute("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . $oID . "' and customers_id = '". $firstname_query_result->fields['customers_id'] ."'");

//    $order_exists = true;

    if ($orders_id_query->RecordCount() < 1){

      $order_exists = false;

      $errmsg = $errmsg . '<p>您沒有這張訂單號碼 !</p>';

      }

    }

	if (strlen($bank) < 3) $errmsg = $errmsg . '<p>請輸入銀行代號(三碼)或名稱 !</p>';


	if (strlen($acc) < 5) $errmsg = $errmsg . '<p>請輸入銀行帳號後五碼(或無摺存款人姓名) !</p>';

	if (strlen($paydate) < 10) $errmsg = $errmsg . '<p>請輸入匯款日期 !</p>';

	if (!is_numeric($amt)) $errmsg = $errmsg . '<p>請輸入正確匯款金額 !</p>';


	if (strlen($errmsg)<1) { // If everything's okay. query customer firstname then Send an email.

  	$message_body_plain = "會員帳號 : {$cID} \n\n訂單號碼 : {$oID} \n\n匯款日期 : {$paydate} \n\n銀行代號 : {$bank} \n\n帳號後五碼(或無摺存款人姓名) : {$acc} \n\n匯款金額 : {$amt}\n\n備註 : {$memo}\n\n";
   	$message_body_html = "會員帳號 : {$cID}<br>訂單號碼 : {$oID}<br>匯款日期 : {$paydate}<br>銀行代號 : {$bank}<br>帳號後五碼(或無摺存款人姓名) : {$acc}<br>匯款金額 : {$amt}<br>備註 : {$memo}<br>";
    $email_subject = '艾沙順勢糖球屋- 匯款通知: '.$oID;
    $email_subject_admin = '匯款通知 : '.$firstname.' : '.$oID;  

//  email to admin , use customer first name as sender's name
    twe_php_mail(EMAIL_SUPPORT_ADDRESS, EMAIL_SUPPORT_NAME, EMAIL_SUPPORT_REPLY_ADDRESS,EMAIL_SUPPORT_NAME, '','','', '', '', $email_subject_admin, $message_body_html, $message_body_plain);
//  email to customer
    twe_php_mail(EMAIL_SUPPORT_ADDRESS, EMAIL_SUPPORT_NAME, $cID, $firstname , '', EMAIL_SUPPORT_REPLY_ADDRESS, EMAIL_SUPPORT_REPLY_ADDRESS_NAME, '', '', $email_subject, $message_body_html, $message_body_plain);

//  save in to SQL database
  	$comments = "匯款日期: ".$paydate."\n銀行代號: ".$bank."\n帳號後五碼: ".$acc."\n匯款金額: ".$amt."\n備註: ".$memo;
    $db->Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . $oID . "', '8',  ".TIMEZONE_OFFSET." , '1', '" . $comments  . "')");

//   v1.4 check href include _GET value as bookmark
    header("location: shop_content.php?coID=8&redir=1&sentdate=".date('YmdHi'));

/* 清除匯款資料 20110905 */

    $cid = "";

    $oid = "";

    $bank = "";

    $acc = "";

    $amt = "";

    $memo = "";

	  }
}

echo "<fieldset style='width: 100%'>";

if (strlen($errmsg)>1) {
  echo '<font color=red>' . $errmsg . '</font><br>';
} 
elseif (isset($_GET['redir']) && ($_GET['sentdate'] == (date('YmdHi')))) { 
  $msg_send = '<font color=blue>' . '謝謝您的通知 !!<br><br>我們會儘快處理您的貨品 !!' . '</font>'; 
  echo '<font color=blue>' . '謝謝您的通知 !!<br><br>我們會儘快處理您的貨品 !!' . '</font><br><br>';
  }

$html_txt = "<legend>請輸入您的匯款資料:";
$html_txt .= $msg_send;
$html_txt .= $errmsg;
$html_txt .= ":</legend>
  <table style='width: 100%; text-align: left; margin-left: auto; margin-right: auto;' border='0' cellpadding='1' cellspacing='1'>
    <tbody>
      <tr><form action='$formlink' method='get'>        
        <td style='text-align: left; width: 20%;'>
        <INPUT class='form-control' TYPE='hidden' name='coID' VALUE='8'><b>會員帳號(Email):</b></td>
        <td colspan='3'><input class='form-control' name='c_ID' type='text' placeholder='請填入訂購的會員帳號' value='" . $cID . "'></td>
      </tr>
      <tr>
        <td style='text-align: left; width: 20%;'><b>訂單號碼:</b></td>
        <td colspan='3'><input class='form-control' name='o_ID' placeholder='共10碼' maxlength='20' type='text' value='$oID'></td>
      </tr>
      <tr>
        <td><b>匯款日期:</b></td>
        <td style='text-align:left'><input class='form-control' name='paydate' size='15' value='$paydate' type='text'>(請依此格式：西元年-月-日)</td>
      </tr>
      <tr>
        <td><b>銀行代號或名稱 :</b></td>
        <td><input class='form-control' name='b_code' size='10' maxlength='10' type='text' value='$bank'></td>
		</tr>
		<tr>
        <td style='width: 20%; text-align: left;'><b>帳號後五碼 或</br>無摺存款人姓名 :</b></td>
        <td><input class='form-control' name='account' size='10' maxlength='10' type='text' value='$acc'></td>
      </tr>
     <tr>
        <td style='text-align: left; width: 20%;'><b>匯款金額:</b></td>
        <td colspan='3'><input class='form-control' name='amount' size='10' maxlength='10' type='text' value='$amt'></td>
      </tr>
      <tr>
        <td colspan='4' style='text-align=left;'><b>備忘錄:</b></td>
      </tr>
      <tr>
        <td colspan='4'><textarea class='form-control' name='memo' wrap='soft' rows='8'></textarea></td>
      </tr>
      <tr>
        <td colspan='4'>
        <div align='center'><input name='submit' value='Send' type='submit'></div></td>
      </tr>
    </tbody>
  </table>
  </fieldset>";
echo $html_txt;

?>
