<?php
   /* -----------------------------------------------------------------------------------------
   $Id: listproducts.php,v 1.1 2004/02/17 21:13:26 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project (earlier name of osCommerce)
   (c) 2002-2003 osCommerce (validproducts.php,v 0.01 2002/08/17); www.oscommerce.com
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contribution:

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org


   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


require('includes/application_top.php');


?>
<html>
<head>
<title>Valid Categories/Products List</title>
<style type="text/css">
<!--
h4 {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; text-align: center}
p {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
th {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
td {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: xx-small}
-->
</style>
<head>
<body>
<table width="550" border="1" cellspacing="1" bordercolor="gray">
<tr>
<td colspan="3">
<h4>Valid Products List</h4>
</td>
</tr>
<?
   $coupon_get="select restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . "  where coupon_id='".$_GET['cid']."'";
   $get_result=$db->Execute($coupon_get);

    echo "<tr><th>Product ID</th><th>Product Name</th><th>Product Model</th></tr><tr>";
    $pr_ids = explode(",", $get_result->fields['restrict_to_products']);
    for ($i = 0; $i < count($pr_ids); $i++) {
      $result = $db->Execute("SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . $_SESSION['languages_id'] . "'and products.products_id = '" . $pr_ids[$i] . "'");
      if ($result->RecordCount()>0) {
            echo "<td>".$result->fields["products_id"]."</td>\n";
            echo "<td>".$result->fields["products_name"]."</td>\n";
            echo "<td>".$result->fields["products_model"]."</td>\n";
            echo "</tr>\n";
      }
    }
      echo "</table>\n";
?>
<br>
<table width="550" border="0" cellspacing="1">
<tr>
<td align=middle><input type="button" value="Close Window" onClick="window.close()"></td>
</tr></table>
</body>
</html>