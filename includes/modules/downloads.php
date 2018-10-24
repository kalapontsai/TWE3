<?php
/* -----------------------------------------------------------------------------------------
   $Id: downloads.php,v 1.3 2004/05/31 10:15:35 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(downloads.php,v 1.2 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (downloads.php,v 1.6 2003/08/13); www.nextcommerce.org
   (c) 2003-2004 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  // ibclude the needed functions
  if(!function_exists('twe_date_long')) {
      require_once(DIR_FS_INC . 'twe_date_long.inc.php');
  }

  $module_smarty = new Smarty;

  if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
    // Get last order id for checkout_success
    $orders = $db->Execute("select orders_id, orders_status from " . TABLE_ORDERS . " where customers_id = '" . $_SESSION['customer_id'] . "' order by orders_id desc limit 1");
    $last_order = $orders->fields['orders_id'];
    $order_status = $orders->fields['orders_status'];
  } else {
    $last_order = $_GET['order_id'];
    $orders = $db->Execute("SELECT orders_status FROM " . TABLE_ORDERS . " WHERE orders_id = '" . $last_order . "'");
    $order_status = $orders->fields['orders_status'];
  }
  if($order_status < DOWNLOAD_MIN_ORDERS_STATUS) {
      $module_smarty->assign('dl_prevented', 'true');
  }
  // Now get all downloadable products in that order
  $downloads = $db->Execute("select date_format(o.date_purchased, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, op.products_name, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd where o.customers_id = '" . $_SESSION['customer_id'] . "' and o.orders_id = '" . $last_order . "' and o.orders_id = op.orders_id and op.orders_products_id = opd.orders_products_id and opd.orders_products_filename != ''");
    if ($downloads->RecordCount() > 0) { 
      $jj = 0;
//<!-- list of products -->
    while (!$downloads->EOF) {
// MySQL 3.22 does not have INTERVAL
      list($dt_year, $dt_month, $dt_day) = explode('-', $downloads->fields['date_purchased_day']);
      $download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads->fields['download_maxdays'], $dt_year);
      $download_expiry = date('Y-m-d H:i:s', $download_timestamp);
//<!-- left box -->
      // The link will appear only if:
      // - Download remaining count is > 0, AND
      // - The file is present in the DOWNLOAD directory, AND EITHER
      // - No expiry date is enforced (maxdays == 0), OR
      // - The expiry date is not reached
      if ( ($downloads->fields['download_count'] > 0) && (file_exists(DIR_FS_DOWNLOAD . $downloads->fields['orders_products_filename'])) && ( ($downloads->fields['download_maxdays'] == 0) || ($download_timestamp > time())) && ($order_status >= DOWNLOAD_MIN_ORDERS_STATUS) ) {
          $dl[$jj]['download_link'] = '<a href="' . twe_href_link(FILENAME_DOWNLOAD, 'order=' . $last_order . '&Did=' . $downloads->fields['orders_products_download_id']) . '">' . $downloads->fields['products_name'] . '</a>';
          $dl[$jj]['pic_link'] = twe_href_link(FILENAME_DOWNLOAD, 'order=' . $last_order . '&Did=' . $downloads->fields['orders_products_download_id']);
      } else {
          $dl[$jj]['download_link'] = $downloads->fields['products_name'];
      }
//<!-- right box -->
      $dl[$jj]['date'] = twe_date_long($download_expiry);
      $dl[$jj]['count'] = $downloads->fields['download_count'];
      $jj++;
	$downloads->MoveNext();  
    }
  }
  $module_smarty->assign('dl', $dl);
  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  $module_smarty->caching = 0;
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/downloads.html');
  $smarty->assign('downloads_content', $module);
  ?>