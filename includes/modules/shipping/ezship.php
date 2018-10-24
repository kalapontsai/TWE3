<?php
/*
  http://shop.elhomeo.com/ 
  modify for Twe3.05, 2015/03/24
  Kadela Tsai 

  $Id: ezship.php, v1.0, 2006/11/23$
  Author: Light Tseng
  http://www.ezeshop.com.tw/
*/

  class ezship {
    var $code, $title, $description, $icon, $enabled;

    function ezship() {
      global $order;

      $this->code = 'ezship';
      $this->title = MODULE_SHIPPING_EZSHIP_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_EZSHIP_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_EZSHIP_SORT_ORDER;
      $this->icon = DIR_WS_ICONS . 'ezship.gif';
      $this->tax_class = MODULE_SHIPPING_EZSHIP_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_EZSHIP_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_EZSHIP_ZONE > 0) ) {
        $check_flag = false;
        $check_query = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_EZSHIP_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while (!$check->EOF) {
          if ($check->fields['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check->fields['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
          $check->MoveNext();
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function quote($method = '') {
      global $order, $PHP_SELF, $HTTP_POST_VARS;

      $add_str='';
      if(basename($PHP_SELF)==FILENAME_CHECKOUT_SHIPPING && ($HTTP_POST_VARS['action'] != 'process') ) {
        $add_str='<br>'.MODULE_SHIPPING_EZSHIP_TEXT_NOTICE;
      }
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_EZSHIP_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_EZSHIP_TEXT_WAY.$add_str,
                                                     'cost' => MODULE_SHIPPING_EZSHIP_COST)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = twe_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (twe_not_null($this->icon)) $this->quotes['icon'] = twe_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
    	global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_EZSHIP_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

/*    function author() {
      return '<div align=right><a href="http://www.ezeshop.com.tw/ezeshop/product_info.php?products_id=81" target="_new"><img align=top border=0 alt="EZeShop" src="http://www.ezeshop.com.tw/ezeshop/images/bloglogo1.gif?ezship"></a></div>';
    }  
*/
    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_SHIPPING_EZSHIP_STATUS', 'True', '6', '0', 'twe_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_EZSHIP_COST', '50', '6', '0', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_SHIPPING_EZSHIP_TAX_CLASS', '0', '6', '0', 'twe_get_tax_class_title', 'twe_cfg_pull_down_tax_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_SHIPPING_EZSHIP_ZONE', '0', '6', '0', 'twe_get_zone_class_title', 'twe_cfg_pull_down_zone_classes(', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_EZSHIP_SORT_ORDER', '0', '6', '0', now())");
    }

    function remove() {
	    global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_EZSHIP_STATUS', 'MODULE_SHIPPING_EZSHIP_COST', 'MODULE_SHIPPING_EZSHIP_TAX_CLASS', 'MODULE_SHIPPING_EZSHIP_ZONE', 'MODULE_SHIPPING_EZSHIP_SORT_ORDER');
    }
  }
?>
