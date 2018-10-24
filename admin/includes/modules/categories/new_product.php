<?php
/* --------------------------------------------------------------
   20180321- modify products_description textarea field's id name - ELHOMEO
   --------------------------------------------------------------
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
      $product_query = "select p.products_fsk18,
                                            p.product_template,
                                            p.options_template,
                                            pd.products_name,
                                            pd.products_description,
                                            pd.products_short_description,
                                            pd.products_meta_title,
                                            pd.products_meta_description,
                                            pd.products_meta_keywords,
                                            pd.products_url,
                                            p.products_id,
                                            p.group_ids,
                                            p.products_sort,
                                            p.products_shippingtime,
                                            p.products_quantity,
                                            p.products_model,
                                            p.products_image,
                                            p.products_price,
                                            p.products_discount_allowed,
                                            p.products_weight,
                                            p.products_date_added,
                                            p.products_last_modified,
                                            date_format(p.products_date_available, '%Y-%m-%d') as products_date_available,
                                            p.products_status,
                                            p.products_tax_class_id,
                                            p.manufacturers_id,
											p.products_featured from " . TABLE_PRODUCTS . " p,
                                            " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                            where p.products_id = '" . (int)$_GET['pID'] . "'
                                            and p.products_id = pd.products_id
                                            and pd.language_id = '" . $_SESSION['languages_id'] . "'";

      $product = $db->Execute($product_query);
      $pInfo = new objectInfo($product->fields);

    } elseif ($_POST) {
      $pInfo = new objectInfo($_POST);
      $products_name = $_POST['products_name'];
      $products_description = $_POST['products_description'];
      $products_short_description = $_POST['products_short_description'];
      $products_meta_title = $_POST['products_meta_title'];
      $products_meta_description = $_POST['products_meta_description'];
      $products_meta_keywords = $_POST['products_meta_keywords'];
      $products_url = $_POST['products_url'];
    } else {
      $pInfo = new objectInfo(array());
    }

    $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers = $db->Execute("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while (!$manufacturers->EOF) {
      $manufacturers_array[] = array('id' => $manufacturers->fields['manufacturers_id'],
                                     'text' => $manufacturers->fields['manufacturers_name']);
   $manufacturers->MoveNext(); 
	}

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class = $db->Execute("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while (!$tax_class->EOF) {
      $tax_class_array[] = array('id' => $tax_class->fields['tax_class_id'],
                                 'text' => $tax_class->fields['tax_class_title']);
    $tax_class->MoveNext();
	}
    $shipping_statuses = array();
    $shipping_statuses=twe_get_shipping_status();
    $languages = twe_get_languages();

    switch ($pInfo->products_status) {
      case '0': $status = ''; $out_status = 'checked'; break;
      case '1':
      default: $status = 'checked'; $out_status = '';
    }
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
</script>
<tr><td>
<?php $fsk18_array=array(array('id'=>0,'text'=>NO),array('id'=>1,'text'=>YES)); ?>
<?php $featured_array=array(array('id'=>0,'text'=>NO),array('id'=>1,'text'=>YES)); ?>
<?php $stock_array=array(array('id'=>0,'text'=>TEXT_PRODUCT_NOT_AVAILABLE),array('id'=>1,'text'=>TEXT_PRODUCT_AVAILABLE)); ?>
<span class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, twe_output_generated_category_path($current_category_id),TEXT_NEW_PRODUCT); ?></span><br />
<table width="100%"  border="0">
  <tr>
	<td><?php echo HEADING_PRODUCT_OPTIONS; ?></td>
     <td class="smallText" align="right"><?php echo IMAGE_NEW_PRODUCT.HEADING_TITLE_GOTO . ' ' . twe_draw_pull_down_menu('cPath', twe_get_category_tree(), $current_category_id, 'linkData="action=getcPath" id="createP"'); ?></td>
     <td class="smallText" width="200"><div id="createB"></div></td>
     <td><a href="Javascript:void();" linkData="action=removeProduct&cPath=<?php echo $_GET['cPath']?>&pid=<?php echo $_GET['pID']?>" id="removeP"><?php echo twe_image_button('button_delete.gif', IMAGE_DELETE)?></a></td>
  </tr>
 </table>
<table bgcolor="f3f3f3" style="border: 1px solid; border-color: #cccccc;" width="100%"  border="0">
   <tr>
    <td width="50%" >
<table width="100%"  border="0">
   <tr>
    <td width="30%" class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
    <td class="main"><?php echo twe_draw_pull_down_menu('products_status', $stock_array, $pInfo->products_status,'id="pChange"'); ?></td>
    </tr>
        <tr>
          <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?></td>
          <td class="main"><form id="seldateAvailable" name="new_product" method="post" action=""><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script>&nbsp;&nbsp;<small>(YYYY-MM-DD)</small><input type="submit" value="<?php echo SAVE_ENTRY?>" /></form></td>
        </tr>
    <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_SORT; ?></td>
        <td class="main"><?php echo  twe_draw_input_field('products_sort', $pInfo->products_sort,'id="pChange"'); ?></td>
      </tr>
              <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
        <td class="main"><?php echo  twe_draw_input_field('products_model', $pInfo->products_model,'id="pChange"'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
        <td class="main"><?php echo twe_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id,'id="pChange"'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_FSK18; ?></td>
        <td class="main"><?php echo twe_draw_pull_down_menu('products_fsk18', $fsk18_array, $pInfo->products_fsk18,'id="pChange"'); ?></td>
      </tr>
      </table>
      </td>
      <td width="50%" >
    <table  width="100%"  border="0">
	   <tr>
        <td width="30%"><span class="main"><?php echo TEXT_FEATURED; ?></span></td>
        <td><span class="main"><?php echo twe_draw_pull_down_menu('products_featured', $featured_array, $pInfo->products_featured,'id="pChange"'); ?></span></td>
      </tr>
      <tr>
<?php if (ACTIVATE_SHIPPING_STATUS=='true') { ?>
        <td><span class="main"><?php echo BOX_SHIPPING_STATUS.':'; ?></span></td>
        <td><span class="main"><?php echo twe_draw_pull_down_menu('products_shippingtime', $shipping_statuses, $pInfo->products_shippingtime,'id="pChange"'); ?></span></td>
      </tr>
<?php } ?>
          <tr>
            <td><span class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></span></td>
			 <td><span class="main"><?php echo twe_draw_input_field('products_quantity', $pInfo->products_quantity,'id="pChange"'); ?></span></td>
          </tr>          
          <tr>
            <td><span class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></span></td>
			<td><span class="main"><?php echo twe_draw_input_field('products_weight', $pInfo->products_weight,'id="pChange"'); ?><?php echo TEXT_PRODUCTS_WEIGHT_INFO; ?></span></td>
          </tr>
      <tr>      
          <?php
        $files=array();
 if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_info/')){
 while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_info/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
 }
 $default_array=array();
 // set default value in dropdown!
if ($content['content_file']=='') {
$default_array[]=array('id' => 'default','text' => TEXT_SELECT);
$default_value=$pInfo->product_template;
$files=array_merge($default_array,$files);
} else {
$default_array[]=array('id' => 'default','text' => TEXT_NO_FILE);
$default_value=$pInfo->product_template;
$files=array_merge($default_array,$files);
}
echo '<td class="main">'.TEXT_CHOOSE_INFO_TEMPLATE.':</td>';
echo '<td><span class="main">'.twe_draw_pull_down_menu('product_template',$files,$default_value,'id="pChange"');
?>
        </span></td>
      </tr>
      <tr>


          <?php
        $files=array();
 if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_options/')){
 while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_options/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
 }
 // set default value in dropdown!
 $default_array=array();
if ($content['content_file']=='') {
$default_array[]=array('id' => 'default','text' => TEXT_SELECT);
$default_value=$pInfo->options_template;
$files=array_merge($default_array,$files);
} else {
$default_array[]=array('id' => 'default','text' => TEXT_NO_FILE);
$default_value=$pInfo->options_template;
$files=array_merge($default_array,$files);
}
echo '<td class="main">'.TEXT_CHOOSE_OPTIONS_TEMPLATE.':'.'</td>';
echo '<td><span class="main">'.twe_draw_pull_down_menu('options_template',$files,$default_value,'id="pChange"');
?>
        </span></td>
      </tr>
      
    </table></td>
  </tr>
</table>
<div id="group_tab">
<?php
if (GROUP_CHECK=='true') {
$customers_statuses_array = twe_get_customers_statuses();
$customers_statuses_array=array_merge(array(array('id'=>'all','text'=>TXT_ALL)),$customers_statuses_array);
?>
<table width="100%" border="0">
<tr>
<td style="border-top: 1px solid; border-color: #ff0000;" valign="top" class="main" ><?php echo ENTRY_CUSTOMERS_STATUS; ?></td>
<td style="border-top: 1px solid; border-left: 1px solid; border-color: #ff0000;"  bgcolor="#FFCC33" class="main">
<?php

for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
if (strstr($pInfo->group_ids,'c_'.$customers_statuses_array[$i]['id'].'_group')) {

$checked='checked ';
} else {
$checked='';
}
echo '<input id="pChecked" type="checkbox" name="groups[]" value="'.$customers_statuses_array[$i]['id'].'"'.$checked.'> '.$customers_statuses_array[$i]['text'].'<br>';
}
?>
</td>
</tr>
</table>
<?php
}
?>
</div>
<br />
<table width="100%" border="0">
        <tr>
          <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td><?php include(DIR_WS_MODULES.'group_prices.php'); ?></td>
        </tr>
        <tr>
          <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
</table>
    </form>
<div id="Tabbed_product">
 <ul>
  <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
    <li><a href="#<?php echo $languages[$i]['name']?>" class="<?php echo $languages[$i]['name']?>"><?php echo twe_image(DIR_WS_LANGUAGES . $languages[$i]['directory'] .'/'. $languages[$i]['image'], $languages[$i]['name']); ?></a></li>
  <?php } ?>
  </ul>
  <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
<div id="<?php echo $languages[$i]['name']?>">
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td width="120" class="main"><?php echo TEXT_PRODUCTS_NAME; ?></td>
	<td class="main"><?php echo twe_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : twe_get_products_name($pInfo->products_id, $languages[$i]['id'])),'id="pdChange" size=60'); ?></td>
  </tr>
  <tr>
    <td class="main"><?php echo TEXT_PRODUCTS_URL; ?></td>
	<td class="main"><?php echo twe_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (($products_url[$languages[$i]['id']]) ? stripslashes($products_url[$languages[$i]['id']]) : twe_get_products_url($pInfo->products_id, $languages[$i]['id'])),'id="pdChange" size=60'); ?><?php '&nbsp;<small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
  </tr>
 <tr>
   <td class="main"><?php echo TEXT_META_TITLE; ?></td>
   <td class="main"><?php echo twe_draw_input_field('products_meta_title[' . $languages[$i]['id'] . ']', (($products_meta_title[$languages[$i]['id']]) ? stripslashes($products_meta_title[$languages[$i]['id']]) : twe_get_products_meta_title($pInfo->products_id, $languages[$i]['id'])),'class="pdMetaChange" size=60'); ?></td>
  </tr> 
  <tr>
    <td class="main"><?php echo TEXT_META_DESCRIPTION; ?></td>
	<td class="main"><?php echo twe_draw_input_field('products_meta_description[' . $languages[$i]['id'] . ']', (($products_meta_description[$languages[$i]['id']]) ? stripslashes($products_meta_description[$languages[$i]['id']]) : twe_get_products_meta_description($pInfo->products_id, $languages[$i]['id'])),'class="pdMetaChange" size=60'); ?></td>
  </tr>
  <tr>
    <td class="main"><?php echo TEXT_META_KEYWORDS; ?></td>
	<td class="main"><?php echo twe_draw_input_field('products_meta_keywords[' . $languages[$i]['id'] . ']', (($products_meta_keywords[$languages[$i]['id']]) ? stripslashes($products_meta_keywords[$languages[$i]['id']]) : twe_get_products_meta_keywords($pInfo->products_id, $languages[$i]['id'])),'class="pdMetaChange" size=60'); ?></td>
  </tr>
</table>
<form id="products_description_<?php echo $languages[$i]['id']?>" action="">
<table width="100%" border="0">
  <tr>
    <td class="main"><b><?php echo TEXT_PRODUCTS_DESCRIPTION; ?></b><br>
<!--// debug SQL code - 20180321 
      <div id="debug_SQL">debug_SQL</div><br>
      -->
<!-- 修改文字框的id, 不再與form id相同 -20180321 -->
      <?php echo twe_draw_textarea_field('txt_products_description_' . $languages[$i]['id'], 'soft', '130', '50', (($products_description[$languages[$i]['id']]) ? stripslashes($products_description[$languages[$i]['id']]) : twe_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
  </tr>
  <tr>
  <td align="right" class="main"><input type="submit" value="<?php echo IMAGE_UPDATE.TEXT_PRODUCTS_DESCRIPTION?>" /></td>
  </tr>
  </table> </form>
  <form id="products_short_description_<?php echo $languages[$i]['id']?>" action="">									  
<table width="100%" border="0">
  <tr>
    <td class="main" valign="top"><b><?php echo TEXT_PRODUCTS_SHORT_DESCRIPTION; ?></b><br>
      <!-- 修改文字框的id, 不再與form id相同 -20180321 -->
      <?php echo twe_draw_textarea_field('txt_products_short_description_' . $languages[$i]['id'], 'soft', '100', '25', (($products_short_description[$languages[$i]['id']]) ? stripslashes($products_short_description[$languages[$i]['id']]) : twe_get_products_short_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
</tr>
<tr>
<td align="right" class="main"><input type="submit" value="<?php echo IMAGE_UPDATE.TEXT_PRODUCTS_SHORT_DESCRIPTION?>" /></td>
  </tr>
</table></form>
</div>
<?php } ?>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<?php
if ($_GET['action']=='new_product') {
       echo '<form id="upload" method="post" action="categories.php?rType=ajax" enctype="multipart/form-data" ><td class="main">'.TEXT_PRODUCTS_IMAGE.'<br>'.twe_draw_file_field('products_image', 'id="products_image"') . '<br>' . twe_draw_separator('pixel_trans.gif', '24', '15') . twe_draw_hidden_field('products_previous_image_0', $pInfo->products_image).twe_draw_hidden_field('action', 'uploadimg').twe_draw_hidden_field('pid', $_GET['pID']).'</form>';
		echo '<div id="view">';
		if ($pInfo->products_image){
		echo '<tr><td colspan="4"><table class="display"><tr><td align="center" class="main" width="'.(PRODUCT_IMAGE_THUMBNAIL_WIDTH+15).'">' . twe_image(DIR_WS_CATALOG_THUMBNAIL_IMAGES.$pInfo->products_image, 'Standard Image') . '<br>' . $pInfo->products_image .'</td></tr></table>';
		} else {
		echo '</tr>';
		}
		echo '</div>';	
?>	
	           
<?php
        // display MORE PICS
if (MORE_PICS > 0){
 $mo_images = twe_get_more_images($pInfo->products_id); 
?>
<tr><td class="main" colspan="4">
 <div id="Tabbed_mopic">
  	<ul>
<?php for ($i=0;$i<MORE_PICS;$i++) { ?>
  			<li><a href="#<?php echo $i ?>" class="<?php echo $i ?>"><?php echo TEXT_PRODUCTS_IMAGE.' '.($i+1); ?></a></li>
<?php }?>
	</ul>
<?php for ($i=0;$i<MORE_PICS;$i++) { ?>
	<div id="<?php echo $i ?>">
<?php	
    echo '<div id="view'.$i.'"><form id="more_upload'.$i.'" method="post" action="categories.php?rType=ajax" enctype="multipart/form-data" >'.twe_draw_file_field('mo_pics_'.$i, 'id="mo_pics_'.$i.'"') . twe_draw_separator('pixel_trans.gif', '10', '15') .twe_draw_hidden_field('action', 'uploadmoreimg').twe_draw_hidden_field('pid', $_GET['pID']).twe_draw_hidden_field('lan_id', $i).'</form></div>';
?>    
    
    <div id="mo_view<?php echo $i ?>">
	<?php echo (($mo_images[$i]["image_name"])? '<div id="remove">'.twe_image(DIR_WS_CATALOG_THUMBNAIL_IMAGES.$mo_images[$i]["image_name"], 'Image '.($i+1)).'<a href="Javascript:void();" linkData="action=removeimg&pid='.$_GET['pID'].'&lan_id='.$i.'&img='.$mo_images[$i]["image_name"].'" id="removeImg'.$i.'"><img border="0" src="'.DIR_WS_IMAGES.'icons/cross.gif"><span class="main">'. TEXT_DELETE.'</span></a><span class="main">'.$mo_images[$i]["image_name"].'</span></div>' : '');?>
    </div>
</div><?php
	    }
?></div></td></tr><?php
        }  
		
   }  
?>
<tr>
      <td class="main" align="right"><?php echo '<a href="' . twe_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $_GET['pID']) . '">' . twe_image_button('button_confirm_red.gif', IMAGE_CONFIRM) . '</a>' ?></td> 
   </tr>
</table>
</td>
</tr>
    