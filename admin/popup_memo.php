<?php
/* --------------------------------------------------------------
   $Id: popup_memo.php,v 1.2 2004/02/29 17:05:18 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercecoding standards www.oscommerce.com
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   --------------------------------------------------------------*/

   require('includes/application_top.php');
   include(DIR_FS_LANGUAGES . $_SESSION['language'] . '/admin/customers.php');

if ($_GET['action']) {
switch ($_GET['action']) {

        case 'save':

        $memo_title = twe_db_prepare_input($_POST['memo_title']);
        $memo_text = twe_db_prepare_input($_POST['memo_text']);

        if ($memo_text != '' && $memo_title != '' ) {
          $sql_data_array = array(
            'customers_id' => $_POST['ID'],
            'memo_date' => date("Y-m-d"),
            'memo_title' =>$memo_title,
            'memo_text' => nl2br($memo_text),
            'poster_id' => $_SESSION['customer_id']);

          twe_db_perform(TABLE_CUSTOMERS_MEMO, $sql_data_array);
          }
        break;

        case 'remove':
        $db->Execute("DELETE FROM " . TABLE_CUSTOMERS_MEMO . " WHERE memo_id = '" . $_GET['mID'] . "'");
        break;

}
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<?php require('includes/includes.js.php'); ?>
</head>
<body>
<div class="pageHeading"><?php echo TITLE_MEMO; ?></div></p>
    <table width="100%">
      <tr>
      <form name="customers_memo" method="POST" action="popup_memo.php?action=save&ID=<?php echo (int)$_GET['ID'];?>">
        <td class="main" style="border-top: 1px solid; border-color: #cccccc;"><b><?php echo TEXT_TITLE ?></b>:<?php echo twe_draw_input_field('memo_title').twe_draw_hidden_field('ID',(int)$_GET['ID']); ?><br><?php echo twe_draw_textarea_field('memo_text', 'soft', '73', '5'); ?><br><?php echo twe_image_submit('button_insert.gif', IMAGE_INSERT); ?></td>
      </tr>
    </table></form>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">

  <tr>
    <td>



    <td class="main"><?php
  $memo_values = $db->Execute("SELECT
                                  *
                              FROM
                                  " . TABLE_CUSTOMERS_MEMO . "
                              WHERE
                                  customers_id = '" . (int)$_GET['ID'] . "'
                              ORDER BY
                                  memo_id DESC");
  while (!$memo_values->EOF) {
    $poster_query = "SELECT customers_firstname, customers_lastname FROM " . TABLE_CUSTOMERS . " WHERE customers_id = '" . $memo_values->fields['poster_id'] . "'";
    $poster_values = $db->Execute($poster_query);
?><table width="100%">
      <tr>
        <td class="main"><hr noshade><b><?php echo TEXT_DATE; ?></b>:<i><?php echo $memo_values->fields['memo_date']; ?><br></i><b><?php echo TEXT_TITLE; ?></b>:<?php echo $memo_values->fields['memo_title']; ?><br><b>  <?php echo TEXT_POSTER; ?></b>:<?php echo $poster_values->fields['customers_lastname']; ?> <?php echo $poster_values->fields['customers_firstname']; ?></td>
      </tr>
      <tr>
        <td width="142" class="main" style="border: 1px solid; border-color: #cccccc;"><?php echo $memo_values->fields['memo_text']; ?></td>
      </tr>
      <tr>
        <td><a href="<?php echo twe_href_link('popup_memo.php', 'ID=' . $_GET['ID'] . '&action=remove&mID=' . $memo_values->fields['memo_id']); ?>" onClick="return confirm('<?php echo DELETE_ENTRY; ?>')"><?php echo twe_image_button('button_delete.gif', IMAGE_DELETE); ?></a></td>
      </tr>
    </table>
<?php
$memo_values->MoveNext();
  }
?>
  </td>
    </td>
  </tr>
</table>

</body>
</html>