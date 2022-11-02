<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3">
        <p style='text-align:center;'> <b>Обновлено: <?=$dateTime->format('d.m.y H:i');?></b></p>
        <hr>
        Всего Групп в базе данных: <b><?=$groupsCount;?></b>
           <hr>
           Всего Машин в базе данных: <b><?=$machinesCount;?></b>
           <hr>
           Всего Приборов в базе данных: <b><?=$devicesCount;?></b>
           <hr>
<!-- <pre> -->
           <?php print_r($groupsTree);?>
<!-- </pre> -->
        </div>
        <div class="col-lg-9">
            <h3>Загрузка и обновление списка машин</h3>
            <hr>
            <div id="errors"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <b>Вариант 1: обновить схему с WEB-сервера АвтоГРАФ</b><br><br> <button class='btn btn-primary' id='schemaWeb' name="schemaWeb" onclick="checkSchemaFile('web')">Запросить схему с сервера</button>
                    </div>
                    <div class="col-lg-4">
                    <b>Вариант 2: загрузить JSON файл схемы.</b> <br><span style="color:red;">ВАЖНО!</span> Файл должен быть сохранен в кодировке: <b>UTF-8 без BOM</b></br>
                    <form id="schemaFileSaveForm" action="admin/machines" method="post">
                        <label class="editInput" for="schemaFile"><b>Файл схемы:</b></label> <input id='schemaFile' class="editInput" name="schemaFile" type="file" onchange="checkSchemaFile('file')" accept=".json">  
                    </form> 
                    </div>
                    <div class="col-lg-4">
                        <b>Обновить схему ГеоЗон с WEB-сервера АвтоГРАФ</b><br><br> <button class='btn btn-primary' id='schemaWeb' name="schemaWeb" onclick="checkSchemaFile('web_geo')">Запросить геозоны с сервера</button>
                    </div>
                </div>
            </div>

            
            <div class="dataFile">
                
                        <hr>
           
            </div>
            <div id="result"></div>
        </div>
    </div>
</div>

<script src="../gtm/src/script/scriptAdmin.js"></script>