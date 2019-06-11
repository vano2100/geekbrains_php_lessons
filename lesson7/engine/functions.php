<?php

//Константы ошибок
define('ERROR_NOT_FOUND', 1);
define('ERROR_TEMPLATE_EMPTY', 2);

/*
* Обрабатывает указанный шаблон, подставляя нужные переменные
*/
function renderPage($page_name, $variables = [])
{
    $file = TPL_DIR . "/" . $page_name . ".tpl";

    if (!is_file($file)) {
        echo 'Template file "' . $file . '" not found';
        exit(ERROR_NOT_FOUND);
    }

    if (filesize($file) === 0) {
        echo 'Template file "' . $file . '" is empty';
        exit(ERROR_TEMPLATE_EMPTY);
    }

    // если переменных для подстановки не указано, просто
    // возвращаем шаблон как есть
    if (empty($variables)) {
        $templateContent = file_get_contents($file);
    } else {
        $templateContent = file_get_contents($file);

        // заполняем значениями
        $templateContent = pasteValues($variables, $page_name, $templateContent);
    }

    return $templateContent;
}

function pasteValues($variables, $page_name, $templateContent)
{
    foreach ($variables as $key => $value) {
        // собираем ключи
        $p_key = '{{' . strtoupper($key) . '}}';

        if (is_array($value)) {
            // замена массивом
            $result = "";
            /**
             * [
             * 'value_key1' => 'item1'
             * 'key2' => 'value2'
             * 'key3' => 'value3'
             * 'key4' => 'value4'
             * ]
             */
            foreach ($value as $value_key => $item) {
                $itemTemplateContent = file_get_contents(TPL_DIR . "/" . $page_name . "_" . $key . "_item.tpl");

                foreach ($item as $item_key => $item_value) {
                    $i_key = '{{' . strtoupper($item_key) . '}}';

                    $itemTemplateContent = str_replace($i_key, $item_value, $itemTemplateContent);
                }

                $result .= $itemTemplateContent;
            }
        } else {
            $result = $value;
        }

        $templateContent = str_replace($p_key, $result, $templateContent);
    }

    return $templateContent;
}

function prepareVariables($page_name)
{
    $vars = [];
    switch ($page_name) {
        case "image":
            incCountView($_GET['image_id']);
            $image = getImageInfo($_GET['image_id']);
            $vars["image_name"] = $image["filename"];
            $vars["size"] = $image["size"];
            $vars["counter"] = $image["click_counter"];
            break;
        case "images":
            $vars["images"] = getImages();
            break;
        case "cart":
            if (isset($_POST["add"])){
                $_SESSION["cart"][] = $_POST["add"];
            }
            if (isset($_POST["del"])){
                deleteFromCart($_POST["del"]);
            }
            $vars["goods"] = getGoodsInCart();
            if (!isset($_SESSION["cart"])){
                $vars["count"] = 0;
                $vars["total"] = 0;
            } else {
                $vars["count"] = count($_SESSION["cart"]);
                $vars["total"] = getTotal();
            }
            break;
        case "goods":
            $vars["goods"] = getGoods();
            if (!isset($_SESSION["cart"])){
                $vars["cart_count"] = "пусто...";
            } else {
                $vars["cart_count"] = count($_SESSION["cart"]);
            }
            break;
        case "good":
            $good = getGoodInfo($_GET['good_id']);
            $vars["id"] = $_GET['good_id'];
            $vars["name"] = $good["name"];
            $vars["price"] = $good["price"];
            $vars["description"] = $good["description"];
            break;            
        case "news":
            $vars["newsfeed"] = getNews();
            $vars["test"] = 123;
            break;
        case "newspage":
            $content = getNewsContent($_GET['id_news']);
            $vars["news_title"] = $content["news_title"];
            $vars["news_content"] = $content["news_content"];

            break;
        case "feedback":
            if (!empty($_GET)) {
                $requestParams = $_GET;
            } elseif (!empty($_POST)) {
                $requestParams = $_POST;
            } else {
                $requestParams = [];
            }

            if ($requestParams !== []) {
                $vars['response'] = setFeedback($requestParams);
            } else {
                $vars['response'] = 'Я просто посмотреть отзывы пришел';
            }

            $vars["feedbackfeed"] = getFeedbacksFeed();
            break;
        case "upload-file":
            uploadFile();
            break;
        case "image-reload":
            reloadImageDir();
            break;
        case "login":
            // если уже залогинен, то выбрасываем на главную
            if(alreadyLoggedIn()){
                header("Location: /lk");
            }

            // если есть куки, то авторизуем сразу
            if(checkAuthWithCookie()) {
                header("Location: /lk");
            }
            // иначе пробуем авторизовать по логину и паролю
            else {
                $vars["autherror"] = "";
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if(!authWithCredentials()) {
                        $vars["autherror"] = "Неправильный логин/пароль";
                    }
                    else{
                        header("Location: /lk");
                    }
                }
            }
            break;
        case "logout":
            unset($_SESSION["user"]);
            session_destroy();
            header("Location: /");
            break;
        case "lk":
            $sql = "SELECT user_name, user_login FROM user WHERE user_id = :userid";
            $user_data = getQueryOne($sql, [':userid'=>$_SESSION['user']['user_id']]);
    
            $vars['name'] = $user_data['user_name'];
            $vars['login'] = $user_data['user_login'];
            break;
        case "registration":
            unset($_SESSION["user"]);
            session_destroy();

            $vars['regerror'] = '';
            if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['name'])){
                if (registration($_POST['name'], $_POST['login'], $_POST['password'])) {
                    header("Location: /lk");
                } else {
                    //$vars['regerror'] = 'Ошибка регистрации';
                    $vars['regerror'] = $_POST['name'] . $_POST['login'] . $_POST['password'];
                }
            }

    }

    return $vars;
}

