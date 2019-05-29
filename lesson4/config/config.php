<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
define('SITE_ROOT', "../");
define('WWW_ROOT', SITE_ROOT . 'public');

define('DATA_DIR', SITE_ROOT . 'data');
define('ENGINE_DIR', SITE_ROOT . 'engine');
define('TPL_DIR', SITE_ROOT . 'templates');

define('SITE_TITLE', 'Урок 4');

require_once('../engine/functions.php');

function alert($var)
{
    echo '<pre class="p-3 mb-2 bg-light text-white">';
    var_dump($var);
    echo '</pre>';
}