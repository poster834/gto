<?php
namespace Gtm\Controllers;


ini_set('display_errors',1);
error_reporting(E_ALL);
use Gtm\Models\Users\User;
use Gtm\Models\Users\UsersAuthService;
use Gtm\Models\Roles\Role;

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

    public function logout()
    {
        setcookie('token', '', -1, '/', '', false, true);
        header('Location: /gtm');
    }
}