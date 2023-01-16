<h5 class="failName"><?=$failuresType->getName();?></h5>
<h6><?=$regionName;?>(<?=$directionName;?>) / <?=$mechanicName;?> (<?=$mechanicPhone;?>, <?=$mechanicEmail;?>)</h6>
<table>
    <tr>
        <td><h6>Подробное описание проблемы</h6></td>
        <td><textarea name="description" id="description<?=$uid;?>" cols="50" rows="5"></textarea></td>
    </tr>
</table>

<hr>
<?php if($mechanicName == "" || $regionName == ""):?>
<span class="warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Для возможности сохранения выберете Механика и Район</span>
    <?php else:?>

<button type="button" class="btn btn-success btn-sm" onclick="saveFailure('<?=$uid;?>', <?=$failuresType->getId()?>, <?=$mechanicId?>, <?=$regionId?>)"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить неисправность</button>
<?php endif;?>
<button type="button" class="btn btn-secondary btn-sm" onclick="showMachine('m_<?=$uid;?>')"><i class="fa fa-window-close-o" aria-hidden="true"></i> Отмена</button>



