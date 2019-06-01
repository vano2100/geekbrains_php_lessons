<?php

require_once('../config/config.php');
$url_array = explode("/", $_SERVER['REQUEST_URI']);


// Галерея из 2х сраних. Либо список всех картинок, либо отображение конкретной.
if ($url_array[1] == "") {
    $page_name = "index";
} else {
    $page_name = "image";
}

//$variables = prepareVariables($page_name);

renderPage($page_name, $url_array[1]);
?>
