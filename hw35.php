<?php

echo " 5. Написать функцию, которая заменяет в строке пробелы на подчеркивания и возвращает видоизмененную строчку. <hr>";

function spaceReplace($in){
    return implode("_", explode(" ",$in));
}

echo spaceReplace("Hello a big world!");
/*
 * тоже самое можно сделать встроееной функцией str_replace
 */
//echo str_replace(" ", "_", "Hello a big world!");