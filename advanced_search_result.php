<?php
/* -----------------------------------------------------------------------------------------
   $Id: advanced_search_result.php,v 1.8 2004/04/05 16:33:49 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(advanced_search_result.php,v 1.68 2003/05/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (advanced_search_result.php,v 1.17 2003/08/21); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
      // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_get_subcategories.inc.php');
  require_once(DIR_FS_INC . 'twe_parse_search_string.inc.php');
  require_once(DIR_FS_INC . 'twe_checkdate.inc.php');
  require_once(DIR_FS_INC . 'twe_get_currencies_values.inc.php');


  $error = 0; // reset error flag to false
  $errorno = 0;

  if ( (isset($_GET['keywords']) && empty($_GET['keywords'])) &&
       (isset($_GET['dfrom']) && (empty($_GET['dfrom']) || ($_GET['dfrom'] == DOB_FORMAT_STRING))) &&
       (isset($_GET['dto']) && (empty($_GET['dto']) || ($_GET['dto'] == DOB_FORMAT_STRING))) &&
       (isset($_GET['pfrom']) && empty($_GET['pfrom'])) &&
       (isset($_GET['pto']) && empty($_GET['pto'])) ) {
    $errorno += 1;
    $error = 1;
  }

  $dfrom_to_check = (($_GET['dfrom'] == DOB_FORMAT_STRING) ? '' : $_GET['dfrom']);
  $dto_to_check = (($_GET['dto'] == DOB_FORMAT_STRING) ? '' : $_GET['dto']);

  if (strlen($dfrom_to_check) > 0) {
    if (!twe_checkdate($dfrom_to_check, DOB_FORMAT_STRING, $dfrom_array)) {
      $errorno += 10;
      $error = 1;
    }
  }  

  if (strlen($dto_to_check) > 0) {
    if (!twe_checkdate($dto_to_check, DOB_FORMAT_STRING, $dto_array)) {
      $errorno += 100;
      $error = 1;
    }
  }  

  if (strlen($dfrom_to_check) > 0 && !(($errorno & 10) == 10) && strlen($dto_to_check) > 0 && !(($errorno & 100) == 100)) {
    if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
      $errorno += 1000;
      $error = 1;
    }
  }

  if (strlen($_GET['pfrom']) > 0) {
    $pfrom_to_check = $_GET['pfrom'];
    if (!settype($pfrom_to_check, "double")) {
      $errorno += 10000;
      $error = 1;
    }
  }

  if (strlen($_GET['pto']) > 0) {
    $pto_to_check = $_GET['pto'];
    if (!settype($pto_to_check, "double")) {
      $errorno += 100000;
      $error = 1;
    }
  }

  if (strlen($_GET['pfrom']) > 0 && !(($errorno & 10000) == 10000) && strlen($_GET['pto']) > 0 && !(($errorno & 100000) == 100000)) {
    if ($pfrom_to_check > $pto_to_check) {
      $errorno += 1000000;
      $error = 1;
    }
  }

  if (strlen($_GET['keywords']) > 0) {
    if (!twe_parse_search_string(stripslashes($_GET['keywords']), $search_keywords)) {
      $errorno += 10000000;
      $error = 1;
    }
  }
  
  if ($error == 1) {
    twe_redirect(twe_href_link(FILENAME_ADVANCED_SEARCH, 'errorno=' . $errorno . '&' . twe_get_all_get_params(array('x', 'y')),'SSL'));
  } else {
    $breadcrumb->add(NAVBAR_TITLE1_ADVANCED_SEARCH, twe_href_link(FILENAME_ADVANCED_SEARCH,'','SSL'));
    $breadcrumb->add(NAVBAR_TITLE2_ADVANCED_SEARCH, twe_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . $_GET['keywords'] . '&search_in_description=' . $_GET['search_in_description'] . '&categories_id=' . $_GET['categories_id'] . '&inc_subcat=' . $_GET['inc_subcat'] . '&manufacturers_id=' . $_GET['manufacturers_id'] . '&pfrom=' . $_GET['pfrom'] . '&pto=' . $_GET['pto'] . '&dfrom=' . $_GET['dfrom'] . '&dto=' . $_GET['dto'],'SSL'));

 require(DIR_WS_INCLUDES . 'header.php');

     //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  // create column list
  $select_str = "select
                 distinct p.products_model,
                 pd.products_name,
                 m.manufacturers_name,
                 p.products_quantity,
                 p.products_fsk18,
                 p.products_image,
                 p.products_weight,
                 pd.products_short_description,
                 pd.products_description,
				 p.products_shippingtime,
                 m.manufacturers_id,
                 p.products_id,
                 pd.products_name,
                 p.products_price,
				 p.products_discount_allowed,
                 p.products_tax_class_id ";

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ( (isset($_GET['pfrom']) && twe_not_null($_GET['pfrom'])) || (isset($_GET['pto']) && twe_not_null($_GET['pto']))) ) {
    $select_str .= ", SUM(tr.tax_rate) as tax_rate ";
  }
    $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id";

  //$from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ( (isset($_GET['pfrom']) && twe_not_null($_GET['pfrom'])) || (isset($_GET['pto']) && twe_not_null($_GET['pto']))) ) {
    if (!isset($_SESSION['customer_country_id'])) {
      $_SESSION['customer_country_id'] = STORE_COUNTRY;
      $_SESSION['customer_zone_id'] = STORE_ZONE;
    }
    $from_str .= " left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$_SESSION['customer_country_id'] . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$_SESSION['customer_zone_id'] . "')";
  }
    if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  
  $from_str .= ", " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";

  //$where_str = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";
  
  $where_str = " where p.products_status = '1' ".$fsk_lock." and
                 p.products_id = pd.products_id
                 and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                 and p.products_id = p2c.products_id
                 ".$group_check."
                 and p2c.categories_id = c.categories_id ";

  if (isset($_GET['categories_id']) && twe_not_null($_GET['categories_id'])) {
    if ($_GET['inc_subcat'] == '1') {
      $subcategories_array = array();
      twe_get_subcategories($subcategories_array, $_GET['categories_id']);
      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$_GET['categories_id'] . "'";
      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
        $where_str .= " or p2c.categories_id = '" . $subcategories_array[$i] . "'";
      }
      $where_str .= ")";
    } else {
      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' and p2c.categories_id = '" . (int)$_GET['categories_id'] . "'";
    }
  }

  if (isset($_GET['manufacturers_id']) && twe_not_null($_GET['manufacturers_id'])) {
    $where_str .= " and m.manufacturers_id = '" . $_GET['manufacturers_id'] . "'";
  }

  if (isset($_GET['keywords']) && twe_not_null($_GET['keywords'])) {
    if (twe_parse_search_string(stripslashes($_GET['keywords']), $search_keywords)) {
      $where_str .= " and (";
      for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
        switch ($search_keywords[$i]) {
          case '(':
          case ')':
          case 'and':
          case 'or':
            $where_str .= " " . $search_keywords[$i] . " ";
            break;
          default:
            $where_str .= "(pd.products_name like '%" . addslashes($search_keywords[$i]) . "%' or p.products_model like '%" . addslashes($search_keywords[$i]) . "%' or m.manufacturers_name like '%" . addslashes($search_keywords[$i]) . "%'";
            if (isset($_GET['search_in_description']) && ($_GET['search_in_description'] == '1')) $where_str .= " or pd.products_description like '%" . addslashes($search_keywords[$i]) . "%'";
            $where_str .= ')';
            break;
        }
      }
      $where_str .= " )";
    }
  }

  if (isset($_GET['dfrom']) && twe_not_null($_GET['dfrom']) && ($_GET['dfrom'] != DOB_FORMAT_STRING)) {
    $where_str .= " and p.products_date_added >= '" . twe_date_raw($dfrom_to_check) . "'";
  }

  if (isset($_GET['dto']) && twe_not_null($_GET['dto']) && ($_GET['dto'] != DOB_FORMAT_STRING)) {
    $where_str .= " and p.products_date_added <= '" . twe_date_raw($dto_to_check) . "'";
  }

  $rate=twe_get_currencies_values($_SESSION['currency']);
  $rate=$rate->fields['value'];
  if ($rate && $_GET['pfrom'] != '') {
    $pfrom = $_GET['pfrom'] / $rate;
  }
  if ($rate && $_GET['pto'] != '') {
      $pto = $_GET['pto'] / $rate;
  }
  
  if ($pfrom !='') $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) >= " . $pfrom . ")";
  if ($pto !='')   $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) <= " . $pto . ")";
  
if (!isset($_GET['sorting']) && !twe_not_null($_GET['sorting'])) {
  $order_str = ' group by pd.products_name order by pd.products_name';
	}else{
  $order_str = ' group by pd.products_name order by  '.$_GET['sorting'].'';
	}

  $listing_sql = $select_str . $from_str . $where_str . $order_str;

  require(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
}
  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
  ?>