<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3">
           Всего Групп в базе данных: <b><?=$groupsCount;?></b>
           <br>
           Всего Машин в базе данных: <b><?=$machinesCount;?></b>
           <hr>
           <h3>Список Машин</h3>
           # раскрывающийся список машин по группам с блокировкой каждой единицы или группы в целом для запрета показа в основном меню и исключение из общей статистики #
        </div>
        <div class="col-lg-9">

            <div id="result"></div>
        </div>
    </div>
</div>

<script src="../gtm/src/script/scriptAdmin.js"></script>