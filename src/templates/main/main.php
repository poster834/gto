<?php //include ('../gtm/src/templates/header.php');?>
<?php include (__DIR__.'/../header.php');?>
<div class="row">
    <div class="col-2">

    <?php 
        if (!isset($user)){
            echo isset($error) && strlen($error)>0? $error.'<br>' : '';
            
        }
        elseif($user) {
            include('menuMain.php'); 
        }
        else {
            throw new \Gtm\Exceptions\NotAllowException();
        }
       ?>

        <?php if (isset($user) && $user->isAdmin()):?>
            <h6>Вы в режиме "Пользователь"</h6>
            <span class="btn btn-success" onclick="window.location.href = 'admin'">Вход в АдминПанель</span>
        <?php endif;?>

    </div>
    <div class="col">
        <?php echo isset($error) && strlen($error)>0? $error.'<br>' : '';?>
        <div class="col text-start">
      <div id="block"></div>
      <?php if(isset($_GET['id']) && isset($_GET['access_token'])):?>
        <input type="hidden" name="ID" id="ID" value="<?=$_GET['id'];?>">
        <input type="hidden" name="AT" id="AT" value="<?=$_GET['access_token'];?>">
        <?php endif;?>
    </div>
    </div>
</div>
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