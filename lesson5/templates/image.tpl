<?php 
$sql = "select id, filename, size, click_counter from images where id = " . (int)$variables;
$image = getResultAsAssocArray($sql);
$image = $image[0]; 
incCountView($image['id']);
?>
<html>
    <head>
        <!--Bootstrap-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <title><?= SITE_TITLE ?></title>
    </head>
    <body class="p-3 mb-2 bg-dark text-white">
        <div class="container">
            <h1><?= $image['filename'] ?></h1>
            <hr/>
            <p>Размер изображения: <?= $image['size'] ?></p>
            <p>Количество просмотров: <?= $image['click_counter'] ?></p>
            <img src='img/<?= $image['filename'] ?>'>
        </div>
    </body>
</html>