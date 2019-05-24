<?php
// Ключи - элементы меню, значения - подменю.
$menu = [
	"Элемент 1" => [],
	"Элемент 2" => [],
	"Элемент 3" => ["подменю 1" => [], 
				"подменю 2" => [], 
				"подменю 3" => []
				],
	"Элемент 4" => []
];

?>
<html>
    <head>
        <title>Lesson 3 task 6</title>
	<style>
		.menu {
		  list-style-type: none;
		}
	</style>
    </head>
    <body>
        <h1> Lesson 3 task 6 </h1>
        <div>6. В имеющемся шаблоне сайта заменить статичное меню (ul - li) на 
            генерируемое через PHP. Необходимо представить пункты меню как 
            элементы массива и вывести их циклом. Подумать, как можно 
            реализовать меню с вложенными подменю? Попробовать 
            его реализовать.
        </div>
    <ul class="menu">
	<?php foreach ($menu as $menuitem => $submenu){ ?>
        <li><a href = "#"><?= $menuitem ?></a>
		<?php 
			if (isset($submenu)){
				echo "<ul class='menu'>";
				foreach($submenu as $subsubmenu => $subitem){
					echo "<li><a href = '#'>$subsubmenu</a></li>";
				}
				echo "</ul>";
			}
		?>
		</li>
 	<?php } ?>
    </ul>
    </body>
</html>
