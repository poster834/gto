<?php
namespace Gtm\Models\Users;

use \Gtm\Models\ActiveRecordEntity;
use \Gtm\Exceptions\InvalidArgumentException;

class User extends ActiveRecordEntity
{

    /** @var string*/
    protected $login;

    /** @var string*/
    protected $passwordHash;
    
    /** @var string*/
    protected $name;

    /** @var int*/
    protected $active;

    /** @var string*/
    protected $email;

    /** @var string*/
    protected $dateLogin;

    /** @var string*/
    protected $phoneNumber;

    /** @var string*/
    protected $authToken;

    /** @var int*/
    protected $role;

    protected static function getTableName(): string
    {
        return 'users';
    }

    public function getPasswordHash():string
    {
        return $this->passwordHash;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isAdmin()
    {
        if ($this->role == 1) {
            return true;
        }
        return false;
    }

    public function getRoleId()
    {
        return $this->role;
    }

    public static function login(array $loginData)
    {
        if (empty($loginData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }
        if (empty($loginData['login'])) {
            throw new InvalidArgumentException('Не передан login');
        }
    
        $user = User::findOneByColumn('login', $loginData['login']);

        if ($user === null) {
            throw new InvalidArgumentException('Нет пользователя с таким login');
        }
    
        if (!password_verify($loginData['password'], $user->getPasswordHash())) {
            throw new InvalidArgumentException('Неправильный пароль');
        }
    
        $user->refreshAuthToken();
        $user->save();

        return $user;      
    }

    private function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }
    
    public function getAuthToken()
    {
        return $this->authToken;
    }

}