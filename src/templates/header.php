<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="src/templates/style.css">
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <title>Система мониторинга транспорта</title>
</head>

<body>
    <div class="container-fluid text-center">
        <div class="row topLine">
            <div class="col-2">
                <div class="logo">
                    <a href="/gtm"><img src="#" alt="logo"></a>   
                </div>
            </div>
            <div class="col">
                <div class="companyData">
                    <h2>Система мониторинга транспорта</h2>
                    <h4><?=$companyName;?></h4>
                </div>
            </div>
            <div class="col-2 d-Flex">
                <?php if (isset($authData)):?>
                    <div class="authData"><?=$authData?></div>
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
                    <form action="auth" method="post">
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