<?php
namespace Gtm\Controllers;

use Gtm\Models\Roles\Role;
use Gtm\Models\Users\User;
use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Exceptions\NotAllowException;
use Gtm\Models\Users\UsersAuthService;

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

    public function edit(int $id)
    {
                $role = Role::getById($id);
                if ($role === null) {
                throw new NotFoundException();
                }
                if (!empty($_POST)) {
                    try {
                        $role->updateFromArray($_POST);
                    } catch (InvalidArgumentException $e) {
                        $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                        exit();
                    }          
                    $pageNumber = Role::getActivePageById($id);
                    echo $pageNumber;
                }
    }

    public function delete(int $id)
    {
                $userTest = User::findOneByColumn('role_id',$id);
                $role = Role::getById($id);
                if ($userTest !== null) {
                    $this->view->renderHtml('errors/relationError.php', ['table'=>'Пользователи', 'data'=>"id => ".$userTest->getId()]);
                    exit();
                } else {
                    $role->delete();
                    $pageNumber = Role::getActivePageById($id-1);
                    echo $pageNumber;
                }
    }

    public function saveRole()
    {
                $role = new Role();   
                try {
                    $role->updateFromArray($_POST);
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                    exit();
                }          
                $pageNumber = (int) Role::getActivePageById($role->getId());
                echo $pageNumber;
    }

    public function showAdd()
    {
        $this->view->renderHtml('admin/blocks/addRole.php', []);
    }

}