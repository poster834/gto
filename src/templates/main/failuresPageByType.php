<h3>Типы поломок</h3>

    <div class="row">
        <div class="col-lg-3">
            <ul class="subMenu">
            <?php foreach($failureTypes as $failureType):?>
                <?php $count = "";?>
                <?php foreach($activeFailuresAllTypes as $afat):?>
                <?php if ($afat['failuresTypeId'] == $failureType->getId()) {
                    $count = "<b> (".$afat['count'].")</b>";
                }?>
                    <?php endforeach;?>
                <li id="failuresType<?=$failureType->getId()?>" onclick="selectFailuresType(<?=$failureType->getId()?>,1)"><?=$failureType->getName().$count;?></li>
            <?php endforeach;?>
            </ul>
        
        </div>
        <div class="col-lg-9">
            <span id="rightCol">
            <pre>
        <?php var_dump($activeFailuresAllTypes);?>
       </pre>
            </span>
       
            
        </div>
    </div>

