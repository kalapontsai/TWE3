<?php
/* -----------------------------------------------------------------------------------------
   $Id: products_new.php,v 1.13 2005/04/23 20:39:46 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(products_new.php,v 1.25 2003/05/27); www.oscommerce.com 
   (c) 2003	 nextcommerce (products_new.php,v 1.16 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
               // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed function
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_products_data_array.inc.php');
  $breadcrumb->add(NAVBAR_TITLE_PRODUCTS_NEW, twe_href_link(FILENAME_PRODUCTS_NEW,'','SSL'));

 require(DIR_WS_INCLUDES . 'header.php');



  $products_new_array = array();
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }   if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  $products_new_query_raw = "select distinct
                                    p.products_id,
                                    p.products_fsk18,
                                    pd.products_name,
									pd.products_short_description,
                                    p.products_image,
                                    p.products_price,
									p.products_discount_allowed,
                                    p.products_tax_class_id,
                                    p.products_date_added,
                                    m.manufacturers_name
                                    from " . TABLE_CATEGORIES . " c left join
                                    " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                                    on (c.categories_id = p2c.categories_id),
									" . TABLE_PRODUCTS . " 
									p left join " . TABLE_MANUFACTURERS . " 
									m on (p.manufacturers_id = m.manufacturers_id) left join
									 " .TABLE_PRODUCTS_DESCRIPTION . " pd 
									 on p.products_id = pd.products_id ".$fsk_lock."
                                    and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
									where
                                    c.categories_status=1
                                    and p.products_id = p2c.products_id 
                                    and products_status = '1'
                                    ".$group_check."
                                    order by p.products_date_added DESC";
                                    
  $products_new_split = new splitPageResults($products_new_query_raw, $_GET['page'], MAX_DISPLAY_PRODUCTS_NEW,'p.products_id');

  if (($products_new_split->number_of_rows > 0)) {
   $smarty->assign('NAVIGATION_BAR','
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText">'.$products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW).'</td>
            <td align="right" class="smallText">'.TEXT_RESULT_PAGE . ' ' . $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, twe_get_all_get_params(array('page', 'info', 'x', 'y'))).'</td>
          </tr>
        </table>

   ');

  }

$module_content='';
  if ($products_new_split->number_of_rows > 0) {
    $products_new = $db->Execute($products_new_split->sql_query);
    while (!$products_new->EOF) {
  $module_content[] = twe_products_data_array($products_new->fields);
$products_new->MoveNext();
    }
  } else {

$smarty->assign('ERROR',TEXT_NO_NEW_PRODUCTS);

  }

  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $smarty->assign('module_content',$module_content);
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/new_products_overview.html');
  $smarty->assign('main_content',$main_content);
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>