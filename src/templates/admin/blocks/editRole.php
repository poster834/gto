<h3>Редактор</h3>
<form id="rolesEditForm" action="" method="post">
    <label class="editInput" for="id"><b>id:</b></label> <input class="editInput" name="id" disabled type="text" value="<?=$role->getId();?>">
    <br>
    <label class="editInput" for="name"><b>Имя:</b></label> <input class="editInput" name="name" type="text" value="<?=$role->getRoleName();?>">
    <br>
    <label class="editInput" for="description"><b>Описание:</b></label> <input class="editInput" name="description" type="text" value="<?=$role->getRoleDescription();?>">
    <br><br>
</form>


<div class='btn btn-success' onclick="editActiveRecord('roles',<?=$role->getId();?>)"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>

