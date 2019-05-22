<?php

// lesson 2 task 3
function sum($a, $b){
	return $a + $b;
}

function sub($a, $b){
	return $a - $b;
}

function mul($a, $b){
	return $a * $b;
}

function div($a, $b){
	return $a / $b;
}

// lesson 2 task 4
function mathOperation($arg1, $arg2, $operation){
	switch ($operation){
		case 'sum': return sum($arg1, $arg2);
		case 'sub': return sub($arg1, $arg2);
		case 'mul': return mul($arg1, $arg2);
		case 'div': return div($arg1, $arg2);
	}
}

echo mathOperation(2, 4, 'sum');
echo mathOperation(7, 5, 'sub');
echo mathOperation(2, 4, 'mul');
echo mathOperation(8, 4, 'div');
