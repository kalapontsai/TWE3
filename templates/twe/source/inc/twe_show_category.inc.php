<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_show_category.inc.php,v 1.2 2004/02/22 16:15:30 oldpa Exp $

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com
   (c) 2003	 nextcommerce (twe_show_category.inc.php,v 1.4 2003/08/13); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

     function twe_show_category($counter) {
    global $foo, $categories_string, $id, $db;

    // image for first level

    $img_1='<img src="templates/'.CURRENT_TEMPLATE.'/img/icon_arrow.gif">&nbsp;';

    for ($a=0; $a<$foo[$counter]['level']; $a++) {

      if ($foo[$counter]['level']=='1') {
      $categories_string .= "&nbsp;-&nbsp;";
      }

      $categories_string .= "&nbsp;&nbsp;";

    }
    if ($foo[$counter]['level']=='') {
    if (strlen($categories_string)=='0') {
    $categories_string .='<table width="100%"><tr><td class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">';
    } else {
    $categories_string .='</td></tr></table><table width="100%"><tr><td class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">';
    }

    // image for first level

    $categories_string .= $img_1;
    $categories_string .= '<b><a href="';
    //<img src="templates/zanier/img/recht_small.gif">
    } else {
    $categories_string .= '<a href="';
    }
    if ($foo[$counter]['parent'] == 0) {
      $cPath_new = 'cPath=' . $counter;
    } else {
      $cPath_new = 'cPath=' . $foo[$counter]['path'];
    }

    $categories_string .= twe_href_link(FILENAME_DEFAULT, $cPath_new,'SSL');
    $categories_string .= '">';

    if ( ($id) && (in_array($counter, $id)) ) {
      $categories_string .= '<b>';
    }

    // display category name
    $categories_string .= $foo[$counter]['name'];

    if ( ($id) && (in_array($counter, $id)) ) {
      $categories_string .= '</b>';
    }

    if (twe_has_category_subcategories($counter)) {
      $categories_string .= '';
    }
    if ($foo[$counter]['level']=='') {
    $categories_string .= '</a></b>';
    } else {
    $categories_string .= '</a>';
    }

    if (SHOW_COUNTS == 'true') {
      $products_in_category = twe_count_products_in_category($counter);
      if ($products_in_category > 0) {
        $categories_string .= '&nbsp;(' . $products_in_category . ')';
      }
    }

    $categories_string .= '<br>';

    if ($foo[$counter]['next_id']) {
      twe_show_category($foo[$counter]['next_id']);
    }
  }

?>