<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="src/templates/style.css">
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <title>Приборы Глонасс - ГК Талина</title>
</head>
<body>
    <div class="container-fluid text-center">
        <div class="row topLine">
            <div class="col-3 d-Flex">
                <?php if (isset($authData)):?>
                    <div class="authData"><?=$authData?></div>
                <?php else: ?>
                    <span class="authData">
                    <button type="button" class="btn btn-success">Вход</button>
                    <button type="button" class="btn btn-primary">Регистрация</button>

                    </span>
                <?php endif; ?>
            </div>
            <div class="col">
                <h2>Система мониторинга транспорта</h2>
                <h4><?=$companyName;?></h4>
            </div>
        </div>
   