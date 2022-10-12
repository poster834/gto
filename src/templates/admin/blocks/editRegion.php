<h3>Редакторировать Район</h3>
<?php
    $regionsDirection = $region->getDirectionId();
?>
<form id="regionsEditForm" action="" method="post">
    <label class="editInput" for="fio"><b>Направление:</b></label> <input class="editInput" name="name" type="text" value="<?=$region->getName();?>">
    <br>
    <label class="editInput" for="direction"><b>Направление:</b></label> 
    <select class="editInput" name="direction" id="direction">
                <option value="null" disabled>Выберете направление</option>
                    <?php foreach($directions as $direction):?>
                        <option value="<?=$direction->getId();?>" <?= $direction->getId()==$regionsDirection?' selected':'';?>><?=$direction->getName();?></option>
                    <?php endforeach;?>
    </select>
<br><br>
</form>


<div class='btn btn-success' onclick="editActiveRecord('regions',<?=$region->getId();?>)"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
<div class="btn btn-danger" onclick="cancelForm();"><i class="fa fa-window-close-o" aria-hidden="true"> Отмена</i></div>