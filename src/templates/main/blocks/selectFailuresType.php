<?php
    use Gtm\Models\Users\User;
    use Gtm\Models\Machines\Machine;
    use Gtm\Models\FailuresTypes\FailuresType;
    $type=0;
?>

<?php if(count($activeFailures)>0):?>
        <table class="regionFailuresTable">
        <tr>
            <td>ID</td>
            <td>Машина / ГЛОНАСС</td>
            <td>Тип поломки</td>
            <td>Выявлено</td>
            <td>Оповещение</td>
            <td>Ответственный механик</td>
        </tr>

        <?php foreach($activeFailures as $aF):?>
            <?php 
            $mechanic = User::getById($aF->getMechanicId());
            $mail = $dateAlert = '';
            if($aF->getAlertDate() <> null){
                $mail = '<i style="color:red;" class="fa fa-envelope-o" aria-hidden="true"></i>';
                $dateAlert = date("d.m.Y", strtotime($aF->getAlertDate()));
            }
            ?>
            <tr>
                <td><?=$aF->getId()?></td>
                <td><?=Machine::findOneByColumn('uid', $aF->getUid())->getName();?><br><b><?=$aF->getSerial();?></b></td>
                <td><?=FailuresType::getById($aF->getTypeId())->getName();?></td>
                <td><?=date("d.m.Y", strtotime($aF->getDateCreate()))?><br><?=User::getById($aF->getUserId())->getName();?><br><?=$aF->getDescription();?></td>
                <td><?=$mail;?><hr><?=$dateAlert;?></td>
                <td><?=$mechanic->getName();?><br><?=$mechanic->getPhone().'<br>'.$mechanic->getEmail()?></td>
            </tr>
            <?php $type = $aF->getTypeId();?>
        <?php endforeach;?>
        </table>
       
        <div id="pagesPaginatorRow">
            <hr>
            Страницы:  
            <?php
            for($i = 1; $i <= $pages; $i++):?>
            <?php 
            $addClass ="";
                if($i == $activePage){
                    $addClass = 'pagePaginator-active';
                }
            ?>
                 <span id="pagePaginator<?=$i;?>" class="pagePaginator <?=$addClass;?>" onclick="selectFailuresType(<?=$type?>,<?=$i?>)"><?=$i;?></span>
            <?php endfor;?>
            <hr>
        </div>
        
        <?php else:?>
            <table class="noActualTask">
                <tr>
                    <td class="greenBC">
                        Поломок данного типа в системе нет
                    </td>
                </tr>
            </table>
        <?php endif;?>