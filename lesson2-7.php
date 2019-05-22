<?php
//ini_set("date.timezone", "Asia/Yakutsk");

function sklonenie($number, $one, $few, $many){
    if (($number % 100 >= 11) and ($number % 100 <= 14)){
        return $many;
    }
    // склонение определяется последней цифрой в числе
    $ostatok = $number % 10;
    // для 1 - именительный падеж (кто? что?)
    if ($ostatok == 1){
        return $one;
    }
    // для 2-4 - родительный падеж (кого? чего?)
    if (($ostatok >=2) and ($ostatok <= 4)){
        return $few;
    }
    // 5-9 или ноль – родительный падеж и множественное число
    if ((($ostatok >=5) and ($ostatok <= 9)) or ($ostatok == 0)){
        return $many;
    }
}

$hours_now = (int)date('H');
$minutes_now = (int)date('i');

echo $hours_now . " " . sklonenie($hours_now, "час", "часа", "часов") . " ";
echo $minutes_now . " " . sklonenie($minutes_now, "минута", "минуты", "минут");