function _log($s, $suffix = '')
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

function getNews()
{
    $sql = "SELECT id_news, news_title, news_preview FROM news";
    $news = getResultAsAssocArray($sql);

    return $news;
}

function getImages()
{
    $sql = "SELECT id, filename FROM images order by click_counter desc";
    $images = getResultAsAssocArray($sql);

    return $images;
}

function getGoodsInCart(){
    $items = $_SESSION["cart"];
    $goods = array();
    $sql = "SELECT id, name, description, price FROM goods WHERE id = :id";
    foreach($items as $item){
        $goods[] = getQueryOne($sql, [':id' => $item]);
    } 
    return $goods;
}

function getTotal(){
    $items = $_SESSION["cart"];
    $sql = "SELECT sum(price) FROM goods where id in (" . implode(",", $items) . ")";
    $total = getResultAsAssocArray($sql);
    return $total[0][0];
}

function getGoods()
{
    $sql = "SELECT id, name, description, price FROM goods";
    $goods = getResultAsAssocArray($sql);

    return $goods;
}

function getNewsContent($id_news)
{
    $id_news = (int)$id_news;

    $sql = "SELECT * FROM news WHERE id_news = " . $id_news;
    $news = getResultAsAssocArray($sql);

    $result = [];
    if (isset($news[0])) {
        $result = $news[0];
    }

    return $result;
}


function getImageInfo($id_image)
{
    $id_image = (int)$id_image;

    $sql = "SELECT * FROM images WHERE id = " . $id_image;
    $image = getResultAsAssocArray($sql);

    $result = [];
    if (isset($image[0])) {
        $result = $image[0];
    }

    return $result;
}

function getGoodInfo($id_good)
{
    $id_good = (int)$id_good;

    $sql = "SELECT * FROM goods WHERE id = " . $id_good;
    $good = getResultAsAssocArray($sql);

    $result = [];
    if (isset($good[0])) {
        $result = $good[0];
    }

    return $result;
}

function deleteFromCart($id){
    $goods = $_SESSION["cart"];
    $index = array_search($id,$goods);
    unset($goods[$index]);
    
    $_SESSION["cart"] = $goods;
}

function getFeedbacksFeed()
{
    $sql = "SELECT * FROM feedback";
    $feed = getResultAsAssocArray($sql);

    return $feed;
}



