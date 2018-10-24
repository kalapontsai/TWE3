<?php
/* -----------------------------------------------------------------------------------------
   $Id: application_top.php,v 1.20 2004/04/25 16:30:44 oldpa Exp $

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(application_top.php,v 1.273 2003/05/19); www.oscommerce.com
   (c) 2003	 nextcommerce (application_top.php,v 1.54 2003/08/25); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------
   Third Party contribution:
   Add A Quickie v1.0 Autor  Harald Ponce de Leon

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
  // start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());
  
/* if(defined('ADMIN')){
include('../../includes/classes/errorhandler.php');
  $tweErrorHandler =& tweErrorHandler::getInstance();
  $tweErrorHandler->activate(true);
}else{
  if(!defined('IN_PHPBB')){
  include('includes/classes/errorhandler.php');
  $tweErrorHandler =& tweErrorHandler::getInstance();
  $tweErrorHandler->activate(true);
}else{
 include('../includes/classes/errorhandler.php');
  $tweErrorHandler =& tweErrorHandler::getInstance();
  $tweErrorHandler->activate(true);
}
}*/
  // set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);
//  error_reporting(E_ALL);

  // Set the local configuration parameters - mainly for developers - if exists else the mainconfigure
if (file_exists('includes/local/configure.php')) {
    include('includes/local/configure.php');
    } else {
if(defined('ADMIN')){
    require('../../includes/configure.php');
    }else if(defined('IN_PHPBB')){
   require('../includes/configure.php');
    }else{
   require('includes/configure.php');
   }
}
 if (version_compare(PHP_VERSION, '5.1.0', '>=')) {
	date_default_timezone_set('Asia/Taipei');
  }
  
   
  // define the project version
  define('PROJECT_VERSION', 'Twe-Commerce v3.05-utf8');

  // set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

  // set php_self in the local scope
  $PHP_SELF = $_SERVER['PHP_SELF'];

  // include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

  // include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');


  define('DISPLAY_PRICE_WITH_TAX', true);
  define('TAX_DECIMAL_PLACES','2');
  // include used functions

  require_once(DIR_FS_INC . 'twe_db_perform.inc.php');
  require_once(DIR_FS_INC . 'twe_db_output.inc.php');
  require_once(DIR_FS_INC . 'twe_db_input.inc.php');
  require_once(DIR_FS_INC . 'twe_db_prepare_input.inc.php');
  require_once(DIR_FS_INC . 'twe_get_top_level_domain.inc.php');
  require_once(DIR_FS_INC . 'twe_not_null.inc.php');
  require_once(DIR_FS_INC . 'twe_update_whos_online.inc.php');
  require_once(DIR_FS_INC . 'twe_encrypt_password.inc.php');

  require_once(DIR_FS_INC . 'twe_activate_banners.inc.php');
  require_once(DIR_FS_INC . 'twe_expire_banners.inc.php');
  require_once(DIR_FS_INC . 'twe_expire_specials.inc.php');
  require_once(DIR_FS_INC . 'twe_href_link.inc.php');
  require_once(DIR_FS_INC . 'twe_parse_category_path.inc.php');
  require_once(DIR_FS_INC . 'twe_get_product_path.inc.php');
  require_once(DIR_FS_INC . 'twe_get_parent_categories.inc.php');
  require_once(DIR_FS_INC . 'twe_redirect.inc.php');
  require_once(DIR_FS_INC . 'twe_get_uprid.inc.php');
  require_once(DIR_FS_INC . 'twe_get_all_get_params.inc.php');
  require_once(DIR_FS_INC . 'twe_has_product_attributes.inc.php');
  require_once(DIR_FS_INC . 'twe_image.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_attribute_price.inc.php');
  require_once(DIR_FS_INC . 'twe_check_stock_attributes.inc.php');
  require_once(DIR_FS_INC . 'twe_currency_exists.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_separator.inc.php');
  require_once(DIR_FS_INC . 'twe_remove_non_numeric.inc.php');
  require_once(DIR_FS_INC . 'twe_get_ip_address.inc.php');
  require_once(DIR_FS_INC . 'twe_setcookie.inc.php');
  require_once(DIR_FS_INC . 'twe_check_agent.inc.php');
  require_once(DIR_FS_INC . 'twe_count_cart.inc.php');
  require_once(DIR_FS_INC . 'twe_get_qty.inc.php');
  require_once(DIR_FS_INC . 'create_coupon_code.inc.php');
  require_once(DIR_FS_INC . 'twe_gv_account_update.inc.php');
  require_once(DIR_FS_INC . 'twe_get_tax_rate_from_desc.inc.php');
  require_once(DIR_FS_INC . 'twe_input_validation.inc.php');
  require_once(DIR_FS_INC . 'twe_parse_news_category_path.inc.php');
  require_once(DIR_FS_INC . 'twe_get_news_parent_categories.inc.php');
  require_once(DIR_FS_INC . 'twe_get_news_product_path.inc.php');
  require_once(DIR_FS_INC . 'twe_has_specials.inc.php');
  require_once(DIR_FS_INC . 'twe_has_banners.inc.php');
  require_once(DIR_FS_INC . 'twe_has_tax.inc.php');
  require (DIR_WS_CLASSES . 'query_factory.php');
  $db = new queryFactory();
  $db->connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, USE_PCONNECT);
  $zc_cache = new cache();
  //$db->Execute("SET NAMES 'UTF8'");
  //$db->Execute("SET CHARACTER SET UTF8");
  
