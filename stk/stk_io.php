<?php
  require('../includes/configure.php');
  include(DIR_WS_INCLUDES.'stk_header.inc');

$p_time = date("Y-m-d H:i:s");

if (isset($_POST['action']) && ($_POST['action'] == 'add')) {
  $add_model = strtoupper($_POST['add_model']);
  $add_desc = $_POST['add_desc'];
  $add_qty = $_POST['add_qty'];
  $add_u_cost = $_POST['add_u_cost'];
  $add_mfg = $_POST['add_mfg'];

  $qry_chk = "SELECT count(*) as total FROM `stock` WHERE `stocks_model` = '".$add_model."'";
  $qry_chk_exec=mysql_query($qry_chk) or die('Query failed: '.mysql_error());
  $row = mysql_fetch_array($qry_chk_exec,MYSQL_NUM);
  if ($row[0] > 0) { 
    $err_msg = "庫存產品名稱重複<br>$add_model"; }
    else {
      $qry_add = "INSERT INTO `stock` (`stocks_id` ,`stocks_quantity` ,`stocks_model` ,`stocks_description` ,`stocks_unit_cost` ,`stocks_last_modified` ,`manufacturers_id`)
                 VALUES (NULL ,  '$add_qty',  '$add_model',  '$add_desc',  '$add_u_cost', '$p_time' ,  '$add_mfg')";
      $qry_exec=mysql_query($qry_add) or die('Query failed: '.mysql_error());
      }
}

if (isset($_POST['action']) && ($_POST['action'] == 'delete')) {
  $p_id = $_POST['p_id'];
  $qry_del = "DELETE FROM `stock` WHERE `stock`.`stocks_id` = '" . $p_id . "' LIMIT 1";
  $qry_exec=mysql_query($qry_del) or die('Query failed: '.mysql_error());
}

if (isset($_POST['action']) && ($_POST['action'] == 'update')) {
//  $p_model = strtoupper($_POST['p_model']);
  $p_desc = $_POST['p_desc'];
  $p_id = $_POST['p_id'];
  $p_qty = $_POST['p_qty'];
  $p_u_cost = $_POST['p_u_cost'];
  $qry_update = 'UPDATE `stock` SET `stocks_description` = "' . $p_desc . '",`stocks_quantity` = "' . $p_qty . '",`stocks_unit_cost` = "' . $p_u_cost . '", stocks_last_modified = "' . $p_time . '" where `stocks_id` = "' . $p_id . '"';
  $qry_exec=mysql_query($qry_update) or die('Query failed: '.mysql_error());
}

if  ($err_msg) { 
  $msg = "<font color=red>".  $err_msg . "</font>";
  } ELSE {
  $msg = "&nbsp;";
  }

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

        $query= 'SELECT stock.stocks_id,
                        stock.stocks_model,
                        stock.stocks_description,
                        stock.stocks_quantity,
                        stock.stocks_unit_cost,
                        stock.stocks_last_modified,
                        manufacturers.manufacturers_name
                        FROM stock left join manufacturers
                        on stock.manufacturers_id = manufacturers.manufacturers_id
                        WHERE stock.stocks_model like "' . $query_str . '%"
                        ORDER BY stock.stocks_model';
$result=mysql_query($query) or die('Query failed: '.mysql_error());

$qry_mfg= 'SELECT DISTINCT manufacturers_id, manufacturers_name FROM manufacturers ORDER BY manufacturers_id';
$rs_mfg=@mysql_query($qry_mfg);

/*  分頁功能*/
echo "<tr><td>";
include('stk_cate.inc');
echo "</td></tr>";

$caption = "<tr class='stockcaption' align='center'><td>&nbsp;</td>\n
<td>型號</td><td>名稱</td><td>重量</td>
<td>單位價格</td><td>Last Modify</td><td>廠商</td>
<td>&nbsp;</td>
</tr>\n";

echo "<tr><td><table width='100%' cellspacing='5' cellpadding='5' frame='border' rules='rows'>\n" . $caption;

       echo "
             <tr class='stock'field1' align='center'>\n
             <td><form name='add' method='POST' action='$filename'>\n
             <INPUT TYPE='hidden' name='action' VALUE='add'>
             <INPUT TYPE='hidden' name='cate' VALUE=$cate> $msg</td>\n
             <td><INPUT name='add_model' type='text' size=8></td>\n
             <td><INPUT name='add_desc' type='text' size=30></td>\n
             <td><INPUT name='add_qty' type='text' size=6></td>\n
             <td><INPUT name='add_u_cost' type='text' size=6 value='0.00'></td>\n
             <td>$p_time</td>\n
             <td><SELECT name='add_mfg'><OPTION value='0'>NA";
        while ($row = mysql_fetch_array($rs_mfg,MYSQL_NUM)) 
        {echo '<OPTION value="'.$row[0].'">'.$row[1];}             
             
       echo "</td>
             <td><INPUT TYPE='submit' VALUE='New'></form></td>
             </tr>\n";

$column = 1;
$css_id = 'stockfield';
if ($result)
  {
    while ($row = mysql_fetch_array($result,MYSQL_NUM))
      {
       if ( ($column%20) == 0 ) {echo $caption;}
       echo "<tr class='$css_id' align='center'><td>
             <form name='del$column' method='POST' action='$filename'>
             <INPUT TYPE='hidden' name='action' VALUE='delete'>
             <INPUT TYPE='hidden' name='p_id' VALUE=$row[0]>
             <INPUT TYPE='hidden' name='cate' VALUE=$cate>
             <INPUT TYPE='hidden' name='position' VALUE=$column>
             <INPUT type='Image' name='submit' align='BOTTOM' src='../images/trash-icon.png'></form></td>\n";
       echo "<td>
             <form name='a$column' method='POST' action='$filename'>
             <INPUT TYPE='hidden' name='action' VALUE='update'>
             <INPUT TYPE='hidden' name='p_id' VALUE='$row[0]'>
             <INPUT TYPE='hidden' name='cate' VALUE='$cate'>
             <INPUT TYPE='hidden' name='position' VALUE='$column'>$row[1]</td>\n
            <td><INPUT name='p_desc' type='text' size=30 value='$row[2]'></td>\n
            <td><INPUT name='p_qty' type='text' size=6 value='$row[3]'></td>\n
            <td><INPUT name='p_u_cost' type='text' size=6 value='$row[4]'></td>\n
            <td>$row[5]</td><td>$row[6]</td>\n
            <td><INPUT type='Image' name='submit' align='BOTTOM' src='../images/Refresh-icon.png'></form></td></tr>\n";
       ++$column;
       if ($css_id == 'stockfield') { $css_id = 'stockfield1'; } else { $css_id = 'stockfield'; }  // 更改背景顏色
      }
  }

echo "</TABLE>
  </td></tr></table>";
echo "</body></html>";

mysql_free_result_all;
mysql_close();
?>
