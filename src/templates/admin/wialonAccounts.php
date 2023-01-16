<h3>Обновление Токенов Wialon</h3>

<table class='tableBase'>
                <tr class='topTr'>
                    <td>id</td>
                    <td>Наименование</td>
                    <td>Логин</td>
                    <td>Пароль </td>
                    <td>URL адрес</td>
                    <td>Состояние токена </td>
                    <td>Управление Токеном</td>
                    <td>Количество машин в базе данных</td>
                    <td>Управление Списком машин</td>
                </tr>


                <?php

use Gtm\Models\HiredMachines\HiredMachine;
use Gtm\Models\WialonAccounts\WialonAccount;

 foreach ($accounts as $account):?>
                                   <?php 
                $today=date('Y-m-d');
                $d=0;
                $upDate = date('d-m-Y', strtotime ($account->getUpDate())+(100 * 86400));
                $d = ( strtotime ($upDate)- strtotime($today)) / (60*60*24);
                ?> 
                        <tr id="userRow_<?=$account->getId()?>">
                            <td><?=$account->getId();?></td>
                            <td><?=$account->getName();?></td>
                            <td id="WTokenLogin<?=$account->getId()?>"><?=$account->getLogin();?></td>
                            <td><?=$account->getPassword();?></td>
                            <td id="WTokenUrl<?=$account->getId()?>"><?=$account->getUrl();?></td>
                            <td>
                            <?php if((strlen($account->getAccessToken()) < 1)):?>
                                <span style="color:red;">Токен отсутствует</span><br>
                            <?php elseif($d <= 0):?>
                                <span style="color:red;">Токен просрочен</span><br>
                            <?php else:?>

                                <span style="color:green;">Токен будет активен еще <?=$d?> дн.</span><br>
                            <?php endif;?>

                            </td>
                            <td>
                                <span class="btn btn-info btn-sm" onclick="upDateToken(<?=$account->getId()?>)">Получить новый ТОКЕН</span>       
                            </td>                          
                            <td>
                                <?=count(HiredMachine::findAllByColumn('account_id', $account->getId()));?> машин.
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" id="upDateMachine<?=$account->getId()?>" onclick="updateMachineList(<?=$account->getId()?>)">Обновить список машин</button>
                            </td>

                            
                        </tr>
                <?php endforeach;?>
            </table>