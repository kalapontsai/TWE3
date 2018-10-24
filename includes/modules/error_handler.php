<?php
/* -----------------------------------------------------------------------------------------
   $Id: error_handler.php,v 1.1 2004/04/26 10:31:17 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

   $module_smarty= new Smarty;
   $module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  require_once(DIR_FS_INC . 'twe_hide_session_id.inc.php');



  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('ERROR',$error);
  $module_smarty->assign('BUTTON','<a href="javascript:history.back(1)">'. twe_image_button('button_back.gif', IMAGE_BUTTON_CONTINUE).'</a>');
  $module_smarty->assign('language', $_SESSION['language']);

  // search field
  $module_smarty->assign('FORM_ACTION',twe_draw_form('new_find', twe_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get').twe_hide_session_id());
  $module_smarty->assign('INPUT_SEARCH',twe_draw_input_field('keywords', '', 'size="30" maxlength="30"'));
  $module_smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_add_quick.gif', BOX_HEADING_SEARCH).'</form>');
  $module_smarty->assign('LINK_ADVANCED',twe_href_link(FILENAME_ADVANCED_SEARCH));



  $module_smarty->caching = 0;
  $module_smarty->caching = 0;
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/error_message.html');

  if (strstr($PHP_SELF, FILENAME_PRODUCT_INFO))  $product_info=$module;

  $smarty->assign('main_content',$module);
?>