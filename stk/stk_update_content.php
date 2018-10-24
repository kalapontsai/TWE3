<?php
/* -----------------------------------------------------------------------------------------
   $Id: stk_update_content.php,v 1.0 2011/03/04 ELHOMEO.com 
   manager twe database table 'products' by a dedicated program  
   -----------------------------------------------------------------------------------------
   v0.1  sorting all the products model name to update the q'ty and status
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
require('../includes/configure.php');
// ----------------  header ---------------------------
//Make the connection and then select the database.
$dbc=@mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die('could not connect to MySQL:'.mysql_error());
@mysql_select_db(DB_DATABASE) or die('Could not select database:'.mysql_error());

$p_time = date("Y-m-d H:i:s");

/* -------------- 上架動作 -----------------*/

if (isset($_POST['action']) && ($_POST['action'] == 'calc')) {
   $p_id = $_POST['p_id'];
   $p_model = $_POST['p_model'];
   $pd_name = $_POST['pd_name'];
   $p_qty = $_POST['p_qty'];
   $adj_qty = $_POST['adj_qty'];

   if (($p_qty + $adj_qty)< 0) {
                  $err_msg .= "下架數量大於現有數量<br>";
                  $err =1;
    } else {
                $qry = 'SELECT sr.products_model, sr.stocks_model, s.stocks_description, sr.weight, s.stocks_quantity, s.stocks_id
                               FROM stock_relation sr LEFT JOIN stock s ON sr.stocks_model = s.stocks_model
                               WHERE sr.products_model = "'.$p_model.'"';
                 $rs = mysql_query($qry) or die('Query failed: '.mysql_error());
                 $model = array();
                $desc = array();
                $weight = array();
                $s_qty = array();
                $stocks_id = array();
                $s_qty_final = array();
                $i = 0;
               while ($row = mysql_fetch_array($rs,MYSQL_NUM))
                     {  $i ++;
                         $model[$i] = "$row[1]";
                         $desc[$i] = "$row[2]";
                         $weight[$i] = "$row[3]";
                         $s_qty[$i] = "$row[4]";
                         $stocks_id[$i] = "$row[5]";
                         if ($row[4] >= ($row[3]*$adj_qty))
                            { $s_qty_final[$i] = $row[4] - ($row[3] * $adj_qty);}
                         else { 
                               $s_qty_final[$i] = $row[4] - ($row[3] * $adj_qty);
                               $err_msg .= "型號:".$row[1]."-".$row[2] . "數量不足<br>";
                               $err =1;
                              }
                     }  // while
    }  //if (($p_qty + $adj_qty)< 0) else

   if ($err != 1) 
    {
       $p_qty_final = $p_qty + $adj_qty;
       $products_update = 'UPDATE `products` SET `products_quantity` = "' . $p_qty_final . '", `products_status` = "1"
                                                 WHERE `products_id` = "' . $p_id . '"';
       $products_update_exec=mysql_query($products_update) or die('Query failed: '.mysql_error());
//  更新庫存數量
       for($j=1;$j<=$i;$j++)
           { 
              $stock_update = 'UPDATE `stock` SET `stocks_quantity` = "' . $s_qty_final[$j] . '"
                                                WHERE `stocks_id` = "' . $stocks_id[$j] . '"';
              $stock_update_exec=mysql_query($stock_update) or die('Query failed: '.mysql_error());
           }
    } // if ($err != 1)
} //if (isset($_POST['action']) && ($_POST['action'] == 'calc'))
/* -------------- 上架動作 -----------------*/

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
<title>庫存更動</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<link href='/templates/twe/stylesheet.css' rel='stylesheet' type='text/css' />
</head><body>";
   echo "<table width='90%' cellspacing='5' cellpadding='5' frame='border' rules='rows' align='center'>
                 <tr><td><h1><span>調整庫存</span></h1></td></tr>";

   if  (isset($err_msg)) { echo "<tr><td><font color='red'>$err_msg</font></td></tr>";}

/*  debug mode 顯示在頁面上
   if (isset($_POST['action']) && ($_POST['action'] == 'calc')) {

       echo "<tr><td><table width='100%' cellspacing='5' cellpadding='5' frame='border' rules='rows' align='center'>";
       for($x=1;$x<=$i;$x++) { 
             echo "<tr><td>$model[$x]</td><td>$desc[$x]</td><td>$weight[$x]</td><td>$s_qty_final[$x]</td></tr>";
       }
       echo "</table></td></tr>";
    }
*/
   if (isset($_POST['p_model']) && isset($_POST['pd_name']) && isset($_POST['p_qty']) ){
   $p_id=$_POST['p_id'];
   $p_model=strtoupper($_POST['p_model']);
   $pd_name=$_POST['pd_name'];
   if (!isset($_POST['action']) && ($_POST['action'] != 'calc')) {
      $p_qty_final = $_POST['p_qty'];
   }

// 找出關聯表與p_model相關連的庫存型號
   $qry = 'SELECT sr.products_model, sr.stocks_model, s.stocks_description, sr.weight, s.stocks_quantity
                                               FROM stock_relation sr LEFT JOIN stock s
                                               ON sr.stocks_model = s.stocks_model
                                               WHERE sr.products_model = "'.$p_model.'"';
   $rs = mysql_query($qry) or die('Query failed: '.mysql_error());

   echo "<tr><td><table width='100%' cellspacing='5' cellpadding='5' frame='border' rules='rows' align='center'>";
   echo "<tr class='stockcaption' align='center'><td>$p_model</td><td>$pd_name</td><td>上架數量 : $p_qty_final</td>";
  if ((mysql_num_rows($rs)) >0)
    {
   echo "<td>新增數量:<form name='calc' method='POST' action='stk_update_content.php'>
                <INPUT TYPE='hidden' name='action' VALUE='calc'>
                <INPUT TYPE='hidden' name='p_id' VALUE='$p_id'>
                <INPUT TYPE='hidden' name='p_model' VALUE='$p_model'>
                <INPUT TYPE='hidden' name='pd_name' VALUE='$pd_name'>
                <INPUT TYPE='hidden' name='p_qty' VALUE='$p_qty_final'>
                <INPUT name='adj_qty' type='text' size=6 value='0'> <INPUT TYPE=submit VALUE='計算'></form>
                </td></tr><tr><td colspan=4>&nbsp;</td></tr>";
    } else {
    echo "<td><font color='red'>查無關聯產品</font></td></tr><tr><td colspan=4>&nbsp;</td></tr>";
    }
   echo "<tr class='stockcaption' align='center'><td>型號</td><td>名稱</td><td>每單位數量</td><td>剩餘數量</td></tr>";
   while ($row = mysql_fetch_array($rs,MYSQL_NUM))
      {    
        echo "<tr class='stock'field1' align='center'><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td></tr>";
      }
   echo "</TABLE></td></tr>";
   }
echo "</body></html>";

mysql_free_result_all;
mysql_close();
?>
