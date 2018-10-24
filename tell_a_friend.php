<?php
/* -----------------------------------------------------------------------------------------
   $Id: tell_a_friend.php,v 1.13 2018/03/28 16:48:30 ELHOMEO
   modify mail content and SSL link
   $Id: tell_a_friend.php,v 1.12 2005/04/22 16:15:30 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(tell_a_friend.php,v 1.39 2003/05/28); www.oscommerce.com
   (c) 2003	 nextcommerce (tell_a_friend.php,v 1.13 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  include('includes/application_top.php');
      $smarty = new Smarty;
      $mail_smarty= new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php');
  // include needed functions
  require_once(DIR_FS_INC . 'twe_draw_textarea_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_validate_email.inc.php');
  require_once(DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');

  if (isset($_SESSION['customer_id'])) {
    
  } elseif (ALLOW_GUEST_TO_TELL_A_FRIEND == 'false') {

    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $valid_product = false;
 if (isset($_GET['products_id'])) {
    $product_info_query = "select pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";

    $product_info = $db->Execute($product_info_query);

    if ($product_info->RecordCount() > 0) {
      $valid_product = true;
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_TELL_A_FRIEND, twe_href_link(FILENAME_TELL_A_FRIEND, 'send_to=' . $_GET['send_to'] . '&products_id=' . $_GET['products_id'],'SSL'));

 require(DIR_WS_INCLUDES . 'header.php');

  if ($valid_product == false) {
twe_redirect(FILENAME_DEFAULT);
  } else {
    $smarty->assign('heading_tell_a_friend',sprintf(HEADING_TITLE_TELL_A_FRIEND, $product_info->fields['products_name']));

    $error = false;

    if (isset($_GET['action']) && ($_GET['action'] == 'process') && !twe_validate_email(trim($_POST['friendemail']))) {
      $friendemail_error = true;
      $error = true;
    } else {
      $friendemail_error = false;
    }

    if (isset($_GET['action']) && ($_GET['action'] == 'process') && empty($_POST['friendname'])) {
      $friendname_error = true;
      $error = true;
    } else {
      $friendname_error = false;
    }

    if (isset($_SESSION['customer_id'])) {
      $from_name = $_SESSION['customer_first_name'] . ' ' . $_SESSION['customer_last_name'];
      $from_email_address = $_SESSION['customer_email_address'];
    } else {
      $from_name = $_POST['yourname'];
      $from_email_address = $_POST['from'];
    }
	  
    if (!isset($_SESSION['customer_id'])) {
      if (isset($_GET['action']) && ($_GET['action'] == 'process') && !twe_validate_email(trim($from_email_address))) {
        $fromemail_error = true;
        $error = true;
      } else {
        $fromemail_error = false;
      }
    }

    if (isset($_GET['action']) && ($_GET['action'] == 'process') && empty($from_name)) {
      $fromname_error = true;
      $error = true;
    } else {
      $fromname_error = false;
    }

    if (isset($_GET['action']) && ($_GET['action'] == 'process') && ($error == false)) {

      $mail_smarty->assign('message',$_POST['yourmessage']);
      $mail_smarty->assign('language', $_SESSION['language']);
      $mail_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
      $mail_smarty->assign('logo_path',HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');
      $mail_smarty->assign('PRODUCTS_LINK',twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id','SSL']));
// add a sender name into mail - 20180328
      $mail_smarty->assign('from_name', $from_name);
      $mail_smarty->caching = 0;
      $html_mail = $mail_smarty->fetch(CURRENT_TEMPLATE . '/mail/'.$_SESSION['language'].'/tell_friend_mail.html');
      $mail_smarty->caching = 0;
      $txt_mail = $mail_smarty->fetch(CURRENT_TEMPLATE . '/mail/'.$_SESSION['language'].'/tell_friend_mail.txt');

      $smarty->assign('action','send');
      $smarty->assign('message',sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, stripslashes($_POST['products_name']), $_POST['friendemail']));
      $smarty->assign('BUTTON_CONTINUE','<a href="' . twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id'],'SSL') . '">' . twe_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>');

      twe_php_mail($from_email_address, $from_name,$_POST['friendemail'],$_POST['friendname'],'', $from_email_address, $from_name, '', '', CONTACT_US_EMAIL_SUBJECT, $html_mail , $txt_mail );

    } else {
      if (isset($_SESSION['customer_id'])) {
        $your_name_prompt = $_SESSION['customer_first_name'] . ' ' . $_SESSION['customer_last_name'];
        $your_email_address_prompt = $_SESSION['customer_email_address'];
      } else {
        $your_name_prompt = twe_draw_input_field('yourname', (($fromname_error == true) ? $_POST['yourname'] : $_GET['yourname']));
        if ($fromname_error == true) $your_name_prompt .= '&nbsp;' . TEXT_REQUIRED;
        $your_email_address_prompt = twe_draw_input_field('from', (($fromemail_error == true) ? $_POST['from'] : $_GET['from']));
        if ($fromemail_error == true) $your_email_address_prompt .= ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
      }

$smarty->assign('FORM_ACTION',twe_draw_form('email_friend', twe_href_link(FILENAME_TELL_A_FRIEND, 'action=process&products_id=' . $_GET['products_id'],'SSL')) . twe_draw_hidden_field('products_name', $product_info->fields['products_name']));
$smarty->assign('INPUT_NAME',$your_name_prompt);
$smarty->assign('INPUT_EMAIL',$your_email_address_prompt);
$smarty->assign('INPUT_MESSAGE',twe_draw_textarea_field('yourmessage', 'soft', 40, 8));

$input_friendname= twe_draw_input_field('friendname', (($friendname_error == true) ? $_POST['friendname'] : $_GET['friendname']));
 if ($friendname_error == true) $input_friendname.= '&nbsp;' . TEXT_REQUIRED;

$input_friendemail= twe_draw_input_field('friendemail', (($friendemail_error == true) ? $_POST['friendemail'] : $_GET['send_to']));
if ($friendemail_error == true) $input_friendemail.= ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
$smarty->assign('INPUT_FRIENDNAME',$input_friendname);
$smarty->assign('INPUT_FRIENDEMAIL',$input_friendemail);

$smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id'],'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
$smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));
    }
  }

  $smarty->assign('language', $_SESSION['language']);
   // set cache ID
  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/tell_a_friend.html');
  $smarty->assign('main_content',$main_content);
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
  ?>