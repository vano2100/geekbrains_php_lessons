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
    $dbConnect = getConnectToDb();
    $result = [];
    foreach ($dbConnect->query($sql) as $row){
        $result[]=$row;
    }

    return $result;
}

function getConnectToDb()
{
    return new PDO('mysql:host=db;port=3306;dbname=galery', 'root', 'password');
}

function clearTable(){
    $dbConnect = getConnectToDb();
    $sql ="TRUNCATE images";
    $stmt = $dbConnect->prepare($sql);
    $stmt->execute();
}

function insertImageInfo($image){
    $dbConnect = getConnectToDb();
    $sql = "insert into images (filename, size) values (:name, :size)";
    $stmt = $dbConnect->prepare($sql);
    $stmt->execute($image);
}

// Счетчик просмотров
function incCountView($id){
    // Получает текущее количество просмотров
    $sqlGetCount = "select click_counter from images where id = " . $id;
    $count = getResultAsAssocArray($sqlGetCount);
    // Увеличиваем количество просмотров
    $count[0][0]++;
    $dbConnect = getConnectToDb();
    $sql = "update images set click_counter = " . $count[0][0] . " where id = " . $id;
    $stmt = $dbConnect->prepare($sql);
    $stmt->execute();
}