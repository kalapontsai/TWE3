<?php
/* --------------------------------------------------------------
   $Id: backup.php,v 1.1 2003/12/19 13:19:08 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
     --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(backup.php,v 1.57 2003/03/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (backup.php,v 1.11 2003/08/2); www.nextcommerce.org

   Released under the GNU General Public License 
   Datenbank Backup Ver. 1.91b
   modified by web28 - www.rpa-com.de 05.06.2010
   //Rechte automatisch setzen fr Unteradmin
   
   Datenbank Backup Ver. 1.91a
   modified by web28 - www.rpa-com.de 28.04.2010
   New Restore Method:  like MySqlDumper
   New Backup Method:  single tables with reload site 
   Backup includes - depends on database version - engine, auto_increment, default charset, collate
   no exec needed
   used upzip.lib.php , zip.lib.php for zip compress/uncompress
   --------------------------------------------------------------*/
  define('BK_FILENAME', 'backup_db.php'); 
  
  
  require('includes/application_top.php');
  
  //Adminrechte automatisch fr backup_db setzen
	$result_array = $db->Execute("select * from ".TABLE_ADMIN_ACCESS."");
		if (!isset($result_array->fields['backup_db'])) {	
			$db->Execute("ALTER TABLE `". TABLE_ADMIN_ACCESS."` ADD `backup_db` INT( 1 ) DEFAULT '0' NOT NULL") ;
			$db->Execute("UPDATE `".TABLE_ADMIN_ACCESS."` SET `backup_db` = '1' WHERE `customers_id` = '2' LIMIT 1") ;
			//Rechte automatisch setzen fr Unteradmin
			if ($_SESSION['customer_id'] >= 3) {
				$db->Execute("UPDATE `".TABLE_ADMIN_ACCESS."` SET `backup_db` = '1' WHERE `customers_id` = '".$_SESSION['customer_id']."' LIMIT 1") ;
			}
		}
  
  if (!function_exists(twe_copy_uploaded_file)){
	  function twe_copy_uploaded_file($filename, $target) {
		if (substr($target, -1) != '/') $target .= '/';
		$target .= $filename['name'];
		move_uploaded_file($filename['tmp_name'], $target);
	  }
  }	  

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'forget':
        mysql_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DB_LAST_RESTORE'");
        $messageStack->add_session(SUCCESS_LAST_RESTORE_CLEARED, 'success');
        twe_redirect(twe_href_link(FILENAME_BACKUP));
        break;
	  case 'download':
        $extension = substr($_GET['file'], -3);
        if ( ($extension == 'zip') || ($extension == '.gz') || ($extension == 'sql') ) {
          if ($fp = fopen(DIR_FS_BACKUP . $_GET['file'], 'rb')) {
            $buffer = fread($fp, filesize(DIR_FS_BACKUP . $_GET['file']));
            fclose($fp);
            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $_GET['file']);
            echo $buffer;
            exit;
          }
        } else {
          $messageStack->add(ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
        }
        break;     
      case 'deleteconfirm':
        if (strstr($_GET['file'], '..')) twe_redirect(twe_href_link(FILENAME_BACKUP));

        twe_remove(DIR_FS_BACKUP . '/' . $_GET['file']);
        if (!$twe_remove_error) {
          $messageStack->add_session(SUCCESS_BACKUP_DELETED, 'success');
          twe_redirect(twe_href_link(FILENAME_BACKUP));
        }
        break;
	  case 'restorelocalnow':          
		  $file = twe_get_uploaded_file('sql_file');
		  if (is_uploaded_file($file['tmp_name'])) {
            twe_copy_uploaded_file($file, DIR_FS_BACKUP);
			$messageStack->add_session(SUCCESS_BACKUP_UPLOAD, 'success');
			twe_redirect(twe_href_link(FILENAME_BACKUP));
		  }
	    break; 	
    }
  }

