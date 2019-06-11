<form method="post">
    <div class="form-group">
        <label for="item_name">Наименование</label>
        <input name="item_name" value="<?= $item_name ?>" type="text" class="form-control" id="item_name" placeholder="Название товара">
    </div>

    <div class="form-group">
        <label for="item_price">Стоимость</label>
        <input name="item_price" value="<?= $item_price ?>" type="number" class="form-control" id="item_price" placeholder="150.00">
    </div>

    <div class="form-group">
        <label for="item_quantity">Количество</label>
        <input name="item_quantity" value="<?= $item_quantity ?>" type="number" class="form-control" id="item_quantity" placeholder="16">
    </div>

    <div class="form-group">
        <label for="item_category">Категория</label>
        <select name="item_category" class="form-control" id="item_category">
            
            <?php foreach ($categories as $cat) :?>
            <option <?php $cat['id'] == $item_category ? "selected" : "" ?> value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="item_description">Описание</label>
        <textarea name="item_description" class="form-control" id="item_description" placeholder="Описание товара" rows="7"><?= $item_description ?></textarea>
    </div>
    <input type="hidden" name="item_id" value="<?= $item_id ?>">
    <button type="submit" class="btn btn-primary" name="edt_product">Сохранить</button>
</form>