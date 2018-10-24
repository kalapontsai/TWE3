<?php
/*
  $Id: new_products.php,v 1.1.1.1 2004/08/14 08:01:09 oldpa Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
   (c) 2003	 xt-commerce  www.xt-commerce.com

  Released under the GNU General Public License
*/
  include('includes/application_top.php');
  $smarty = new Smarty;  
  $smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');

  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_products_data_array.inc.php');
  header('Content-type: text/html; charset=utf-8');

  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock= ' and p.products_fsk18!=1';
  }
  $group_check= '';
  if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
 function new_products(){ 
 global $db,$group_check,$fsk_lock; 
   $new_products_query = "SELECT * FROM
			                                         " . TABLE_PRODUCTS . " p,
			                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE
			                                         p.products_id=pd.products_id 
			                                         " . $group_check . "
			                                         " . $fsk_lock . "
			                                         and p.products_status = '1' and pd.language_id = '" . (int) $_SESSION['languages_id'] . "'
			                                         order by p.products_date_added DESC";
  $new_products = $db->Execute($new_products_query,MAX_DISPLAY_INDEX_NEW_PRODUCTS,SQL_CACHE,CACHE_LIFETIME);
  if ($new_products->fields > 0) {
  $module_content='';	  
  $module_content = array();
  while (!$new_products->EOF) {   
    $module_content[] = twe_products_data_array($new_products->fields);
   $new_products->MoveNext();
  }
 }
 return $module_content;
}

function products_featured(){ 
 global $db,$group_check,$fsk_lock,$buy_now; 
   								$new_products_query = "SELECT * FROM
			                                         " . TABLE_PRODUCTS . " p,
			                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE
			                                         p.products_id=pd.products_id 
			                                         " . $group_check . "
			                                         " . $fsk_lock . "
													 and p.products_featured = '1'
			                                         and p.products_status = '1' and pd.language_id = '" . (int) $_SESSION['languages_id'] . "'
			                                         order by p.products_ordered DESC";
  $new_products = $db->Execute($new_products_query,MAX_DISPLAY_INDEX_FEATURED,SQL_CACHE,CACHE_LIFETIME);
  if ($new_products->fields > 0) {
  $module_content='';	  
  $module_content = array();
  while (!$new_products->EOF) {   
    $module_content[] = twe_products_data_array($new_products->fields);
   $new_products->MoveNext();
  }
 }
 return $module_content;
}
function products_best(){ 
 global $db,$group_check,$fsk_lock; 
   								$new_products_query = "SELECT * FROM
			                                         " . TABLE_PRODUCTS . " p,
			                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE
			                                         p.products_id=pd.products_id 
			                                         " . $group_check . "
			                                         " . $fsk_lock . "
													 and p.products_ordered > 0
			                                         and p.products_status = '1' and pd.language_id = '" . (int) $_SESSION['languages_id'] . "'
			                                         order by p.products_ordered DESC";
  $new_products = $db->Execute($new_products_query,MAX_DISPLAY_INDEX_BESTSELLERS,SQL_CACHE,CACHE_LIFETIME);
  if ($new_products->fields > 0) {
  $module_content='';	  
  $module_content = array();
  while (!$new_products->EOF) {   
    $module_content[] = twe_products_data_array($new_products->fields);
   $new_products->MoveNext();
  }
 }
 return $module_content;
}

