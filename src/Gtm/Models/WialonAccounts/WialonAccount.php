<?php
namespace Gtm\Models\WialonAccounts;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Services\Db;
use Gtm\Models\FailuresTypes\FailuresType;
use Gtm\Models\Users\User;


// ini_set('display_errors',1);
// error_reporting(E_ALL);

class WialonAccount extends ActiveRecordEntity
{

/** @var string*/
protected $name;

/** @var string*/
protected $login;

/** @var string*/
protected $password;

/** @var string*/
protected $url;

/** @var string*/
protected $accessToken;

/** @var string*/
protected $upDate;

/** @var int*/
protected $carsCount;

    
    protected static function getTableName()
    {
        return 'wialonAccount';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($Name)
    {
        $this->name = $Name;
    }

    public function getLogin()
    {
        return $this->login;
    }
    public function setLogin($Login)
    {
        $this->login = $Login;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($Password)
    {
        $this->password = $Password;
    }

    public function getUrl()
    {
        return $this->url;
    }
    public function setUrl($Url)
    {
        $this->url = $Url;
    }

    public function getCarsCount()
    {
        return $this->carsCount;
    }
    public function setCarsCount($CarsCount)
    {
        $this->carsCount = $CarsCount;
    }
    
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    public function setAccessToken($AccessToken)
    {
        $this->accessToken = $AccessToken;
    }
    
    public function getUpDate()
    {
        return $this->upDate;
    }
    public function setUpDate($UpDate)
    {
        $this->upDate = $UpDate;
    }
    
    
    public function updateFromArray(array $fields)
    {
        $testUniLogin = WialonAccount::uniquenessColumnTest($fields,'login');
        $testUniName = WialonAccount::uniquenessColumnTest($fields,'name');
        if (empty($fields['name'])) {
            throw new InvalidArgumentException("Не указано поле: Наименование");
        }
        if (empty($fields['login'])) {
            throw new InvalidArgumentException("Не указано поле: Логин");
        }

        if (empty($fields['password'])) {
            throw new InvalidArgumentException("Не указано поле: Пароль");
        }
        if (empty($fields['url'])) {
            throw new InvalidArgumentException("Не указано поле: URL адрес");
        }
        if (!$testUniLogin) {
            throw new InvalidArgumentException("Значение поля 'Логин:' не уникально");
        }
        if (!$testUniName) {
            throw new InvalidArgumentException("Значение поля 'Наименование:' не уникально");
        }

        $this->setLogin($fields['login']);
        $this->setName($fields['name']);
        $this->setUrl($fields['url']);
        $this->setPassword($fields['password']);
        $this->save();
        return $this;
    }
    
    public static function findOverdueTokens()
    {
        $db = Db::getInstance();
        // $upDate = date('Y-m-d', time() - 100 * 86400); //100 дней время жизни токена Wialon
        // $result = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE access_token IS NULL || up_date < :upDate ;', [':upDate' => $upDate], static::class);
        $result = $db->query('SELECT * FROM `'.static::getTableName().'`;', [], static::class);
        return $result;
    }

    
}