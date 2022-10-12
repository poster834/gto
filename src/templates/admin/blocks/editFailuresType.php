<h3>Редактировать Тип поломки</h3>
<form id="failuresTypesEditForm" action="" method="post">
    <label class="editInput" for="id"><b>id:</b></label> <input class="editInput" readonly name="id" type="text" value="<?=$failuresType->getId();?>">
    <br>
    <label class="editInput" for="name"><b>Имя:</b></label> <input class="editInput" name="name" type="text" value="<?=$failuresType->getName();?>">
</form>

<br>
<div class='btn btn-success' onclick="editActiveRecord('failuresTypes',<?=$failuresType->getId();?>)"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
<div class="btn btn-danger" onclick="cancelForm();"><i class="fa fa-window-close-o" aria-hidden="true"> Отмена</i></div>

