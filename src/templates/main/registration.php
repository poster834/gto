<?php include ('../gto/src/templates/header.php');?>
<div class="row">
    <div class="col text-left">
        <?php if (empty($error)):?>
            <p class="badge bg-success text-wrap fs-3 lh-lg"> Заявка на регистрацию успешно создана.<br>Скоро Вам создадут учетную запись.<br>Учетные данные будут отправлены на адрес электронной почты, указанный при регистрации.</p>
        <?php else: ?>
            <?php foreach($error as $err):?>
            <p class="badge bg-danger text-wrap fs-3 lh-lg"> <?=$err;?></p><br>
            <?php endforeach;?>
        <?php endif; ?>
    </div>
</div>
<?php include('../gto/src/templates/footer.php');?>