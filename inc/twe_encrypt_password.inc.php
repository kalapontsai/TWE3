<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_encrypt_password.inc.php,v 1.2 2004/02/08 16:18:03 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(password_funcs.php,v 1.10 2003/02/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_encrypt_password.inc.php,v 1.4 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  
// This function makes a new password from a plaintext password. 
  function twe_encrypt_password($plain) {

     $password=md5($plain);

    return $password;

  }
 ?>