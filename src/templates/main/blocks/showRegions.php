<div class="row">
    <div class="col-lg-12">
        <?php $senderBtn = "";$senderBtn = 'Нарушений и поломок нет';?>
        
        <?php foreach ($regions as $region):?>
            <?php
            $btnType = "secondary";         
            if ($activeFailureByRegions[$region->getId()] > 0) {
                $btnType = "danger";
                $senderBtn = '<button type="button" id="sendDirection'.$region->getDirectionId().'" class="btn btn-primary btn-sm" onclick="sendFailuresAlerts('.$region->getdirectionId().')">Отправить</button> оповещение сервисной службе и механикам по направлению: <b><i>'.$region->getDirectionName().'</i></b>';
            }
            ?>
            <button type="button" id="regionBtn<?=$region->getId();?>" class="btn btn-<?=$btnType?> btn-sm regionBtn" onclick="showRegionInfo(<?=$region->getId()?>,<?=$region->getDirectionId()?>,1)"><?=$region->getName()?></button>
        <?php endforeach;?>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-lg-12">
        <span id="regionInfo">
            <?=$senderBtn;?>
            <hr>
            <span id="sendResult">
                
            </span>
        </span>
        
    </div>
</div>
