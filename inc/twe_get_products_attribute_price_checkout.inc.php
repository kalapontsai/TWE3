<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_products_attribute_price_checkout.inc.php,v 1.2 2004/02/21 22:38:22 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (twe_get_products_attribute_price_checkout.inc.php,v 1.6 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function twe_get_products_attribute_price_checkout($attribute_price,$attribute_tax,$price_special,$quantity,$prefix,$calculate_currencies=true)
	{
		if ($_SESSION['customers_status']['customers_status_show_price'] == '1') {
			//$attribute_tax=twe_get_tax_rate($tax_class);
		// check if user is allowed to see tax rates
		$attribute_tax='';
		if ($_SESSION['customers_status']['customers_status_show_price_tax'] =='1') {
		$attribute_tax=twe_get_tax_rate($tax_class);
		}
		// add tax
		$price_string=(twe_add_tax($attribute_price,$attribute_tax))*$quantity;
		if ($_SESSION['customers_status']['customers_status_discount_attributes']=='0') {
		// format price & calculate currency
		$price_string=twe_format_price($price_string,$price_special,$calculate_currencies);
			if ($price_special=='1') {
				$price_string = ' '.$prefix.' '.$price_string.' ';
			}
			} else {
			$discount=$_SESSION['customers_status']['customers_status_discount'];
			$rabatt_string = $price_string - ($price_string/100*$discount);
			$price_string=twe_format_price($price_string,$price_special,$calculate_currencies);
			$rabatt_string=twe_format_price($rabatt_string,$price_special,$calculate_currencies);
			if ($price_special=='1' && $price_string != $rabatt_string) {
				$price_string = ' '.$prefix.'<font color="ff0000"><s>'.$price_string.'</s></font> '.$rabatt_string.' ';
			} else {
			$price_string=$rabatt_string;
			if ($price_special=='1') $price_string=' '.$prefix.' '.$price_string;
			}	
			}
		} else {
		$price_string= '  ' .NOT_ALLOWED_TO_SEE_PRICES;
		}
		return $price_string;
	}
 ?>