<?php
// var_dump($hCar);
// var_dump($wialonAccount);
?>
<div id="status<?=$hCar->getId()?>">

</div>
<h3>
    <?=$hCar->getName();?>
</h3>
<h5>
<?=$wialonAccount->getName();?>
</h5>

<div class="btn-group" role="group" aria-label="">
  <input type="radio" class="btn-check" name="btnradio" typeOver="overTime" id="overTime_<?=$hCar->getId()?>" autocomplete="off" onclick="showParamHiredCarOffenses(this.id)">
  <label class="btn btn-outline-warning" for="overTime_<?=$hCar->getId()?>">Завышены часы</label>

  <input type="radio" class="btn-check" name="btnradio" typeOver="overCruise" id="overCruise_<?=$hCar->getId()?>" autocomplete="off" onclick="showParamHiredCarOffenses(this.id)">
  <label class="btn btn-outline-warning" for="overCruise_<?=$hCar->getId()?>">Завышены рейсы</label>
</div>

<div id="HC_offensesAdd" class="noVisible">
    <div id="HC_result">
        <hr>
        <label class="form-label" for="quantity">Завышено на:</label>
        <input class="form-control" type="number" id="quantity" autocomplete="off">
    </div>
    <hr>
    <button type="button" id="addOffensesHiredCar<?=$hCar->getId()?>" class="btn btn-danger btn-sm addOffensesHiredCar" onclick="addOffensesHiredCar(<?=$hCar->getId()?>)">Добавить нарушение</button>
</div>