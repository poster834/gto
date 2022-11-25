<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/../../../../../../gtm/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?=__DIR__.'/../../../../../../gtm/src/templates/style.css'?>">
    <link rel="stylesheet" href="<?=__DIR__.'/../../../../../../gtm/vendor/twbs/bootstrap/dist/css/bootstrap.min.css'?>">
    <link rel="stylesheet" href="<?=__DIR__.'/../../../../../../gtm/vendor/font-awesome-4.7.0/css/font-awesome.min.css'?>">

    <script src="<?=__DIR__.'/../../../../../../gtm/vendor/JQuery/jquery-3.5.1.min.js'?>"></script>
        <script src="<?=__DIR__.'/../../../../../../gtm/vendor/JQuery/jquery.form.js'?>"></script>
        <script src="<?=__DIR__.'/../../../../../../gtm/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js'?>"></script> 
        
    <title>Система мониторинга транспорта <?= $company->getName();?></title>
</head>
<?php

?>
<body>
<div class="wrapper">
    <div class="content">
        
    <div class="container-fluid text-center">
        <div class="row topLine">
            <div class="col-2">
                <div class="logo">
                    <a href="/gtm"><img class="logoImg" src="../gtm/src/templates/img/company/uploads/<?=$company->getLogo()?>" alt="logo"></a>   
                </div>
            </div>
            <div class="col">
                <div class="companyData">
                    <h2>Система мониторинга транспорта</h2>
                    <h4><?= $company->getName();?></h4>
                </div>
            </div>
            <div class="col-2 d-Flex">
                <?php if (isset($user)):?>
                    <div class="authData">
                        <div id="loginName">
                            <?=$user->getName();?>
                        </div>
                        <div id="logoutBtn">
                            <form action="<?=__DIR__.'/../../../../../../gtm/logout'?>" method="post">
                            <input type="submit" class="btn btn-danger" value="Выйти">
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="authData">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#enterModal">Вход</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal enter-->
        <div class="modal fade" id="enterModal" tabindex="-1" aria-labelledby="enterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="enterModalLabel">Вход в систему</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?=__DIR__.'/../../../../../../gtm/auth'?>" method="post">
                        <div class="modal-body">
                            <label for="email"><b>Логин:</b></label>
                            <input class="form-control" type="text" name="login" id="login" placeholder="Логин">

                            <label for="password"><b>Пароль:</b></label>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Пароль">
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" value="Войти">
                        </div>
                    </form>
                </div>
            </div>
        </div>