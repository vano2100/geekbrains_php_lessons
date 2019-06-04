<html>
    <head>
        <title>Lesson6 hw 1</title>
    </head>
<body>
    <h1>Урок 6 ДЗ 1</h1>
    <p>Создать форму-калькулятор операциями: сложение, вычитание, умножение, деление. 
    Не забыть обработать деление на ноль! Выбор операции можно осуществлять с помощью
     тега &lt;select&gt;.
    </p>
    <form action="">
        <label for="number1">Число А:</label>
        <input type="text" id="number1" name="num1"><br>
        <label for="number2">Число B:</label>
        <input type="text" id="number2" name="num2"><br>
        <label for="ops">Операция:</label>
        <select name="ops" id="ops">
            <option value="sum">+</option>
            <option value="sub">-</option>
            <option value="mul">*</option>
            <option value="div">/</option>
        </select><br>
        <input type="submit">
    </form>

<?php

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
    if ($b == 0) {
        return 0;
    }
	return $a / $b;
}

function mathOperation($arg1, $arg2, $operation){
	switch ($operation){
		case 'sum': return sum($arg1, $arg2);
		case 'sub': return sub($arg1, $arg2);
		case 'mul': return mul($arg1, $arg2);
		case 'div': return div($arg1, $arg2);
	}
}

if (isset($_GET['ops']) && isset($_GET['num1']) && isset($_GET['num2'])){
    $result = mathOperation((int)$_GET['num1'], (int)$_GET['num2'], $_GET['ops']);
    echo "Результат: <b>$result</b>";
}
?>
</body>
</html>
