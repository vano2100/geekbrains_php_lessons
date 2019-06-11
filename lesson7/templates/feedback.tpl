<form method="post">
    <p>Имя пользоваетля </p>
    <p><input type="text" name="feedback[username]"/></p>
    <p>Текст сообщения </p>
    <p><input type="text" name="feedback[text]"/></p>
    <p><input type="submit" value="Отправить"/></p>
</form>

<div class="feedback_response">
    {{RESPONSE}}
</div>

<div class="feedback_feed">
    {{FEEDBACKFEED}}
</div>