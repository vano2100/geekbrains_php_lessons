<?php

// для работы скрипта необходимо расширение включить mb_string в php.ini

echo '4. Объявить массив, индексами которого являются буквы русского языка, а значениями – соответствующие латинские буквосочетания (‘а’=> ’a’, ‘б’ => ‘b’, ‘в’ => ‘v’, ‘г’ => ‘g’, …, ‘э’ => ‘e’, ‘ю’ => ‘yu’, ‘я’ => ‘ya’).
Написать функцию транслитерации строк.<hr>';

//таблица перекодировки
$translit_codes = [
    "а" => "a",
    "б" => "b",
    "в" => "v",
    "г" => "g",
    "д" => "d",
    "е" => "e",
    "ё" => "jo",
    "ж" => "zh",
    "з" => "z",
    "и" => "i",
    "й" => "j",
    "к" => "k",
    "л" => "l",
    "м" => "m",
    "н" => "n",
    "о" => "o", 
    "п" => "p",
    "р" => "r",
    "с" => "s",
    "т" => "t",
    "у" => "u",
    "ф" => "f",
    "х" => "h",
    "ц" => "c",
    "ч" => "ch",
    "ш" => "sh",
    "щ" => "shh",
    "ъ" => "#", 
    "ы" => "y",
    "ь" => "'",
    "э" => "e",
    "ю" => "yu",
    "я" => "ya"   
];

function translit($source, $translit_codes){
    // Перевод сроки в нижний регистр. В в массиве есть только нижний регистр букв.
    $in = mb_strtolower($source);
    $result = [];
    for ($i = 0; $i < mb_strlen($in); $i++){
        // Проверяем есть ли соответствие в массие, если нет вернем исходный символ.
        if (array_key_exists(mb_substr($in, $i, 1),$translit_codes)){
            $result[] = $translit_codes[mb_substr($in, $i, 1)];
        } else {
            $result[] = mb_substr($in, $i, 1);
        }
    }
    return implode("", $result);
}

echo translit("Привет мир!", $translit_codes);
