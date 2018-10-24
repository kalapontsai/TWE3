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


  define('MODULE_PAYMENT_TITLE', '貨到付款加收手續費');
  define('MODULE_PAYMENT_DESCRIPTION', '貨到付款加收手續費');
  define('SHIPPING_NOT_INCLUDED', '[不包含運費]');
  define('TAX_NOT_INCLUDED', '[不含稅金]');
  define('MODULE_PAYMENT_STATUS_TITLE','使用付款手續費模組');
  define('MODULE_PAYMENT_STATUS_DESC','使用付款手續費模組?');
  define('MODULE_PAYMENT_SORT_ORDER_TITLE','排序');
  define('MODULE_PAYMENT_SORT_ORDER_DESC','排列順序');
  define('MODULE_PAYMENT_INC_SHIPPING_TITLE','包括運費');
  define('MODULE_PAYMENT_INC_SHIPPING_DESC','計算中包括運費');
  define('MODULE_PAYMENT_INC_TAX_TITLE','包括稅金');
  define('MODULE_PAYMENT_INC_TAX_DESC','計算中包括稅金');
  define('MODULE_PAYMENT_PERCENTAGE_TITLE','手續費');
  define('MODULE_PAYMENT_PERCENTAGE_DESC','手續費值(直接填入金額).');
  define('MODULE_PAYMENT_CALC_TAX_TITLE','重新計算稅金');
  define('MODULE_PAYMENT_CALC_TAX_DESC','重新計算手續費的稅金數額');
  define('MODULE_PAYMENT_MINIMUM_TITLE','最低訂單金額');
  define('MODULE_PAYMENT_MINIMUM_DESC','最低訂單金額加收手續費');
  define('MODULE_PAYMENT_TYPE_TITLE','付款方式選項');
  define('MODULE_PAYMENT_TYPE_DESC','付款方式選項,使用逗號區隔');
  
?>