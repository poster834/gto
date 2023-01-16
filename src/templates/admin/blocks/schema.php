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

        <h6>Блокировка групп для пользователей</h6>
        <?php echo $groupsTree;?>

        </div>
        <div class="col-lg-9">
            <h3>Загрузка и обновление схемы машин</h3>
            <hr>
            <div id="errors"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                    <b>Вариант 1: обновить схему с WEB-сервера АвтоГРАФ</b><br>
                    
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
                                    <?php endif;?></td>
                        </tr>
                    </table>

                    <!-- <form id="schemaServerForm" action="admin" method="post">
                        <table style="width: 100%;">
                            <tr>
                                <td>HTTPS cервера:</td>
                                <td><input type="text" name="serverUrl" id="serverUrl" placeholder="online.tkserver.ru"></td>
                            </tr>
                            <tr>
                                <td>Логин:</td>
                                <td><input type="text" name="serverLogin" id="serverLogin" placeholder="companyLogin"></td>
                            </tr>
                            <tr>
                                <td>Пароль:</td>
                                <td><input type="password" name="serverPassword" id="serverPassword" placeholder="*****"></td>
                            </tr>
                        </table>
                    </form>-->
                    <br>
                    <?php if ($btnShow):?>
                        <button class='btn btn-primary <?=$btnShow?>' id='schemaWeb' name="schemaWeb" onclick="checkSchemaFile('web','machine')">Запросить список доступных Схем машин</button> 
                    <?php endif;?>
                    </div>

                    <div class="col-lg-6">
                    <!-- <b>Вариант 2: загрузить JSON файл схемы.</b> <br><span style="color:red;">ВАЖНО!</span> Файл должен быть сохранен в кодировке: <b>UTF-8 без BOM</b></br>
                    <form id="schemaFileSaveForm" action="admin/machines" method="post">
                        <label class="editInput" for="schemaFile"><b>Файл схемы:</b></label> <input id='schemaFile' class="editInput" name="schemaFile" type="file" onchange="checkSchemaFile('file')" accept=".json">  
                    </form>  -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">

                    </div>
                </div>
            </div>

            
            <div class="dataFile">
           
            </div>
            <div id="result"></div>
    
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#search").val('');
        $("#search").keyup(function(){
            let root = document.querySelectorAll('#menu > span:first-child');
            let rootId = document.querySelectorAll('#menu > span:first-child')[0].id;
            collapsGroup(rootId, root);

            _this = this;
            $.each($("#menu span.menuMachine"), function() {
                if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                    $(this).addClass('noVisible');
                    $(this).removeClass('visible');
                } else {
                    $(this).addClass('visible');
                    $(this).removeClass('noVisible');
                    $(this).addClass('menuMachineActive');
                    $(this).removeClass('menuMachineDeActive');

                }                
            });
            if ($("#search").val()=='') {
                $.each($(".menuMachine"), function() {
                    $(this).addClass('noVisible');
                    $(this).removeClass('visible');
                    $(this).removeClass('menuMachineActive');
                    $(this).addClass('menuMachineDeActive');
                });
                let root = document.querySelectorAll('#menu > span:first-child');
                let rootId = document.querySelectorAll('#menu > span:first-child')[0].id;
                collapsGroup(rootId, root);
                
                $('#'+rootId).removeClass('open');
                $.each($("#menu span.menuMachine"), function() {
                    $(this).removeClass('menuMachineActive');
                    $(this).addClass('menuMachineDeActive');
                });
            }
        });
    });
</script>