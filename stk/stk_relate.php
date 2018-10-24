<?php
  require('../includes/configure.php');
  include(DIR_WS_INCLUDES.'stk_header.inc');

$p_time = date("Y-m-d H:i:s");

if (isset($_POST['action']) && ($_POST['action'] == 'add')) {
  $p_model = strtoupper($_POST['p_model']);
  $s_model = strtoupper($_POST['s_model']);
  $weight = $_POST['weight'];
  if (($weight <0) || (!is_numeric($weight))) { $err_msg = " 重量格式錯誤";}
  $qry_check = "SELECT `stocks_model` FROM `stock` WHERE  `stocks_model` = '$s_model'";
  $qry_exec=mysql_query($qry_check) or die('Query failed: '.mysql_error());
  if (mysql_num_rows($qry_exec) <1){ $err_msg .= "<br> 庫存產品不存在"; }

  if  (!isset($err_msg))
      {
        $qry_add = "  INSERT INTO  `stock_relation` (`products_model` ,`stocks_model` ,`weight`)
                                  VALUES ('$p_model',  '$s_model',  '$weight')";
        $qry_exec=mysql_query($qry_add) or die('Query failed: '.mysql_error());
        }
}


if (isset($_POST['action']) && ($_POST['action'] == 'delete')) {
  $index_id = $_POST['index_id'];
  $qry_del = "DELETE FROM `stock_relation` WHERE `index_id` = '" . $index_id . "' LIMIT 1";
  $qry_exec=mysql_query($qry_del) or die('Query failed: '.mysql_error());
}

/*  取消編輯功能
if (isset($_POST['action']) && ($_POST['action'] == 'update')) {
  $index_id = $_POST['index_id'];
  $p_model = strtoupper($_POST['p_model']);
  $s_model = strtoupper($_POST['s_model']);
  $weight = $_POST['p_qty'];  // 為了定位功能 輸入欄位 name = p_qty
  if (($weight <0) || (!is_numeric($weight))) { 
      $err_msg = " 重量格式錯誤";
      } ELSE {
                    $qry_update = 'UPDATE `stock_relation` SET `products_model` = "' . $p_model . '",
                                                                                                  `stocks_model` = "' . $s_model . '",
                                                                                                  `weight` = "' .     $weight . '"
                                                                                                  where `index_id` = "' . $index_id . '"';
                    $qry_exec=mysql_query($qry_update) or die('Query failed: '.mysql_error());
      }
}
*/

if (isset($_POST['action']) && (($_POST['action'] == 'sort') || ($_POST['action'] == 'update') || ($_POST['action'] == 'delete') || ($_POST['action'] == 'add'))) {
  switch ($_POST['cate']){
	case "single":
        $cate = 'single';
	    $query_str = 'S';
		break;
	case "chemical":
        $cate = 'chemical';
	    $query_str = 'C';
		break;
	case "assemble":
        $cate = 'assemble';
	    $query_str = 'A';
		break;
	case "oint":
	    $cate = 'oint';
        $query_str = 'O';
		break;
	case "b":
	    $cate = 'b';
        $query_str = 'B';
		break;
	case "m":
	    $cate = 'm';
        $query_str = 'M';
		break;
  }
} else {
	    $cate = 'single';
        $query_str = 'S';
		}

$qry_products = 'SELECT DISTINCT products_model FROM products
                                  WHERE products_model like "' . $query_str . '%" ORDER BY products_model';
$rs_products=@mysql_query($qry_products);

$query= 'SELECT a.index_id, a.products_model, c.products_name, a.stocks_model, d.stocks_description, a.weight
FROM stock_relation a, products b, products_description c, stock d
WHERE a.products_model = b.products_model
AND b.products_id = c.products_id
AND a.stocks_model = d.stocks_model
AND a.products_model like "' . $query_str . '%" ORDER BY a.products_model, a.stocks_model';
$result=mysql_query($query) or die('Query failed: '.mysql_error());

/*  分頁功能*/
echo "<tr><td>";
include('stk_cate.inc');
echo "</td></tr>";