function setFeedback(array $requestParams)
{
    $user = null;
    $body = null;
    if (isset($requestParams['feedback']['username'])) {
        $user = (string)htmlspecialchars(strip_tags($requestParams['feedback']['username']));
    }

    if (isset($requestParams['feedback']['text'])) {
        $body = (string)htmlspecialchars(strip_tags($requestParams['feedback']['text']));
    }

    if (!isset($body) || !isset($user)){
        return 'Произошла ошибка!';
    }

    $sql = "INSERT INTO feedback (feedback_user, feedback_body) VALUES (:user, :body)";
    $res = executeQuery($sql, [':user' =>$user, ':body' => $body]);

    if (!$res) {
        $response = "Произошла ошибка!";
    } else {
        $response = "Отзыв добавлен";
    }

    return $response;
}

/**
 * & -> '&amp'
 * "" -> '&quot'
 * ' -> '&#039' '&apos'
 * > -> '&gt;'
 * < ->'&lt;'
 */


function uploadFile()
{
    if (!empty($_FILES)) {
        $uploadDir = WWW_ROOT . '/img/uploads/';
        $uploadFile = $uploadDir . basename($_FILES['userfile']['tmp_name']);

        if ($_FILES['userfile']['size'] > 0 && $_FILES['userfile']['size'] < 100000) {
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {
                echo '<img src="img/uploads/' . $_FILES['userfile']['name'] . '">';
                echo 'Файл корректен и успешно загружен';
                return true;
            } else {
                echo 'Ошибка в при загрузке файла';
                return false;

            }
        }

    }
}

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

    echo "<div class='alert alert-primary'>Информация о картинка загружена!</div>";
}

function saveImagesToDB($images){
    $sql = "insert into images (filename, size) values (:name, :size)";
    foreach($images as $image){
        executeQuery($sql, $image);
    }
}

// блок функций авторизации
/**
 * валидация пользовательского куки
 * @return bool
 */
function checkAuthWithCookie(){
    $result = false;

    if(isset($_COOKIE['id_user']) && isset($_COOKIE['cookie_hash'])){
        // получаем данные пользователя по id

        $sql = "SELECT id_user, user_name, user_password FROM user WHERE id_user = :id_user";
        $user_data = getQueryOne($sql, [':id_user'=>$_COOKIE['id_user']]);

        if(($user_data['user_password'] !== $_COOKIE['cookie_hash']) || ($user_data['id_user'] !== $_COOKIE['id_user'])){
            setcookie("id", "", time() - 3600*24*30*12, "/");
            setcookie("cookie_hash", "", time() - 3600*24*30*12, "/");
        }
        else {
            header("Location: /");
        }

    }

    return $result;
}

/**
 * авторизация через логин и пароль
 */
function authWithCredentials(){
    $username = $_POST['login'];

    // получаем данные пользователя по логину
    $sql = "SELECT user_id, user_name, user_password FROM user WHERE user_login = :userlogin";
    $user_data = getQueryOne($sql, [':userlogin'=>$username]);
    $isAuth = false;
    

    // проверяем соответствие логина и пароля
    if (checkPassword($user_data)) {
        $isAuth = true;
        // если стояла галка, то запоминаем пользователя на сутки
        if(isset($_POST['rememberme']) && $_POST['rememberme'] == 'on'){
            setcookie("id_user", $user_data['user_id'], time()+86400);
            setcookie("cookie_hash", $user_data['user_password'], time()+86400);
        }

        // сохраним данные в сессию
        $_SESSION['user'] = $user_data;
    }


    return $isAuth;
}

function hashPassword($password)
{
    return md5($password);
}

/**
 * @param $user_data
 * @return bool
 */
function checkPassword($user_data) {
    $hash = $user_data['user_password'];
    $password = md5($_POST['password']);

    return $hash === $password;
}

function alreadyLoggedIn(){
    return !empty($_SESSION['user']);
}


function registration($user_name, $login, $password)
{
    $sql = 'SELECT *  FROM "user" WHERE user_login = :user_login';
    $user = getQueryOne($sql, [':user_login' => $login]);
    if (!$user) {
        $password = hashPassword($password);
        $sql = "INSERT INTO user (user_name, user_login, user_password) VALUES (:user_name, :user_login, :user_password)";
        return executeQuery($sql, [':user_login' => $login, ':user_password' => $password, ':user_name'=>$user_name]);
    }
    return false;
}

