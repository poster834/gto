<?php
namespace Gtm\Models\HiredMachines;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Models\WialonAccounts\WialonAccount;

class HiredMachine extends ActiveRecordEntity
{
    /** @var string*/
    protected $name;

    /** @var int*/
    protected $accountId;

    /** @var int*/
    protected $serial;

    public static function getTableName()
    {
        return 'hired_machines';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    public function setAccountId($AccountId)
    {
        $this->accountId = $AccountId;
    }
    public function getAccountId()
    {
        return $this->accountId;
    }

    public function setName($Name)
    {
        $this->name = $Name;
    }
    public function getName()
    {
        return $this->name;
    }
    
    public function getSerial()
    {
        return $this->serial;
    }
    public function setSerial($Serial)
    {
        $this->serial = $Serial;
    }

    public function getAccountName()
    {
        $wa = WialonAccount::findOneByColumn('id', $this->getAccountId());
        return $wa->getName();

    }

}