// set the application parameters
  
  $configuration_query = 'select configuration_key as cfgkey, configuration_value as cfgvalue
                          from ' . TABLE_CONFIGURATION .' where configuration_key != "USE_CACHE"';

   $configuration = $db->Execute($configuration_query);

  while (!$configuration->EOF) {
    define($configuration->fields['cfgkey'], $configuration->fields['cfgvalue']);
    $configuration->MoveNext();
  }

  // if gzip_compression is enabled, start to buffer the output
  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= '4') ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
      ob_start('ob_gzhandler');
    } else {
      ini_set('zlib.output_compression_level', GZIP_LEVEL);
    }
  }

  // set the HTTP GET parameters manually if search_engine_friendly_urls is enabled
  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    if (strlen(getenv('PATH_INFO')) > 1) {
      $GET_array = array();
      $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);
      $vars = explode('/', substr(getenv('PATH_INFO'), 1));
      for ($i=0, $n=sizeof($vars); $i<$n; $i++) {
        if (strpos($vars[$i], '[]')) {
          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];
        } else {
          $_GET[$vars[$i]] = $vars[$i+1];
        }
        $i++;
      }

      if (sizeof($GET_array) > 0) {
        while (list($key, $value) = each($GET_array)) {
          $_GET[$key] = $value;
        }
      }
    }
  }
require (DIR_WS_CLASSES.'class.inputfilter.php');
$InputFilter = new InputFilter();
$_GET = $InputFilter->process($_GET);
$_POST = $InputFilter->process($_POST); 
$_REQUEST = $InputFilter->process($_REQUEST);
$_GET = $InputFilter->safeSQL($_GET);
$_POST = $InputFilter->safeSQL($_POST); 
$_REQUEST = $InputFilter->safeSQL($_REQUEST);
// set the cookie domain
 $http_domain = twe_get_top_level_domain(HTTP_SERVER);
 $https_domain = twe_get_top_level_domain(HTTPS_SERVER);
 $cookie_domain = (($request_type == 'NONSSL') ? $http_domain : $https_domain);
 $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);
 
  // include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

  // include navigation history class
  require(DIR_WS_CLASSES . 'navigation_history.php');
  
  
  // some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

  // define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

  // set the session name and save path
  session_name('Twesid');
 if (STORE_SESSIONS != 'mysql')  session_save_path(SESSION_WRITE_DIRECTORY);

 // set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, $cookie_path, $cookie_domain);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }

  // set the session ID if it exists
  if (isset($_POST[session_name()])) {
    session_id($_POST[session_name()]);
  } elseif ( ($request_type == 'SSL') && isset($_GET[session_name()]) ) {
    session_id($_GET[session_name()]);
  }

  // start the session
  $session_started = false;
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    twe_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

    if (isset($_COOKIE['cookie_test'])) {
      session_start();
     $session_started = true;
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if (twe_not_null($user_agent)) {
      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
        if (twe_not_null($spiders[$i])) {
          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
            $spider_flag = true;
            break;
          }
        }
      }
    }

    if ($spider_flag == false) {
      session_start();
      $session_started = true;
    }
  } else {
    session_start();
    $session_started = true;
  }

  // verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if (!isset($_SESSION['SSL_SESSION_ID'])) {
      $_SESSION['SESSION_SSL_ID'] = $ssl_session_id;
    }

    if ($_SESSION['SESSION_SSL_ID'] != $ssl_session_id) {
      session_destroy();
      twe_redirect(twe_href_link(FILENAME_SSL_CHECK,'','SSL'));
    }
  }

  // verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if (!isset ($_SESSION['SESSION_USER_AGENT'])) {
      $_SESSION['SESSION_USER_AGENT'] = $http_user_agent;
    }

    if ($_SESSION['SESSION_USER_AGENT'] != $http_user_agent) {
      session_destroy();
      twe_redirect(twe_href_link(FILENAME_LOGIN,'','SSL'));
    }
  }

  // verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = twe_get_ip_address();
    if (!isset($_SESSION['SESSION_IP_ADDRESS'])) {
      $_SESSION['SESSION_IP_ADDRESS'] = $ip_address;
    }

    if ($_SESSION['SESSION_IP_ADDRESS'] != $ip_address) {
      session_destroy();
      twe_redirect(twe_href_link(FILENAME_LOGIN,'','SSL'));
    }
  }

