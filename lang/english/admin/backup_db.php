<?php
/*
  $Id: backup.php,v 1.16 2002/03/16 21:30:02 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Database Backup Manager');

define('TEXT_INFO_DO_BACKUP', 'The database backup is created!');
define('TEXT_INFO_DO_BACKUP_OK', 'The database backup was created!');
define('TEXT_INFO_DO_GZIP', 'The backup file is compressed!');
define('TEXT_INFO_WAIT', 'Please wait!');

define('TEXT_INFO_DO_RESTORE', 'The database is restored!');
define('TEXT_INFO_DO_RESTORE_OK', 'The database has been restored!');
define('TEXT_INFO_DO_GUNZIP', 'The backup file is unzipped!');

define('ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST', 'Error: the directory for the backup does not exist. Please fix the errors in your configure.php.');
define('ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE', 'Error: In the directory for the backup can not be written.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Error: The download link is not acceptable.');
define('ERROR_DECOMPRESSOR_NOT_AVAILABLE', 'Error: No suitable decompressor available.');
define('ERROR_UNKNOWN_FILE_TYPE', 'Error: unknown file type.');
define('ERROR_RESTORE_FAILES', 'Error: Restore failed.');
define('ERROR_DATABASE_SAVED', 'Error: The database could not be saved.');
define('ERROR_TEXT_PATH', 'Error: The path to dump mysql not found!');
define('SUCCESS_LAST_RESTORE_CLEARED', 'Successful: The last restoration date was deleted.');
define('SUCCESS_DATABASE_SAVED', 'Success: The database was backed up.');
define('SUCCESS_DATABASE_RESTORED', 'Success: The database has been restored.');
define('SUCCESS_BACKUP_DELETED', 'Successful: The protection was removed.');

define('TEXT_BACKUP_UNCOMPRESSED', 'The backup file was unpacked: ');

define('TEXT_SIMULATION', '<br>(Simulation log file)');
define('TEXT_TABLELINE', 'The number of processed data table: ');
define('TEXT_TABLELINE_NAME', 'Data table name: ');
define('TEXT_RELOAD', 'Reload number: ');

?>