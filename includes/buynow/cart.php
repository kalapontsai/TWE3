<div id="shoppingCart">
<table border="0" width="100%" cellspacing="0" cellpadding="5">
 <tr class="dataTableHeadingRow"> 
  <td class="smallText"><b><?php echo TABLE_HEADING_PRODUCTS_NAME;?></b></td>
  <td class="smallText"><b><?php echo TABLE_HEADING_PRODUCTS_QTY;?></b></td>
  <td class="smallText"><b><?php echo TABLE_HEADING_PRODUCTS_PRICE;?></b></td>
  <td class="smallText" align="right"><b><?php echo TABLE_HEADING_PRODUCTS_FINAL_PRICE;?></b></td>
  <td class="smallText" align="right"></td>
 </tr>
<?php
 for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
     $stockCheck = '';
     if (STOCK_CHECK == 'true') {
         $stockCheck = twe_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
   		 if ($stockCheck) $_SESSION['any_out_of_stock']=1;
     }
if (STOCK_CHECK == 'true') {
	if ($stockCheck){
    if ($_SESSION['any_out_of_stock']== 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
        // write permission in session
        $_SESSION['allow_checkout'] = 'true';
?>
<tr>
    <td colspan="6" class="main" style="border: 1px solid; border-color: #ff0000;" bgcolor="#FFCCCC"><div align="center"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></div></td>
  </tr>
  <?php
      } else {
        $_SESSION['allow_checkout'] = 'false';
?>
<tr>
    <td colspan="6" class="main" style="border: 1px solid; border-color: #ff0000;" bgcolor="#FFCCCC"><div align="center"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></div></td>
  </tr>
 <?php 
      }
    } else {
      $_SESSION['allow_checkout'] = 'true';
    }
	}
}
     $productAttributes = '';
     if (isset($order->products[$i]['attributes']) && sizeof($order->products[$i]['attributes']) > 0) {
         for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
			  if (ATTRIBUTE_STOCK_CHECK == 'true' && STOCK_CHECK == 'true') {
            $attribute_stock_check = twe_check_stock_attributes($order->products[$i]['attributes'][$j]['attributes_id'], $order->products[$i]['qty']);
            if ($attribute_stock_check) $_SESSION['any_out_of_stock']=1;
          }
             $productAttributes .= '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '&nbsp;</small></nobr>' . twe_draw_hidden_field('id[' . $order->products[$i]['id'] . '][' . $order->products[$i]['attributes'][$j]['option_id'] . ']', $order->products[$i]['attributes'][$j]['value_id']);

         }
     }
	 $productAttributesPrice = '';
     if (isset($order->products[$i]['attributes']) && sizeof($order->products[$i]['attributes']) > 0) {
         for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
             $productAttributesPrice .= '<br><nobr><small>&nbsp;'. twe_get_products_attribute_price($order->products[$i]['attributes'][$j]['price'],$tax_class=$order->products[$i]['tax_class_id'],$price_special=1,$order->products[$i]['qty'],$order->products[$i]['attributes'][$j]['prefix']).'</i></small></nobr>';

         }
     }
 $image='';
  if ($order->products[$i]['image']!='') {
  $image=DIR_WS_THUMBNAIL_IMAGES.$order->products[$i]['image'];
  }
?>     
 <tr>
  <!--<td class="thumbnail" valign="top"><a href="<?php echo twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . twe_get_prid($order->products[$i]['id'])); ?>"><img data-src="holder.js/100x100" src="<?php echo $image; ?>" alt="<?php echo $order->products[$i]['name']?>"/></a></td>-->
  <td class="main" valign="top"><a href="<?php echo twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . twe_get_prid($order->products[$i]['id']),'SSL'); ?>"><?php echo $order->products[$i]['name']; ?></a><?php echo $stockCheck . $productAttributes . $attribute_stock_check;?></td>
  <td width="80" class="main" valign="top"><?php
   echo twe_draw_input_field('qty[' . $order->products[$i]['id'] . ']', $order->products[$i]['qty'], 'size="5" onkeyup="$(\'input[name^=qty]\').attr(\'readonly\', true); $(\'#updateCartButton\').trigger(\'click\')"'); ?></td>
    <td class="main" valign="top"><?php 
   echo twe_format_price(twe_get_products_price(twe_get_prid($order->products[$i]['id']), $price_special=0, $quantity=$order->products[$i]['qty'],$order->products[$i]['s_price'],$order->products[$i]['discount_allowed'],$order->products[$i]['tax_class_id'])/$quantity=$order->products[$i]['qty'],1,1);
  ?></td>
  <td class="main" align="right" valign="top"><?php
   echo twe_get_products_price(twe_get_prid($order->products[$i]['id']), $price_special=1, $quantity=$order->products[$i]['qty'],$order->products[$i]['s_price'],$order->products[$i]['discount_allowed'],$order->products[$i]['tax_class_id']).$productAttributesPrice;
  ?></td>
  <td width="1" class="main" align="right" valign="top"><a href="Javascript:void();" linkData="action=removeProduct&pID=<?php echo $order->products[$i]['id'];?>" id="removeFromCart"><img border="0" src="<?php echo DIR_WS_IMAGES;?>icons/delete.png"></a></td>
 </tr>
<?php           
 }
?>
</table></div>