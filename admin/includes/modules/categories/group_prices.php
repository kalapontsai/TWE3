<?php
/* --------------------------------------------------------------
   $Id: new_product.php,v 1.12 2004/03/16 15:01:16 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.140 2003/03/24); www.oscommerce.com
   (c) 2003  nextcommerce (categories.php,v 1.37 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   --------------------------------------------------------------
   Third Party contribution:
   Enable_Disable_Categories 1.3               Autor: Mikel Williams | mikel@ladykatcostumes.com
   New Attribute Manager v4b                   Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com
   Category Descriptions (Version: 1.5 MS2)    Original Author:   Brian Lowe <blowe@wpcusrgrp.org> | Editor: Lord Illicious <shaolin-venoms@illicious.net>
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   --------------------------------------------------------------*/
   if ( ($_GET['pID']) && (!$_POST) ) {
      $product_query = "select  pd.products_name,
                                            p.products_id,
                                            p.products_price,
											p.products_image,
                                            p.products_tax_class_id from " . TABLE_PRODUCTS . " p,
                                            " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                            where p.products_id = '" . (int)$_GET['pID'] . "'
                                            and p.products_id = pd.products_id
                                            and pd.language_id = '" . $_SESSION['languages_id'] . "'";

      $product = $db->Execute($product_query);
      $pInfo = new objectInfo($product->fields);

    } elseif ($_POST) {
      $pInfo = new objectInfo($_POST);
      $products_name = $_POST['products_name'];
    } else {
      $pInfo = new objectInfo(array());
    }

    
    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class = $db->Execute("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while (!$tax_class->EOF) {
      $tax_class_array[] = array('id' => $tax_class->fields['tax_class_id'],
                                 'text' => $tax_class->fields['tax_class_title']);
    $tax_class->MoveNext();
	}
    


?>
<span class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, twe_output_generated_category_path($current_category_id)).'<br>'.$pInfo->products_name; ?></span><br>

