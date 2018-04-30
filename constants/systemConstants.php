<?php
/* System Details */
define('MULTILANG', true);
define('LANGS', array('az', 'en', 'ru'));
define('SITE_LINK', 'http://localhost/mvc_git/');
define('SITE_PATH', '/mvc_git');// default "" else "/folder_name" /* for import files */
define('CONTROL_PANEL', 'admin');
define('PLUGIN_DIR', SITE_PATH.'/plugins');
define('EXE_DIR', SITE_PATH.'/exe');
define('DEFAULT_LANG', 'en');
define('ERROR_LOG_DIR', 'error_log/'); // error_log file dir

define('CACHE_DURATION', 30);// in seconds

define('BOOKMARKS_TIME', 0); // in seconds
/* ./System Details */

/* DB Details */
define('DB_HOST', '');
define('DB_NAME', '');
define('DB_USERNAME', '');
define('DP_PASS', '');
/* ./DB Details */

/* Mail Credentials */
define('MAIL_FROM_NAME', '');
define('MAIL_FROM', '');
define('REPLY_TO_NAME', '');
define('REPLY_TO', '');

define('MAIL_BOX', "");// example: {mx1.test.com:993/imap/ssl/}INBOX
define('MAIL_USERNAME', ''); // example: mailnme@test.com
define('MAIL_PASSWORD', '');
define('SMTP_PORT', '');
/* ./Mail Credentials */
?>
