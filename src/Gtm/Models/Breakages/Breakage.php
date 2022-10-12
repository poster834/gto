<?php
namespace Gtm\Models\Breakages;

use Gtm\Models\ActiveRecordEntity;

class Breakage extends ActiveRecordEntity
{
    protected static function getTableName()
    {
        return 'breakages';
    }

    protected static function getCountPerPage()
    {
        return 15;
    }
}