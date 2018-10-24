<?php
/* -----------------------------------------------------------------------------------------
   $Id: specials.php,v 1.3 2004/03/16 14:59:01 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(specials.php,v 1.30 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (specials.php,v 1.10 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
$box_smarty = new smarty;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$box_content='';
  // include needed functions
  require_once(DIR_FS_INC . 'twe_random_select.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');

      //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  $random_product_query = "select
                                           p.products_id,
                                           pd.products_name,
                                           p.products_price,
										   p.products_discount_allowed,
                                           p.products_tax_class_id,
                                           p.products_image,
                                           s.expires_date,
                                           s.specials_new_products_price
                                           from " . TABLE_PRODUCTS . " p,
                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                           " . TABLE_SPECIALS .
                                           " s where p.products_status = '1'
                                           and p.products_id = s.products_id
                                           and pd.products_id = s.products_id ".$fsk_lock."
                                           and pd.language_id = '" . $_SESSION['languages_id'] . "'
                                           and s.status = '1'
                                           ".$group_check."
                                           order by s.specials_date_added
                                           desc"; 
   
    $random_product = $db->ExecuteRandomMulti($random_product_query,MAX_RANDOM_SELECT_SPECIALS);
	if ($random_product->RecordCount() > 0)  {
									   
										   
    $box_smarty->assign('LINK',twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product->fields["products_id"]));
    $box_smarty->assign('IMAGE',DIR_WS_THUMBNAIL_IMAGES . $random_product->fields['products_image']);
    $box_smarty->assign('NAME',$random_product->fields['products_name']);
    $box_smarty->assign('PRICE',twe_get_products_price($random_product->fields['products_id'],$price_special=1,$quantity=1,$random_product->fields['products_price'],$random_product->fields['products_discount_allowed'],$random_product->fields['products_tax_class_id']));
    $box_smarty->assign('EXPIRES',$random_product->fields['expires_date']);

    

    $box_smarty->assign('SPECIALS_LINK',twe_href_link(FILENAME_SPECIALS));


	$box_smarty->assign('language', $_SESSION['language']);
  
  if ($random_product->fields["products_id"]!='') {
       	  // set cache ID
  $box_smarty->caching = 0;
  $box_specials= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_specials.html');
  $smarty->assign('specials',$box_specials);
   } 
}
?>