// check if the backup directory exists
  $dir_ok = false;
  if (is_dir(DIR_FS_BACKUP)) {
    $dir_ok = true;
    if (!is_writeable(DIR_FS_BACKUP)) $messageStack->add(ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php require('includes/includes.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td class="boxCenter" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FILE_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FILE_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if ($dir_ok) {
    $dir = dir(DIR_FS_BACKUP);
    $contents = array();
	$exts = array("sql","sql.zip","sql.gz");
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_BACKUP . $file)) {
	  foreach ($exts as $value) {
	  if (twe_CheckExt($file, $value)) {
	  
        $contents[] = $file;
		}
		}
      }
    }
    sort($contents);

    for ($files = 0, $count = sizeof($contents); $files < $count; $files++) {
      $entry = $contents[$files];

      $check = 0;

      if (((!$_GET['file']) || ($_GET['file'] == $entry)) && (!$buInfo) && ($_GET['action'] != 'backup') && ($_GET['action'] != 'restorelocal')) {
        $file_array['file'] = $entry;
        $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry));
        $file_array['size'] = number_format(filesize(DIR_FS_BACKUP . $entry)) . ' bytes';
        switch (substr($entry, -3)) {
          case 'zip': $file_array['compression'] = 'ZIP'; break;
          case '.gz': $file_array['compression'] = 'GZIP'; break;		  
          default: $file_array['compression'] = TEXT_NO_EXTENSION; break;
        }

        $buInfo = new objectInfo($file_array);
      }

      if (is_object($buInfo) && ($entry == $buInfo->file)) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'">' . "\n";
        $onclick_link = 'file=' . $buInfo->file . '&action=restore';
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'">' . "\n";
        $onclick_link = 'file=' . $entry;
      }
?>
                <td class="dataTableContent" onClick="document.location.href='<?php echo twe_href_link(FILENAME_BACKUP, $onclick_link); ?>'"><?php echo '<a href="' . twe_href_link(FILENAME_BACKUP, 'action=download&file=' . $entry) . '">' . twe_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry; ?></td>
                <td class="dataTableContent" align="center" onClick="document.location.href='<?php echo twe_href_link(FILENAME_BACKUP, $onclick_link); ?>'"><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)); ?></td>
                <td class="dataTableContent" align="right" onClick="document.location.href='<?php echo twe_href_link(FILENAME_BACKUP, $onclick_link); ?>'"><?php echo number_format(filesize(DIR_FS_BACKUP . $entry)); ?> bytes</td>
<!-- BOF - Tomcraft - 2009-06-10 - added some missing alternative text on admin icons -->
<!--
                <td class="dataTableContent" align="right"><?php if ( (is_object($buInfo)) && ($entry == $buInfo->file) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $entry) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
-->
								<td class="dataTableContent" align="right"><?php if ( (is_object($buInfo)) && ($entry == $buInfo->file) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ICON_ARROW_RIGHT); } else { echo '<a href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $entry) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
<!-- EOF - Tomcraft - 2009-06-10 - added some missing alternative text on admin icons -->
              </tr>
<?php
    }
    $dir->close();
  }
?>
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_BACKUP_DIRECTORY . ' ' . DIR_FS_BACKUP; ?></td>
                <td align="right" class="smallText"><?php if ( ($_GET['action'] != 'backup') && (isset($dir))) echo '<a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'action=backup') . '">' . twe_image_button('button_backup.gif', IMAGE_BACKUP) . '</a>'; if ( ($_GET['action'] != 'restorelocal') && ($dir) ) echo '&nbsp;&nbsp;<a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'action=restorelocal') . '">' . twe_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>'; ?></td>
              </tr>
