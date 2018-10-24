<?php
/*  ---------------------------------------
  stk_v2a.php : TWE305 product stock management for ELHOMEO.COM
  v1.0 20150314
  v1.1 20150408 add check box to decide whether update product status
  
  --------------------------------- */

  require('../includes/configure.php');
  $dbc=@mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die('could not connect to MySQL:'.mysql_error());
  @mysql_select_db(DB_DATABASE) or die('Could not select database:'.mysql_error());
  @mysql_query("SET NAMES 'UTF8'", $dbc); //指定提取資料的校對字元表
  @mysql_query("set character set UTF8",$dbc);//指定提取資料的校對字元表
  if (isset($_GET['action']) && ($_GET['action'] == 'update')) {
    $p_id = $_GET['id'];
    $p_qty = $_GET['qty'];
    $p_pos = $_GET['pos'];
    $p_status = $_GET['status'];

    $qry_update = 'UPDATE `products` SET `products_quantity` = "' . $p_qty . '", `products_status` = "' . $p_status . '" where `products_id` = "' . $p_id . '"';
    $qry_exec=mysql_query($qry_update) or die('Query failed: '.mysql_error());

      if (mysql_error()){
        $arr = mysql_error();   
        } else {
          $arr = $p_status;
          if ( $p_status == 0 ) { $arr = "<font color = 'red'><b>" . $p_status . "</b></font>"; }
        }           

    echo $arr;
  }
?>