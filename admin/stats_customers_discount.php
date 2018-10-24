<?php
/* --------------------------------------------------------------
   $Id: stats_customers_discount.php 2011/05/18 ELHOMEO.com
   V1.01 modify the period to 2014.3 to 2015.2.14
   v1.02 modify order acount for one year only - 20150414 
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(orders.php,v 1.109 2003/05/28); www.oscommerce.com 
   (c) 2003	 nextcommerce (orders.php,v 1.19 2003/08/24); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   (c) 2003	 twe-commerce  www.oldpa.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php require('includes/includes.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_statistic.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr>
    <td class="main" valign="top">TWE Statistics</td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="right">Customer ID</td>
                <td class="dataTableHeadingContent" align="right">Email Account</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_PURCHASED; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right">訂單次數</td>
                <td class="dataTableHeadingContent" align="right">現有禮卷</td>
                <td class="dataTableHeadingContent" align="right">次年禮卷</td>
              </tr>
<?php
  $p_since = "2017-04-01 00:00:00" ;  //年度期間調整這兩個變數即可
  $p_end = "2018-03-31 23:59:59";
  if ($_GET['page'] > 1) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $customers_query_numrows_query = "select customers_id from " . TABLE_ORDERS . " group by customers_id";
  $customers_query_numrows = $db->Execute($customers_query_numrows_query);

  $customers_query_raw = "select c.customers_id, c.customers_firstname, c.customers_email_address, sum(op.value) as ordersum from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_TOTAL . " op, " . TABLE_ORDERS . " o where c.customers_id = o.customers_id and o.orders_id = op.orders_id and op.class = 'ot_total' and o.date_purchased > '" . $p_since . "' and o.date_purchased < '" . $p_end . "' group by c.customers_id order by ordersum DESC";
  $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
  
  $customers = $db->Execute($customers_query_raw);
  while (!$customers->EOF) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
    $id = $customers->fields['customers_id'];
    
// count how many order the customer made 20110518*****  

    $order_count_query = "SELECT count(*) as total FROM ". TABLE_ORDERS . " WHERE customers_id = '". $id . "' and date_purchased > '" . $p_since . "' and date_purchased < '" . $p_end . "' LIMIT 0,1";
    $order_count_result = $db->Execute($order_count_query);
    $ocount = $order_count_result->fields['total'] ;
// ****

// Get Coupon value
    $coupon_query = "SELECT amount FROM coupon_gv_customer WHERE customer_id = '". $id ."'";
    $coupon_result = $db->Execute($coupon_query);
    if ($coupon_result->fields['amount'] == null ) {
      $coupon = "N/A";
      } else {
      $coupon = round( $coupon_result->fields['amount'] , 0 );
    }

// calculate gift by next year
    $o_sum = $customers->fields['ordersum'];
    Switch ($o_sum)
        {  case ($o_sum > 60000):
             $gift = 3000;
             break;
           case ($o_sum > 50000):
             $gift = 2500;
             break;
           case ($o_sum > 40000):
             $gift = 2000;
             break;
           case ($o_sum > 30000):
             $gift = 1500;
             break;
           case ($o_sum > 20000):
             $gift = 1000;
             break;
           case ($o_sum > 10000):
             $gift = 500;
             break;
           case ($o_sum > 4000):
             $gift = 200;
             break;
           default:    
             $gift = 50;
             }

?>
              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'" onclick="document.location.href='<?php echo twe_href_link(FILENAME_CUSTOMERS, 'search=' . $customers->fields['customers_firstname'], 'NONSSL'); ?>'">
                <td class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?php echo '<a href="' . twe_href_link(FILENAME_CUSTOMERS, 'search=' . $customers->fields['customers_firstname'], 'NONSSL') . '">' . $customers->fields['customers_firstname'] . ' ' . $customers->fields['customers_lastname'] . '</a>'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $customers->fields['customers_id']; ?>&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo $customers->fields['customers_email_address']; ?>&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format($customers->fields['ordersum']); ?>&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo $ocount; ?></td>
                <td class="dataTableContent" align="right"><?php echo $coupon; ?></td>
                <td class="dataTableContent" align="right"><?php echo $gift; ?></td>
              </tr>
<?php
$customers->MoveNext();
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
