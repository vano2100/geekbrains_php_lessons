<?php

/**
 * начальная страница сайта
 */

// поключаем конфигурации приложения
require '../engine/core.php';

function routeIndex() {
    echo render('site/home');
}

function routeError() {
    echo render('site/error');
}

route();