// check the Agent
$agent_session_id = false;
if (CHECK_CLIENT_AGENT) {
	if (twe_check_agent() == 1) {
		$agent_session_id = true;
	}
}
if ( $agent_session_id == true ) {
    if (preg_match('/' . twe_session_name() . '/i', $_SERVER['REQUEST_URI']) ){
        $location = twe_href_link(basename($_SERVER['SCRIPT_NAME']), twe_get_all_get_params(array(twe_session_name())), 'NONSSL', false);
        header("HTTP/1.0 301 Moved Permanently");
        header("Location: $location");
    }
}
//if (!(preg_match('/^[a-z0-9]{26}$/i', session_id()) || preg_match('/^[a-z0-9]{32}$/i', session_id()))) {
 //   session_regenerate_id(true);
//}

if (!isset($_COOKIE['Twesid']) || (isset($_GET['Twesid'])) || (twe_check_agent() == 1)) {
  define('USE_CACHE','false');	
   }else{
$configuration = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION." where configuration_key = 'USE_CACHE'");
  define('USE_CACHE',$configuration->fields['configuration_value']);
}  
  // create the shopping cart & fix the cart if necesary
  if (!is_object($_SESSION['cart'])) {
    $_SESSION['cart'] = new shoppingCart;
  }

  // include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  // include the mail classes
  if (EMAIL_TRANSPORT == 'sendmail') include(DIR_WS_CLASSES . 'class.phpmailer.php');
  if (EMAIL_TRANSPORT == 'smtp') include(DIR_WS_CLASSES . 'class.smtp.php');


   // set the language
if (!isset ($_SESSION['language']) || isset ($_GET['language'])) {

	include (DIR_WS_CLASSES.'language.php');
	$lng = new language(twe_input_validation($_GET['language'], 'char', ''));

	if (!isset ($_GET['language']))
		$lng->get_browser_language();

	$_SESSION['language'] = $lng->language['directory'];
	$_SESSION['languages_id'] = $lng->language['id'];
	$_SESSION['language_charset'] = $lng->language['language_charset'];
	$_SESSION['language_code'] = $lng->language['code'];
}

