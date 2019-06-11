<h1>Заказы</h1>

<table class="table">
    <tr>
        <th>Номер</th>
        <th>Дата заказа</th>
        <th>Статус</th>
        <th>Клиент</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($orders as $x) : ?>
    <tr id="order-<?= $x['id'] ?>">
        <td><?= $x['id'] ?></td>
        <td><?= $x['created_at'] ?></td>
        <td class="field-status"><?= ($x['status'] == 0) ? 'В обработке' : 'Проведен' ?></td>
        <td><?= $x['user_id'] ?></td>
        <td>
            <button class="btn btn-success btn_process" data-id="<?= $x['id'] ?>">Провести</button>
            <button class="btn btn-danger btn_remove" data-id="<?= $x['id'] ?>">Удалить</button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<script defer src="/js/service.orders.js"></script>