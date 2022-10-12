<?php
namespace Gtm\Controllers;

use Gtm\Models\Roles\Role;
use Gtm\Models\Regions\Region;
use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Exceptions\NotAllowException;
use Gtm\Models\Directions\Direction;

ini_set('display_errors',1);
error_reporting(E_ALL);
class DirectionsController extends AbstractController
{
    public function editRow(int $id)
    {
        $editDirection = Direction::getById($id);
        $this->view->renderHtml('admin/blocks/editDirection.php',[
            'direction'=>$editDirection,
        ]);  
    }

    public function edit(int $id)
    {
                $direction = Direction::getById($id);
                if ($direction === null) {
                throw new NotFoundException();
                }
                if (!empty($_POST)) {
                    try {
                        $direction->updateFromArray($_POST);
                    } catch (InvalidArgumentException $e) {
                        $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                        exit();
                    }          
                    $pageNumber = Direction::getActivePageById($id);
                    echo $pageNumber;
                }
    }

    public function delete(int $id)
    {
                $regionTest = Region::findOneByColumn('direction',$id);
                $direction = Direction::getById($id);
                if ($regionTest !== null) {
                    $this->view->renderHtml('errors/relationError.php', ['table'=>'Районы', 'data'=>"id => ".$regionTest->getId()]);
                    exit();
                } else {
                    $direction->delete();
                    $pageNumber = Direction::getActivePageById($id-1);
                    echo $pageNumber;
                }
    }

    public function saveDirection()
    {
                $direction = new Direction();   
                try {
                    $direction->updateFromArray($_POST);
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                    exit();
                }          
                $pageNumber = (int) Direction::getActivePageById($direction->getId());
                echo $pageNumber;
    }

    public function showAdd()
    {
        $this->view->renderHtml('admin/blocks/addDirection.php', []);
    }

}