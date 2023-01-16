<?php
use Gtm\Models\HiredMachines\HiredMachine;
?>
<div class="row">
        <div class="col-6">
        <h3>Аккаунты Wialon</h3>

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
                
                <?php foreach ($accounts as $account):?>
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
                                <input type="hidden" name="WToken" id="WToken<?=$account->getId()?>" value="<?=$account->getAccessToken()?>">
                            <?php endif;?>
                            </td>
                            <td>
                                <?php if(($user->isAdmin()) || ($d < 1) || strlen($account->getAccessToken()) < 1):?>
                                <span class="btn btn-info btn-sm" onclick="upDateToken(<?=$account->getId()?>,<?=$pageNumber?>)">Получить новый ТОКЕН</span>    
                                <?php else:?>
                                    Токен получен <?=date('d.m.Y', strtotime ($account->getUpDate()));?>
                                <?php endif;?>   
                            </td>        
                            <td>
                                <?="Количество машин: ". count(HiredMachine::findAllByColumn('account_id', $account->getId()));?>
                            </td>                  
                            <td>
                                <button type="button" class="btn btn-success btn-sm" id="upDateMachine<?=$account->getId()?>" onclick="updateMachineList(<?=$account->getId()?>)">Обновить список машин</button>
                            </td>
                                 

                            
                        </tr>
                <?php endforeach;?>
            </table>
            <hr>
            <div class="pagesPaginator"> Страницы:  
            <?php
            for($i = 1; $i <= $pages; $i++):?>
                 <span id="pagePaginator<?=$i;?>" class="pagePaginator" onclick="showBlock('wialonAccounts',<?=$i;?>)"><?=$i;?></span>
            <?php endfor;?>
            </div>
            <hr>
            <?php if($user->isAdmin()):?>
                <span class='btn btn-secondary' id="showAddRow" onclick="showFormAddRow('addWialonAccount')"><i class="fa fa-plus" aria-hidden="true"> Добавить</i></span>
            <?php endif;?>
        </div>

        <div class="col-6">
            <div id="errors"></div>
            <div id="result"></div>
        </div>
    </div>

</div>

    <script src="../gtm/src/script/scriptAdmin.js"></script>