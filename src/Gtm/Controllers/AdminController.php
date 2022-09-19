<?php
namespace Gtm\Controllers;
use Gtm\Models\Roles\Role;

class AdminController extends AbstractController
{
    public function mainAdmin()
    {
        $this->view->renderHtml('admin/mainAdmin.php',[
            'companyName'=>'',
        ]);
    }

    public function company()
    {
        $this->view->renderHtml('admin/blocks/company.php',[
            'companyName'=>'',
        ]);
    }

    public function roles($page)
    {
        $roles = Role::findAllPerPage($page);
        $pages = Role::getPagesPaginator();
        $this->view->renderHtml('admin/blocks/roles.php',[
            'companyName'=>'',
            'roles'=>$roles,
            'pages'=>$pages,
        ]);
    }
}