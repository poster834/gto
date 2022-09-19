<?php
    if (count($roles)>3) {
        // echo "Нужен пагинатор";
        // var_dump($pages);
    } else {
        // echo "Не нужен пагинатор";
    }
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <h3>Список</h3>
            <table class='tableBase'>
                <tr class='topTr'>
                    <td>id</td>
                    <td>Имя</td>
                    <td>Описание </td>
                    <td>Управление</td>
                </tr>
            <?php foreach ($roles as $role):?>
                    <tr>
                        <td>
                            <?=$role->getId();?>
                        </td>
                        <td>
                            <?=$role->getRoleName();?>
                        </td>
                        <td>
                            <?=$role->getRoleDescription();?>
                        </td>
                        <td>
                            <span class='btn' id="editRow" onclick="showEditRow(<?=$role->getId();?>)"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                            <span class='btn' id="deleteRow" onclick="deleteActiveRecord('roles',<?=$role->getId();?>)"><i class="fa fa-trash" aria-hidden="true"></i></span>
                        </td>
                    </tr>
            <?php endforeach;?>
            </table>
            <hr>
            <div class="pagesPaginator"> Страницы:  
            <?php
            for($i = 1; $i <= $pages; $i++):?>
                 <span id="pagePaginator<?=$i;?>" class="pagePaginator" onclick="showBlock('roles',<?=$i;?>)"><?=$i;?></span>
            <?php endfor;?>
            </div>
            <hr>
        <span class='btn btn-secondary' id="showAddRow" onclick="showFormAddRow('roles')"><i class="fa fa-plus" aria-hidden="true"> Добавить</i></span>
    </div>

    <div class="col-6">
        <div id="result"></div>
        <div id="addRole" style="display: none;">
        <h3>Добавить новую группу</h3>
        <form id="rolesAddForm" action="" method="post">
            <label class="editInput" for="name"><b>Имя:</b></label> <input class="editInput" name="name" type="text" value="">
            <br>
            <label class="editInput" for="description"><b>Описание:</b></label> <input class="editInput" name="description" type="text" value="">
            <br><br>
        </form>
        <div class='btn btn-success' onclick="save('roles')"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
        <div class='btn btn-danger' onclick="$('#addRole').hide();"><i class="fa fa-save" aria-hidden="true"> Отмена</i></div>
                
        </div>
    </div>
    </div>

    </div>

    <script src="../gtm/src/script/scriptAdmin.js"></script>