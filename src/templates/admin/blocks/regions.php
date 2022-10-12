<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <h3>Список районов</h3>
            <table class='tableBase'>
                <tr class='topTr'>
                    <td>id</td>
                    <td>Имя</td>
                    <td>Направление</td>
                    <td>Управление</td>
                </tr>
            <?php foreach ($regions as $region):?>
                    <tr>
                        <td>
                            <?=$region->getId();?>
                        </td>
                        <td>
                            <?=$region->getName();?>
                        </td>
                        <td>
                            <?=$region->getDirectionName();?>
                        </td>
                        <td>
                            <span class='btn' id="editRow" onclick="showEditRow('regions',<?=$region->getId();?>)"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                            <span class='btn' id="deleteRow" onclick="deleteActiveRecord('regions',<?=$region->getId();?>)"><i class="fa fa-trash" aria-hidden="true"></i></span>
                        </td>
                    </tr>
            <?php endforeach;?>
            </table>
            <hr>
            <div class="pagesPaginator"> Страницы:  
            <?php
            for($i = 1; $i <= $pages; $i++):?>
                 <span id="pagePaginator<?=$i;?>" class="pagePaginator" onclick="showBlock('regions',<?=$i;?>)"><?=$i;?></span>
            <?php endfor;?>
            </div>
            <hr>
        <span class='btn btn-secondary' id="showAddRow" onclick="showFormAddRow('addRegion')"><i class="fa fa-plus" aria-hidden="true"> Добавить</i></span>
        </div>

        <div class="col-6">
            <div id="errors"></div>
            <div id="result"></div>
        </div>
    </div>

</div>

    <script src="../gtm/src/script/scriptAdmin.js"></script>