<?php
namespace Gtm\Models\Companys;

use Gtm\Models\ActiveRecordEntity;


class Company extends ActiveRecordEntity
{
        /** @var string*/
        protected $name;
        
        /** @var string*/
        protected $logo;
    
        /** @var string*/
        protected $phone;
    
        /** @var string*/
        protected $email;

        /** @var string*/
        protected $rootGuid;

        /** @var string*/
        protected $dateUpdate;

        /** @var string*/
        protected $agLogin;

        /** @var string*/
        protected $agServer;
        
        /** @var string*/
        protected $agToken;


    protected static function getTableName()
    {
        return 'company';
    }
    protected static function getCountPerPage()
    {
        return 1;
    }

    public function getAgToken()
    {
        return $this->agToken;
    }
    public function setAgToken($val)
    {
        $this->agToken = $val;
    }

    public function getAgServer()
    {
        return $this->agServer;
    }
    public function setAgServer($val)
    {
        $this->agServer = $val;
    }

    public function getAgLogin()
    {
        return $this->agLogin;
    }
    public function setAgLogin($val)
    {
        $this->agLogin = $val;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRootGuid()
    {
        return $this->rootGuid;
    }

    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate($newDateUpdate)
    {
        $this->dateUpdate = $newDateUpdate;
    }

    public function setPhone($newPhone)
    {
        $this->phone = $newPhone;
    }

    public function setEmail($newEmail)
    {
        $this->email = $newEmail;
    }

    public function setLogo($newLogo)
    {
        $this->logo = $newLogo;
    }

    public function setRootGuid($newGuid)
    {
        $this->rootGuid = $newGuid;
    }

    public function updateFromArray(array $fields)
    {
        $this->setName($fields['name']);
        $this->setEmail($fields['email']);
        $this->setPhone($fields['phone']);
        $this->setRootGuid($fields['rootGuid']);
        $this->save();
        return $this;
    }
}