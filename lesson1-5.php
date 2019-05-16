<?php

$a = 1;
$b = 2;
echo "before changes a= $a, b= $b <br>";
$a = $a + $b;
$b = $a - $b;
$a = $a - $b;
echo "after changes a= $a, b= $b";
