<?php //include ('../gtm/src/templates/header.php');?>
<?php include (__DIR__.'/../header.php');?>
<div class="row">
    <div class="col-3">
        <?php if (isset($user) && $user->isAdmin()):?>
            <span class="btn btn-success" onclick="window.location.href = 'admin'">Вход в АдминПанель</span>
        <?php endif;?>

    </div>
    <div class="col">
        <?php echo isset($error) && strlen($error)>0? $error.'<br>' : '';?>
    </div>
</div>
<?php include (__DIR__.'/../footer.php');?>