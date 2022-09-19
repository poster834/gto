<?php
namespace Gtm\Controllers;

use Gtm\Models\Users\User;

class MainController extends AbstractController
{
    public function main()
    {        
        if (isset($this->user) && $this->user->isAdmin()) {
            $this->admin();
        } else {
            $this->system();
        }
    }

    public function admin()
    {
        $this->view->renderHtml('admin/indexAdmin.php',[
            'companyName'=>'',
        ]);
    }

    public function system()
    {
        $this->view->renderHtml('main/main.php',[
            'companyName'=>'',
        ]); 
    }

    
}
