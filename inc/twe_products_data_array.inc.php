<?php
/*
  $Id: twe_products_data_array.inc.php ,v 1.1.1.1 2011/01/30 08:01:09 oldpa Exp $
   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
   (c) 2003	 xt-commerce  www.xt-commerce.com

  Released under the GNU General Public License
*/
?>
<?php
  require_once(DIR_FS_INC . 'twe_get_shipping_status_name.inc.php');

function twe_products_data_array(&$array,$status='') {
	global $PHP_SELF;
			$products_price = twe_get_products_price($array['products_id'],$price_special=1,$quantity=1,$array['products_price'],$array['products_discount_allowed'],$array['products_tax_class_id']);
			$buy_now = ''; 
	if ($_SESSION['customers_status']['customers_status_show_price'] != '0') {
		 if ($array['products_quantity'] > 0) {
        if ($_SESSION['customers_status']['customers_fsk18'] == '1') {
		if (twe_has_product_attributes($array['products_id'])) {
          if (isset($array['products_fsk18']) && $array['products_fsk18'] == '0')$buy_now='<a class="button btn-block" role="button" href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action'),'SSL') . 'action=buy_now&BUYproducts_id=' . $array['products_id'], 'NONSSL') . '">' . IMAGE_BUTTON_IN_CART.'</a>';
			}else{
          if (isset($array['products_fsk18']) && $array['products_fsk18'] == '0')$buy_now='<a class="button btn-block" role="button" href="Javascript:void();" linkData="action=addProductToCart&BUYNOW_id='.$array['products_id'].'" id="addToCart">' . IMAGE_BUTTON_IN_CART.'</a>';
		 }
		} else {
			if (twe_has_product_attributes($array['products_id'])) {
				$buy_now='<a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action'),'SSL') . 'action=buy_now&BUYproducts_id=' . $array['products_id'], 'NONSSL') . '">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $array['products_name'] . TEXT_NOW).'</a>';
			}else{
				$buy_now='<a href="Javascript:void();" linkData="action=addProductToCart&BUYNOW_id='.$array['products_id'].'" id="addToCart">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $array['products_name'] . TEXT_NOW).'</a>';
			}
	    }
		}  ELSE { $buy_now = '<div style="height:40px" align="center"><font color = red>*補貨中*</font></div>';}// 加入庫存數量判斷 是否顯示 [立即購] 按鍵  --------------------------- ELHOMEO
	}
            $shipping_status_name = '';
            $shipping_status_image = '';
		if (ACTIVATE_SHIPPING_STATUS=='true') {
			if($status!=''){
            $shipping_status=twe_get_shipping_status_name($array['products_shippingtime']);
            $shipping_status_name=$shipping_status['name'];
        if ($shipping_status['image']!='') $shipping_status_image='admin/images/icons/'.$shipping_status['image'];
		}
       }
		if(function_exists('mb_substr')){
	$array['products_short_description'] = mb_substr(strip_tags($array['products_short_description']),'0', MAX_DISPLAY_SHORT_DESCRIPTION,'UTF-8'); 		
		}else{
	$array['products_short_description'] = substr(strip_tags($array['products_short_description']), 1, MAX_DISPLAY_SHORT_DESCRIPTION);		
		}
  return array ('PRODUCTS_NAME' => $array['products_name'], 
				'PRODUCTS_ID'=>$array['products_id'],
				'PRODUCTS_MODEL'=>isset($array['products_model']) ? $array['products_model'] : '',
				'PRODUCTS_LINK' => twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $array['products_id'],'SSL'),
				'PRODUCTS_PRICE' => $products_price, 
				'BUTTON_BUY_NOW' => $buy_now,
				'SHIPPING_NAME'=>$shipping_status_name,
				'SHIPPING_IMAGE'=>$shipping_status_image,	
       			'PRODUCTS_DESCRIPTION' => isset($array['products_short_description']) ? $array['products_short_description'] : '', 
        		'PRODUCTS_QUANTITY' => isset($array['products_quantity']) ? (int)$array['products_quantity'] :'',
        		'PRODUCTS_EXPIRES' => isset($array['expires_date']) ? $array['expires_date'] : 0, 
				'PRODUCTS_SHORT_DESCRIPTION' => isset($array['products_short_description']) ? $array['products_short_description'] : '', 
				'PRODUCTS_FSK18' => isset($array['products_fsk18']) ? $array['products_fsk18'] : 0, 
				'PRODUCTS_IMAGE' => ($array['products_image'] ? DIR_WS_THUMBNAIL_IMAGES . $array['products_image'] : '')
        );

	}

?>