if (isset($_SESSION['language']) && !isset($_SESSION['language_charset'])) {
	
	include (DIR_WS_CLASSES.'language.php');
	$lng = new language(twe_input_validation($_SESSION['language'], 'char', ''));


	$_SESSION['language'] = $lng->language['directory'];
	$_SESSION['languages_id'] = $lng->language['id'];
	$_SESSION['language_charset'] = $lng->language['language_charset'];
	$_SESSION['language_code'] = $lng->language['code'];
	
}

  // include the language translations
  require(DIR_WS_LANGUAGES . $_SESSION['language'].'/'.$_SESSION['language'] . '.php');

  // currency
  if (!isset($_SESSION['currency']) || isset($_GET['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $_SESSION['currency']) ) ) {

    if (isset($_GET['currency'])) {
      if (!$_SESSION['currency'] = twe_currency_exists($_GET['currency'])) $_SESSION['currency'] = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    } else {
      $_SESSION['currency'] = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
	$currencies_query = "SELECT symbol_left,
          symbol_right,
		  thousands_point,
		  decimal_point,
          decimal_places,
          value
          FROM ". TABLE_CURRENCIES ." WHERE
          code = '".$_SESSION['currency'] ."'";
$currencies_value = $db->Execute($currencies_query);
      $_SESSION['SYMBOL_CURRENCY'] = array();
	  $_SESSION['SYMBOL_CURRENCY']= array(
      'SYMBOL_LEFT'=>$currencies_value->fields['symbol_left'],
      'SYMBOL_RIGHT'=>$currencies_value->fields['symbol_right'],
      'DECIMAL_PLACES'=>$currencies_value->fields['decimal_places'],
      'DECIMAL_POINT'=>$currencies_value->fields['decimal_point'],
      'VALUE'=> $currencies_value->fields['value'],
      'THOUSANDS_POINT'=> $currencies_value->fields['thousands_point']);
  }
  
  // Shopping cart actions
  if (isset($_GET['action'])) {
    // redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
      twe_redirect(twe_href_link(FILENAME_COOKIE_USAGE,'','SSL'));
    }

    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
    } else {
      $goto = basename($PHP_SELF);
      if ($_GET['action'] == 'buy_now') {
        $parameters = array('action', 'pid', 'products_id');
      } else {
        $parameters = array('action', 'pid');
      }
    }
    switch ($_GET['action']) {
      // customer wants to update the product quantity in their shopping cart
      case 'update_product':
        for ($i = 0, $n = sizeof($_POST['products_id']); $i < $n; $i++) {
          if (in_array($_POST['products_id'][$i], (is_array($_POST['cart_delete']) ? $_POST['cart_delete'] : array()))) {
            $_SESSION['cart']->remove($_POST['products_id'][$i]);
          } else {
            $attributes = ($_POST['id'][$_POST['products_id'][$i]]) ? $_POST['id'][$_POST['products_id'][$i]] : '';
            $_SESSION['cart']->add_cart($_POST['products_id'][$i], twe_remove_non_numeric($_POST['cart_quantity'][$i]), $attributes, false);
          }
        }
        twe_redirect(twe_href_link($goto, twe_get_all_get_params($parameters),'SSL'));
        break;
      // customer adds a product from the products page
      case 'add_product':
        if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {
          $_SESSION['cart']->add_cart((int)$_POST['products_id'], $_SESSION['cart']->get_quantity(twe_get_uprid($_POST['products_id'], $_POST['id']))+$_POST['products_qty'], $_POST['id']);
        }
        twe_redirect(twe_href_link($goto, twe_get_all_get_params($parameters),'SSL'));
        break;

      case 'check_gift':
      require_once(DIR_FS_INC .'twe_collect_posts.inc.php');
      twe_collect_posts();
      //echo $_POST['gv_redeem_code'];
      break;
      
            // customer wants to add a quickie to the cart (called from a box)
      case 'add_a_quickie' :
        if (GROUP_CHECK=='true') {
        $group_check="and group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
        }

      $quickie = $db->Execute("select
                         products_fsk18,
                         products_id from " . TABLE_PRODUCTS . "
                         where products_model = '" . $_POST['quickie'] . "'
                         ".$group_check);
							if (!$quickie->RecordCount()) {								 
                                if (GROUP_CHECK=='true') {
                                $group_check="and group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
                                }
                                $quickie = $db->Execute("select
                                                 products_fsk18,
                                                 products_id from " . TABLE_PRODUCTS . "
                                                 where products_model LIKE '%" . $_POST['quickie'] . "%'
                                                 ".$group_check);
                              }
								if ($quickie->RecordCount() != 1) {								 
						  
                                twe_redirect(twe_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . $_POST['quickie'], 'SSL'));
                              }
                              if (twe_has_product_attributes($quickie ->fields['products_id'])) {
                                twe_redirect(twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $quickie ->fields['products_id'], 'SSL'));
                              } else {
                                if ($quickie ->fields['products_fsk18']=='1' && $_SESSION['customers_status']['customers_fsk18']=='1') {
                                twe_redirect(twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $quickie ->fields['products_id'], 'SSL'));
                                }
                                if ($_SESSION['customers_status']['customers_fsk18_display']=='0' && $quickie ->fields['products_fsk18']=='1') {
                                twe_redirect(twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $quickie ->fields['products_id'], 'SSL'));
  								}
                                if ($_POST['quickie']!='') {
                                $_SESSION['cart']->add_cart($quickie->fields['products_id'], $_SESSION['cart']->get_quantity((int)$quickie->fields['products_id'])+1);
                                twe_redirect(twe_href_link($goto, twe_get_all_get_params(array('action')), 'SSL'));
                                } else {
                                 twe_redirect(twe_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . $_POST['quickie'], 'SSL'));
                                }
                              }
                              break;

      // performed by the 'buy now' button in product listings and review page
      case 'buy_now':
	   if (isset($_GET['BUYproducts_id'])) {
        // check permission to view product
        $permission_query="SELECT group_ids from ".TABLE_PRODUCTS." where products_id='".(int)$_GET['BUYproducts_id']."'";
        $permission=$db->Execute($permission_query);
           if (GROUP_CHECK=='true') {

         if (!strstr($permission->fields['group_ids'],'c_'.$_SESSION['customers_status']['customers_status_id'].'_group')) {
          twe_redirect(twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$_GET['BUYproducts_id'],'SSL'));
         }
         }
          if (twe_has_product_attributes($_GET['BUYproducts_id'])) {
            twe_redirect(twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$_GET['BUYproducts_id'],'SSL'));
          } else {
            $_SESSION['cart']->add_cart((int)$_GET['BUYproducts_id'], $_SESSION['cart']->get_quantity((int)$_GET['BUYproducts_id'])+1);
          }
        }
        twe_redirect(twe_href_link($goto, twe_get_all_get_params(array('action')),'SSL'));
        break;
      
      case 'cust_order':
        if (isset($_SESSION['customer_id']) && isset($_GET['pid'])) {
          if (twe_has_product_attributes((int)$_GET['pid'])) {
            twe_redirect(twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$_GET['pid'],'SSL'));
          } else {
            $_SESSION['cart']->add_cart((int)$_GET['pid'], $_SESSION['cart']->get_quantity((int)$_GET['pid'])+1);
          }
        }
        twe_redirect(twe_href_link($goto, twe_get_all_get_params($parameters),'SSL'));
        break;
    }
  }

  // write customers status in session
  require(DIR_WS_INCLUDES . 'write_customers_status.php');

  // include the who's online functions
  twe_update_whos_online();

  // split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

  // infobox
  require(DIR_WS_CLASSES . 'boxes.php');

  // auto activate and expire banners
  $has_banners = twe_has_banners();
  if(sizeof($has_banners) >0){
  twe_activate_banners();
  twe_expire_banners();
}
  // auto expire special products
 $has_specials = twe_has_specials();
  twe_expire_specials();
 $has_tax = twe_has_tax();

  // calculate category path
  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } elseif (isset($_GET['products_id']) && !isset($_GET['manufacturers_id'])) {
    $cPath = twe_get_product_path((int)$_GET['products_id']);
  } else {
    $cPath = '';
  }

  if (twe_not_null($cPath)) {
    $cPath_array = twe_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

// calculate news category path
  if (isset($_GET['news_cPath'])) {
    $news_cPath = $_GET['news_cPath'];
  } elseif (isset($_GET['newsid']) && !isset($_GET['manufacturers_id'])) {
    $news_cPath = twe_get_news_product_path((int)$_GET['newsid']);
  } else {
    $news_cPath = '';
  }

  if (twe_not_null($news_cPath)) {
    $news_cPath_array = twe_parse_news_category_path($news_cPath);
    $news_cPath = implode('_', $news_cPath_array);
    $current_news_category_id = $news_cPath_array[(sizeof($news_cPath_array)-1)];
  } else {
    $current_news_category_id = 0;
  }

  // include the breadcrumb class and start the breadcrumb trail
  require(DIR_WS_CLASSES . 'breadcrumb.php');
  $breadcrumb = new breadcrumb;

  $breadcrumb->add(HEADER_TITLE_TOP, '');
  $breadcrumb->add(HEADER_TITLE_CATALOG, twe_href_link(FILENAME_DEFAULT,'','SSL'));

  // add category names or the manufacturer name to the breadcrumb trail
  if (isset($cPath_array)) {
  $group_check='';
    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
      if (GROUP_CHECK=='true') {
   $group_check="and c.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
      $categories_query = "select
                                        cd.categories_name
                                        from " . TABLE_CATEGORIES_DESCRIPTION . " cd,
                                        ".TABLE_CATEGORIES." c
                                        where cd.categories_id = '" . $cPath_array[$i] . "'
                                        and c.categories_id=cd.categories_id
                                        ".$group_check."
                                        and cd.language_id='" . (int)$_SESSION['languages_id'] . "'";
        $categories = $db->Execute($categories_query);
	  if ($categories->RecordCount() > 0 ) {									
        $breadcrumb->add($categories->fields['categories_name'], twe_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1))),'SSL'));
      } else {
        break;
      }
    }
  } elseif (isset($_GET['manufacturers_id'])) {
    $manufacturers_query = "select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
    $manufacturers = $db->Execute($manufacturers_query);
    $breadcrumb->add($manufacturers->fields['manufacturers_name'], twe_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . (int)$_GET['manufacturers_id'],'SSL'));
  }

  // add the products model to the breadcrumb trail
  if (isset($_GET['products_id'])) {
    $model_query = "select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$_GET['products_id'] . "'";
    $model = $db->Execute($model_query);
    $breadcrumb->add($model->fields['products_model'], twe_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . (int)$_GET['products_id'],'SSL'));
  }

