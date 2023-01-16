<div id="companyBlock">
    <div class="container-fluid">
    <div class="row">
        <div class="col-6">
                <h3>Настройка параметров организации</h3>
                <hr>            
                <div id="errors"></div>     
            <div class="logo">
                <?php 
                if ($company->getLogo()):?>
                <img class="logoImg" src="../gtm/src/templates/img/company/uploads/<?=$company->getLogo()?>" alt="Логотип">
                <span class="btn btn-danger btn-sm" id="showAddRow" onclick="deleteLogo()">X</span> 
                <?php endif;?>
                
            </div>
            <br>
            <form id="companySaveLogoForm" action="admin" method="post">
            <label class="editInput" for="logo"><b>Логотип:</b></label> <input id='logo' class="editInput" name="logo" type="file" onchange="selectLogo()" accept=".png, .jpg, .jpeg">  
            </form> 
                <form id="companySaveForm" action="admin" method="post">
                    <label class="editInput" for="name"><b>Наименование:</b></label> <input class="editInput" name="name" type="text" value="<?=$company->getName();?>">
                    <label class="editInput" for="phone"><b>Телефон:</b></label> <input class="editInput" name="phone" type="text" value="<?=$company->getPhone();?>">
                    <label class="editInput" for="email"><b>E-mail:</b></label> <input class="editInput" name="email" type="text" value="<?=$company->getEmail();?>">
                    <label class="editInput" for="rootGuid"><b>Корневой GUID схемы машин:</b></label> <input class="notEditInput" readonly name="rootGuid" type="text" value="<?=$company->getRootGuid();?>">
                    <label class="editInput" for="agToken"><b>Токен авторизации на сервере АвтоГраф:</b></label> <input class="notEditInput" readonly name="agToken" type="text" value="<?=$company->getAgToken();?>">
                    <br>
                    <br>
                    <div class='btn btn-success' onclick="saveCompany()"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
                </form> 
            </div>
      
        <div class="col-6">
        <h3>Авторизация на сервере Автограф</h3>
        <form id="agServerForm" action="admin" method="post">
                        <table style="width: 100%;">
                            <tr>
                                <td>HTTPS cервера:</td>
                                <td><input type="text" name="serverUrl" id="serverUrl" value="<?=$company->getAgServer();?>"></td>
                            </tr>
                            <tr>
                                <td>Логин:</td>
                                <td><input type="text" name="serverLogin" id="serverLogin" value="<?=$company->getAgLogin();?>"></td>
                            </tr>
                            <tr>
                                <td>Пароль:</td>
                                <td><input type="password" name="serverPassword" id="serverPassword"></td>
                            </tr>
                        </table>
                    </form>
                    <br>
                    <button class='btn btn-primary' id='schemaWeb' name="schemaWeb" onclick="getAgToken()">Получить ТОКЕН авторизации</button>
        </div>
    </div>
</div>
  </div>
<script src="../gtm/src/script/scriptAdmin.js"></script>