<?php
namespace Gto\Controllers;

class MainController extends AbstractController
{

    public function main()
    {        
        $authData = null;
        $this->view->renderHtml('main/main.php',[
            'companyName'=>'',
            'authData'=>$authData,
        ]);
    }

    public function admin()
    {
        $authData = null;
        $isUserAdmin = true;
        if ($isUserAdmin) {
            $this->view->renderHtml('admin/mainAdmin.php',[
                'companyName'=>'',
                'authData'=>$authData,
            ]);    
        } else {
            $this->main();
        } 
    }

    public function auth()
    {
        $authData = null;
        $isUserAdmin = true;
        if ($isUserAdmin) {
            $this->admin();
        } else {
            $this->main();
        } 
    }

    
}
