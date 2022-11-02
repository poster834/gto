<?php
namespace Gtm\Controllers;

use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Models\PropertiesTypes\PropertiesType;


ini_set('display_errors',1);
error_reporting(E_ALL);
class PropertiesTypesController extends AbstractController
{
    public function editRow(int $id)
    {
        $propertiesType = PropertiesType::getById($id);
        $this->view->renderHtml('admin/blocks/editPropertiesType.php',[
            'propertiesType'=>$propertiesType,
        ]);  
    }

    public function edit(int $id)
    {
                $property = PropertiesType::getById($id);
                if ($property === null) {
                throw new NotFoundException();
                }
                if (!empty($_POST)) {
                    try {
                        $property->updateFromArray($_POST);
                    } catch (InvalidArgumentException $e) {
                        $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                        exit();
                    }          
                    $pageNumber = PropertiesType::getActivePageById($id);
                    echo $pageNumber;
                }
    }

    public function delete(int $id)
    {
        $property = PropertiesType::getById($id);
        $property->delete();
        $pageNumber = PropertiesType::getActivePageById($id-1);
        echo $pageNumber;        
    }

    public function savePropertiesTypes()
    {
                $property = new PropertiesType();   
                try {
                    $property->updateFromArray($_POST);
                } catch (InvalidArgumentException $e) {
                    $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                    exit();
                }          
                $pageNumber = (int) PropertiesType::getActivePageById($property->getId());
                echo $pageNumber;
    }

    public function showAdd($id)
    {
        $propertiesType = PropertiesType::getById((int)$id);
        $this->view->renderHtml('admin/blocks/addPropertiesType.php', [
            'propertiesType'=>$propertiesType,
        ]);
    }

    public function setUnuse($id)
    {
        $property = PropertiesType::getById($id);
        $property->setIsUses(0);
        $property->setDescription(null);
        $property->save();
        $pageNumber = PropertiesType::getActivePageById($id-1);
        echo $pageNumber;  
    }

}