function specials_center(){ 
 global $db,$group_check,$fsk_lock; 
   								$new_products_query = "select p.products_id,
                                pd.products_name,
								pd.products_short_description,
                                p.products_price,
								p.products_discount_allowed,
                                p.products_tax_class_id,
                                p.products_image,
								p.products_fsk18,
								s.expires_date,
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
  $new_products = $db->Execute($new_products_query,MAX_DISPLAY_INDEX_SPECIAL_PRODUCTS,SQL_CACHE,CACHE_LIFETIME);
  if ($new_products->fields > 0) {
  $module_content='';	  
  $module_content = array();
  while (!$new_products->EOF) {   
    $module_content[] = twe_products_data_array($new_products->fields);
   $new_products->MoveNext();
  }
 }
 return $module_content;
}
function upcoming_products(){ 
global $db,$group_check,$fsk_lock; 
   								$new_products_query = "select p.products_id, p.products_image,
                                  pd.products_name,
								  pd.products_short_description,
                                  products_date_available as date_expected from " .
                                  TABLE_PRODUCTS . " p, " .
                                  TABLE_PRODUCTS_DESCRIPTION . " pd
                                  where to_days(products_date_available) >= to_days(now())
                                  and p.products_id = pd.products_id
                                  ".$group_check."
                                  and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                  order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT;
  $new_products = $db->Execute($new_products_query,MAX_DISPLAY_UPCOMING_PRODUCTS,SQL_CACHE,CACHE_LIFETIME);
  if ($new_products->fields > 0) {
  $module_content='';	  
  $module_content = array();
  while (!$new_products->EOF) {   
    $module_content[] = twe_products_data_array($new_products->fields);
   $new_products->MoveNext();
  }
 }
 return $module_content;
}
function center_news(){ 
global $db,$fsk_lock;
  require_once(DIR_FS_INC . 'twe_row_number_format.inc.php');
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');

        $listing_sql = "select p.products_fsk18,
                               p.products_model,
                               pd.products_name,
							   pd.products_viewed, 
                               p.products_image,
							   p.products_last_modified,
							   p.products_date_added,  
                               p.products_id
                               from
							    " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " pd,
								" . TABLE_NEWS_PRODUCTS . " p, 
							    " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " p2c
                               where p.products_status = '1'
                               and p.products_id = p2c.products_id
                               and pd.products_id = p2c.products_id                               
                               and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' ".$fsk_lock."
                               ORDER BY p.products_sort ASC";
     $listing = $db->Execute($listing_sql,MAX_DISPLAY_INDEX_NEWS,SQL_CACHE,CACHE_LIFETIME);

  $module_content=array();
  if ($listing->fields > 0) {
    $rows = 0;
    while (!$listing->EOF) {
      $rows++;   
	if (strlen($listing->fields['products_name']) > 20){ 
		$line_title = mb_substr($listing->fields['products_name'],'0','20','UTF-8')." ..."; 
      }else{ 
         $line_title = $listing->fields['products_name']; 
      } 
  		$image='';
      if ($listing->fields['products_image']!='') {
      $image=DIR_WS_IMAGES .'news_product/'. $listing->fields['products_image'];
      }else{
      $image=DIR_WS_IMAGES .'pixel_trans.gif';
  	  }
	  $last_news = '';
	  if($listing->fields['products_last_modified'] ==''){
	  $last_news = sprintf(TEXT_NEWS_DATE_ADDED, twe_date_long($listing->fields['products_date_added']));
	  }else{
	  $last_news = sprintf(TEXT_NEWS_DATE_ADDED, twe_date_long($listing->fields['products_last_modified']));
	  }

	  $module_content[]=array(
	                       'VIEWED' => sprintf(TEXT_NEWS_VIEWED, $listing->fields['products_viewed']), 
	                       'LAST_TIME' => $last_news,
	                       'MORE' => READ_MORE_NEWS,
	                       'ID'=> twe_row_number_format($rows),
						   'NAME'=> $line_title,
						   'IMAGE'=> $image,
						   'LINK'=>twe_href_link(FILENAME_NEWS_PRODUCT_INFO, 'newsid=' . $listing->fields['products_id']));
		$listing->MoveNext();
    }    
 }
 return $module_content;
}


 $action = (isset($_POST['action']) ? $_POST['action'] : '');
  if (twe_not_null($action)){
	  switch($action){
  case 'getShopContent':
  $shop_content_query="SELECT
                     content_title,
                     content_heading,
                     content_text,
                     content_file
                     FROM ".TABLE_CONTENT_MANAGER."
                     WHERE content_group='5'
                     AND languages_id='".$_SESSION['languages_id']."'";
     $shop_content_data=$db->Execute($shop_content_query,'',SQL_CACHE,CACHE_LIFETIME);
	if ($shop_content_data->fields['content_file']!=''){
       ob_start();
                  if (strpos($shop_content_data->fields['content_file'],'.txt')) echo '<pre>';
                       include(DIR_FS_CATALOG.'media/content/'.$shop_content_data->fields['content_file']);
                  if (strpos($shop_content_data->fields['content_file'],'.txt')) echo '</pre>';
                  $shop_content_data->fields['content_text']=ob_get_contents();
       ob_end_clean();
        }
   echo $shop_content_data->fields['content_heading'];	
   echo $shop_content_data->fields['content_text'];
  break;
		  
  case 'getUpcomingProducts':
  $module_content = upcoming_products();	  
  $smarty->assign('module_content',$module_content);
  $smarty->assign('language', $_SESSION['language']);
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/upcoming_products.html');
   }else{
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_SESSION['currency'];
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/upcoming_products.html',$cache_id);
   }
   break;	  
  case 'getCenterNews':
  $module_content = center_news();	
  $smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
  $smarty->assign('heading_text',HEADER_NEWS);
  $smarty->assign('module_content',$module_content);
  $smarty->assign('language', $_SESSION['language']);
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/center_news.html');
   }else{
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_SESSION['currency'];
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/center_news.html',$cache_id);
   }
   break;		  
  case 'getNewProducts':
  $module_content = new_products();	  
  $smarty->assign('module_content',$module_content);
  $smarty->assign('language', $_SESSION['language']);
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/new_products_default.html');
   }else{
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_SESSION['currency'];
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/new_products_default.html',$cache_id);
   }
   break;
  case 'getProductsFeatured':
  $module_content = products_featured();	  
  $smarty->assign('module_content',$module_content);
  $smarty->assign('language', $_SESSION['language']);
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html');
   }else{
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_SESSION['currency'];
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html',$cache_id);
   }
   break;
     case 'getProductsBest':
  $module_content = products_best();	  
  $smarty->assign('module_content',$module_content);
  $smarty->assign('language', $_SESSION['language']);
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/products_best.html');
   }else{
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_SESSION['currency'];
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/products_best.html',$cache_id);
   }
    break;
     case 'getProductsSpecials':
  $module_content = specials_center();	  
  $smarty->assign('module_content',$module_content);
  $smarty->assign('language', $_SESSION['language']);
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/specials_center.html');
   }else{
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_SESSION['currency'];
  $smarty->display(CURRENT_TEMPLATE.'/module/center_modules/specials_center.html',$cache_id);
   }
    break;
		  }
	  twe_session_close();
      exit;
  }
?>