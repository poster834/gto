<?php
namespace Gto\Controllers;

class MainController extends AbstractController
{
    public function main()
    {        
        $authData = null;
        $this->view->renderHtml('main/main.php',[
            'companyName'=>'ГК Талина',
            'authData'=>$authData,
        ]);
    }

    public function admin()
    {
        $authData = 'Пользователь';
        $isUserAdmin = true;
        if ($isUserAdmin) {
            $this->view->renderHtml('admin/mainAdmin.php',[
                'companyName'=>'ГК Талина',
                'authData'=>$authData,
            ]);    
        } else {
            $this->main();
        }
        
    }
}
