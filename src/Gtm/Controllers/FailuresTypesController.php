<?php
namespace Gtm\Controllers;

use Gtm\Models\Roles\Role;
use Gtm\Models\Regions\Region;
use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Exceptions\NotAllowException;
use Gtm\Models\FailuresTypes\FailuresType;

ini_set('display_errors',1);
error_reporting(E_ALL);
class FailuresTypesController extends AbstractController
{
    public function editRow(int $id)
    {
        $editFailuresType = FailuresType::getById($id);
        $this->view->renderHtml('admin/blocks/editFailuresType.php',[
            'failuresType'=>$editFailuresType,
        ]);  
    }

    public function edit(int $id)
    {
        $failuresType = FailuresType::getById($id);
        if ($failuresType === null) {
        throw new NotFoundException();
        }
        if (!empty($_POST)) {
            try {
                $failuresType->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                exit();
            }          
            $pageNumber = FailuresType::getActivePageById($id);
            echo $pageNumber;
        }
    }

    public function delete(int $id)
    {
        // $failureTest = Failure::findOneByColumn('type_id',$id); //проверить в базе на наличие в других таблицах
        $failureTest = null;
        $failuresType = FailuresType::getById($id);
        if ($failureTest !== null) {
            $this->view->renderHtml('errors/relationError.php', ['table'=>'Поломки', 'data'=>"id => ".$failureTest->getId()]);
            exit();
        } else {
            $failuresType->delete();
            $pageNumber = FailuresType::getActivePageById($id-1);
            echo $pageNumber;
        }
    }

    public function saveFailuresType()
    {
                $failuresType = new FailuresType();   
                try {
                    $failuresType->updateFromArray($_POST);
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                    exit();
                }          
                $pageNumber = (int) FailuresType::getActivePageById($failuresType->getId());
                echo $pageNumber;
    }

    public function showAdd()
    {
        $this->view->renderHtml('admin/blocks/addFailuresType.php', []);
    }

}