<?php
  if (defined('DB_LAST_RESTORE')) {
?>
              <tr>
                <td class="smallText" colspan="4"><?php echo TEXT_LAST_RESTORATION . ' ' . DB_LAST_RESTORE . ' <a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'action=forget') . '">' . TEXT_FORGET . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'backup':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_BACKUP . '</b>');

      //$contents = array('form' => twe_draw_form('backup', FILENAME_BACKUP, 'action=backupnow'));
	  $contents = array('form' => twe_draw_form('backup', BK_FILENAME, 'action=backupnow'));
	  
      $contents[] = array('text' => TEXT_INFO_NEW_BACKUP);

      if ($messageStack->size > 0) {
        $contents[] = array('text' => '<br />' . twe_draw_radio_field('compress', 'no', true) . ' ' . TEXT_INFO_USE_NO_COMPRESSION);
        //$contents[] = array('text' => '<br />' . twe_draw_radio_field('download', 'yes', true) . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br /><br />*' . TEXT_INFO_BEST_THROUGH_HTTPS);
      } else {
		if(function_exists('gzopen')) {
          $contents[] = array('text' => '<br />' . twe_draw_radio_field('compress', 'gzip', true) . ' ' . TEXT_INFO_USE_GZIP);
		}        	
        $contents[] = array('text' => twe_draw_radio_field('compress', 'no') . ' ' . TEXT_INFO_USE_NO_COMPRESSION);
		$contents[] = array('text' => '<br />' . twe_draw_checkbox_field('complete_inserts', 'yes', true) . ' ' . TEXT_COMPLETE_INSERTS);
        //$contents[] = array('text' => '<br />' . twe_draw_checkbox_field('download', 'yes') . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br /><br />*' . TEXT_INFO_BEST_THROUGH_HTTPS);
      }

      $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_BACKUP . '"/>&nbsp;<a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'restore':
      $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

      $contents[] = array('text' => twe_break_string(sprintf(TEXT_INFO_RESTORE, DIR_FS_BACKUP . (($buInfo->compression != TEXT_NO_EXTENSION) ? substr($buInfo->file, 0, strrpos($buInfo->file, '.')) : $buInfo->file), ($buInfo->compression != TEXT_NO_EXTENSION) ? TEXT_INFO_UNPACK : ''), 35, ' '));
      //$contents[] = array('align' => 'center', 'text' => '<br /><a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=restorenow') . '">' . BUTTON_RESTORE . '</a>&nbsp;<a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file) . '">' . BUTTON_CANCEL . '</a>');
      $contents[] = array('align' => 'center', 'text' => '<br /><a class="button" onclick="this.blur();" href="' . twe_href_link(BK_FILENAME, 'file=' . $buInfo->file . '&action=restorenow') . '">' . twe_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>&nbsp;<a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');      
	  break;
    case 'restorelocal':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_RESTORE_LOCAL . '</b>');

      $contents = array('form' => twe_draw_form('restore', FILENAME_BACKUP, 'action=restorelocalnow', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL . '<br /><br />' . TEXT_INFO_BEST_THROUGH_HTTPS);
      $contents[] = array('text' => '<br />' . twe_draw_file_field('sql_file'));
      $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL_RAW_FILE);
      //$contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_RESTORE . '"/>&nbsp;<a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP) . '">' . BUTTON_CANCEL . '</a>');
      $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . BUTTON_UPLOAD . '"/>&nbsp;<a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

      $contents = array('form' => twe_draw_form('delete', FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $buInfo->file . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br /><input type="submit" class="button" onclick="this.blur();" value="' . IMAGE_DELETE . '"/> <a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($buInfo)) {
        $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');

        //$contents[] = array('align' => 'center', 'text' => '<a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=restore') . '">' . BUTTON_RESTORE . '</a> <a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=delete') . '">' . BUTTON_DELETE . '</a>');
        $contents[] = array('align' => 'center', 'text' => '<a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=restore') . '">' . twe_image_button('button_restore.gif', IMAGE_RESTORE) . '</a><br><a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br><a class="button" onclick="this.blur();" href="' . twe_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file . '&action=download') . '">' . twe_image_button('button_download.gif', 'Download') . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_DATE . ' ' . $buInfo->date);
        $contents[] = array('text' => TEXT_INFO_SIZE . ' ' . $buInfo->size);
        $contents[] = array('text' => '<br />' . TEXT_INFO_COMPRESSION . ' ' . $buInfo->compression);
      }
      break;
  }

  if ( (twe_not_null($heading)) && (twe_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>