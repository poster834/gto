<h3>Редактор пользователя</h3>
<?php
    $usersRole = $editUser->getRole()->getId();
?>
<form id="usersEditForm" action="" method="post">
    <label class="editInput" for="id"><b>id:</b></label> <input class="editInput" readonly name="id" type="text" value="<?=$editUser->getId();?>">
    <br>
    <label class="editInput" for="login"><b>Логин:</b></label> <input class="editInput" readonly name="login" type="text" value="<?=$editUser->getLogin();?>">
    <br>
    <label class="editInput" for="fio"><b>ФИО:</b></label> <input class="editInput" name="name" type="text" value="<?=$editUser->getName();?>">
    <br>
    <label class="editInput" for="email"><b>e-Mail:</b></label> <input class="editInput" name="email" type="email" value="<?=$editUser->getEmail();?>">
    <br>
    <label class="editInput" for="phone"><b>Телефон:</b></label> <input class="editInput" name="phone" type="text" value="<?=$editUser->getPhone();?>">
    <br>
    <label class="editInput" for="roleId"><b>Группа:</b></label> 
    <select class="editInput" name="roleId" id="roleId">
                <option value="null" disabled>Выберете группу пользователя</option>
                    <?php foreach($roles as $role):?>
                        <option value="<?=$role->getId();?>" <?= $role->getId()==$usersRole?' selected':'';?>><?=$role->getName()." (".$role->getDescription().")";?></option>
                    <?php endforeach;?>
    </select>
<br><br>
</form>


<div class='btn btn-success' onclick="editActiveRecord('users',<?=$editUser->getId();?>)"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
<div class="btn btn-danger" onclick="cancelForm();"><i class="fa fa-window-close-o" aria-hidden="true"> Отмена</i></div>