<?php
namespace Gtm\Controllers;

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
        $authData = $this->db->query('SELECT * FROM `users`;');

            $this->view->renderHtml('admin/mainAdmin.php',[
                'companyName'=>'',
                'authData'=>$authData,
            ]);
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
