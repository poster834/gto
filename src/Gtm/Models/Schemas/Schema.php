<?php
namespace Gtm\Models\Schemas;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;

class Schema extends ActiveRecordEntity
{
    public static function getTableName()
    {
        return 'schema';
    }

    public static function getCountPerPage()
    {
        return 5;
    }
}