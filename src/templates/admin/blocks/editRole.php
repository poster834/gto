<h3>Редактор группы пользователей</h3>
<form id="rolesEditForm" action="" method="post">
    <label class="editInput" for="id"><b>id:</b></label> <input class="editInput" readonly name="id" type="text" value="<?=$role->getId();?>">
    <br>
    <label class="editInput" for="name"><b>Имя:</b></label> <input class="editInput" name="name" type="text" value="<?=$role->getName();?>">
    <br>
    <label class="editInput" for="description"><b>Описание:</b></label> <input class="editInput" name="description" type="text" value="<?=$role->getDescription();?>">
    <br><br>
</form>


<div class='btn btn-success' onclick="editActiveRecord('roles',<?=$role->getId();?>)"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
<div class="btn btn-danger" onclick="cancelForm();"><i class="fa fa-window-close-o" aria-hidden="true"> Отмена</i></div>

