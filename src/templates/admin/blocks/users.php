<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <h3>Список</h3>
            <table class='tableBase'>
                <tr class='topTr'>
                    <td>id</td>
                    <td>Логин</td>
                    <td>ФИО </td>
                    <td>e-mail </td>
                    <td>Телефон </td>
                    <td>Группа </td>
                    <td>Статус </td>
                    <td>Дата входа </td>
                    <td>Управление </td>
                </tr>
            <?php foreach ($users as $user):?>
                    <tr>
                        <td><?=$user->getId();?></td>
                        <td><?=$user->getLogin();?></td>
                        <td><?=$user->getName();?></td>
                        <td><?=$user->getEmail();?></td>
                        <td><?=$user->getPhone();?></td>
                        <td><?=$user->getRole()->getRoleName();?></td>
                        <td><?=$user->getStatus();?></td>
                        <td><?=$user->getDateActive();?></td>
                        <td>
                            <?php if ($user->getStatus == 'unlock'):?>
                            <span class='btn' id="lockRow" onclick="lockUser('users',<?=$user->getId();?>)"><i class="fa fa-user-lock" aria-hidden="true"></i></span>
                            <?php endif;?>
                            <?php if ($user->getStatus == 'lock'):?>
                            <span class='btn' id="unlockRow" onclick="unlockUser('users',<?=$user->getId();?>)"><i class="fa fa-user-unlock" aria-hidden="true"></i></span>
                            <?php endif;?>
                            <span class='btn' id="resetPassword" onclick="resetPassword(<?=$user->getId();?>)"><i class="fa fa-user-secret" aria-hidden="true"></i></span>
                            <span class='btn' id="editRow" onclick="showEditRow(<?=$user->getId();?>)"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                            <span class='btn' id="deleteRow" onclick="deleteActiveRecord('users',<?=$user->getId();?>)"><i class="fa fa-trash" aria-hidden="true"></i></span>
                        </td>
                    </tr>
            <?php endforeach;?>
            </table>
            <hr>
            <div class="pagesPaginator"> Страницы:  
            <?php
            for($i = 1; $i <= $pages; $i++):?>
                 <span id="pagePaginator<?=$i;?>" class="pagePaginator" onclick="showBlock('users',<?=$i;?>)"><?=$i;?></span>
            <?php endfor;?>
            </div>
            <hr>
        <span class='btn btn-secondary' id="showAddRow" onclick="showFormAddRow('users')"><i class="fa fa-plus" aria-hidden="true"> Добавить</i></span>
    </div>

    <div class="col-6">
        <div id="result"></div>
        <div id="addUser" style="display: none;">
        <h3>Добавить пользователя</h3>
        <form id="usersAddForm" action="" method="post">
                    <td>Логин</td>
                    <td>ФИО </td>
                    <td>e-mail </td>
                    <td>Телефон </td>
                    <td>Группа </td>

            <label class="editInput" for="login"><b>Логин:</b></label> <input class="editInput" name="login" type="text" value="">
            <br>
            <label class="editInput" for="fio"><b>ФИО:</b></label> <input class="editInput" name="fio" type="text" value="">
            <br>
            <label class="editInput" for="email"><b>e-Mail:</b></label> <input class="editInput" name="email" type="email" value="">
            <br>
            <label class="editInput" for="phone"><b>Телефон:</b></label> <input class="editInput" name="phone" type="text" value="">
            <br>
            <label class="editInput" for="role"><b>Группа:</b></label> 
            <select name="role" id="role">
                <?php foreach($roles as $role):?>
                    <option value="<?=$role->getId();?>"><?=$role->getRoleName();?></option>
                <?php endforeach;?>
            </select>
            <br>
        </form>
        <div class='btn btn-success' onclick="save('users')"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
        <div class='btn btn-danger' onclick="$('#addUser').hide();"><i class="fa fa-save" aria-hidden="true"> Отмена</i></div>
                
        </div>
    </div>
    </div>

    </div>

    <script src="../gtm/src/script/scriptAdmin.js"></script>