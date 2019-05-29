<?php

// Переместил верстку в шаблон чтоб не отвлекала от кода.
require_once('../config/config.php');
$url_array = explode("/", $_SERVER['REQUEST_URI']);

if ($url_array[1] == "") {
    $page_name = "index";
    renderPage($page_name);
} else {
    getImage($url_array[1]);
}
