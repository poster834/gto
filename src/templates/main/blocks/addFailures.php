<div class="container">
</div>
<hr>
<?php foreach($failuresTypes as $fType):?>
    <?php if(!in_array($fType->getId(), $openFailuresType)):?>
    <span><button type="button" class="btn btn-secondary btn-sm failBtn" id="failBtn<?=$fType->getId();?>" onclick="showAddFailureForm('<?=$uid;?>', <?=$fType->getId();?>)"><?=$fType->getName();?></button></span>
    <?php endif;?>
<?php endforeach;?>
<hr>
<div id="addFailuresForm">

</div>
