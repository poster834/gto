<?php
namespace Gtm\Models\Fences;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;
use Gtm\Models\Companys\Company;

class Fence extends ActiveRecordEntity
{
        /** @var string*/
        protected $name;

        /** @var string*/
        protected $uid;
        
        /** @var string*/
        protected $guid;

        /** @var float*/
        protected $perimeter;
        
        /** @var float*/
        protected $square;

    public static function getTableName()
    {
        return 'geoFences';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function setGuid($newGuid)
    {
        $this->guid = $newGuid;
    }

    public function setUid($newUid)
    {
        $this->uid = $newUid;
    }

    public function setPerimeter($newPerimeter)
    {
        $this->perimeter = $newPerimeter;
    }

    public function setSquare($newSquare)
    {
        $this->square = $newSquare;
    }
    

    public function getName()
    {
        return $this->name;
    }
    public function getGuid()
    {
        return $this->guid;
    }
    public function getUid()
    {
        return $this->uid;
    }
    public function getPerimeter()
    {
        return $this->perimeter;
    }
    public function getSquare()
    {
        return $this->square;
    }


    public static function saveToBase($allArray)
    {
        $saveArr = [];
        foreach($allArray as $fenceArr)
        {
            $saveArr[$fenceArr['ID']]['name'] = isset($fenceArr['Name'])?$fenceArr['Name']:'';
            $saveArr[$fenceArr['ID']]['guid'] = isset($fenceArr['ParentID'])?$fenceArr['ParentID']:'';
            $saveArr[$fenceArr['ID']]['uid'] = isset($fenceArr['ID'])?$fenceArr['ID']:'';
            
            $saveArr[$fenceArr['ID']]['perimeter'] = 0;
            $saveArr[$fenceArr['ID']]['square'] = 0;

            foreach($fenceArr['Properties'] as $properties){
                if ($properties['Name'] == 'Perimeter') {
                    $saveArr[$fenceArr['ID']]['perimeter'] = $properties['Value']==0?0.0001:$properties['Value'];        
                }
                if ($properties['Name'] == 'Square') {
                    $saveArr[$fenceArr['ID']]['square'] = $properties['Value']==0?0.0001:$properties['Value'];
                }
            }
        }

        foreach($saveArr as $fenceI)
        {
            $fence = new Fence();
            $fence->setName($fenceI['name']);
            $fence->setGuid($fenceI['guid']);
            $fence->setUid($fenceI['uid']);
            $fence->setPerimeter($fenceI['perimeter']);
            $fence->setSquare($fenceI['square']);
            $fence->save();
        }
    }
}