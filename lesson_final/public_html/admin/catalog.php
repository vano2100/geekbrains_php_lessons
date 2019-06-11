<?php

require '../../engine/core.php';

function routeIndex() {
    $categories = getItemArray("select * from category");
    $products = getItemArray("select * from product");

    echo render(
        'admin/catalog/list',
        [
            'categories' => $categories,
            'products' => $products
        ],
        true,
        'admin'
    );
}

function routeAddcategory() {
    if (isset($_POST['new_category'])) {
        $name = htmlspecialchars($_POST['cat_name']);
        $sql = "insert into category(`name`) values ('{$name}')";

        if (execute($sql)) {
            header("Location: /admin/catalog.php");
        }
    }

    echo render(
        'admin/catalog/add-category',
        [],
        true,
        'admin'
    );
}

function routeAddproduct() {
    $categories = getItemArray("select * from category");

    if (isset($_POST['new_product'])) {
        $name = htmlspecialchars($_POST['item_name']);
        $description = htmlspecialchars($_POST['item_description']);
        $price = intval($_POST['item_price']);
        $quantity = intval($_POST['item_quantity']);
        $category_id = intval($_POST['item_category']);

        $sql = "insert into product(`name`,`description`,`price`,`quantity`,`category_id`) values ('{$name}','{$description}',{$price},{$quantity},{$category_id})";

        if (execute($sql)) {
            header("Location: /admin/catalog.php");
        }
    }

    echo render(
        'admin/catalog/add-product',
        [
            'categories' => $categories,
        ],
        true,
        'admin'
    );
}

function routeEdtproduct() {
    $categories = getItemArray("select * from category");
    $product = getItem("select * from product where id = " . $_GET['id']);

    if (isset($_POST['edt_product'])) {
        $name = htmlspecialchars($_POST['item_name']);
        $description = htmlspecialchars($_POST['item_description']);
        $price = intval($_POST['item_price']);
        $quantity = intval($_POST['item_quantity']);
        $category_id = intval($_POST['item_category']);
        $id = intval($_POST['item_id']);
        $sql = "update product set `name`='{$name}',`description`='{$description}',`price`={$price},`quantity`={$quantity},`category_id`={$category_id} where id =" . $id;

        if (execute($sql)) {
            header("Location: /admin/catalog.php");
        }
    }

    echo render(
        'admin/catalog/edt-product',
        [
            'categories' => $categories,
            'item_name' => $product['name'],
            'item_price' => $product['price'],
            'item_quantity' => $product['quantity'],
            'item_description' => $product['description'],
            'item_category' => $product['category_id'],
            'item_id' => $_GET['id'],
        ],
        true,
        'admin'
    );
}

function routeDelProduct() {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        $sql = "delete from product where id = {$id}";

        if (execute($sql)) {
            header("Location: /admin/catalog.php");
        }
    }
}

route();