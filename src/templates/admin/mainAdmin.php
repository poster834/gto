<?php //include ('../gtm/src/templates/header.php');?>
<?php include (__DIR__.'/../header.php');?>

<div class="row">
    <div class="col-2">
       <?php 
        if (!isset($user)){
            echo isset($error) && strlen($error)>0? $error.'<br>' : '';
        }
        elseif($user->isAdmin()) {
            include('menuAdmin.php'); 
        }
        else {
            throw new \Gtm\Exceptions\NotAllowException();
        }
       ?>

    </div>
    <div class="col text-start">
      <div id="block"></div>
      <?php if(isset($_GET['id']) && isset($_GET['access_token'])):?>
        <input type="hidden" name="ID" id="ID" value="<?=$_GET['id'];?>">
        <input type="hidden" name="AT" id="AT" value="<?=$_GET['access_token'];?>">
        <?php endif;?>


      <span style="color: red;"><h2>Добавить сервис базы данных (принудительная очистка и т.д.)</h2></span>
    </div>
</div>
<!-- <script src="../gtm/src/script/scriptAdmin.js"></script> -->
<!-- <script src="<?=__DIR__.'/../../../../../../../gtm/src/script/scriptAdmin.js'?>"></script> -->

<?php //include('../gtm/src/templates/footer.php');?>
<?php include (__DIR__.'/../footer.php');?>

<script>
window.onload = function() {
    if ($('#ID').val() !== undefined && $('#AT').val() !== undefined) {
        arr = $('#ID').val().split('_');
        id = arr[0];
        mypage = arr[1];
        token = $('#AT').val();
        adrUrl = 'http://'+document.location.host + document.location.pathname; //убираем все get параметы
        history.pushState(null, null, adrUrl); //подставляем адрес без get параметов
        saveToken(id, token, mypage);// отправляем полученные get параметры на запись в БД
    }
};
</script>

<!-- http://scsrv.talina.ru/gtm/admin?id=2&access_token=dac229906e23daa0278792cb3c1e690b1E17DFED837510A2D0A1C850EC152B24D92D5D74 -->