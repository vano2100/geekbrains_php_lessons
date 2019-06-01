<?php

//Константы ошибок
define('ERROR_NOT_FOUND', 1);
define('ERROR_TEMPLATE_EMPTY', 2);

/*
* Обрабатывает указанный шаблон, подставляя нужные переменные
*/
function renderPage($page_name, $variables)
{
    $file = TPL_DIR.'/'.$page_name.'.tpl';

    if (!is_file($file)) {
        echo 'Template file "' . $file . '" not found';
        exit(ERROR_NOT_FOUND);
    }

    if (filesize($file) === 0) {
        echo 'Template file "' . $file . '" is empty';
        exit(ERROR_TEMPLATE_EMPTY);
    }

    // Первичня загрузка данных о изображениях
    //reloadImageDir();

    require_once($file);
    die();

    if (empty($variables)) {
        $templateContent = file_get_contents($file);
    } else {
        $templateContent = file_get_contents($file);

        // заполняем значениями
        $templateContent = pasteValues($variables, $page_name, $templateContent);
    }

    (logFileContent($file.'## '.$templateContent));

    //return $templateContent;
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

function pasteValues($variables, $page_name, $templateContent){
    foreach ($variables as $key => $value) {
        if ($value != null) {
            // собираем ключи
            $p_key = '{{' . strtoupper($key) . '}}';

            if(is_array($value)){
                // замена массивом
                $result = "";
                foreach ($value as $value_key => $item){
                    $itemTemplateContent = file_get_contents(TPL_DIR . "/" . $page_name ."_".$key."_item.tpl");

                    foreach($item as $item_key => $item_value){
                        $i_key = '{{' . strtoupper($item_key) . '}}';

                        $itemTemplateContent = str_replace($i_key, $item_value, $itemTemplateContent);
                    }

                    $result .= $itemTemplateContent;
                }
            }
            else
                $result = $value;

            $templateContent = str_replace($p_key, $result, $templateContent);
        }
    }

    return $templateContent;
}

function prepareVariables($page_name){
    $vars = [];
    switch ($page_name){
        case "news":
            $vars["newsfeed"] = getAllNews();
            $vars["test"] = 123;
            break;
        case "newspage":
            $content = getCurrentNewsByTitle($_GET['id_news']);
            if (empty($content)){
                return false;
            }
            $vars["title"] = $content["title"];
            $vars["content"] = $content["content"];

            break;
    }

    return $vars;
}

function getAllNews()
{
    $sql = "SELECT id, title, preview FROM news";
    $allNews = getResultAsAssocArray($sql);

    return $allNews;
}

function getCurrentNewsContent($id_news)
{
    $id_news = (int)$id_news;

    $sql = "SELECT * FROM news WHERE id = " . $id_news;
    $news = getResultAsAssocArray($sql);

    $result = [];
    if (isset($news[0])) {
        $result = $news[0];
    }

    return $result;
}


function getCurrentNewsByTitle($title)
{
    $sql = "SELECT * FROM news WHERE id = '$title'";
    $news = getResultAsAssocArray($sql);

    $result = [];
    if (isset($news[0])) {
        $result = $news[0];
    }

    return $result;
}

//  Загрузка данных о картинках в БД
function reloadImageDir(){
    $dh = opendir(WWW_ROOT . DIRECTORY_SEPARATOR . "img");
    $images = [];
    while (($entry = readdir($dh)) !== false) {
        // пропускаем текущий и родительский каталоги
        if (($entry == '.') || ($entry == '..')) {
            continue;
        }
        $fullFileName = WWW_ROOT . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . $entry;

        // добавили в массив только файлы
        if (is_file($fullFileName)){
            $images[] = ["name" => $entry, "size" => filesize($fullFileName)];
        }
        
    }
    // чистим таблицу и загружаем в нее данные о картинках
    clearTable();
    saveImagesToDB($images);
}

function saveImagesToDB($images){
    foreach($images as $image){
        insertImageInfo($image);
    }
}


