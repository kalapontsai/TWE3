<?php
/*
  $Id: backup.php,v 1.16 2002/03/16 21:30:02 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', '資料庫管理備份');

define('TEXT_INFO_DO_BACKUP', '資料庫備份中!');
define('TEXT_INFO_DO_BACKUP_OK', '資料庫備份完成!');
define('TEXT_INFO_DO_GZIP', '備份資料GZIP壓縮中!');
define('TEXT_INFO_WAIT', '請稍後!');

define('TEXT_INFO_DO_RESTORE', '資料回覆中!');
define('TEXT_INFO_DO_RESTORE_OK', '資料庫資料回覆完成!');
define('TEXT_INFO_DO_GUNZIP', '備份資料ZIP壓縮中!');

define('ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST', '錯誤：備份資料夾不存在，請檢查configure.php.');
define('ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE', '錯誤：備份資料夾無寫入權限');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', '錯誤：下載的連結錯誤');
define('ERROR_DECOMPRESSOR_NOT_AVAILABLE', '錯誤：沒有合適的解壓縮套件可用.');
define('ERROR_UNKNOWN_FILE_TYPE', '錯誤：未知的文件類型.');
define('ERROR_RESTORE_FAILES', '錯誤：資料回覆失敗.');
define('ERROR_DATABASE_SAVED', '錯誤 :資料未儲存完成!.');
define('ERROR_TEXT_PATH', '錯誤:未找到回覆資料路徑!');

define('SUCCESS_LAST_RESTORE_CLEARED', '成功：上次的恢復日期已被清除.');
define('SUCCESS_DATABASE_SAVED', '成功: 資料備份完成.');
define('SUCCESS_DATABASE_RESTORED', '成功: 資料回覆完成.');
define('SUCCESS_BACKUP_DELETED', '成功:備份資料已移除.');

define('TEXT_BACKUP_UNCOMPRESSED', '備份資料解壓縮完成: ');

define('TEXT_SIMULATION', '<br>(Simulation log file)');
define('TEXT_TABLELINE', '處理資料表數量: ');
define('TEXT_TABLELINE_NAME', '處理資料表名稱: ');
define('TEXT_RELOAD', '網頁重新載入次數: ');
?>