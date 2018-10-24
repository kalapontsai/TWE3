<?php
/* -----------------------------------------------------------------------------------------
   $Id: specials.php,v 1.9 2005/03/16 15:01:16 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(specials.php,v 1.47 2003/05/27); www.oscommerce.com 
   (c) 2003	 nextcommerce (specials.php,v 1.12 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
    $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_products_data_array.inc.php');


  $breadcrumb->add(NAVBAR_TITLE_SPECIALS, twe_href_link(FILENAME_SPECIALS));

 require(DIR_WS_INCLUDES . 'header.php');

      //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
    if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  $specials_query_raw = "select p.products_id,
                                pd.products_name,
								pd.products_short_description,
                                p.products_price,
								p.products_discount_allowed,
                                p.products_tax_class_id,
                                p.products_image,
								p.products_fsk18,
                                s.specials_new_products_price from " .
                                TABLE_PRODUCTS . " p, " .
                                TABLE_PRODUCTS_DESCRIPTION . " pd, " .
                                TABLE_SPECIALS . " s
                                where p.products_status = '1'
                                and s.products_id = p.products_id ".$fsk_lock."
                                and p.products_id = pd.products_id
                                ".$group_check."
                                and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                and s.status = '1' order by s.specials_date_added DESC";
  $specials_split = new splitPageResults($specials_query_raw, $_GET['page'], MAX_DISPLAY_SPECIAL_PRODUCTS);
$module_content='';
    $row = 0;
    $specials = $db->Execute($specials_split->sql_query);
    while (!$specials->EOF) {
                    $module_content[] = twe_products_data_array($specials->fields);
   $specials->MoveNext();
    }

if (($specials_split->number_of_rows > 0)) {
$smarty->assign('NAVBAR','
<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText">'.$specials_split->display_count(TEXT_DISPLAY_NUMBER_OF_SPECIALS).'</td>
            <td align="right" class="smallText">'.TEXT_RESULT_PAGE . ' ' . $specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, twe_get_all_get_params(array('page', 'info', 'x', 'y'))).'</td>
          </tr>
        </table>

');
}

  $smarty->assign('module_content',$module_content);
  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/specials.html');
  $smarty->assign('main_content',$main_content);
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
  ?>