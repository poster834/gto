<?php include ('../gtm/src/templates/header.php');?>
<div class="row">
    <div class="col-3">
        <?php if(isset($user)&& $user !== null):?>
            <p style="background-color: #e78409; color:#fff;"><b>Обратитесь к специалистам для настройки программы.</b></p>
            <?php else:?>
                <p style="background-color: green; color:#fff;"><b>Для отображения меню войдите в систему мониторинга!</b></p>    
            <?php endif;?>
    </div>
    <div class="col">
        <?php echo isset($error) && strlen($error)>0? $error.'<br>' : '';?>
    </div>
</div>
<?php include('../gtm/src/templates/footer.php');?>