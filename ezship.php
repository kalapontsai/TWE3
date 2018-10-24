<?php
 
/*
http://www.ezship.com.tw/emap/ezship_request_order_api.jsp?su_id=service@elhomeo.com&order_id=2015032701&order_status=A01&order_type=3&order_amount=100&rv_name=蔡小兵&rv_email=kadelat@gmail.com&rv_mobile=0931876507&st_code=TFM0038&rtn_url=http://shop.elhomeo.com/ezship.php
http://shop.elhomeo.com/admin/ezship.php?order_id=1234&sn_id=46923809&order_status=S01&webPara=

http://map.ezship.com.tw/ezship_map_web.jsp?su_ID=service@elhomeo.com&process_ID=2015032701&rtURL=http://shop.elhomeo.com/ezship.php
*/

echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>EZSHIP 測試</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<H3><a href="http://shop.elhomeo.com/ezship.php">EZSHIP 測試</a></H3>
';
 if (isset($_GET['order_id']))   {

    foreach ($_GET as $key => $value) {
//    $ke = iconv('Big5','UTF-8',$key);
//    $va = iconv('Big5','UTF-8',$value);
      echo "$key, $value <br>";
    }
//    print_r($_POST)));
//    var_dump($_POST);
      echo '<hr>';
    }

 if (isset($_POST['st_cate']) && isset($_POST['st_code']))   {

    foreach ($_POST as $key => $value) {
      echo "$key, $value <br>";
    }
 }

   echo ' 
    <table style="border-top:1px solid; border-bottom:1px solid;" width="100%" border="0">
        <tr>
          <td colspan="3"><a href="http://map.ezship.com.tw/ezship_map_web.jsp?su_ID=service@elhomeo.com&process_ID=2015032701&rtURL=http://shop.elhomeo.com/ezship.php">
          Map查詢</a><br>http://map.ezship.com.tw/ezship_map_web.jsp?su_ID=service@elhomeo.com&process_ID=2015032701&rtURL=http://shop.elhomeo.com/ezship.php</td>
        </tr>
        <tr><td><form name="auto_store" action="http://www.ezship.com.tw/emap/ezship_request_order_api.jsp" method="post">
          su_id<input type="text" name="su_id" value="service@elhomeo.com"></td></tr>
          <tr><td>order_id<input type="text" name="order_id" value="2015032701"></td></tr>
          <tr><td>order_type<input type="text" name="order_type" value="3"></td></tr>
          <tr><td>order_amount<input type="text" name="order_amount" value="100"></td></tr>
          <tr><td>st_Code<INPUT type="text" name="st_code" value="TFM0038"></td></tr>
          <tr><td>rv_name<input type="text" name="rv_name" value="蔡小兵"></td></tr>
          <tr><td>rv_email<input type="text" name="rv_email" value="kadelat@gmail.com"></td></tr>
          <tr><td>rv_mobile<input type="text" name="rv_mobile" value="0981876507"></td></tr>
          <tr><td>rtn_url<input type="text" name="rtn_url" value="http://shop.elhomeo.com/ezship.php"></td></tr>
          <tr><td>web_para<input type="text" name="web_para" value="4"></td></tr>
          <tr><td>order_status<INPUT type="text" name="order_status" size="6" value="A01"></td></tr>
          <tr><td><input name="submit" value="A01" type="submit"></form></td></tr>
        
      </table>';

echo '</body></html>';
?>