<?php

date_default_timezone_set('Asia/Jakarta');
ini_set('display_errors', true);

// Copy this file to config.php, then fill the local database credentials.
$host = 'localhost';
$port = 3306;
$db_username = 'sister_app';
$db_password = '';
$db_name = 'sister_api';

// Main directory. Leave empty when the project is the web root.
define('DIR_MAIN', '');

// Backend directory relative to the web root.
define('DIR_ADMIN', 'backend');

define('DB_CHARACSET', 'utf8');
define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' . DIR_MAIN);
define('DIR_API', 'api');

$language = 'en';

require_once "lang/$language.php";
require_once 'helper/main.php';
require_once 'helper/pmb.php';
require_once 'lib/vendor/autoload.php';

use Backend\Database as DB;
use Backend\Pagination;
use Backend\Dtable as Datatable;

$db = new DB($host, $port, $db_username, $db_password, $db_name);
$pg = new Pagination($db);
$datatable = new Datatable($host, $port, $db_username, $db_password, $db_name);

function handleException($exception)
{
    echo $exception->getMessage();
}

set_exception_handler('handleException');
