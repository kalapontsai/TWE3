<?php
/* -----------------------------------------------------------------------------------------
   $Id: getCartBox.php,v 1.34 2011/11/26 10:31:17 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_info.php,v 1.94 2003/05/04); www.oscommerce.com 
   (c) 2003      nextcommerce (product_info.php,v 1.46 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------*/
 require_once(DIR_FS_INC . 'twe_check_stock.inc.php');
if ($_SESSION['cart']->count_contents() > 0) {
	$products_in_cart ='';
   		$products = $_SESSION['cart']->get_products();		  
 		for ($i=0, $n=sizeof($products); $i<$n; $i++) {
    		$stockCheck = '';
     		if (STOCK_CHECK == 'true') {
         	$stockCheck = twe_check_stock($products[$i]['id'], $products[$i]['quantity']);
    		 }     
 		$products_in_cart .='<table cellpadding="5" border="0" width="100%"><tr>'.($products[$i]['image']?'<td width="50"><img src="'.DIR_WS_THUMBNAIL_IMAGES . $products[$i]['image'].'" width="50" border="0"></td>':'').'<td><a href="'.twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']).'">' . mb_substr($products[$i]['name'],'0', 16,'UTF-8').'...</a>x&nbsp;'. $products[$i]['quantity'].'</td>
        <td align="right"><a href="Javascript:void();" linkData="action=removeBoxProduct&pID='.$products[$i]['id'].'" id="removeBoxFromCart"><h3><span class="glyphicon glyphicon-remove"></span></h3></a></td>
		</tr></table>';
 		}
		$total_content='<hr><table width="100%"  border="0" cellpadding="2" cellspacing="0"><tr>';
   			$total_price =twe_format_price($_SESSION['cart']->show_total(), $price_special = 0, $calculate_currencies = false);
   			 if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == '1' && $_SESSION['customers_status']['customers_status_ot_discount'] != '0.00' ) {
     	$total_content .='<td align="right" class="boxText"><font color="#FF0000"><strong>'.TEXT_DISCOUNT.'</strong>'.twe_format_price(twe_recalculate_price(($total_price*(-1)), $_SESSION['customers_status']['customers_status_ot_discount']), $price_special = 1, $calculate_currencies = false).'</font><br><strong>'.TEXT_TOTAL.'</strong><h4>'.twe_format_price(($total_price), $price_special = 1, $calculate_currencies = false).'</h4></td>';	  
    		} else {
     	$total_content .='<td align="right" class="boxText"><strong>'.TEXT_TOTAL.'</strong><h4>'.twe_format_price(($total_price), $price_special = 1, $calculate_currencies = false).'</h4></td>';	  
    		}
		$total_content .='</tr></table>';	
          echo $products_in_cart.$total_content.'<div class="text-center"><a href="'.twe_href_link(FILENAME_SHOPPING_CART, '', 'SSL').'" class="button-s btn-block">'.NAVBAR_TITLE_SHOPPING_CART.'</a><a href="'. twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL').'" class="button-a btn-block">'.IMAGE_BUTTON_CHECKOUT.'</a></div>';
		  }else{
		  echo '<p align="center">'.TEXT_CART_EMPTY.'</p>';
	}
  ?>