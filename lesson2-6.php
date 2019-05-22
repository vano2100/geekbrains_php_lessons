<?php

function power($val, $pow){
	if ($pow > 1){
		$val = $val * power($val, $pow - 1);
	}
	return $val;
}

echo power(3, 3);
