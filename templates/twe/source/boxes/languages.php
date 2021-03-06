<?php
/* -----------------------------------------------------------------------------------------
   $Id: languages.php,v 1.2 2004/02/17 16:20:07 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(languages.php,v 1.14 2003/02/12); www.oscommerce.com
   (c) 2003	 nextcommerce (languages.php,v 1.8 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

  // include needed functions
  require_once(DIR_FS_INC . 'twe_get_all_get_params.inc.php');

  global $lng;

  if (!isset($lng) && !is_object($lng)) {
    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language;
  }

  $languages_string = '';
  $count_lng='';
  reset($lng->catalog_languages);
  while (list($key, $value) = each($lng->catalog_languages)) {
  $count_lng++;
    if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
      $languages_string .= ' <a href="' . twe_href_link(basename($PHP_SELF), 'language=' . $key.'&'.twe_get_all_get_params(array('language', 'currency')), $request_type) . '">' . twe_image('lang/' .  $value['directory'] .'/' . $value['image'], $value['name']) . '</a> ';
    } else {
      $languages_string .= ' <a href="' . twe_href_link(basename($PHP_SELF), 'language=' . $key.'&amp;'.twe_get_all_get_params(array('language', 'currency')), $request_type) . '">' . twe_image('lang/' .  $value['directory'] .'/' . $value['image'], $value['name']) . '</a> ';
    }
  }

  // dont show box if there's only 1 language
  if ($count_lng > 1 ) {

 $box_smarty = new smarty;
 $box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
 $box_content='';
 $box_smarty->assign('BOX_CONTENT', $languages_string);
 $box_smarty->assign('language', $_SESSION['language']);


    	  // set cache ID
  $box_smarty->caching = 0;
  $box_languages= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_languages.html');
  $smarty->assign('languages',$box_languages);
  }
   ?>