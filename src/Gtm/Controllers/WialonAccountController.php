<?php
namespace Gtm\Controllers;

use Gtm\Models\Roles\Role;
use Gtm\Models\Regions\Region;
use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Exceptions\NotAllowException;
use Gtm\Models\Directions\Direction;
use Gtm\Models\Failures\Failure;
use Gtm\Models\HiredMachines\HiredMachine;
use Gtm\Models\WialonAccounts\WialonAccount;


// ini_set('display_errors',1);
// error_reporting(E_ALL);
class WialonAccountController extends AbstractController
{
    // public function editRow(int $id)
    // {
    //     $editDirection = Direction::getById($id);
    //     $this->view->renderHtml('admin/blocks/editDirection.php',[
    //         'direction'=>$editDirection,
    //     ]);  
    // }

    // public function edit(int $id)
    // {
    //             $direction = Direction::getById($id);
    //             if ($direction === null) {
    //             throw new NotFoundException();
    //             }
    //             if (!empty($_POST)) {
    //                 try {
    //                     $direction->updateFromArray($_POST);
    //                 } catch (InvalidArgumentException $e) {
    //                     $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
    //                     exit();
    //                 }          
    //                 $pageNumber = Direction::getActivePageById($id);
    //                 echo $pageNumber;
    //             }
    // }

    // public function delete(int $id)
    // {
    //             $regionTest = Region::findOneByColumn('direction',$id);
    //             $direction = Direction::getById($id);
    //             if ($regionTest !== null) {
    //                 $this->view->renderHtml('errors/relationError.php', ['table'=>'Районы', 'data'=>"id => ".$regionTest->getId()]);
    //                 exit();
    //             } else {
    //                 $direction->delete();
    //                 $pageNumber = Direction::getActivePageById($id-1);
    //                 echo $pageNumber;
    //             }
    // }

    // public function saveDirection()
    // {
    //             $direction = new Direction();   
    //             try {
    //                 $direction->updateFromArray($_POST);
    //             } catch (InvalidArgumentException $e) {
    //                 $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
    //                 exit();
    //             }          
    //             $pageNumber = (int) Direction::getActivePageById($direction->getId());
    //             echo $pageNumber;
    // }

    public function showAddForm()
    {
        $this->view->renderHtml('admin/blocks/addWialonAccount.php', [

        ]);
    }

    public function wialonAccounts($pageNumber)
    {
        $pages = WialonAccount::getPagesPaginator();
        $accounts = WialonAccount::findAllPerPage($pageNumber);
        $this->view->renderHtml('admin/blocks/wialonAccount.php',[
            'pages' => $pages,
            'accounts' => $accounts,
            'pageNumber' => $pageNumber,
        ]);
    } 

    public function saveWialonAccount()
    {
            $wa = new WialonAccount();
            try {
                $wa->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('errors/invalidArgument.php', ['error'=>$e->getMessage()]);
                exit();
            }
            $pageNumber = (int) WialonAccount::getActivePageById($wa->getId());
            echo $pageNumber;

    }
  
    public function tokenWialon($id,$token)
    {
        $accounts = WialonAccount::findOverdueTokens();
        $this->view->renderHtml('admin/wialonAccounts.php',[
            'accounts' => $accounts,
        ]);
    } 

    public function saveToken($id,$token)
    {
        $wialonAccount = WialonAccount::getById($id);
        $wialonAccount->setAccessToken($token);
        $wialonAccount->setUpDate(date('Y-m-d'));
        $wialonAccount->save();
    }

    public function updateMachineList($accountId,$token)
    {
        $wialonAccount = WialonAccount::getById($accountId);
        $json = file_get_contents('https://hst-api.wialon.com/wialon/ajax.html?svc=token/login&params={"token":"'.$token.'","fl":1}');
        $jsonArr = json_decode($json, true);
        if (isset($jsonArr['error'])) {
            echo "<span style='background-color:red;color:#fff;padding:5px;'><b>Ошибка ".$jsonArr['error'].": ".$jsonArr['reason'].'</b></span>';
        } else {
            $eid = $jsonArr['eid'];
            $get = 'https://hst-api.wialon.com/wialon/ajax.html?svc=core/search_items&params={"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"0","propType":"0","or_logic":""},"force":1,"flags":1,"from":0,"to":0}&sid='.$eid;
            $cars = file_get_contents($get);
            $carsArr = json_decode($cars, true);
            if (count($carsArr['items'])>0) {
                $upd = $add = 0;
                $renew = "";
                foreach($carsArr['items'] as $machine)
                    {
                        $hiredMachine = HiredMachine::findOneByColumn('serial',$machine['id']);
                        
                        if ($hiredMachine) {
                            $oldName = $hiredMachine->getName();
                            $oldAccountName = $hiredMachine->getAccountName();
                            $oldAccountId = $hiredMachine->getAccountId();
                            
                            if ($oldAccountId*1 <> $accountId*1) {
                            // echo $oldAccountId ."-".$accountId." *** ";
                                $wa = WialonAccount::getById($accountId);
                                $renew .= "Смена владельца техники. <br>Старое имя: <b>".$oldName."(".$oldAccountName.")</b><br>Новое имя: <b>".$machine['nm']." (".$wa->getName().")</b><br>
                                В случае значительных расхождений обратитесь к владельцу техники";    
                            }
                            $hiredMachine->setName($machine['nm']);
                            $hiredMachine->setAccountId($accountId);
                            $hiredMachine->save();
                            $upd ++;
                            $wialonAccount->setCarsCount($upd);
                            $wialonAccount->save();
                        } else {
                            $hiredMachine = new HiredMachine();
                            $hiredMachine->setName($machine['nm']);
                            $hiredMachine->setSerial($machine['id']);
                            $hiredMachine->setAccountId($accountId);
                            $hiredMachine->save();
                            $add ++;
                            $wialonAccount->setCarsCount($add);
                            $wialonAccount->save();
                        }
                    }    
            }
            echo "Найдено машин в сервисе Wialon: ".count($carsArr['items']).".<br>Добавлено в систему мониторинга новых машин: ".$add." ед.<br>"."Обновлено в системе мониторинга машин: ".$upd." ед.<br>".$renew;
        }
    }
    
}