<?php
namespace Gtm\Controllers;

use Gtm\Models\Regions\Region;
use Gtm\Models\Directions\Direction;
use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Models\Failures\Failure;
use Gtm\Models\MachinesFixed\MachineFixed;

// ini_set('display_errors',1);
// error_reporting(E_ALL);
class RegionsController extends AbstractController
{
    public function editRow(int $id)
    {
        $editRegion = Region::getById($id);
        $directions = Direction::findAll();
        $this->view->renderHtml('admin/blocks/editRegion.php',[
            'region'=>$editRegion,
            'directions'=>$directions,
        ]);  
    }

    public function edit(int $id)
    {
                $editRegion = Region::getById($id);
                if ($editRegion === null) {
                throw new NotFoundException();
                }
                if (!empty($_POST)) {
                    try {
                        $editRegion->updateFromArray($_POST);
                    } catch (InvalidArgumentException $e) {
                        $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                        exit();
                    }          
                    $pageNumber = Region::getActivePageById($id);
                    echo $pageNumber;
                }
    }

    public function delete(int $id)
    {
                $test = MachineFixed::findOneByColumn('region_id',$id);
                $deleteRegion = Region::getById($id);
                if ($test !== null) {
                    $this->view->renderHtml('errors/relationError.php', ['table'=>'Привязка машин к районам', 'data'=>"id => ".$test->getId()]);
                    exit();
                } else {
                    $deleteRegion->delete();
                    $pageNumber = Region::getActivePageById($id-1);
                    echo $pageNumber;
                }
    }

    public function saveRegion()
    {
                $region = new Region();   
                try {
                    $region->updateFromArray($_POST);
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                    exit();
                }          
                $pageNumber = (int) Region::getActivePageById($region->getId());
                echo $pageNumber;
    }

    public function showAdd()
    {
        $directions = Direction::findAll();
        $this->view->renderHtml('admin/blocks/addRegion.php', ['directions'=>$directions]);
    }


    public function showRegionInfo($id,$page)
    {
        $region = Region::getById($id);
        $activeFailures = Failure::findActiveByRegionId($id, $page);
        $servicedFailures = Failure::findServisedByRegionId($id);
        $pages = Failure::getActivePaginatorPagesByColumn('region_id', $id);
        $this->view->renderHtml('main/blocks/showRegionInfo.php',[
            'region'=>$region,
            'activeFailures'=>$activeFailures,
            'servicedFailures'=>$servicedFailures,
            'pages'=>$pages,
            'activePage'=>$page

        ]);  
    }
}