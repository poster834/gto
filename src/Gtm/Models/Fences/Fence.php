<?php
namespace Gtm\Models\Fences;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;
use Gtm\Models\Companys\Company;
use Gtm\Models\Coords\Coord;

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
    
        /** @var array*/
        protected $xCoords;

        /** @var array*/
        protected $yCoords;


    public static function getTableName()
    {
        return 'geoFences';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    public function setName($val)
    {
        $this->name = $val;
    }

    public function setGuid($val)
    {
        $this->guid = $val;
    }

    public function setUid($val)
    {
        $this->uid = $val;
    }

    public function setPerimeter($val)
    {
        $this->perimeter = $val;
    }

    public function setSquare($val)
    {
        $this->square = $val;
    }

    public function setXCoords($arr)
    {
        $this->xCoords = $arr;
    }

    public function setYCoords($arr)
    {
        $this->yCoords = $arr;
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

    public function getXCoords()
    {
        return Coord::getXCoords($this->uid);
    }

    public function getYCoords()
    {
        return Coord::getYCoords($this->uid);
    }
}