$caption = "<tr class='stockcaption' align='center'><td>&nbsp;</td>\n
<td>上架物品</td><td>名稱</td><td>庫存料號</td>
<td>名稱</td><td>重量</td><td>&nbsp;</td>
</tr>\n";

if  ($err_msg) { 
  $msg = "<font color=red>".  $err_msg . "</font>";
  } ELSE {
  $msg = "&nbsp;";
  }

echo "<tr><td><table width='100%' cellspacing='5' cellpadding='5' frame='border' rules='rows'>\n" . $caption;
// 新增
       echo "
             <tr class='stock'field1' align='center'>\n
             <td><form name='add' method='POST' action='$filename'>\n
             <INPUT TYPE='hidden' name='action' VALUE='add'>
             <INPUT TYPE='hidden' name='cate' VALUE=$cate>&nbsp;</td>\n
             <td><SELECT name='p_model' value=$p_model>";
        while ($row = mysql_fetch_array($rs_products,MYSQL_NUM)) 
        { if (($row[0] == $p_model) && ($_POST['p_model'])) {
                    echo '<OPTION value="'.$row[0].'" selected="selected">'.$row[0]. '</OPTION>';
               } ELSE {
                             echo '<OPTION value="'.$row[0].'">'.$row[0]. '</OPTION>';
                            }
        }             
       echo "</td>
             <td>&nbsp;</td>\n
             <td><INPUT name='s_model' type='text' size=6 value=$s_model></td>\n
             <td>$msg</td>
             <td><INPUT name='weight' type='text' size=6 value=$weight></td>\n
            <td><INPUT TYPE='submit' VALUE='New'></form></td>
             </tr>\n";

$column = 1;
$css_id = 'stockfield1';
if ($result)
  {
    while ($row = mysql_fetch_array($result,MYSQL_NUM))
      {
       if ( ($column%20) == 0 ) {echo $caption;}
       if ($tmp != $row[1]){ if ($css_id == 'stockfield') { $css_id = 'stockfield1'; } else { $css_id = 'stockfield'; } } // 先辨識model是否相同再更改背景顏色
       echo "<tr class='$css_id' align='center'><td>
             <form name='del$column' method='POST' action='$filename'>
             <INPUT TYPE='hidden' name='action' VALUE='delete'>
             <INPUT TYPE='hidden' name='index_id' VALUE=$row[0]>
             <INPUT TYPE='hidden' name='cate' VALUE=$cate>
             <INPUT TYPE='hidden' name='position' VALUE=$column>
             <INPUT type='Image' name='submit' align='BOTTOM' src='../images/trash-icon.png'></form></td>\n";
// 為了定位功能, weight 改成p_qty
       echo "<td><font color = blue>$row[1]</font></td>
                    <td align=left><font color = blue>$row[2]</font></td>\n
                    <td>$row[3]</td>
                    <td align=left>$row[4]</td>\n
                    <td>$row[5]</td>\n
                   <td>&nbsp;</td></tr>\n";
       $tmp = $row[1];

/* 取消編輯功能
// 為了定位功能, weight 改成p_qty
       echo "<td class='stockfield'>
             <form name='a$column' method='POST' action='$filename'>
             <INPUT TYPE='hidden' name='action' VALUE='update'>
             <INPUT TYPE='hidden' name='index_id' VALUE=$row[0]>
             <INPUT TYPE='hidden' name='cate' VALUE=$cate>
             <INPUT TYPE='hidden' name='position' VALUE=$column>
             <INPUT name='p_model' type='text' size=8 value=$row[1]></td>
             <td class='stockfield' align=left>$row[2]</td>\n
             <td><INPUT name='s_model' type='text' size=8 value=$row[3]></td>
             <td class='stockfield' align=left>$row[4]</td>\n
             <td><INPUT name='p_qty' type='text' size=6 value=$row[5]></td>\n
            <td><INPUT type='Image' name='submit' align='BOTTOM' src='../images/Refresh-icon.png'></form></td></tr>\n";
*/
       ++$column;
      }
  }

echo "</TABLE>
  </td></tr></table>";
echo "</body></html>";

mysql_free_result_all;
mysql_close();
?>
