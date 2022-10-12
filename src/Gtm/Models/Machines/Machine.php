<?php
namespace Gtm\Models\Machines;

use Gtm\Models\ActiveRecordEntity;

class Machine extends ActiveRecordEntity
{
    public static function getTableName()
    {
        return 'machines';
    }

    public static function getCountPerPage()
    {
        return 5;
    }
}