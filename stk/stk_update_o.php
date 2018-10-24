<?php
/* -----------------------------------------------------------------------------------------
   $Id: stk_update.php,v 1.0 2011/03/04 ELHOMEO.com 
   manager twe database table 'products' by a dedicated program  
   -----------------------------------------------------------------------------------------
   v0.1  sorting all the products model name to update the q'ty and status
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
require('../includes/configure.php');
  include(DIR_WS_INCLUDES.'stk_header.inc');

if (isset($_POST['action']) && ($_POST['action'] == 'update')) {
  $p_id = ($_POST['p_id']);
  $p_qty = ($_POST['p_qty']);
  $p_status = ($_POST['status']);  
  if (($p_qty <0) || (!is_numeric($p_qty))) { 
      $err_msg = "數量錯誤";
      } ELSE {
	 if (($p_status == '0') && ($p_qty > 0)) $p_status = '1';
     $qry_update = 'UPDATE `products` SET `products_quantity` = "' . $p_qty . '", `products_status` = "' . $p_status . '" where `products_id` = "' . $p_id . '"';
     $qry_exec=mysql_query($qry_update) or die('Query failed: '.mysql_error());
     }
}

if  ($err_msg) { 
  $msg = "<font color=red>".  $err_msg . "</font>";
  } ELSE {
  $msg = "&nbsp;";
  }

if (isset($_POST['action']) && (($_POST['action'] == 'sort') || ($_POST['action'] == 'update'))) {
  switch ($_POST['cate']){
	case "single":
        $cate = 'single';
	    $query_str = 's';
		break;
	case "chemical":
        $cate = 'chemical';
	    $query_str = 'c';
		break;
	case "assemble":
        $cate = 'assemble';
	    $query_str = 'A';
		break;
	case "oint":
	    $cate = 'oint';
        $query_str = 'o';
		break;
	case "b":
	    $cate = 'b';
        $query_str = 'b';
		break;
	case "m":
	    $cate = 'm';
        $query_str = 'm';
		break;
  }
} else {
	    $cate = 'single';
        $query_str = 's';
		}

$query= 'SELECT p.products_id as products_id,
                p.products_model as products_model,
                pd.products_name as products_name,
                p.products_quantity as products_quantity,
                p.products_status as products_status
                FROM `products` as p,
                products_description as pd
                WHERE products_model like "' . $query_str . '%"
                and p.products_id = pd.products_id
                ORDER BY `products_model`';  
$result=mysql_query($query) or die('Query failed: '.mysql_error());

/*  debug */
//    if(count($_POST)>0)
//        foreach($_POST as $k=>$v)
//            echo $k.'='.$v.'<br>';

/*  分頁功能*/
echo "<tr><td>";
include('stk_cate.inc');
echo "</td></tr>";

$caption = "<tr class='stockcaption' align='center'><td>型號</td><td>名稱</td><td>數量</td><td>狀態</td><td>$msg</td></tr>";

echo "<tr><td><table width='100%' cellspacing='5' cellpadding='5' frame='border' rules='rows'>" . $caption;

$column = 1;
$css_id = 'stockfield';

if ($result)
  {
    while ($row = mysql_fetch_array($result,MYSQL_NUM))
      {
       if ( ($column%20) == 0 ) {echo "<tr class='stockcaption' align='center'><td>型號</td><td>名稱</td><td>數量</td><td>&nbsp;</td></tr>\n";}
       echo "<tr class='$css_id' align='center'>\n
             <td>$row[1]</td><td>$row[2]</td>\n
             <td><form name='add$column' method='POST' target=_new action='stk_update_content.php'>
             <INPUT TYPE='hidden' name='p_id' VALUE='$row[0]'>
             <INPUT TYPE='hidden' name='p_model' VALUE='$row[1]'>
             <INPUT TYPE='hidden' name='pd_name' VALUE='$row[2]'>
             <INPUT TYPE='hidden' name='p_qty' VALUE='$row[3]'>
             <INPUT type='Image' name='submit' align='BOTTOM' src='/images/Add-icon.png'></form>
             <form name='a$column' method='POST' action='$filename'>
             <INPUT TYPE='hidden' name='action' VALUE='update'>
             <INPUT TYPE='hidden' name='p_id' VALUE='$row[0]'>
             <INPUT TYPE='hidden' name='cate' VALUE='$cate'>
             <INPUT TYPE='hidden' name='status' VALUE='$row[4]'>
             <INPUT TYPE='hidden' name='position' VALUE='$column'>
             <INPUT name='p_qty' type='text' size=6 value='$row[3]'></td>\n";
			if (($row[3] < 1)&&($row[4]=='0')) { echo "<td><font color = 'red'><b>$row[4]</b></font></td>\n";}
			  else { echo "<td>$row[4]</td>\n";}
			echo "<td><INPUT type='Image' name='submit' align='BOTTOM' src='../images/Refresh-icon.png'></form></td></tr>\n";
       ++$column;
       if ($css_id == 'stockfield') { $css_id = 'stockfield1'; } else { $css_id = 'stockfield'; }
      }
  }

echo "</TABLE>
  </td></tr></table>";
echo "</body></html>";

mysql_free_result_all;
mysql_close();
?>
