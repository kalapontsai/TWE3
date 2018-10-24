<?php
/* -----------------------------------------------------------------------------------------
   $Id: also_purchased_products.php,v 1.11 2004/03/16 15:01:16 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(also_purchased_products.php,v 1.21 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (also_purchased_products.php,v 1.9 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

$module_smarty= new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  // include needed files
  require_once(DIR_FS_INC . 'twe_get_products_name.inc.php');
  require_once(DIR_FS_INC .  'twe_get_short_description.inc.php');
  require_once(DIR_FS_INC . 'twe_products_data_array.inc.php');
  require_once(DIR_FS_INC . 'twe_array_merge.inc.php');

  if (isset($_GET['products_id'])) {

    //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
    $orders_query = "select p.products_fsk18, p.products_id, p.products_quantity, p.products_image, p.products_price,
                            p.products_discount_allowed, p.products_tax_class_id
                         from " .TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " .TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p 
                         where opa.products_id = '" . (int)$_GET['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$_GET['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id ".$fsk_lock." and p.products_status = '1' and p.products_quantity > 0 ".$group_check." group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED;
   
    $orders = $db->Execute($orders_query);
	$num_products_ordered = $orders->RecordCount();
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
      $row = 0;
      $module_content = array();
	  $file_merge = array();
      while (!$orders->EOF) {
        $orders->fields['products_name'] = twe_get_products_name($orders->fields['products_id']);
        $orders->fields['products_short_description'] = twe_get_short_description($orders->fields['products_id']);
    $file_merge[] = array(array('id' => $orders->fields['products_short_description'], 'text' => $orders->fields['products_short_description']), array('id' => $orders->fields['products_name'], 'text' => $orders->fields['products_name']));
	$module_content[] = twe_products_data_array(twe_array_merge($orders->fields,$file_merge));
   $orders->MoveNext();	
   }

  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('module_content',$module_content);
  // set cache ID
  $module_smarty->caching = 0;
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/also_purchased.html');
  $info_smarty->assign('MODULE_also_purchased',$module);
  
    }
  }
?>