<?php
/* -----------------------------------------------------------------------------------------
   $Id: ot_surcharge.php,v 1.3 2011/04/21 17:54:46 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_loworderfee.php,v 1.11 2003/02/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (ot_loworderfee.php,v 1.7 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  class ot_surcharge {
    var $title, $output;

    function ot_surcharge() {
      $this->code = 'ot_surcharge';
      $this->title = MODULE_PAYMENT_TITLE;
      $this->description = MODULE_PAYMENT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_STATUS;
      $this->sort_order = MODULE_PAYMENT_SORT_ORDER;
      $this->include_shipping = MODULE_PAYMENT_INC_SHIPPING;
      $this->include_tax = MODULE_PAYMENT_INC_TAX;
      $this->percentage = MODULE_PAYMENT_PERCENTAGE;
      $this->minimum = MODULE_PAYMENT_MINIMUM;
      $this->calculate_tax = MODULE_PAYMENT_CALC_TAX;
      $this->output = array();
    }

    function process() {
     global $order, $currencies;

      $od_amount = $this->calculate_fee($this->get_order_total());
      if ($od_amount>0) {
      $this->addition = $od_amount;
      $this->output[] = array('title' => $this->title . ':',
                              'text' => '<b>' . $currencies->format($od_amount, true, $order->info['currency'], $order->info['currency_value']) . '</b>',
                              'value' => $od_amount);
    $order->info['total'] = $order->info['total'] + $od_amount;  
}
    }
    

  function calculate_fee($amount) {
    global $order;
    $od_amount=0;
    $od_pc = $this->percentage; //this is percentage plus the base fee
    $do = false;
    if ($amount > $this->minimum) {
    $table = explode("[,]" , MODULE_PAYMENT_TYPE);
    for ($i = 0; $i < count($table); $i++) {
          if ($_SESSION['payment'] == $table[$i]) $do = true;
        }
    if ($do) {
// Calculate tax reduction if necessary
    if($this->calculate_tax == 'true') {
// Calculate main tax reduction
      $tod_amount = round($order->info['tax']*10)/10*$od_pc/100;
      $order->info['tax'] = $order->info['tax'] + $tod_amount;
// Calculate tax group deductions
      reset($order->info['tax_groups']);
      while (list($key, $value) = each($order->info['tax_groups'])) {
        $god_amount = round($value*10)/10*$od_pc/100;
        $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] + $god_amount;
      }  
    }
    $od_amount = $od_pc;
    
    }
    }
    return $od_amount;
  }

   
  function get_order_total() {
    global  $order, $db;
	
    $order_total = $order->info['total'];
// Check if gift voucher is in cart and adjust total
    $products = $_SESSION['cart']->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $t_prid = twe_get_prid($products[$i]['id']);
      $gv_result = $db->Execute("select products_price, products_tax_class_id, products_model from " . TABLE_PRODUCTS . " where products_id = '" . $t_prid . "'");
      if (preg_match('/^GIFT/', addslashes($gv_result->fields['products_model']))) { 
        $qty = $_SESSION['cart']->get_quantity($t_prid);
        $products_tax = twe_get_tax_rate($gv_result->fields['products_tax_class_id']);
        if ($this->include_tax =='false') {
           $gv_amount = $gv_result->fields['products_price'] * $qty;
        } else {
          $gv_amount = ($gv_result->fields['products_price'] + twe_calculate_tax($gv_result->fields['products_price'],$products_tax)) * $qty;
        }
        $order_total=$order_total - $gv_amount;
      }
    }
    if ($this->include_tax == 'false') $order_total=$order_total-$order->info['tax'];
    if ($this->include_shipping == 'false') $order_total=$order_total-$order->info['shipping_cost'];
    return $order_total;
  }  

    
    function check() {
    global $db;
      if (!isset($this->check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_STATUS'");
        $this->check = $check_query->RecordCount();
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_PAYMENT_STATUS', 'MODULE_PAYMENT_SORT_ORDER','MODULE_PAYMENT_PERCENTAGE','MODULE_PAYMENT_MINIMUM', 'MODULE_PAYMENT_TYPE', 'MODULE_PAYMENT_INC_SHIPPING', 'MODULE_PAYMENT_INC_TAX', 'MODULE_PAYMENT_CALC_TAX');
    }

    function install() {
    global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_STATUS', 'true', '6', '1','twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_SORT_ORDER', '25', '6', '2', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) values ('MODULE_PAYMENT_INC_SHIPPING', 'false', '6', '5', 'twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) values ('MODULE_PAYMENT_INC_TAX', 'false', '6', '6','twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_PERCENTAGE', '30', '6', '7', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function ,date_added) values ('MODULE_PAYMENT_CALC_TAX', 'false', '6', '5','twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_MINIMUM', '', '6', '2', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_TYPE', 'cod','6', '2', now())");
    }

    function remove() {
	global $db;
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }
  }
?>