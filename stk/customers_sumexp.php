<?php
  require('../includes/configure.php');
$dbc=@mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die('could not connect to MySQL:'.mysql_error());
@mysql_select_db(DB_DATABASE) or die('Could not select database:'.mysql_error());

$customers_query_raw = "SELECT c.customers_email_address, c.customers_firstname, sum( ot.value ) AS sumexp
FROM orders o, customers c, orders_total ot
WHERE c.customers_id = o.customers_id
AND o.orders_id = ot.orders_id
AND ot.class = 'ot_total'
GROUP BY c.customers_id
ORDER BY c.customers_email_address";

$customers=mysql_query($customers_query_raw) or die('Query failed: '.mysql_error());


?>
<!DOCTYPE html PUBLIC'-//W3C//DTD HTML 4.0//EN' 'http://www.w3.org/TR/REC-html/strict.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<title>統計</title>
<link href='/templates/twe/stylesheet.css' rel='stylesheet' type='text/css' />
<link href=$favicon rel='shortcut icon'/>
</head>
<body>
<table width=1024 border='0' cellspacing='0' cellpadding='0' align='center'>
<?php
$caption = "<tr class='stockcaption' align='center'><td>id</td><td>firstname</td><td>ot_total</td></tr>\n";

echo "<tr><td><table width='100%' cellspacing='5' cellpadding='5' frame='border' rules='rows'>\n" . $caption;

$column = 1;
$css_id = 'stockfield1';
    while ($row = mysql_fetch_array($customers,MYSQL_NUM))
      {
       if ($css_id == 'stockfield') { $css_id = 'stockfield1'; } else { $css_id = 'stockfield'; }
       echo "<tr class='$css_id'>
             <td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>\n";
       $tmp = $row[1];
       ++$column;
      }
echo "</TABLE>
  </td></tr></table>";
echo "</body></html>";

mysql_free_result_all;
mysql_close();
?>
