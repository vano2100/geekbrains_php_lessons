<h1>Личный кабинет - <?=  $_SESSION['auth']['login'] ?></h1>

<form method="post">
    <div class="form-group">
        <label for="user_name">Имя пользователя</label>
        <input name="user_name" value="<?= $_SESSION['auth']['login'] ?>" type="text" class="form-control" id="user_name" placeholder="Имя пользователя">
    </div>
    <div class="form-group">
        <label for="user_pass">Пароль</label>
        <input name="user_pass" type="text" class="form-control" id="user_pass" placeholder="Пароль">
    </div>
    <div class="form-group">
        <label for="user_pass_confirm">Еще раз пароль</label>
        <input name="user_pass_confirm" type="text" class="form-control" id="user_pass_confirm" placeholder="Подтверждение пароля">
    </div>


    <button type="submit" class="btn btn-primary" name="edt_user">Изменить</button>
</form>