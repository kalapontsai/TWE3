<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_findTitle.inc.php,v 1.1 2003/12/31 20:23:01 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(new_attributes); www.oscommerce.com
   (c) 2003     nextcommerce (new_attributes.php,v 1.13 2003/08/21); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   --------------------------------------------------------------
   Third Party contributions:
   New Attribute Manager v4b                Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


  function twe_findTitle($current_pid, $languageFilter) {
    $query = "SELECT * FROM products_description where language_id = '" . $_SESSION['languages_id'] . "' AND products_id = '" . $current_pid . "'";

    $result = mysql_query($query) or die(mysql_error());

    $matches = mysql_num_rows($result);

    if ($matches) {
      while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $productName = $line['products_name'];
      }
      return $productName;
    } else {
      return "Something isn't right....";
    }
  }
?>