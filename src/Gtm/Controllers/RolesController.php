<?php
namespace Gtm\Controllers;

use Gtm\Models\Roles\Role;
use Gtm\Models\Users\User;
use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Exceptions\RelationException;

ini_set('display_errors',1);
error_reporting(E_ALL);
class RolesController extends AbstractController
{
    public function editRow(int $id)
    {
        $role = Role::getById($id);
        $this->view->renderHtml('admin/blocks/editRole.php',[
            'role'=>$role,
        ]);  
    }

    public function edit(int $roleId)
    {
        $role = Role::getById($roleId);
        
 
        if ($role === null) {
          throw new NotFoundException();
        }
        if (!empty($_POST)) {
            try {
                $role->updateFromArray($_POST);
                $pageNumber = Role::getActivePageById($roleId);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
            }
            echo $pageNumber;
        }
    }

    public function delete(int $id)
    {
        $user = User::findOneByColumn('role',$id);
        $role = Role::getById($id);
        if ($user !== null) {
            $this->view->renderHtml('errors/relationError.php', ['table'=>'Пользователи', 'data'=>"id => ".$user->getId()]);
            exit();
        } else {
            $role->delete();
        }
        $pageNumber = Role::getActivePageById($id);
        echo $pageNumber;
    }

    public function saveRole()
    {
        $role = new Role();   
        $role->updateFromArray($_POST);
        $roles = Role::findAllPerPage(1);
        $pages = Role::getPagesPaginator();
        $this->view->renderHtml('admin/blocks/roles.php',[
            'companyName'=>'',
            'roles'=>$roles,
            'pages'=>$pages
            ]);

    }

}