// add news category names or the manufacturer name to the breadcrumb trail
  if (isset($news_cPath_array)) {
  $group_check='';
    for ($i=0, $n=sizeof($news_cPath_array); $i<$n; $i++) {
      if (GROUP_CHECK=='true') {
   $group_check="and c.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
      $categories_query = "select
                                        cd.categories_name
                                        from " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " cd,
                                        ".TABLE_NEWS_CATEGORIES." c
                                        where cd.categories_id = '" . $news_cPath_array[$i] . "'
                                        and c.categories_id=cd.categories_id
                                        ".$group_check."
                                        and cd.language_id='" . (int)$_SESSION['languages_id'] . "'";
         $categories = $db->Execute($categories_query);
     
	  if ($categories->RecordCount() > 0) {
        $breadcrumb->add($categories->fields['categories_name'], twe_href_link(FILENAME_NEWS, 'news_cPath=' . implode('_', array_slice($news_cPath_array, 0, ($i+1))),'SSL'));
      } else {
        break;
      }
    }
  } 
  // add the news products model to the breadcrumb trail
  if (isset($_GET['newsid'])) {
    $model_query = "select products_model from " . TABLE_NEWS_PRODUCTS . " where products_id = '" . (int)$_GET['newsid'] . "'";
    $model = $db->Execute($model_query);
    $breadcrumb->add($model->fields['products_model'], twe_href_link(FILENAME_NEWS_PRODUCT_INFO, 'news_cPath=' . $news_cPath . '&newsid=' . (int)$_GET['newsid'],'SSL'));
   }

  // initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

  // set which precautions should be checked
  define('WARN_INSTALL_EXISTENCE', 'true');
  define('WARN_CONFIG_WRITEABLE', 'true');
  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'false');
  define('WARN_SESSION_AUTO_START', 'true');
  define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');

 
  // Include Template Engine
  //if (version_compare(PHP_VERSION, '5.0.0', '>=')) {
  // require(DIR_WS_CLASSES . 'Smarty_3.1.19/Smarty.class.php');
//}else{
  require(DIR_WS_CLASSES . 'Smarty_2.6.26/Smarty.class.php');
//}
  
  // modification for nre graduated system
  unset($_SESSION['actual_content']);
  twe_count_cart(); 
        
?>