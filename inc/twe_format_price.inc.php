<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_format_price.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (twe_format_price.inc.php,v 1.7 2003/08/19); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
// include needed functions
require_once(DIR_FS_INC . 'twe_precision.inc.php');
function twe_format_price ($price_string,$price_special,$calculate_currencies,$show_currencies=1)
{

if ($calculate_currencies=='true') {
$price_string=$price_string * $_SESSION['SYMBOL_CURRENCY']['VALUE'];
}
// round price
$price_string=twe_precision($price_string,$_SESSION['SYMBOL_CURRENCY']['DECIMAL_PLACES']);


if ($price_special=='1') { 

$price_string=number_format($price_string,$_SESSION['SYMBOL_CURRENCY']['DECIMAL_PLACES'], $_SESSION['SYMBOL_CURRENCY']['DECIMAL_POINT'], $_SESSION['SYMBOL_CURRENCY']['THOUSANDS_POINT']);
  if ($show_currencies == 1) {
    $price_string = $_SESSION['SYMBOL_CURRENCY']['SYMBOL_LEFT']. ' '.$price_string.' '.$_SESSION['SYMBOL_CURRENCY']['SYMBOL_RIGHT'];
  }
}
return $price_string;
}
?>