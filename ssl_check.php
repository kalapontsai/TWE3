<?php
/* -----------------------------------------------------------------------------------------
   $Id: ssl_check.php,v 1.3 2004/02/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ssl_check.php,v 1.1 2003/03/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (ssl_check.php,v 1.9 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  //include needed functions
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');

  $breadcrumb->add(NAVBAR_TITLE_SSL_CHECK, twe_href_link(FILENAME_SSL_CHECK,'','SSL'));

 require(DIR_WS_INCLUDES . 'header.php');   $smarty->assign('BUTTON_CONTINUE','<a href="' . twe_href_link(FILENAME_DEFAULT,'','SSL') . '">' . twe_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>');
  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $smarty->assign('main_content',$main_content);
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
  ?>