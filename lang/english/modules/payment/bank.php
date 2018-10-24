<?php
/*
  $Id: bank.php,v 1.1 2004/07/19 08:27:22 oldpa Exp $

  TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(german.php,v 1.119 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (german.php,v 1.25 2003/08/25); www.nextcommerce.org
   (c) 2003	 xtcommerce  www.xt-commerce.com   
   Released under the GNU General Public License 

*/
  define('MODULE_PAYMENT_BANK_TEXT_TITLE', 'Bank transfer');
  define('MODULE_PAYMENT_BANK_TEXT_DESCRIPTION', 'Bank transfer');
  define('MODULE_PAYMENT_BANK_TEXT_DESCRIPTION1', 'Please transfer the total money to: <br><br>Bank name: ' . MODULE_PAYMENT_BANK_NAME . '<br>Account name: ' . MODULE_PAYMENT_BANK_ACCNAME . '<br>Account number: ' . MODULE_PAYMENT_BANK_ACCNUM . '<br><br>We will contact you and begin shipping asap. <br>Notice: Please quote your order number in your bank transfer form so we can know you. Thanks.');
  define('MODULE_PAYMENT_BANK_TEXT_EMAIL_FOOTER', "Please transfer the total money to: \n\nBank name: " . MODULE_PAYMENT_BANK_NAME . "\nAccount name: " . MODULE_PAYMENT_BANK_ACCNAME . "\nAccount number: " . MODULE_PAYMENT_BANK_ACCNUM . "\n\nWe will contact you and begin shipping asap. \nNotice: Please quote your order number in your bank transfer form so we can know you. Thanks.");
  
  define('MODULE_PAYMENT_BANK_STATUS_TITLE', 'Enable bank transfer module');  
  define('MODULE_PAYMENT_BANK_STATUS_DESC', 'Are you sure?');  
  define('MODULE_PAYMENT_BANK_ALLOWED_TITLE','Bank transfer country'); 
  define('MODULE_PAYMENT_BANK_ALLOWED_DESC','Input country code, so only listed countries are allowed(EX: AT,DE). Leave empty for unlimited');  
  define('MODULE_PAYMENT_BANK_NAME_TITLE', 'Bank name');
  define('MODULE_PAYMENT_BANK_NAME_DESC', 'Please input bank name');
  define('MODULE_PAYMENT_BANK_ACCNAME_TITLE', 'Account name');
  define('MODULE_PAYMENT_BANK_ACCNAME_DESC', 'Please input your account name');
  define('MODULE_PAYMENT_BANK_ACCNUM_TITLE', 'Account number'); 
  define('MODULE_PAYMENT_BANK_ACCNUM_DESC', 'Please input your account number');
  define('MODULE_PAYMENT_BANK_SORT_ORDER_TITLE', 'Sort order');  
  define('MODULE_PAYMENT_BANK_SORT_ORDER_DESC', 'Sort order, the minimun the front');
  define('MODULE_PAYMENT_BANK_ZONE_TITLE', 'Bank transfer zone');  
  define('MODULE_PAYMENT_BANK_ZONE_DESC', 'If you use this function, then only selected zone is allowed to use this module.');
  define('MODULE_PAYMENT_BANK_ORDER_STATUS_ID_TITLE', 'Order status for this module');  
  define('MODULE_PAYMENT_BANK_ORDER_STATUS_ID_DESC', 'Specify the order status for this module, such as pending, processing or delieverd.');
?>
