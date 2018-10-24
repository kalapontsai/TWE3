<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_format_special_price.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (twe_format_special_price.inc.php,v 1.6 2003/08/20); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

function twe_format_special_price ($special_price,$price,$price_special,$calculate_currencies,$quantity,$products_tax)
	{	 
	
	$price=$price*$quantity;
	$special_price=$special_price*$quantity;
	
	if ($calculate_currencies=='true') {
	$special_price=$special_price * $_SESSION['SYMBOL_CURRENCY']['VALUE'];
	$price=$price * $_SESSION['SYMBOL_CURRENCY']['VALUE'];
	
	}
	// round price
	$special_price=twe_precision($special_price,$_SESSION['SYMBOL_CURRENCY']['DECIMAL_PLACES'] );
	$price=twe_precision($price,$_SESSION['SYMBOL_CURRENCY']['DECIMAL_PLACES'] );
	
	if ($price_special=='1') {
	$price=number_format($price,$_SESSION['SYMBOL_CURRENCY']['DECIMAL_PLACES'], $_SESSION['SYMBOL_CURRENCY']['DECIMAL_POINT'], $_SESSION['SYMBOL_CURRENCY']['THOUSANDS_POINT']);
	$special_price=number_format($special_price,$_SESSION['SYMBOL_CURRENCY']['DECIMAL_PLACES'], $_SESSION['SYMBOL_CURRENCY']['DECIMAL_POINT'], $_SESSION['SYMBOL_CURRENCY']['THOUSANDS_POINT']);

	$special_price ='<font color="ff0000"><s>'. $_SESSION['SYMBOL_CURRENCY']['SYMBOL_LEFT'].' '.$price.' '.$_SESSION['SYMBOL_CURRENCY']['SYMBOL_RIGHT'].' </s></font>'. $_SESSION['SYMBOL_CURRENCY']['SYMBOL_LEFT']. ' '.$special_price.' '.$_SESSION['SYMBOL_CURRENCY']['SYMBOL_RIGHT'];
	} 
	return $special_price;
	}
 ?>