<div id="companyBlock">
    <div class="container-fluid">
    <div class="row">
        <div class="col-6">
                <h3>Настройка параметров организации</h3>
                <hr>
                <form id="companySaveForm" action="admin" method="post">
                    <label class="editInput" for="name"><b>Наименование:</b></label> <input class="editInput" name="name" type="text" value="<?=$company->getName();?>">
                    <label class="editInput" for="phone"><b>Телефон:</b></label> <input class="editInput" name="phone" type="text" value="<?=$company->getPhone();?>">
                    <label class="editInput" for="email"><b>E-mail:</b></label> <input class="editInput" name="email" type="text" value="<?=$company->getEmail();?>">
                    <label class="editInput" for="rootGuid"><b>Корневой GUID:</b></label> <input class="notEditInput" readonly name="rootGuid" type="text" value="<?=$company->getRootGuid();?>">
                    <br>
                    <br>
                    <div class='btn btn-success' onclick="saveCompany()"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
                </form> 
            </div>
      
        <div class="col-6">
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
        </div>
    </div>
</div>
  </div>
<script src="../gtm/src/script/scriptAdmin.js"></script>