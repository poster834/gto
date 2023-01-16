<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <h3>Доступные параметры машин в схеме</h3>
            <table class='tableBase'>
                <tr class='topTr'>
                    <td>id</td>
                    <td>Имя</td>
                    <td>Название в карточке</td>
                    <td>Порядок сортировки</td>
                    <td>Управление</td>
                </tr>
            <?php foreach ($propertiesTypes as $propertyType):?>
                    <tr>
                        <td>
                            <?=$propertyType->getId();?>
                        </td>
                        <td>
                            <?=$propertyType->getName();?>
                        </td>
                        <td>
                            <?=$propertyType->getDescription();?>
                        </td>
                        <td>
                            <?=$propertyType->getSort();?>
                        </td>
                        <td>
                            <?php if($propertyType->getIsUses()==0):?>
                                <span class="btn btn-warning btn-sm" id="showAddRow" onclick="showFormAddRowProp('addPropertiesTypes','<?=$propertyType->getId()?>')"><i class="fa fa-plus" aria-hidden="true"></i></span>    
                            
                                <?php else:?>
                                    <span class="btn btn-danger btn-sm" id="showAddRow" onclick="setUnUse('<?=$propertyType->getId()?>')"><i class="fa fa-minus" aria-hidden="true"></i></span>    
                                    <span class='btn' id="editRow" onclick="showEditRow('propertiesTypes',<?=$propertyType->getId();?>)"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                <?php endif;?>
                            
                            
                        </td>
                    </tr>
            <?php endforeach;?>
            </table>
            <hr>
            <div class="pagesPaginator"> Страницы:  
            <?php
            for($i = 1; $i <= $pages; $i++):?>
                 <span id="pagePaginator<?=$i;?>" class="pagePaginator" onclick="showBlock('propertiesTypes',<?=$i;?>)"><?=$i;?></span>
            <?php endfor;?>
            </div>
    <hr>

    </div>

        <div class="col-6">
            <div id="errors"></div>
            <div id="result"></div>
        </div>
    </div>

</div>

    <script src="../gtm/src/script/scriptAdmin.js"></script>