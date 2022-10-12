<?php include ('../gtm/src/templates/header.php');?>

        <div class="row">
        <?php 
        if ($user->isAdmin()):?> 
            <div class="col text-center">
                <h3>Вы авторизованы пользователем группы "Администратор"</h3>
                <span class="btn btn-success" onclick="window.location.href = '<?=__DIR__.'/../../../../../../../gtm/admin'?>'">Вход в АдминПанель</span>
                <span class="btn btn-primary" onclick="window.location.href = '<?=__DIR__.'/../../../../../../../gtm/system'?>'">Режим пользователя</span>
            </div>
        <?php else:?>
            <?php throw new \Gtm\Exceptions\NotAllowException();?>
        <?php endif;?>
        </div>
  

<?php include('../gtm/src/templates/footer.php');?>





