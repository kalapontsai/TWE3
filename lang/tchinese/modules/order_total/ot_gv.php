<?php
/* -----------------------------------------------------------------------------------------
   $Id: ot_gv.php,v 1.2 2004/04/01 14:19:25 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_gv.php,v 1.1.2.1 2003/05/15); www.oscommerce.com
   (c) 2003	 xtcommerce  www.xt-commerce.com   

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  define('MODULE_ORDER_TOTAL_GV_TITLE', '禮券');
  define('MODULE_ORDER_TOTAL_GV_HEADER', '禮券/折價券');
  define('MODULE_ORDER_TOTAL_GV_DESCRIPTION', '禮券');
  define('SHIPPING_NOT_INCLUDED', ' [不包含運費]');
  define('TAX_NOT_INCLUDED', ' [不包含稅金]');
  define('MODULE_ORDER_TOTAL_GV_USER_PROMPT', '使用禮券/折價券抵扣 ->&nbsp;');
  define('TEXT_ENTER_GV_CODE', '輸入兌換碼 &nbsp;&nbsp;');
  
  define('MODULE_ORDER_TOTAL_GV_STATUS_TITLE', '啟動禮券額度總計');
  define('MODULE_ORDER_TOTAL_GV_STATUS_DESC', '啟動禮券額度總計?');
  define('MODULE_ORDER_TOTAL_GV_SORT_ORDER_TITLE', '顯示順序');
  define('MODULE_ORDER_TOTAL_GV_SORT_ORDER_DESC', '顯示時的順序');
  define('MODULE_ORDER_TOTAL_GV_QUEUE_TITLE', '購買禮券序列');
  define('MODULE_ORDER_TOTAL_GV_QUEUE_DESC', '排列購買禮券序列?');
  define('MODULE_ORDER_TOTAL_GV_INC_SHIPPING_TITLE', '包含運費');
  define('MODULE_ORDER_TOTAL_GV_INC_SHIPPING_DESC', '計算時包含運費');
  define('MODULE_ORDER_TOTAL_GV_INC_TAX_TITLE', '包含稅金');
  define('MODULE_ORDER_TOTAL_GV_INC_TAX_DESC', '計算時包含稅金.');
  define('MODULE_ORDER_TOTAL_GV_CALC_TAX_TITLE', '稅金計算方式');
  define('MODULE_ORDER_TOTAL_GV_CALC_TAX_DESC', '選擇稅金計算方式');
  define('MODULE_ORDER_TOTAL_GV_TAX_CLASS_TITLE', '稅別');
  define('MODULE_ORDER_TOTAL_GV_TAX_CLASS_DESC', '禮券使用稅別');
  define('MODULE_ORDER_TOTAL_GV_CREDIT_TAX_TITLE', '新增帳號禮券包含稅金');
  define('MODULE_ORDER_TOTAL_GV_CREDIT_TAX_DESC', '當新增帳號時禮券包含稅金');
?>