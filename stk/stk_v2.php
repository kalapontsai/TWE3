<?php
/*  ---------------------------------------
  stk_v2.php : TWE305 product stock management for ELHOMEO.COM
  v1.0 20150314
  v1.1 20150408 add check box to decide whether update product status
  
  --------------------------------- */

  require('../includes/configure.php');
  $dbc=@mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die('could not connect to MySQL:'.mysql_error());
  @mysql_select_db(DB_DATABASE) or die('Could not select database:'.mysql_error());
  @mysql_query("SET NAMES 'UTF8'", $dbc); //指定提取資料的校對字元表
  @mysql_query("set character set UTF8",$dbc);//指定提取資料的校對字元表

  /*
  $path = explode('/',$_SERVER['PHP_SELF']);
  $filename = array_pop($path);
  $func_list = "庫存 更新(Ajax版製作中)";
  $favicon = "/images/green-toy.ico";

  */
  echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
  <html xmlns='http://www.w3.org/1999/xhtml' dir='LTR' lang='utf-8'>
  <head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
  <title>ELHOMEO-SHOP 庫存更新(Ajax版)</title>
  <link href='/templates/twe/stylesheet.css' rel='stylesheet' type='text/css' />
  <link href='/images/green-toy.ico' rel='shortcut icon'/>
  <script src='stk_v2.js'></script>
  </head>
  <body>
  <table width=1024 border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr><td>
  <table width='60%' border='0' cellspacing='5' cellpadding='5' align='center'>
    <tr>
      <td width='50%'><H2><a href='stk_v2.php'>庫存 更新(新版)</a></H2></td>
      <td><input type='checkbox' name='status_switch' id='status_switch' value='false'>&nbsp;重新上架</td>
    </tr>
  </table>
  </td></tr>";
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
      }   //EOF Switch
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

  $caption = "<tr class='stockcaption' align='center'><td>型號</td><td>名稱</td><td>數量</td><td>狀態</td></tr>";

// 產品清單
  echo "<tr><td><table width='100%' cellspacing='5' cellpadding='5' frame='border' rules='rows'>" . $caption;
  $column = 1;
  $css_id = 'stockfield';

  if ($result)  {
    while ($row = mysql_fetch_array($result,MYSQL_NUM))
      {
       if ( ($column%20) == 0 ) {echo "<tr class='stockcaption' align='center'><td>型號</td><td>名稱</td><td>數量</td><td>狀態</td></tr>\n";}
       $pos_index = "pos".$column;
       echo "<tr class='$css_id' align='center'>\n
             <td>$row[1]</td><td>$row[2]</td>\n
             <td><INPUT id='$column 'type='text' size=6 VALUE='$row[3]' onfocus='saveq(this.value)' onblur='upt(\"$row[0]\",this.value,\"$pos_index\",\"$row[4]\")'></td>\n";

			 if (($row[3] < 1)&&($row[4]=='0')) { echo "<td><span id='$pos_index'><font color = 'red'><b>$row[4]</b></font></span></td>\n";}
			   else { echo "<td><span id='$pos_index'>$row[4]</span></td>\n";}
		   echo "</tr>\n";

       ++$column;
       if ($css_id == 'stockfield') { $css_id = 'stockfield1'; } else { $css_id = 'stockfield'; }
      }   //EOF While
  }    // EOF If
  echo "</TABLE>";  // EOF 產品清單
  
  echo "</td></tr></table>
        </body></html>";

mysql_free_result_all;
mysql_close();   
?>