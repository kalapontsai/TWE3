<?php
/* --------------------------------------------------------------

  TWE-Commerce - community made shopping
  http://www.oldpa.com.tw

   Copyright (c) 2005 TWE-Commerce
  --------------------------------------------------------------
  based on:
  (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
  (c) 2002-2003 osCommerce (configure.php,v 1.14 2003/02/21); www.oscommerce.com
  (c)  xt-Commerce  www.xt-commerce.com

  Released under the GNU General Public License
  --------------------------------------------------------------*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://shop.elhomeo.com'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://shop.elhomeo.com'); // eg, https://localhost - should not be empty for productive servers
  define('HTTP_CATALOG_SERVER', 'http://shop.elhomeo.com');
  define('HTTPS_CATALOG_SERVER', 'https://shop.elhomeo.com');
  define('ENABLE_SSL', false); // secure webserver for checkout procedure?
  define('HTTP_COOKIE_DOMAIN', 'shop.elhomeo.com');
  define('HTTPS_COOKIE_DOMAIN', 'shop.elhomeo.com');
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_FS_DOCUMENT_ROOT', '/home/elhomeocom/twe305_SSL/'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '/admin/'); // absolute path required
  define('DIR_FS_ADMIN', '/home/elhomeocom/twe305_SSL/admin/'); // absolute pate required
  define('DIR_WS_CATALOG', '/'); // absolute path required
  define('DIR_FS_CATALOG', '/home/elhomeocom/twe305_SSL/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_ORIGINAL_IMAGES', DIR_FS_CATALOG_IMAGES .'product_images/original_images/');
  define('DIR_FS_CATALOG_THUMBNAIL_IMAGES', DIR_FS_CATALOG_IMAGES .'product_images/thumbnail_images/');
  define('DIR_FS_CATALOG_INFO_IMAGES', DIR_FS_CATALOG_IMAGES .'product_images/info_images/');
  define('DIR_FS_CATALOG_POPUP_IMAGES', DIR_FS_CATALOG_IMAGES .'product_images/popup_images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_CATALOG_ORIGINAL_IMAGES', DIR_WS_CATALOG_IMAGES .'product_images/original_images/');
  define('DIR_WS_CATALOG_THUMBNAIL_IMAGES', DIR_WS_CATALOG_IMAGES .'product_images/thumbnail_images/');
  define('DIR_WS_CATALOG_INFO_IMAGES', DIR_WS_CATALOG_IMAGES .'product_images/info_images/');
  define('DIR_WS_CATALOG_POPUP_IMAGES', DIR_WS_CATALOG_IMAGES .'product_images/popup_images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_CATALOG. 'lang/');
  define('DIR_FS_LANGUAGES', DIR_FS_CATALOG. 'lang/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
  define('DIR_FS_INC', DIR_FS_CATALOG . 'inc/');
  define('DIR_FS_FILE_MANAGER_ROOT', '/home/elhomeocom/twe305_SSL');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');

// define our database connection
  define('DB_CHARSET', 'UTF8');
  define('DB_TYPE', 'mysql');
  define('DIR_FS_SQL_CACHE', DIR_FS_DOCUMENT_ROOT .'cache');
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'elhomeoc_twe305');
  define('DB_SERVER_PASSWORD', 'x(x#b%nfMH03');
  define('DB_DATABASE', 'elhomeoc_twe305');
  define('USE_PCONNECT', 'false'); // use persisstent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
  define('TIMEZONE_OFFSET', "ADDTIME(now(),'0:0:0')");  // use for vitual host timezone offset to Taiwan

?>
