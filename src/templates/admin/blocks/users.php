<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <h3>Список пользователей</h3>
            <table class='tableBase'>
                <tr class='topTr'>
                    <td>id</td>
                    <td>Логин</td>
                    <td>Контактные данные </td>
                    <td>Группа </td>
                    <td>Статус </td>
                    <td>Дата входа </td>
                    <td>Управление </td>
                </tr>
                <?php foreach ($users as $user):?>
                        <tr id="userRow_<?=$user->getId()?>">
                            <td><?=$user->getId();?></td>
                            <td><?=$user->getLogin();?></td>
                            <td><?=$user->getName();?><br><?=$user->getEmail();?><br><?=$user->getPhone();?></td>
                            <td><?=$user->getRole()->getDescription();?></td>
                            <?php if($user->getId() != 1):?>
                                <td><label><b>Скрыть:</b><input type="checkbox" name="blocking" id="blocking" <?=$user->getBlocking();?> onchange="changeBlockUser(<?=$user->getId();?>)"></label></td>
                            <?php else:?> 
                                <td>Админ</td>
                        <?php endif;?>
                            <td><?=$user->getDateLogin()==null?'нет':$user->getDateLogin();?></td>
                            <td>
                                <span class='btn' id="resetPassword" onclick="showChangePasswordForm(<?=$user->getId();?>)"><i class="fa fa-key" aria-hidden="true"></i></span>
                                <span class='btn' id="editRow" onclick="showEditRow('users',<?=$user->getId();?>)"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                <span class='btn' id="deleteRow" onclick="deleteActiveRecord('users',<?=$user->getId();?>)"><i class="fa fa-trash" aria-hidden="true"></i></span>
                                <span id="actionField<?=$user->getId();?>"></span>
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
        <span class='btn btn-secondary' id="showAddRow" onclick="showFormAddRow('addUser')"><i class="fa fa-plus" aria-hidden="true"> Добавить</i></span>
        </div>

        <div class="col-6">
            <div id="errors"></div>
            <div id="result"></div>
        </div>
    </div>

</div>

    <script src="../gtm/src/script/scriptAdmin.js"></script>