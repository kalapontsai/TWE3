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
if (isset($_POST['action']) && ($_POST['action'] == 'sort')) {
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
echo "<tr><td colspan=2>";
include('stk_cate.inc');
echo "</td></tr>";

$caption = "<tr class='stockcaption' align='center'><td>型號</td><td>名稱</td><td>數量</td><td>狀態</td></tr>\n";

echo "<tr><td valign='top'><table width=640 cellspacing='5' cellpadding='5' frame='border' rules='rows'>" . $caption;

$column = 1;
$css_id = 'stockfield';

if ($result)
  {
    while ($row = mysql_fetch_array($result,MYSQL_NUM))
      {
       if ( ($column%20) == 0 ) {echo $caption;}
       echo "<tr class='$css_id' align='center' onclick='updateprod(\"$row[0]\",\"$row[1]\",\"$row[2]\",\"$row[3]\");'>\n
             <td>$row[1]</td><td>$row[2]</td><td><div id='id$row[0]'>$row[3]</div></td>\n";
			if (($row[3] < 1)&&($row[4]=='0')) { echo "<td><div id='status$row[0]'><font color = 'red'><b>$row[4]</b></font></div></td></tr>\n";}
			  else { echo "<td><div id='status$row[0]'>$row[4]</div></td></tr>\n";}
       ++$column;
       if ($css_id == 'stockfield') { $css_id = 'stockfield1'; } else { $css_id = 'stockfield'; }
      }
  }

echo "</TABLE></td>\n";

//  右邊關聯表 Begin
echo "<td valign='top'><table width=320 border='0' cellspacing='5' cellpadding='5'>";

// adjust function
echo "<tr><td align='right'>
      <form name='adj'>新增數量:<INPUT name='adj_qty' type='text' size='6' value='0' disabled=true>
      <INPUT name='btn' TYPE='button' VALUE='計算' onclick='calc()' disabled=true></form></td></tr>\n";
      
//products
echo "<tr><td>Product_id : <div id='pid'>0</div></td></tr>
      <tr><td>型號 : <div id='pmodel'>0</div></td></tr>
      <tr><td>名稱 : <div id='pdesc'>0</div></td></tr>
      <tr><td>上架數量 : <div id='pqty'>0</div></td></tr>\n";

echo "<tr><td><hr></td></tr>\n";      

//relations
echo "<tr><td><div id='relate'>關聯內容</div></td></tr></table>\n";

echo "</td></tr></table>
      </body></html>";

mysql_free_result_all;
mysql_close($dbc);
?>

