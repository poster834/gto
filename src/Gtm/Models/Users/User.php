<?php
namespace Gtm\Models\Users;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Exceptions\NotAllowException;
use Gtm\Models\Roles\Role;
use Gtm\Services\Db;

class User extends ActiveRecordEntity
{

    /** @var string*/
    protected $login;

    /** @var string*/
    protected $passwordHash;
    
    /** @var string*/
    protected $name;

    /** @var string*/
    protected $blocking;

    /** @var string*/
    protected $email;

    /** @var string*/
    protected $dateLogin;

    /** @var string*/
    protected $phone;

    /** @var string*/
    protected $authToken;

    /** @var int*/
    public $roleId;

    /** @var string*/
    protected $salt;

    protected static function getTableName()
    {
        return 'users';
    }
    protected static function getCountPerPage()
    {
        return 5;
    }

    private function getPasswordHash():string
    {
        return $this->passwordHash;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function getLogin()
    {
        return $this->login;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function isBlocked()
    {
        if($this->blocking == 'checked'){
            return true;
        }
        return false;
    }

    public function getBlocking()
    {
        return $this->blocking;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getDateLogin()
    {
        if($this->dateLogin !== null)
        {
            return date('d.m.y',strtotime($this->dateLogin));
        } else {
            return $this->dateLogin;
        }
        
    }

    public function getRole()
    {
        return Role::getById($this->roleId);
    }
    
    public function getRoleId()
    {
        return $this->roleId;
    }

    public function getAuthToken()
    {
        return $this->authToken;
    }

    public function isAdmin()
    {
        if ($this->roleId == 1) {
            return true;
        }
        return false;
    }

    protected function setLogin($newLogin)
    {
        $this->login = $newLogin;
    }

    protected function setName($newName)
    {
        $this->name = $newName;
    }

    protected function setEmail($newEmail)
    {
        $this->email = $newEmail;
    }

    protected function setPhone($newPhone)
    {
        $this->phone = $newPhone;
    }

    protected function setRoleId($newRoleId)
    {
        $this->roleId = $newRoleId;
    }

    protected function setPasswordHash($newPasswordHash)
    {
        $this->password_hash = $newPasswordHash;
    }

    protected function setAuthToken($newAuthToken)
    {
        $this->authToken = $newAuthToken;
    }

    protected function setBlocking($newBlocking)
    {
        $this->blocking = $newBlocking;
    }

    protected function setDateLogin($newDateLogin)
    {
        $this->dateLogin = $newDateLogin;
    }
    
    protected function setSalt($newSalt)
    {
        $this->salt = $newSalt;
    }

    public static function login(array $loginData)
    {
        if (empty($loginData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }
        if (empty($loginData['login'])) {
            throw new InvalidArgumentException('Не передан login');
        }
    
        $loginUser = User::findOneByColumn('login', $loginData['login']);

        if ($loginUser === null) {
            throw new InvalidArgumentException('Нет пользователя с таким login');
        }

        if ($loginUser->isBlocked()) {
            throw new InvalidArgumentException('Пользователь заблокирован. Обратитесь к администратору');
        }
    
        if (!password_verify($loginData['password'].$loginUser->getSalt(), $loginUser->getPasswordHash())) {
            throw new InvalidArgumentException('Неправильный пароль');
        }
    
        $loginUser->refreshAuthToken();
        $loginUser->refreshDateLogin();
        $loginUser->save();

        return $loginUser;      
    }

    private function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }
    
    private function refreshDateLogin()
    {
        $this->dateLogin = date('Y-m-d');
    }

    public function updateFromArray(array $fields)
    {
        $testUni = User::uniquenessColumnTest($fields,'login');


        if (empty($fields['login'])) {
            throw new InvalidArgumentException("Не указано поле: Логин");
        }
        if (empty($fields['name'])) {
            throw new InvalidArgumentException("Не указано поле: ФИО");
        }
        if (empty($fields['email'])) {
            throw new InvalidArgumentException("Не указано поле: E-mail");
        }
        if (empty($fields['phone'])) {
            throw new InvalidArgumentException("Не указано поле: Телефон");
        }
        if ($fields['roleId']=='null') {
            throw new InvalidArgumentException("Не указано поле: Группа");
        }
        if (!$testUni) {
            throw new InvalidArgumentException("Значение поля 'Логин:' не уникально");
        }
        if(isset($fields['id']) && (int)$fields['id']/1 == 1){
            $this->setRoleId(1);
        } else {
            $this->setRoleId((int)$fields['roleId']);
            $this->refreshAuthToken();
        }
        $this->setLogin($fields['login']);
        $this->setName($fields['name']);
        $this->setEmail($fields['email']);
        $this->setPhone($fields['phone']);
        $this->setPassword($fields['login']);    
        if (!isset($fields['id'])) {
            $this->setSalt(bin2hex(random_bytes(5)));    
        } else {
            
        }
        $this->save();
        return $this;
    }

    private function setPassword($txtPassword)
    {
        $this->setPasswordHash(password_hash($txtPassword.$this->getSalt(), PASSWORD_DEFAULT));
    }

    public function changePassword($fields)
    {
        if (empty($fields['password'])) {
            throw new InvalidArgumentException("Не заполнено поле: Пароль");
        } else {
            $this->setPassword($fields['password']);
            $this->save();
        }
    }

    public function unBlock()
    {
        $this->blocking = null;
        $this->save();
        return $this;
    }

    public function block()
    {
        $this->blocking = 'checked';
        $this->save();
        return $this;
    }
    
}