<table width="100%" border="0">
<form name="new_staffel" action="<?php echo HTTP_SERVER.'/'.$_SERVER['PHP_SELF']; ?>?selected_box=catolog&cPath=<?php echo $_GET['cPath']; ?>&pID=<?php echo $_GET['pID']; ?>&action=update_price" method="POST">


        <tr>
          <td colspan="4"><?php echo twe_image(DIR_WS_CATALOG_THUMBNAIL_IMAGES.$pInfo->products_image, 'Standard Image'); ?></td>
        </tr>
        <tr>
          <td colspan="4"><?php
  require_once(DIR_FS_INC .'twe_get_tax_rate.inc.php');
  require_once(DIR_FS_INC .'twe_format_price.inc.php');
  $i = 0;
  $group_values = $db->Execute("SELECT
                                   customers_status_image,
                                   customers_status_id,
                                   customers_status_name
                               FROM
                                   " . TABLE_CUSTOMERS_STATUS . "
                               WHERE
                                   language_id = '" . $_SESSION['languages_id'] . "' AND customers_status_id != '0'");
  while (!$group_values->EOF) {
    // load data into array
    $i++;
    $group_data[$i] = array(
      'STATUS_NAME' => $group_values->fields['customers_status_name'],
      'STATUS_IMAGE' => $group_values->fields['customers_status_image'],
      'STATUS_ID' => $group_values->fields['customers_status_id']);
 $group_values->MoveNext();
  }
  echo HEADING_PRICES_OPTIONS;
?><table width="100%" border="0" bgcolor="f3f3f3" style="border: 1px solid; border-color: #cccccc;">
          <tr>
            <td width="5%" class="main"><?php echo TEXT_PRODUCTS_PRICE; ?></td>
<?php
// calculate brutto price for display

if (PRICE_IS_BRUTTO=='true'){
$products_price = twe_round($pInfo->products_price*((100+twe_get_tax_rate($pInfo->products_tax_class_id))/100),PRICE_PRECISION);

} else {
$products_price = twe_round($pInfo->products_price,PRICE_PRECISION);
}




?>
            <td width="50%" class="main"><?php echo twe_format_price($products_price,1,1) .twe_draw_hidden_field('products_id', $pInfo->products_id); ?>
<?php
if (PRICE_IS_BRUTTO=='true'){
echo TEXT_NETTO .'<b>'.twe_format_price((twe_round($pInfo->products_price,PRICE_PRECISION)),1,1).'</b>  ';
}
?>
</td>
          </tr>
<?php
  for ($col = 0, $n = sizeof($group_data); $col < $n+1; $col++) {
    if ($group_data[$col]['STATUS_NAME'] != '') {
?>
          <tr>
            <td style="border-top: 1px solid; border-color: #cccccc;" valign="top" class="main"><?php echo $group_data[$col]['STATUS_NAME']; ?></td>
<?php
if (PRICE_IS_BRUTTO=='true'){
$products_price = twe_round(get_group_price($group_data[$col]['STATUS_ID'], $pInfo->products_id)*((100+twe_get_tax_rate($pInfo->products_tax_class_id))/100),PRICE_PRECISION);

} else {
$products_price = twe_round(get_group_price($group_data[$col]['STATUS_ID'], $pInfo->products_id),PRICE_PRECISION);
}

?>
            <td style="border-top: 1px solid; border-color: #cccccc;" class="main"><?php echo twe_draw_input_field('products_price_' . $group_data[$col]['STATUS_ID'], $products_price);

if (PRICE_IS_BRUTTO=='true' && get_group_price($group_data[$col]['STATUS_ID'], $pInfo->products_id)!='0'){
echo TEXT_NETTO . '<b>'.twe_format_price((twe_round(get_group_price($group_data[$col]['STATUS_ID'], $pInfo->products_id),PRICE_PRECISION)),1,1).'</b>  ';
}


      if ($_GET['pID'] != '') {
        echo ' ' . TXT_STAFFELPREIS; ?><?php
      }
      
?><br><?php
      // ok, lets check if there is already a staffelpreis
      $staffel_values = $db->Execute("SELECT
                                         products_id,
                                         quantity,
                                         personal_offer
                                     FROM
                                         personal_offers_by_customers_status_" . $group_data[$col]['STATUS_ID'] . "
                                     WHERE
                                         products_id = '" . $pInfo->products_id . "' AND quantity != 1
                                     ORDER BY quantity ASC");
      echo '<table width="247" border="0" cellpadding="0" cellspacing="0">';
      while (!$staffel_values->EOF) {
      // load data into array
?>
              <tr>
                <td width="20" class="main" style="border: 1px solid; border-color: #cccccc;"><?php echo $staffel_values->fields['quantity']; ?></td>
                <td width="5">&nbsp;</td>
                <td nowrap width="142" class="main" style="border: 1px solid; border-color: #cccccc;">
<?php
if (PRICE_IS_BRUTTO=='true'){
$tax_query = "select tax_rate from " . TABLE_TAX_RATES . " where tax_class_id = '" . $pInfo->products_tax_class_id . "'";
$tax = $db->Execute($tax_query);

$products_price = twe_round($staffel_values->fields['personal_offer']*((100+$tax->fields['tax_rate'])/100),PRICE_PRECISION);

} else {
$products_price = twe_round($staffel_values->fields['personal_offer'],PRICE_PRECISION);
}
 echo $products_price;
 if (PRICE_IS_BRUTTO=='true'){
echo ' <br>'.TEXT_NETTO .'<b>'. twe_format_price((twe_round($staffel_values->fields['personal_offer'],PRICE_PRECISION)),1,1).'</b>  ';
}

 ?>
 </td>
                <td width="80" align="left"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?><a href="<?php echo twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&del_function=delete&quantity=' . $staffel_values->fields['quantity'] . '&statusID=' . $group_data[$col]['STATUS_ID'] . '&action=new_product&pID=' . $_GET['pID']); ?>"><?php echo twe_image_button('button_delete.gif', IMAGE_DELETE); ?></a></td>
              </tr>
              <tr>
                <td colspan="3" height="5"></td>
              </tr>
<?php
$staffel_values->MoveNext();
      }

      echo '</table>';
      echo TXT_STK;
      echo twe_draw_small_input_field('products_quantity_staffel_'.$group_data[$col]['STATUS_ID'], 0);
      echo TXT_PRICE;
      echo twe_draw_input_field('products_price_staffel_'.$group_data[$col]['STATUS_ID'], 0);
      echo twe_draw_separator('pixel_trans.gif', '10', '10'); 
      echo '<input type="submit" value="'.IMAGE_INSERT.'">';
?><br>
<?php 
    }
  }
?>

        <tr>
          <td colspan="4"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
		</table></form>
<table width="100%" border="0" style="border: 1px solid; border-color: #cccccc;"><tr>
		<td colspan="4" align="right"><?php echo '<a href="' . twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $_GET['pID']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td></tr></table>
