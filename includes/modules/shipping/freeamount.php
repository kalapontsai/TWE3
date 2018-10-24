<?php
/* -----------------------------------------------------------------------------------------
   $Id: freeamount.php,v 1.1 2003/09/06 22:13:54 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(freeamount.php,v 1.01 2002/01/24); www.oscommerce.com 
   (c) 2003	 nextcommerce (freeamount.php,v 1.12 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/


  class freeamount {
    var $code, $title, $description, $icon, $enabled;


    function freeamount() {
      $this->code = 'freeamount';
      $this->title = MODULE_SHIPPING_FREECOUNT_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FREECOUNT_TEXT_DESCRIPTION;
      $this->icon ='';   // change $this->icon =  DIR_WS_ICONS . 'shipping_ups.gif'; to some freeshipping icon
      $this->sort_order = MODULE_SHIPPING_FREECOUNT_SORT_ORDER;
      $this->enabled = MODULE_SHIPPING_FREECOUNT_STATUS;
    }

    function quote($method = '') {
	
	  if (( $_SESSION['cart']->show_total() < MODULE_SHIPPING_FREECOUNT_AMOUNT ) && MODULE_SHIPPING_FREECOUNT_DISPLAY == 'False')
	  return;

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_FREECOUNT_TEXT_TITLE);

      if ( $_SESSION['cart']->show_total() < MODULE_SHIPPING_FREECOUNT_AMOUNT )
        $this->quotes['error'] = MODULE_SHIPPING_FREECOUNT_TEXT_WAY;
      else
 	$this->quotes['methods'] = array(array('id'    => $this->code,
                                               'title' => MODULE_SHIPPING_FREECOUNT_TEXT_WAY,
                                               'cost'  => 0));

      if (twe_not_null($this->icon)) $this->quotes['icon'] = twe_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
	global $db;
	  $check = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FREECOUNT_STATUS'");
      $check =$check->RecordCount();

      return $check;

    }

    function install() {
	global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_SHIPPING_FREECOUNT_STATUS', 'True', '6', '7', 'twe_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_FREEAMOUNT_ALLOWED', '', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_SHIPPING_FREECOUNT_DISPLAY', 'True', '6', '7', 'twe_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_FREECOUNT_AMOUNT', '50.00', '6', '8', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_FREECOUNT_SORT_ORDER', '0', '6', '4', now())");
    }

    function remove() {
	global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_FREECOUNT_STATUS','MODULE_SHIPPING_FREEAMOUNT_ALLOWED', 'MODULE_SHIPPING_FREECOUNT_DISPLAY', 'MODULE_SHIPPING_FREECOUNT_AMOUNT','MODULE_SHIPPING_FREECOUNT_SORT_ORDER');
    }
  }
?>