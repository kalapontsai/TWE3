<?php
/*
  $Id: atm.php,v 1.1 2003/10/19 08:27:22 oldpa Exp $

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

  define('MODULE_PAYMENT_ATM_STATUS_TITLE', 'ATM Bank Transfer');
  define('MODULE_PAYMENT_ATM_TEXT_TITLE', 'ATM Bank Transfer');
  define('MODULE_PAYMENT_ATM_STATUS_DESCRIPTION', 'ATM Bank Transfer');
  define('MODULE_PAYMENT_ATM_TEXT_DESCRIPTION', 'ATM Bank Transfer');
  define('MODULE_PAYMENT_ATM_TEXT_DESCRIPTION1', 'Please use the nearest ATM machine for bank transfer,Just type few numbers and finish rest of processes:<br>1.enter your password<br>2.choose other service<br>3.choose bank transfer<br>4.If your ATM machine not belong'  . MODULE_PAYMENT_ATM_BANKNAME . ' , Please choose other bank<br>5.enter bank code:' . MODULE_PAYMENT_ATM_BANKCODE . '<br>6.enter our account number:' . MODULE_PAYMENT_ATM_ACCNUM . '<br>7.enter the total money<br><br>' . 'Note: Please send a email to tell us your ATM transfer number. <br>We will deliever your product immediately when we received and confirmed your payment. Thanks again.');
  define('MODULE_PAYMENT_ATM_TEXT_EMAIL_FOOTER', "Please use the nearest ATM machine for bank transfer,Just type few numbers and finish rest of processes:\n1.enter your password\n2.choose other service\n3.choose bank transfer\n4.If your ATM machine not belong" . MODULE_PAYMENT_ATM_BANKNAME . ", Please choose other bank\n5.enter bank code:" . MODULE_PAYMENT_ATM_BANKCODE. "\n6.enter our account number:" . MODULE_PAYMENT_ATM_ACCNUM . "\n7.enter the total money\n\nNote: Please send a email to tell us your ATM transfer number.\nWe will deliever your product immediately when we received and confirmed your payment. Thanks again.");
define('MODULE_PAYMENT_ATM_ALLOWED_TITLE','ATM allow country'); 
define('MODULE_PAYMENT_ATM_ALLOWED_DESC','Type country code, So only listed countries are allowed. (Ex: AT,DE (leave empty for unlimited))');  
 
define('MODULE_PAYMENT_ATM_STATUS_TITLE','Enable ATM payment module');
define('MODULE_PAYMENT_ATM_STATUS_DESC','Are you sure?');
define('MODULE_PAYMENT_ATM_BANKCODE_TITLE','Bank code');
define('MODULE_PAYMENT_ATM_BANKCODE_DESC','Please input your bank code');
define('MODULE_PAYMENT_ATM_BANKNAME_TITLE','Name of bank');
define('MODULE_PAYMENT_ATM_BANKNAME_DESC','Name of your bank account');
define('MODULE_PAYMENT_ATM_ACCNUM_TITLE','Bank account numbers');
define('MODULE_PAYMENT_ATM_ACCNUM_DESC','Please type your bank account number');
define('MODULE_PAYMENT_ATM_SORT_ORDER_TITLE','Index order');
define('MODULE_PAYMENT_ATM_SORT_ORDER_DESC','Index order,the minumum the first');
define('MODULE_PAYMENT_ATM_ZONE_TITLE','ATM allow zone');
define('MODULE_PAYMENT_ATM_ZONE_DESC','If zone has selected, then only select zone are allowed to use this payment');
define('MODULE_PAYMENT_ATM_ORDER_STATUS_ID_TITLE','Order status for ATM');
define('MODULE_PAYMENT_ATM_ORDER_STATUS_ID_DESC','Set the order status for ATM, for example: order placed, processing or delieverd');
?>
