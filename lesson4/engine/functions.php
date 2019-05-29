<?php

//Константы ошибок
define('ERROR_NOT_FOUND', 1);
define('ERROR_TEMPLATE_EMPTY', 2);

/*
* Обрабатывает указанный шаблон, подставляя нужные переменные
*/
function renderPage($page_name)
{
    $file = TPL_DIR.'/'.$page_name.'.html';

    if (!is_file($file)) {
        echo 'Template file "' . $file . '" not found';
        exit(ERROR_NOT_FOUND);
    }

    if (filesize($file) === 0) {
        echo 'Template file "' . $file . '" is empty';
        exit(ERROR_TEMPLATE_EMPTY);
    }

    // Используем require_once вместо file_get_contents чтоб иметь 
    // возможность включать в шаблон код на PHP
    require_once($file);
    //$templateContent = file_get_contents($file);


//    if (logFileContent($file.'## '.$templateContent)){
//        return $templateContent;
//    } else {
//        die();
//    };
}

function logFileContent($s, $suffix = '')
{
    if (is_array($s) || is_object($s)) {
        $s = print_r($s, 1);
    }
    $s = "### " . date("d.m.Y H:i:s") . "\r\n" . $s . "\r\n\r\n\r\n";

    if (mb_strlen($suffix)) {
        $suffix = "_" . $suffix;
    }

    _writeToFile($_SERVER['DOCUMENT_ROOT'] . "/_log/logs" . $suffix . ".log", $s, "a+");

    return $s;
}

function _writeToFile($fileName, $content, $mode = "w")
{
    $dir = mb_substr($fileName, 0, strrpos($fileName, "/"));
    if (!is_dir($dir)) {
        _makeDir($dir);
    }

    if ($mode != "r") {
        $fh = fopen($fileName, $mode);
        if (fwrite($fh, $content)) {
            fclose($fh);
            @chmod($fileName, 0644);
            return true;
        }
    }

    return false;
}

function _makeDir($dir, $is_root = true, $root = '')
{
    $dir = rtrim($dir, "/");
    if (is_dir($dir)) {
        return true;
    }
    if (mb_strlen($dir) <= mb_strlen($_SERVER['DOCUMENT_ROOT'])) {
        return true;
    }
    if (str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir) == $dir) {
        return true;
    }

    if ($is_root) {
        $dir = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir);
        $root = $_SERVER['DOCUMENT_ROOT'];
    }
    $dir_parts = explode("/", $dir);

    foreach ($dir_parts as $step => $value) {
        if ($value != '') {
            $root = $root . "/" . $value;
            if (!is_dir($root)) {
                mkdir($root, 0755);
                chmod($root, 0755);
            }
        }
    }
    return $root;
}
// получаем список файлов в каталоге public/img
// 1) Получить список файлов из папки public/img, и на основе этого списка, циклом вывести картинки.
function getImages(){
    $dh = opendir(WWW_ROOT . DIRECTORY_SEPARATOR . "img");
    $images = [];
    while (($entry = readdir($dh)) !== false) {
        // пропускаем текущий и родительский каталоги
        if (($entry == '.') || ($entry == '..')) {
            continue;
        }
        // добавили в массив только файлы
        if (is_file(WWW_ROOT . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . $entry)){
            $images[] .= $entry;
        }
        
    }
    return $images;
}

// 2) При клике на картинку отправлять запрос на mysite.ru/image_name.
//3) Получать из запроса этот image_name, проверять, есть ли такой файл
// в папке public/img и если он есть - выводит его. Если нет, 
// то вывести сообщение что такой картинки нет.
function getImage($image){
    if (file_exists(WWW_ROOT . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . $image)){
        //return "img" . DIRECTORY_SEPARATOR . $image;
        echo "<img src='"."img/" . $image . "'>";
        die();
    } else {
        //echo "404 not found.";
        die("404 not found.");
    }
}
