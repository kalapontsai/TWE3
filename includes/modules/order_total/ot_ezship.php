<?php
/*
  http://shop.elhomeo.com/ 
  modify for Twe3.05, 2015/03/24
  Kadela Tsai 

  $Id: ot_ezship.php, v 1.8, 2007/07/03 $
  Author: Light Tseng
  http://www.ezeshop.com.tw/
*/

  class ot_ezship {
    var $title, $output;

    function ot_ezship() {
      $this->code = 'ot_ezship';
      $this->title = MODULE_ORDER_TOTAL_EZSHIP_TITLE;
      $this->description = MODULE_ORDER_TOTAL_EZSHIP_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_EZSHIP_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_EZSHIP_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies, $shipping, $_GET, $PHP_SELF, $shipping_store;

      list($select_shipping_id)=explode('_',$shipping['id']);
      if($select_shipping_id!='ezship') return;
      $text_str='.';
      $out_str='<br>';
      $out_str.='<script src="http://www.ezeshop.com.tw/sidebar/ezship.php" type="text/javascript"></script>';      
      if(twe_session_is_registered('shipping_store')) {
        $out_str.=$shipping_store['name'].'<br>';
        $out_str.=$shipping_store['tel'].'<br>';
        $out_str.=$shipping_store['add'].'<br>';
        $out_str.='<script type="text/javascript">';
        if(MODULE_ORDER_TOTAL_EZSHIP_SHOW_MAP=='false'){
          $out_str.='show_l(\''.$shipping_store['cate'].'\',\''.$shipping_store['code'].'\',\''.MODULE_ORDER_TOTAL_EZSHIP_TEXT_MAP_LINK.'\');';
        } else {
          $out_str.='show_map(\''.$shipping_store['cate'].'\',\''.$shipping_store['code'].'\');';
        }
        $out_str.='</script>';
        if(basename($PHP_SELF)==FILENAME_CHECKOUT_PROCESS){
          $text_str='<script type="text/javascript">';
          $text_str.='show_q(\''.$shipping_store['snid'].'\',\''.MODULE_ORDER_TOTAL_EZSHIP_TEXT_QUERY.'\');';
          $text_str.='</script>';
        }
      } elseif(strlen($_GET['st_cate'])>0) {
        $shipping_store['name']=(strtoupper(CHARSET)=='UTF-8'?iconv('big5','utf-8',$_GET['st_name']):$_GET['st_name']);
        $shipping_store['add']=(strtoupper(CHARSET)=='UTF-8'?iconv('big5','utf-8',$_GET['st_addr']):$_GET['st_addr']);
        $shipping_store['tel']=$_GET['st_tel'];
        $shipping_store['cate']=$_GET['st_cate'];
        $shipping_store['code']=$_GET['st_code'];
        $shipping_store['snid']=$_GET['sn_id'];
        twe_session_register('shipping_store');
        $out_str.=$shipping_store['name'].'<br>';
        $out_str.=$shipping_store['tel'].'<br>';
        $out_str.=$shipping_store['add'].'<br>';
        if(MODULE_ORDER_TOTAL_EZSHIP_SHOW_MAP=='false'){
          $out_str.='<script type="text/javascript">show_l(\''.$shipping_store['cate'].'\',\''.$shipping_store['code'].'\',\''.MODULE_ORDER_TOTAL_EZSHIP_TEXT_MAP_LINK.'\');</script>';
        } else {
          $out_str.='<script type="text/javascript">show_map(\''.$shipping_store['cate'].'\',\''.$shipping_store['code'].'\');</script>';
        }
      } else {
        $out_str.='
        <script type="text/javascript">
        select_store_str=\''.MODULE_ORDER_TOTAL_EZSHIP_TEXT_SELECT_STORE.'\';
        su_id=\''.MODULE_ORDER_TOTAL_EZSHIP_ACCOUNT.'\';
        order_id=\''.date('mdHis').'\';
        rv_name=\''.urlencode(strtoupper(CHARSET)=='UTF-8'?iconv('utf-8','big5',$order->customer[firstname]):$order->customer[firstname]).'\';
        rv_email=\''.$order->customer[email_address].'\';
        rv_mobil=\''.$order->customer[telephone].'\';
        rturl=\''.urlencode(twe_href_link(FILENAME_CHECKOUT_CONFIRMATION)).'\';
        show_form();
        </script>
        ';
      }
      $this->output[] = array('title' => MODULE_ORDER_TOTAL_EZSHIP_TEXT_CONFIRM.$out_str,
                            'text' => $text_str,
                            'value' => 0);
      if(basename($PHP_SELF)==FILENAME_CHECKOUT_PROCESS){
        if(!twe_session_is_registered('shipping_store')) {
          twe_redirect(twe_href_link(FILENAME_CHECKOUT_CONFIRMATION.'?error_message='.MODULE_ORDER_TOTAL_EZSHIP_TEXT_NO_SELECT, '', 'SSL'));
        }
        twe_session_unregister('shipping_store');
      }
    }

    function check() {
    	global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_EZSHIP_STATUS'");
        $this->_check = $check_query->RecordCount();
      }

      return $this->_check;
    }
/*    
    function author() {
      return '<div align=right><a href="http://www.ezeshop.com.tw/ezeshop/product_info.php?products_id=81" target="_new"><img align=top border=0 alt="EZeShop" src="http://www.ezeshop.com.tw/ezeshop/images/bloglogo1.gif?ezship"></a></div>';
    }  
*/
    function keys() {
      return array('MODULE_ORDER_TOTAL_EZSHIP_STATUS', 'MODULE_ORDER_TOTAL_EZSHIP_SORT_ORDER', 'MODULE_ORDER_TOTAL_EZSHIP_ACCOUNT','MODULE_ORDER_TOTAL_EZSHIP_SHOW_MAP');
    }

    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_EZSHIP_STATUS', 'true', '6', '1','twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_ORDER_TOTAL_EZSHIP_SORT_ORDER', '22', '6', '2', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_EZSHIP_ACCOUNT', '', '6', '5', '', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_EZSHIP_SHOW_MAP', 'true', '6', '8','twe_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    function remove() {
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
