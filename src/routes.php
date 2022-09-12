<?php
/**
*   Настроить контроллер БД и добавить базу данных с таблицами (glonassBase)
*   Добавить в таблицу Пользователей администратора admin/admin при первом входе предлагать сменить пароль и настроить систему (Логотип, Название компании, добавить польщователей и т.д.)
*   
*   Страницы: 
*   Админка(администрирование системы) компания, группы пользователей, пользователи, настройка оповещения, активность групп приборов, настройка поведения, типы проблем и т.д.
*   Главная(основная информация по состоянию приборов и т.д.), 
*   Специалист по мониторингу (основной функционал), 
*   Инженер ИТ(функционал по решению проблем)
*   
*   
*   
*   
*   
*   
*/

return [
    '~^$~' => [Gtm\Controllers\MainController::class, 'main'],
    '~^admin$~' => [Gtm\Controllers\MainController::class, 'admin'],
    '~^user$~' => [Gtm\Controllers\MainController::class, 'user'],
    '~^auth$~' => [Gtm\Controllers\UsersController::class, 'login'],
    '~^company$~' => [Gtm\Controllers\MainController::class, 'company'],
    '~^logout$~' => [Gtm\Controllers\UsersController::class, 'logout'],
];