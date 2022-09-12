<?php
namespace Gtm\Controllers;

use Gtm\Models\Users\User;

class MainController extends AbstractController
{
    public function main()
    {        
        $this->view->renderHtml('main/main.php',[
            'companyName'=>'',
        ]);
    }

    public function admin()
    {
            $this->view->renderHtml('admin/mainAdmin.php',[
                'companyName'=>'',
            ]);  
    }

    public function user()
    {        
        $this->view->renderHtml('user/mainUser.php',[
            'companyName'=>'',
        ]);
    }



    public function company()
    {
        $this->view->renderHtml('admin/company.php',[
            'companyName'=>'',
            
        ]);  
    }

    
}
