<?php

namespace Gtm\Controllers;

class UsersController extends AbstractController
{
    public function registration()
    {
        $error = [];
        
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Некорректно указан Email';
        }

        if (strlen($_POST['fio']) < 5) {
            $error[] = 'Некорректно указано ФИО';
        }
        
        if (strlen($_POST['phone']) < 10) {
            $error[] = 'Некорректно указан Телефон';
        }

        $this->view->renderHtml('main/registration.php',[
            'companyName'=>'ГК Талина',
            'error'=>$error
        ]);  

        if(empty($error)){
            // mail();
        }
    }
}