<?php
/**
 * Created by PhpStorm.
 * User: evg
 * Date: 29/05/2019
 * Time: 17:28
 */

/**
 * @param string $sql
 * @return array
 */
function getResultAsAssocArray($sql)
{
    $dbConnect = getConnection();
    $result = [];
    foreach ($dbConnect->query($sql) as $row){
        $result[]=$row;
    }

    return $result;
}

function getQueryAll($sql, $params=[])
{
    $dbConnect = getConnection();
    $stmt = $dbConnect->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function getQueryOne($sql, $params)
{
    $dbConnect = getConnection();
    $stmt = $dbConnect->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetch();
}

function getConnection()
{
    return new PDO('mysql:host=db;port=3306;dbname=geekbrains', 'root', 'password');
}

/**
 * @param $sql
 * @param $params
 * @return bool
 *
 *
$feedback_body ​=​ mysqli_real_escape_string​(​$db_link​, (​string​)​htmlspecialchars​(​strip_tags​(​$_POST​[​'user'​])));
 */

function executeQuery($sql, $params = []){
    $db = getConnection();

    $result = $db->prepare($sql)->execute($params);
    return $result;
}

function clearTable(){
    $dbConnect = getConnection();
    $sql ="TRUNCATE images";
    $stmt = $dbConnect->prepare($sql);
    $stmt->execute();
}

function incCountView($id){
    // Получает текущее количество просмотров
    $sqlGetCount = "select click_counter from images where id = " . $id;
    $count = getResultAsAssocArray($sqlGetCount);
    // Увеличиваем количество просмотров
    $count[0]['click_counter']++;
    $dbConnect = getConnection();
    $sql = "update images set click_counter = " . $count[0]['click_counter'] . " where id = " . $id;
    $stmt = $dbConnect->prepare($sql);
    $stmt->execute();
}