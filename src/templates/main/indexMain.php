<?php
setlocale(LC_TIME, 'ru_RU.UTF-8'); //или ru_RU.UTF-8
$lastMonth = strftime('%B %Y', strtotime('-1 month'));
?>

<h3>Главная</h3>
<div class="row">
    <div class="col-lg-3">
    <p style='text-align:center;'> <b>Обновление схемы: <?=$dateTime->format('d.m.y H:i');?></b></p>
        <hr>
        Групп всего: <b><?=$groupsCount;?> </b> / используется:  <b><?=$actionGroupCount;?></b>
        <hr>
        Машин всего: <b><?=$machinesCount;?> </b> / используется:  <b><?=$actionMachineCount;?></b>
        <hr>
        Приборов всего: <b><?=$devicesCount;?> </b> / используется:  <b><?=$actionDeviceCount;?></b>
        
        <hr>
        <br>
        
        <hr>
        Поломок всего: <b><?=$allFailuresCount;?> </b> / исправлено:  <b><?=$allDoneFailuresCount;?></b>
        <hr>
        Нарушений всего: <b><?=999;?> </b> / исправлено:  <b><?=999;?></b>
        <hr>
    </div>
    <div class="col-lg-3">        
        <p style='text-align:center;'> <b>Поломки <?=strftime('%B %Y');?></b></p>
        <hr>
        Всего: <b><?=count($TMperiodActiveFailureByTypes)+count($TMperiodDoneFailureByTypes);?> </b> / исправлено: <b><?=count($TMperiodDoneFailureByTypes);?></b> / в работе: <b><?=count($TMperiodActiveFailureByTypes);?></b><br>
        <ul>
            <?php foreach($TMperiodActiveFailureByTypes as $activeFailure):?>
            <!-- <li>
                <?php var_dump($failure)?>
            </li> -->
            <?php endforeach;?>
        </ul>
        <hr>
<br>

    <p style='text-align:center;'> <b>Поломки <?=$lastMonth;?></b></p>
        <hr>
        Всего: <b><?=count($PMperiodActiveFailureByTypes)+count($PMperiodDoneFailureByTypes);?> </b> / исправлено: <b><?=count($PMperiodDoneFailureByTypes);?></b> / в работе: <b><?=count($PMperiodActiveFailureByTypes);?></b><br>
        <ul>
            <?php foreach($PMperiodActiveFailureByTypes as $activeFailure):?>
            <!-- <li>
                <?php var_dump($failure)?>
            </li> -->
            <?php endforeach;?>
        </ul>
        <hr>


        <br>

    <p style='text-align:center;'> <b>Более ранние периоды</b></p>
        <hr>
        Всего: <b><?=count($earlyPeriodActiveByTypes)+count($earlyPeriodDoneByTypes);?> </b> / исправлено: <b><?=count($earlyPeriodDoneByTypes);?></b> / в работе: <b><?=count($earlyPeriodActiveByTypes);?></b><br>
        <ul>
            <?php foreach($PMperiodActiveFailureByTypes as $activeFailure):?>
            <!-- <li>
                <?php var_dump($failure)?>
            </li> -->
            <?php endforeach;?>
        </ul>
        <hr>

    </div>

    <div class="col-lg-3">
        
    </div>
    <div class="col-lg-3">

    </div>
</div>

