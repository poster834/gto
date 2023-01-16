<?php
namespace Gtm\Controllers;

use Gtm\Models\Roles\Role;
use Gtm\Models\Regions\Region;
use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Exceptions\NotAllowException;
use Gtm\Models\OffensesTypes\OffensesType;
use Gtm\Models\Offenses\Offense;

// ini_set('display_errors',1);
// error_reporting(E_ALL);
class OffensesTypesController extends AbstractController
{
    public function editRow(int $id)
    {
        $edit = OffensesType::getById($id);
        $this->view->renderHtml('admin/blocks/editOffensesType.php',[
            'offensesType'=>$edit,
        ]);  
    }

    public function edit(int $id)
    {
        $offensesType = OffensesType::getById($id);
        if ($offensesType === null) {
        throw new NotFoundException();
        }
        if (!empty($_POST)) {
            try {
                $offensesType->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                exit();
            }          
            $pageNumber = OffensesType::getActivePageById($id);
            echo $pageNumber;
        }
    }

    public function delete(int $id)
    {
        $test = Offense::findOneByColumn('type_id',$id); //проверить в базе на наличие в других таблицах
        $offensesType = OffensesType::getById($id);
        if ($test !== null) {
            $this->view->renderHtml('errors/relationError.php', ['table'=>'Нарушения', 'data'=>"id => ".$test->getId()]);
            exit();
        } else {
            $offensesType->delete();
            $pageNumber = OffensesType::getActivePageById($id-1);
            echo $pageNumber;
        }
    }

    public function saveOffensesType()
    {
                $offensesType = new OffensesType();   
                try {
                    $offensesType->updateFromArray($_POST);
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                    exit();
                }          
                $pageNumber = (int) OffensesType::getActivePageById($offensesType->getId());
                echo $pageNumber;
    }

    public function showAdd()
    {
        $this->view->renderHtml('admin/blocks/addOffensesType.php', []);
    }

}