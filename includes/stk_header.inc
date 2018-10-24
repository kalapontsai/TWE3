<?php
//Make the connection and then select the database.
$dbc=@mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die('could not connect to MySQL:'.mysql_error());
@mysql_select_db(DB_DATABASE) or die('Could not select database:'.mysql_error());
@mysql_query("SET NAMES 'UTF8'", $dbc); //指定提取資料的校對字元表
@mysql_query("set character set UTF8",$dbc);//指定提取資料的校對字元表
date_default_timezone_set("Asia/Taipei");

$path = explode('/',$_SERVER['PHP_SELF']);
$filename = array_pop($path);

switch ($filename){
case "stk_update.php":
     $func_list = "庫存 更新";
     $favicon = "/images/green-toy.ico";
     break;
case "stk_update_o.php":
     $func_list = "庫存 更新(m)";
     $favicon = "/images/green-toy.ico";
     break;
case "stk_io.php":
     $func_list = "庫存 出入庫";
     $favicon = "/images/green-toy.ico";
     break;
case "stk_relate.php":
     $func_list = "關聯表";
     $favicon = "/images/green-toy.ico";
     break;
default:
     $func_list ="庫存 未預期錯誤";
     $favicon = "/images/green-toy.ico";
     }
if (isset($_POST['position'])) {
    $position = 'a' . $_POST['position'];
  } ELSE {
  $position = 'a1'; 
  }

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>ELHOMEO- SHOP $func_list</title>
<link href='/templates/twe/stylesheet.css' rel='stylesheet' type='text/css' />
<link href=$favicon rel='shortcut icon'/>\n";

// 放入 Ajax script code
if ($filename=="stk_update.php") echo "<script src='update_content.js'></script>\n";

if ($filename=="stk_update_v2.php") echo "<script src='stk_update_v2.js'></script>\n";

echo "</head>
<body onLoad='document.$position.p_qty.focus()'>
<table width=1024 border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr><td colspan=2>
  <table width='90%' border='0' cellspacing='5' cellpadding='5' align='center'>
    <tr>
      <td><a href='stk_update.php'>庫存 更新</a></td>
      <td><a href='stk_update_o.php'>庫存 更新(m)</a></td>
	    <td><a href='stk_io.php'>庫存 出入庫</a></td>
      <td><a href='stk_relate.php'>關 聯 表</a></td>
    </tr>
    <tr>
	  <td colspan=3><h1><span>$func_list</span></h1></td>
    </tr>
  </table>
  </td></tr>";
?>
