<!-- 
    'geoSchema' => $geoSchema,
    'token' => $token,
    'server' => $url,
    'login' => $login, 
            'geoGroups' => $geoGroups,
            'geoFences' => $geoFences,
-->
<?php

use Gtm\Models\Companys\Company;

ini_set('display_errors',1);
error_reporting(E_ALL);
?>

<div class="row">
<div class="col-lg-6">
    <h3>Загрузка и обновление схемы геозон</h3>
    <hr>
    <div id="errors"></div>
    <?php if($upDate <> null): ?>
        <p style='text-align:center;'> <b>Обновлено: <?=date('d.m.Y H:i', strtotime($upDate));?></b></p>
        <hr>
        Всего Групп в базе данных: <b><?=count($geoGroups)?></b>
        <hr>
        Всего Геозон в базе данных: <b><?=count($geoFences)?></b>
        <hr>
    <?php endif;?>

    <b>Обновить схему с WEB-сервера АвтоГРАФ</b><br>
    <table>
        <tr>
            <td>СЕРВЕР: </td>
            <td id="serverUrl"><?=$server?></td>
        </tr>
        <tr>
            <td>ЛОГИН: </td>
            <td><?=$login?></td>
        </tr>
        <tr>
            <td>ТОКЕН: </td>
            <td>
                <?php if(strlen($token) < 1): ?> 
                    Получите ТОКЕН на вкладке <a href="#" onclick="showBlock('company')">Компания</a> 
                    <?php $btnShow=false;?>
                <?php else:?>
                        <?php echo $token; $btnShow=true;?>
                <?php endif;?>
            </td>
        </tr>
    </table>
    <br>
    <?php if ($btnShow):?>
        <button class='btn btn-primary btn-sm <?=$btnShow?>' id='schemaWeb' name="schemaWeb" onclick="checkSchemaFile('web','geo')">Запросить список доступных Схем геозон</button> 
    <?php endif;?>
    <div id="result"></div>
</div>

<div class="col-lg-6">
<h3>Определение групп для запросов и отчетов</h3>
<?php if ($upDate <> ""):?>
        <label for="plants_uid"><b>Группа всех площадок <?=$companyName;?>: </b></label>
        <select class="form-select form-select-sm" id="plantsUid" onchange="selectKeyGeoGroup('plantsUid')">
        <?php
        $option = "";
        $selected = "";
        if ($plantsUid == null) {
            $option = '<option id="plants_uid0" selected disabled>Выберете корневую группу Площадок '.$companyName.'</option>';
        }
        ?>
            <?=$option;?>
            <?php foreach($geoGroupAllLevel as $group):?>
                <?php if($group->getUid() == $plantsUid){
                    $selected = "selected";
                }
                ?>
                <option id="plants_uid_<?=$group->getUid()?>" value="v_<?=$group->getUid()?>" <?=$selected?>><?=$group->getName();?></option>
                <?php $selected = "";?>
                <?php endforeach;?>
        </select>
        <?php endif;?>
<?php if ($plantsUid <> ""):?>
    <label for="granaries_uid"><b>Площадки-зернохранилища: </b></label>
        <select class="form-select form-select-sm" id="granariesUid" onchange="selectKeyGeoGroup('granariesUid')">
        <?php
        $option = "";
        $selected = "";
        if ($granariesUid == null) {
            $option = '<option id="granaries_uid0" selected disabled>Выберете Группу "Площадки-зернохранилища"</option>';
        }
        ?>
            <?=$option;?>
            <?php foreach($geoGroupAllLevel as $group):?>
                <?php if($group->getUid() == $granariesUid){
                    $selected = "selected";
                }
                ?>
                <option id="granaries_uid_<?=$group->getUid()?>" value="v_<?=$group->getUid()?>" <?=$selected?>><?=$group->getName();?></option>
                <?php $selected = "";?>
                <?php endforeach;?>
        </select>
        <?php endif;?>

        <?php if ($granariesUid <> ""):?>
<label for="forage_uid"><b>Площадка загрузки кормовозов: </b></label>
        <select class="form-select form-select-sm" id="forageUid" onchange="selectKeyGeoGroup('forageUid')">
        <?php
        $option = "";
        $selected = "";
        if ($forageUid == null) {
            $option = '<option id="forage_uid0" selected disabled>Выберете Площадку загрузки комбикорма"</option>';
        }
        ?>
            <?=$option;?>
            <?php foreach($granariesFences as $fence):?>
                <?php if($fence->getUid() == $forageUid){
                    $selected = "selected";
                }
                ?>
                <option id="forage_uid_<?=$fence->getUid()?>" value="v_<?=$fence->getUid()?>" <?=$selected?>><?=$fence->getName();?></option>
                <?php $selected = "";?>
                <?php endforeach;?>
        </select>
<?php endif;?>

<?php if ($plantsUid <> ""):?>
<label for="farms_uid"><b>Площадки СВК или ферм: </b></label>
        <select class="form-select form-select-sm" id="farmsUid" onchange="selectKeyGeoGroup('farmsUid')">
        <?php
        $option = "";
        $selected = "";
        if ($farmsUid == null) {
            $option = '<option id="farms_uid0" selected disabled>Выберете Группу Свинокомплексов или ферм</option>';
        }
        ?>
            <?=$option;?>
            <?php foreach($geoGroupAllLevel as $group):?>
                <?php if($group->getUid() == $farmsUid){
                    $selected = "selected";
                }
                ?>
                <option id="farms_uid_<?=$group->getUid()?>" value="v_<?=$group->getUid()?>" <?=$selected?>><?=$group->getName();?></option>
                <?php $selected = "";?>
                <?php endforeach;?>
        </select>


        <label for="roads_uid"><b>Дороги: </b></label>
        <select class="form-select form-select-sm" id="roadsUid" onchange="selectKeyGeoGroup('roadsUid')">
            <?php
            $option = "";
            $selected = "";
            if ($roadsUid == null) {
                $option = '<option id="roads_uid0" selected disabled>Выберете группу геозон дорог</option>';
            }
            ?>
            <?=$option;?>
            <?php foreach($geoGroupAllLevel as $group):?>
                <?php if($group->getUid() == $roadsUid){
                    $selected = "selected";
                }
                ?>
                <option id="roads_uid_<?=$group->getUid()?>" value="v_<?=$group->getUid()?>" <?=$selected?>><?=$group->getName();?></option>
                <?php $selected = "";?>
                <?php endforeach;?>
        </select>


       <label for="locality_uid"><b>Населенные пункты: </b></label>
        <select class="form-select form-select-sm" id="localityUid" onchange="selectKeyGeoGroup('localityUid')">
        <?php
            $option = "";
            $selected = "";
            if ($localityUid == null) {
                $option = '<option id="locality_uid0" selected disabled>Выберете группу "Населенные пункты"</option>';
            }
            ?>
            <?=$option;?>
            <?php foreach($geoGroupAllLevel as $group):?>
                <?php if($group->getUid() == $localityUid){
                    $selected = "selected";
                }
                ?>
                <option id="locality_uid_<?=$group->getUid()?>" value="v_<?=$group->getUid()?>" <?=$selected?>><?=$group->getName();?></option>
                <?php $selected = "";?>
                <?php endforeach;?>
        </select>
       <hr>
       <?php endif;?>
       <?php if ($plantsUid <> ""):?>
       <button class='btn btn-info btn-sm' id='GeoCoords' name="GeoCoords" onclick="updateGeoCoords()">Обновить координаты выбранных групп</button>
       <?php endif;?>
       <div id="resultCoords"> </div>

    </div>

</div>