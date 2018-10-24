<?php
/* -----------------------------------------------------------------------------------------
   $Id: shop_content.php,v 1.16 2018/04/09 13:30:00 ELHOMEO
   minor change email title for admin
   $Id: shop_content.php,v 1.15 2018/03/28 16:30:00 ELHOMEO
   use Smarty mail template for mail content
   $Id: shop_content.php,v 1.14 2018/03/14 13:19:00 ELHOMEO
   add payment notice function when user got the link from order mail
   test example : https://shop.elhomeo.com/shop_content.php?coID=7&cID=4&oID=2011032102
   https://shop.elhomeo.com/shop_content.php?coID=7&cID=3&oID=2018031505
   ELHOMEO.com
   http://www.elhomeo.com
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(conditions.php,v 1.21 2003/02/13); www.oscommerce.com 
   (c) 2003	 nextcommerce (shop_content.php,v 1.1 2003/08/19); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   (c) 2015  TWE-Commerce http://www.oldpa.com.tw

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  require('includes/application_top.php');
         // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php');

  // include needed functions
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_textarea_field.inc.php');
  require_once(DIR_FS_INC . 'twe_validate_email.inc.php');
  require_once(DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');


  $shop_content_query="SELECT
                content_id,
                content_title,
                content_heading,
                content_text,
                content_file
                FROM ".TABLE_CONTENT_MANAGER."
                WHERE content_group='".(int)$_GET['coID']."'
                AND languages_id='".(int)$_SESSION['languages_id']."'";
  $shop_content_data=$db->Execute($shop_content_query);

  $breadcrumb->add($shop_content_data->fields['content_title'], twe_href_link(FILENAME_CONTENT.'?coID='.(int)$_GET['coID'],'','SSL'));

  if ($_GET['coID']!=7) {
    require(DIR_WS_INCLUDES . 'header.php');
  }
  if ($_GET['coID']==7 && $_GET['action']=='success') {
    require(DIR_WS_INCLUDES . 'header.php');
  }

  $smarty->assign('CONTENT_HEADING',$shop_content_data->fields['content_heading']);


  if ($_GET['coID']==7) {
    $error = false;
    if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
      if (twe_validate_email(trim($_POST['email']))) {
          // use template for mail content - 20180328
          $smarty->assign('message',$_POST['message_body']);
          $smarty->caching = false;
          $txt_mail=$smarty->fetch(CURRENT_TEMPLATE.'/mail/'.$_SESSION['language'].'/shop_content_mail.txt');

        //  save payment message into orders history
        if (isset($_POST['oID']) && (strlen($_POST['oID'])==10)) {
          $order_query = 'Select customers_email_address
                        from '.TABLE_ORDERS.'
                        where orders_id = "'.$_POST['oID'].'"
                        LIMIT 1';
          $order_query_result = $db->Execute($order_query);
          if (($order_query_result->RecordCount() > 0) && ($order_query_result->fields['customers_email_address'] == $_POST['email']) ) {
            $db->Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . $_POST['oID'] . "', '8',  ".TIMEZONE_OFFSET." , '1', '" . $_POST['message_body']  . "')");
          }
          // mail to admin
          twe_php_mail(
             CONTACT_US_EMAIL_ADDRESS,
             CONTACT_US_NAME,
             CONTACT_US_EMAIL_ADDRESS,
             CONTACT_US_NAME,
             '',
             CONTACT_US_EMAIL_ADDRESS,
             CONTACT_US_NAME,
             '',
             '',
             '匯款通知: '.$_POST['contact_name']. '-' .$_POST['oID'],
             nl2br($_POST['message_body']),
             $_POST['message_body']
             );
          // mail to customer
          twe_php_mail(
             CONTACT_US_EMAIL_ADDRESS,
             CONTACT_US_NAME,
             $_POST['email'],
             $_POST['contact_name'],
             '',
             CONTACT_US_EMAIL_ADDRESS,
             CONTACT_US_NAME,
             '',
             '',
             '艾沙順勢糖球屋- 匯款通知: '.$_POST['oID'],
             nl2br($_POST['message_body']),
             $txt_mail
             );
        } else {
                  twe_php_mail(
                     CONTACT_US_EMAIL_ADDRESS,
                     CONTACT_US_NAME,
                     CONTACT_US_EMAIL_ADDRESS,
                     CONTACT_US_NAME,
                     '',
                     $_POST['email'],
                     $_POST['contact_name'],
                     '',
                     '',
                     CONTACT_US_EMAIL_SUBJECT.'-'.$_POST['contact_name'],
                     nl2br($_POST['message_body']),
                     $_POST['message_body']
                     );
                  // add a copy to customer
                  twe_php_mail(
                     CONTACT_US_EMAIL_ADDRESS,
                     CONTACT_US_NAME,
                     $_POST['email'],
                     $_POST['contact_name'],
                     '',
                     CONTACT_US_EMAIL_ADDRESS,
                     CONTACT_US_NAME,
                     '',
                     '',
                     '艾沙順勢糖球屋-'.CONTACT_US_EMAIL_SUBJECT,
                     nl2br($_POST['message_body']),
                     $txt_mail
                     );
        }
        if (!isset($mail_error)) {
          twe_redirect(twe_href_link(FILENAME_CONTENT, 'action=success&coID='.$_GET['coID'],'SSL'));
        } else {
              $smarty->assign('error_message',$mail_error);
            }
      } else {
            // error report hier einbauen
            $smarty->assign('error_message',ERROR_MAIL);
            $error = true;
          }
    }

    $smarty->assign('CONTACT_HEADING',$shop_content_data->fields['content_title']);
    if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
      $smarty->assign('success','1');
      $smarty->assign('BUTTON_CONTINUE','<a href="'.twe_href_link(FILENAME_DEFAULT,'','SSL').'">'.twe_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE).'</a>');
    } else  {
        if ($shop_content_data->fields['content_file']!=''){
          if (strpos($shop_content_data->fields['content_file'],'.txt')) echo '<pre>';
          include(DIR_FS_CATALOG.'media/content/'.$shop_content_data->fields['content_file']);
          if (strpos($shop_content_data->fields['content_file'],'.txt')) echo '</pre>';
        } else {
            $contact_content= $shop_content_data->fields['content_text'];
          }
          require(DIR_WS_INCLUDES . 'header.php');
          $smarty->assign('CONTACT_CONTENT',$contact_content);
          $smarty->assign('FORM_ACTION',twe_draw_form('contact_us', twe_href_link(FILENAME_CONTENT, 'action=send&coID='.$_GET['coID'],'SSL')));
          if (isset($_GET['oID']) ) {
            $cname_query = 'Select customers_id,customers_name,customers_email_address
                                  from '.TABLE_ORDERS.'
                                  where orders_id = "'.$_GET['oID'].'"
                                  LIMIT 1';
            $cname_query_result = $db->Execute($cname_query);
            if (($cname_query_result->RecordCount() > 0) && ($cname_query_result->fields['customers_id'] == $_GET['cID']) ) {
              $cName = $cname_query_result->fields['customers_name'];
              $cEmail = $cname_query_result->fields['customers_email_address'];
              $smarty->assign('INPUT_NAME',twe_draw_input_field('contact_name', $cName,' required="required"'));
              $smarty->assign('INPUT_EMAIL',twe_draw_input_field('email', $cEmail,' required="required"'));
              } else {
                  $smarty->assign('INPUT_NAME',twe_draw_input_field('contact_name', '','placeholder="查無此訂單" required="required"'));
                  $smarty->assign('INPUT_EMAIL',twe_draw_input_field('email','' ,'placeholder="請填入下單的會員帳號" required="required"'));
                  }

            $txt_payment = '訂單號碼 : '.$_GET['oID']
                          .chr(13).chr(10).'匯款日 : '.date('Y-m-d')
                          .chr(13).chr(10).'銀行代號或名稱 : '
                          .chr(13).chr(10).'帳號後5碼 : '
                          .chr(13).chr(10).'匯款金額 : '
                          .chr(13).chr(10).'備    註 : ' ;
            $smarty->assign('INPUT_TEXT',twe_draw_textarea_field('message_body', 'soft', 50, 15, $txt_payment,' required="required"'));
            $smarty->assign('INPUT_HIDDEN',twe_draw_hidden_field('oID', $_GET['oID']));
            } else {
                $smarty->assign('INPUT_NAME',twe_draw_input_field('contact_name', ($error ? $_POST['contact_name'] : $_SESSION['customer_first_name']),' required="required"'));
                $smarty->assign('INPUT_EMAIL',twe_draw_input_field('email', ($error ? $_POST['email'] : $_SESSION['customer_email_address']),' required="required"'));
                $smarty->assign('INPUT_TEXT',twe_draw_textarea_field('message_body', 'soft', 50, 15, $_POST[''],' required="required"'));
                }

          $smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));
        } 
    $smarty->assign('language', $_SESSION['language']);
    $smarty->caching = 0;
    $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/contact_us.html');
  } else {

      if ($shop_content_data->fields['content_file']!=''){
        ob_start();
        if (strpos($shop_content_data->fields['content_file'],'.txt')) echo '<pre>';
        include(DIR_FS_CATALOG.'media/content/'.$shop_content_data->fields['content_file']);
        if (strpos($shop_content_data->fields['content_file'],'.txt')) echo '</pre>';
        $smarty->assign('file',ob_get_contents());
        ob_end_clean();
      } else {
          $content_body = $shop_content_data->fields['content_text'];
        }
      $smarty->assign('CONTENT_BODY',$content_body);
      $smarty->assign('BUTTON_CONTINUE','<a href="javascript:history.back(1)">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
      $smarty->assign('language', $_SESSION['language']);
               // set cache ID
      if (USE_CACHE=='false') {
        $smarty->caching = 0;
        $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/content.html');
      } else {
          $smarty->caching = 1;
          $smarty->cache_lifetime=CACHE_LIFETIME;
          $smarty->cache_modified_check=CACHE_CHECK;
          $cache_id = $_SESSION['language'].$shop_content_data->fields['content_id'];
          $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/content.html',$cache_id);
        }
    }
  $smarty->assign('main_content',$main_content);
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>