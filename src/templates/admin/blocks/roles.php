<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <h3>Список групп пользователей</h3>
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
                            <?=$role->getName();?>
                        </td>
                        <td>
                            <?=$role->getDescription();?>
                        </td>
                        <td>
                            <?php if($role->getEditable() == 1):?>
                            <span class='btn' id="editRow" onclick="showEditRow('roles',<?=$role->getId();?>)"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                            <span class='btn' id="deleteRow" onclick="deleteActiveRecord('roles',<?=$role->getId();?>)"><i class="fa fa-trash" aria-hidden="true"></i></span>
                            <?php else: ?>
                                Описание прав доступа
                            <?php endif; ?>
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
       <!-- <span class='btn btn-secondary' id="showAddRow" onclick="showFormAddRow('addRole')"><i class="fa fa-plus" aria-hidden="true"> Добавить</i></span> -->
        </div>

        <div class="col-6">
            <div id="errors"></div>
            <div id="result"></div>
        </div>
    </div>

</div>

    <script src="../gtm/src/script/scriptAdmin.js"></script>