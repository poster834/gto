<?php
namespace Gtm\Controllers;


ini_set('display_errors',1);
error_reporting(E_ALL);
use Gtm\Models\Users\User;
use Gtm\Models\Users\UsersAuthService;
use Gtm\Models\Roles\Role;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Models\Failures\Failure;
use Gtm\Exceptions\NotAllowException;
use Gtm\Exceptions\NotFoundException;
use Gtm\Models\Offenses\Offense;

class UsersController extends AbstractController
{
    public function login()
    {
        if (!empty($_POST)) {
           try {
            $user = User::login($_POST);
            UsersAuthService::createToken($user);
            header("Location: /gtm");
            exit();
            } catch (\Gtm\Exceptions\InvalidArgumentException $e){
                $this->view->renderHtml('main/main.php',[
                    'companyName'=>'',
                    'error'=>$e->getMessage(),
                ]);
                return;
            }
        }        

        $this->view->renderHtml('main/main.php',[
            'companyName'=>'',
            ]);
    }

    public function changePassword($userId)
    {      
        //Проверка на наличие прав отключена ввиду того, что проверка осуществляется в файле маршрутов и выдаются только доступные для пользователя маршруты
        
        // $user = UsersAuthService::getUserByToken();
        // if (isset($user)) {
        //     if ($user->isAdmin()) {
                if (!empty($_POST)) {
                    try {
                        $userChangePassw = User::getById((int)$userId);
                        $userChangePassw->changePassword($_POST);
                        $pageNumber = User::getActivePageById($userId);
                        echo $pageNumber;
                    } catch (\Gtm\Exceptions\InvalidArgumentException $e) {
                        $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                         exit();
                    }
                }
            // }else {
            //     $this->view->renderHtml('main/main.php', []);
            //     throw new NotAllowException();
            //     exit();
            // }
        // }
    }

    public function logout()
    {
        setcookie('token', '', -1, '/', '', false, true);
        header('Location: /gtm');
    }

    public function editRow(int $id)
    {
        $editUser = User::getById($id);
        $roles = Role::findAll();
        $this->view->renderHtml('admin/blocks/editUser.php',[
            'editUser'=>$editUser,
            'roles'=>$roles,
        ]);  
    }

    public function changeBlocking($userId)
    {
        $blockUser = User::getById($userId);
        if ($blockUser->isBlocked()) {
            $blockUser->unBlock();
        } else {
            $blockUser->block();
        }
        // $blockUser->setBloking($lock);
    }

    public function saveUser()
    {
        $user = new User();
        try {
            $user->updateFromArray($_POST);
        } catch (InvalidArgumentException $e) {
            $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
            exit();
        }
        $pageNumber = (int) User::getActivePageById($user->getId());
        echo $pageNumber;
    }

    public function delete(int $id)
    {
        $user = UsersAuthService::getUserByToken();
        if (isset($user)) {
            if ($user->isAdmin()) {
                $deletedUser = User::getById($id);
                if ($deletedUser == null) {
                    $this->view->renderHtml('errors/invalidArgument.php', ['error'=>'Нет пользователя с таким id. <a href="/gtm">Главная</a>']);
                    exit();
                }
                $breakage = Failure::findOneByColumn('user_id',$id);
                if (empty($breakage)) {
                    $breakage = Failure::findOneByColumn('user_service',$id);
                }
                $offenses = Offense::findOneByColumn('user_id',$id);
                if(empty($offenses))
                {
                    $offenses = Offense::findOneByColumn('user_service',$id);
                }

                if (!empty($breakage)) {
                    $this->view->renderHtml('errors/relationError.php', ['table'=>'Поломки', 'data'=>"id => ".$breakage->getId()]);
                    exit();
                } else if(!empty($offenses)){
                    $this->view->renderHtml('errors/relationError.php', ['table'=>'Нарушения', 'data'=>"id => ".$offenses->getId()]);
                    exit();
                }else if($deletedUser->getId() == 1){
                    $this->view->renderHtml('errors/invalidArgument.php', ['error'=>'Пользователь <b><i>'.$deletedUser->getLogin().'</b></i> является администратором системы. Удалить нельзя.']);
                    exit();
                } else {
                    $deletedUser->delete();
                    $pageNumber = User::getActivePageById($id-1);
                    echo $pageNumber;
                }
            } else {
                $this->view->renderHtml('main/main.php', []);
                throw new NotAllowException();
                exit();
            }
        }       
    }

    public function edit(int $userId)
    {
                $editUser = User::getById($userId);
                if ($editUser === null) {
                throw new NotFoundException();
                }
                if (!empty($_POST)) {
                    try {
                        $editUser->updateFromArray($_POST);
                    } catch (InvalidArgumentException $e) {
                        $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                        exit();
                    }          
                    $pageNumber = User::getActivePageById($userId);
                    echo $pageNumber;
                }
    }

    public function showAdd()
    {
        $roles = Role::findAll();
        $this->view->renderHtml('admin/blocks/addUser.php', ['roles'=>$roles]);
    }

}