<?php
require('../includes/configure.php');
$dbc=@mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die('could not connect to MySQL:'.mysql_error());
@mysql_select_db(DB_DATABASE) or die('Could not select database:'.mysql_error());
//@mysql_query("SET NAMES 'UTF8'", $dbc); //指定提取資料的校對字元表
//@mysql_query("set character set UTF8",$dbc);//指定提取資料的校對字元表
//    if (!$dbc) echo die('Could not select database:'.mysql_error());

if (isset($_GET['model']) && isset($_GET['adjqty'])) 
  {
    $p_id = $_GET['pid'];
    $p_model = $_GET['model'];
    $p_qty = $_GET['pqty'];
    $adj = $_GET['adjqty'];

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
         if ($row[4] >= ($row[3]*$adj))
           { $s_qty_final[$i] = $row[4] - ($row[3] * $adj);}
           else { 
                  $s_qty_final[$i] = $row[4] - ($row[3] * $adj);
                  $err_msg .= "型號:".$row[1]."-".$row[2] . "數量不足<br>";
                  $err =1;
                }
       }  // while EOF


    if ($err != 1) 
      {
       $p_qty_final = $p_qty + $adj;
       $p_status = '1';
       if ($p_qty_final < 1)
         { $p_status = '0';}
       $products_update = 'UPDATE `products` SET `products_quantity` = "' . $p_qty_final . '", `products_status` = "' . $p_status . '"
                           WHERE `products_id` = "' . $p_id . '"';
       $products_update_exec=mysql_query($products_update) or die('Query failed: '.mysql_error());
//  更新庫存數量
       for($j=1;$j<=$i;$j++)
           { 
              $stock_update = 'UPDATE `stock` SET `stocks_quantity` = "' . $s_qty_final[$j] . '"
                                                WHERE `stocks_id` = "' . $stocks_id[$j] . '"';
              $stock_update_exec=mysql_query($stock_update) or die('Query failed: '.mysql_error());
           }
      } ELSE {
//   以na交給前端判定不存在
            $arr[0] = array('model' => 'na');
      }// if ($err != 1)  EOF
  } // adjust EOF
  
    $p_model = $_GET['model'];
    $qry = 'SELECT sr.products_model, sr.stocks_model, s.stocks_description, sr.weight, s.stocks_quantity
            FROM stock_relation sr LEFT JOIN stock s
            ON sr.stocks_model = s.stocks_model
            WHERE sr.products_model = "'.$p_model.'"';
    $rs = mysql_query($qry) or die('Query failed: '.mysql_error());

    if (!$rs) echo die('Query failed: '.mysql_error());

    if (mysql_num_rows($rs) < 1)
      {
          $arr[0] = array('model' => 'na');
      } ELSE {
              $i = 0;
              while ($row = mysql_fetch_array($rs,MYSQL_NUM))
              
                {    
                   $arr[$i] = array('model' => $row[1], 'desc' => $row[2], 'unit' => $row[3], 'qty' => $row[4]);
                   $i++;
                }
      }
    echo json_encode($arr);


mysql_close($dbc);
?>
