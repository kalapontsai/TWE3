<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_sorting.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(database.php,v 1.19 2003/03/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_db_perform.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_sorting($price, $name, $date) {  
    global $PHP_SELF; 
  
  //$PHP_SELF = str_replace("/","",$PHP_SELF);
$nav='<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="height:40px">
    <td width="33%">
	<table width="100%" border="0">
     <tr>
    <td class="main"><a href="'.$PHP_SELF.'?sorting='.$name.'&'.twe_get_all_get_params(array('action','sorting')).'">'.twe_image(DIR_WS_ICONS . 'sort_down.gif', TEXT_ASCENDINGLY, '16' ,'16').'</a>'.TXT_NAME.'<a href="'.$PHP_SELF.'?sorting='.$name.' desc&'.twe_get_all_get_params(array('action','sorting')).'">'.twe_image(DIR_WS_ICONS . 'sort_up.gif', TEXT_DESCENDINGLY, '16' ,'16').'</a></td>
  </tr>
</table>
 </td>
    <td width="33%">
	<table width="100%" border="0">
     <tr>
    <td class="main"><a href="'.$PHP_SELF.'?sorting='.$price.'&'.twe_get_all_get_params(array('action','sorting')).'">'.twe_image(DIR_WS_ICONS . 'sort_down.gif', TEXT_ASCENDINGLY, '16' ,'16').'</a>'.TXT_PRICES.'<a href="'.$PHP_SELF.'?sorting='.$price.' desc&'.twe_get_all_get_params(array('action','sorting')).'">'.twe_image(DIR_WS_ICONS . 'sort_up.gif', TEXT_DESCENDINGLY, '16' ,'16').'</a></td>
  </tr>
</table>
</td>
    <td width="33%">
	<table width="100%" border="0">
     <tr>
    <td class="main"><a href="'.$PHP_SELF.'?sorting='.$date.'&'.twe_get_all_get_params(array('action','sorting')).'">'.twe_image(DIR_WS_ICONS . 'sort_down.gif', TEXT_ASCENDINGLY, '16' ,'16').'</a>'.TXT_DATE.'<a href="'.$PHP_SELF.'?sorting='.$date.' desc&'.twe_get_all_get_params(array('action','sorting')).'">'.twe_image(DIR_WS_ICONS . 'sort_up.gif', TEXT_DESCENDINGLY, '16' ,'16').'</a></td>
  </tr>
</table>
</td>
  </tr>
</table>';
return $nav;
  }
 ?>