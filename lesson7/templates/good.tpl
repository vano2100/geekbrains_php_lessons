<h1>{{NAME}} <span class="badge badge-primary">{{PRICE}}p.</span></h1>
<div>{{DESCRIPTION}}</div>
<form action="cart" method="post">
<input type="hidden" name="add" value="{{ID}}">
<input type="submit" value="В корзину" class="btn btn-primary">
</form>