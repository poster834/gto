<?php 
use Gtm\Models\Users\UsersAuthService;
$allow = false;
    $user = UsersAuthService::getUserByToken();
    if ($user->getRoleId() == 1 || $user->getRoleId() == 3) {
        $allow = true;
    }
    
// ini_set('display_errors',1);
// error_reporting(E_ALL);
$styleCar ='';
$reasonBtnVisible = "noVisible";
$reasons = [
    'temporary_driver'=>'<i class="fa fa-clock-o" aria-hidden="true"></i> Временное отсутствие водителя',
    'conservation'=>'<i class="fa fa-snowflake-o" aria-hidden="true"></i> Консервация',
    'repair'=>'<i class="fa fa-wrench" aria-hidden="true"></i> Ремонт',
    'reserve'=>'<i class="fa fa-retweet" aria-hidden="true"></i> Резерв',
];
$reasonsBtn = [];
if($parking <> null)
{
    $styleCar = 'background-color:#fdffdf;';
    $reasonBtnVisible = "visible";
    foreach($reasons as $reason => $value){
        if ($reason == $parking->getParkingReason()) {
            $reasonsBtn[] = '<input type="radio" class="btn-check" name="reason" checked id="'.$reason.'" autocomplete="off">
            <label class="btn btn-outline-danger btn-sm" for="'.$reason.'" onclick="setParkingReasonBtn(\''.$machine['uid'].'\', \''.$reason.'\')">'.$value.'</label>';
        } else {
            $reasonsBtn[] = '<input type="radio" class="btn-check" name="reason" id="'.$reason.'" autocomplete="off">
            <label class="btn btn-outline-danger btn-sm" for="'.$reason.'" onclick="setParkingReasonBtn(\''.$machine['uid'].'\', \''.$reason.'\')">'.$value.'</label>';
        }
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12" style="<?=$styleCar;?>">
        <h5>
            <?=$machine['name'];?>
        </h5>
        
        <?php if($parking == null):?>
        <span id="" class="btn btn-warning btn-sm" onclick="inParking('<?=$machine['uid']?>')"><i class="fa fa-pause" aria-hidden="true"></i> в гараж</span>
        <?php $comment="";?>
        <?php else:?>
            <span id="" class="btn btn-success btn-sm" onclick="outParking('<?= $machine['uid']?>')"><i class="fa fa-cog fa-spin" aria-hidden="true"></i> <i class="fa fa-play" aria-hidden="true"></i> в работу</span>
             <?php $comment="<textarea class='parkingComment' name=\"comment".$machine['uid']."\" id=\"comment".$machine['uid']."\" cols='30' rows='4'>".$parking->getComment()."</textarea>";?>
        <?php endif;?>
        <span class="btn btn-info btn-sm" id="btn<?=$machine['uid']?>" onclick="showProperties('<?=$machine['uid']?>')"><i class="fa fa-address-card" aria-hidden="true"></i> Показать свойства</span>
        <span class="btn btn-danger btn-sm" id="track_anal_btn<?=$machine['uid']?>" onclick="showTrackAnalysis('<?=$machine['uid']?>')"><i class="fa fa-bar-chart" aria-hidden="true"></i> Анализ треков</span>
        <div class="<?=$reasonBtnVisible?>" id="parkingReasonBtn<?=$machine['uid']?>">
        <hr>
            
        <?php foreach($reasonsBtn as $btn)
        {
            echo $btn;
        }
        ?>

       <span class="commentParking"><?=$comment;?></span>
        <span class="btn btn-success btn-sm saveCommentBtn" onclick="setParkingComment('<?=$machine['uid']?>')"><i class="fa fa-floppy-o" aria-hidden="true"></i> Cохранить</span>
        <!-- ENUM 'temporary_driver','conservation','repair','reserve' -->
        <!-- <input type="radio" class="btn-check" name="reason" id="temporary_driver" autocomplete="off">
        <label class="btn btn-outline-danger btn-sm" for="temporary_driver" onclick="setParkingReasonBtn('<?=$machine['uid']?>', 'temporary_driver')">Временное отсутствие водителя</label>

        <input type="radio" class="btn-check" name="reason" id="conservation" autocomplete="off">
        <label class="btn btn-outline-danger btn-sm" for="conservation">Консервация</label>
        
        <input type="radio" class="btn-check" name="reason" id="repair" autocomplete="off">
        <label class="btn btn-outline-danger btn-sm" for="repair">Ремонт</label>

        <input type="radio" class="btn-check" name="reason" id="reserve" autocomplete="off">
        <label class="btn btn-outline-danger btn-sm" for="reserve">Резерв</label> -->

        <br><br>
        </div>
        <hr>
        <div class="hideProp" id="properties<?=$machine['uid']?>">
         <h6>Основные свойства:</h6>
        <table>
            <?php foreach($machine['properties'] as $id => $prop):?>
                <?php if($prop['is_basic'] == '1'):?>
                <tr id="<?=$id?>">
                    <td><?=$prop['name']?></td>
                    <td id="<?=$id.$machine['uid']?>"><?=$prop['value']?></td>
                    <td><?=$prop['sort']?></td>
                </tr>
                <?php endif;?>
                <?php endforeach;?>
        </table>
        <hr>
        <h6>Дополнительные свойства:</h6>
        <table>
            <?php foreach($machine['properties'] as $prop):?>
                <?php if($prop['is_basic'] == '0'):?>
                <tr>
                    <td><?=$prop['name']?></td>
                    <td><?=$prop['value']?></td>
                    <td><?=$prop['sort']?></td>
                </tr>
                <?php endif;?>
                <?php endforeach;?>
        </table>
        <hr>
                    <!-- 'mechanics'=>$mechanics,
                    'fixedMechanicId'=>$fixedMechanicId,
                    'fixedRegionId'=>$fixedRegionId, -->
        
        </div>
       
    <div class="noVisible trackAnalysis" id="trackAnalysis<?=$machine['uid']?>">
      <h6>Анализ трека машины:</h6>
    <div class="row">
        <div class="col-lg-4">
            <table>
                <tr>
                    <td>С: </td>
                    <td><input type="date" name="dateBegin" id="dateBegin<?=$machine['uid']?>" value="<?=date('Y-m-d')?>"></td>
                    <td><input type="time" name="timeBegin" id="timeBegin<?=$machine['uid']?>" value="00:00"></td>
                </tr>
                <tr>
                    <td>По: </td>
                    <td><input type="date" name="dateEnd" id="dateEnd<?=$machine['uid']?>" value="<?=date('Y-m-d')?>"></td>
                    <td><input type="time" name="timeEnd" id="timeEnd<?=$machine['uid']?>" value="23:59"></td>
                </tr>
            </table> 
            
<!-- #################################### -->
            <?php if ($granariesFences <> ""):?>
            <label for="plants_uid">Площадка загрузки кормовозов: </label>
                    <select class="form-select form-select-sm" id="forage_uid">
                    <?php
                    $option = "";
                    $selected = "";
                    if ($forageUid == "") {
                        $option = '<option id="forage_uid0" selected disabled>Выберете Площадку загрузки комбикорма"</option>';
                    }
                    ?>
                        <?=$option;?>
                        <?php foreach($granariesFences as $group):?>
                            <?php if($group->getUid() == $forageUid){
                                $selected = "selected";
                            }
                            ?>
                            <option id="forage_uid_<?=$group->getUid()?>" value="v_<?=$group->getUid()?>" <?=$selected?>><?=$group->getName();?></option>
                            <?php $selected = "";?>
                            <?php endforeach;?>
                    </select>
                    <br>
                    <span class="btn btn-danger btn-sm" id="track_anal_btn<?=$machine['uid']?>" onclick="getStops('<?=$machine['uid']?>')"><i class="fa fa-neuter" aria-hidden="true"></i> Найти остановки с грузом</span>
                    <?php endif;?>
        
<!-- ##################################### -->
        
        </div>
        <div class="col-lg-8">
            <span id="resultGetStop">
                            
            </span>
        </div>

    </div>
<hr>
    </div>

        <h6>Привязка:</h6>
        <table>
            <tr>
                <td>Механик: </td>
                <td>
                    <?php
                    if ($fixedMechanicId == 0) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    ?>
                    <select name="fixedMechanic" id="fixedMechanic<?=$machine['uid']?>" onchange="changeMechanic('<?=$machine['uid']?>')">
                        <option value="0" disabled <?=$selected;?>>Выберете ответственного механика</option>
                        <?php foreach($mechanics as $mechanic):?>
                            <?php 
                            $selected = '';
                            if($mechanic->getId() == $fixedMechanicId){
                                $selected = 'selected';
                            } 
                        ?>
                        <option value="<?=$mechanic->getId()?>" <?=$selected;?>><?=$mechanic->getName();?> </option>
                        <?php endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Район: </td>
                <td>
                    <?php
                    if ($fixedRegionId == 0) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    ?>
                    <select name="fixedRegion" id="fixedRegion<?=$machine['uid']?>" onchange="changeRegion('<?=$machine['uid']?>')">
                        <option value="0" disabled <?=$selected;?>>Выберете основной район работы техники</option>
                        <?php foreach($regions as $region):?>
                            <?php 
                            $selected = '';
                            if($region->getId() == $fixedRegionId){
                                $selected = 'selected';
                            } 
                        ?>
                        <option value="<?=$region->getId()?>" <?=$selected;?>><?=$region->getName();?> (<?=$region->getDirectionName();?>) </option>
                        <?php endforeach;?>
                    </select>
                </td>
            </tr>
        </table>
        <hr>


        <h6>Текущее состояние:</h6>
        <table class="actualTask">
        <?php
        if (count($machine['activeFailures']) > 0): ?>
            <?php foreach($machine['activeFailures'] as $activeFailures):?>
                <?php $id = $activeFailures['id'];?>
                <tr class='topTableTask'>
                    <td><b>#<?=$id;?>: <?=$activeFailures['typeName'];?></b></td>
                    <td colspan="3"><span class="resultTask" id='resultTask<?=$id;?>' style="color:red;"></span></td>
                </tr>
                <tr>  
                <td class='redBC'><?=date('d.m.Y',strtotime($activeFailures['date_create']));?> / <?=$activeFailures['user_add_name'];?></td>        

                <td class='redBC'>
                <span id='btnDetailedShow<?=$id;?>'>
                    <span class="btn btn-info btn-sm m2px" onclick="showSolvBlock(<?=$id;?>)"><i class="fa fa-address-card" aria-hidden="true"></i> Подробнее</span>
                </span>
                <span class="btnDetailedHide" id="btnDetailedHide<?=$id;?>">
                    <span class="btn btn-secondary btn-sm m2px" onclick="hideSolvBlock(<?=$id;?>,'<?=$machine['uid'];?>')"><i class="fa fa-window-close-o" aria-hidden="true"></i> Скрыть</span>
                </span>
                
                    <span class="failureBtnBlock" id="failureBtnBlock<?=$id;?>">
                        <?php if($allow):?>
                            <span class="btn btn-success btn-sm m2px" onclick="saveTask(<?=$id?>,'<?=$machine['uid'];?>')"><i class="fa fa-check" aria-hidden="true"></i> Выполнено</span>
                            <span class="btn btn-danger btn-sm m2px" data-bs-toggle="modal" data-bs-target="#confirmDelete<?=$id?>"><i class="fa fa-trash-o" aria-hidden="true"></i> Удалить</span> 
                        <?php endif;?>
                    </span>
                </td>
                <td class='redBC'>
                    <span style="vertical-align: text-bottom;">
                    <form class="uploadPhotoForm" name="uploadPhotoForm<?=$id;?>" id="uploadPhotoForm<?=$id;?>" method="post" enctype="multipart/form-data">
                        <label class="addPhoto btn btn-outline-info btn-sm" for="photo<?=$id;?>"><i class="fa fa-plus" aria-hidden="true"></i> фото</label>
                        <input style="visibility:hidden;" type="file" name="photo<?=$id;?>" id="photo<?=$id;?>" accept=".jpg,.jpeg,.png" onchange="onSelectFailuresPhoto(<?=$id?>,'<?=$machine['uid'];?>')">
                    </form>
                    </span>
                </td>
                </tr>
                
                <tr class='solvTask' id='solvTask<?=$id;?>'>
                    <td class='greenBC'>
                    <b>Описание:</b> <br>
                    <textarea name="description<?=$id;?>" id="description<?=$id;?>" cols="30" rows="5"><?=$activeFailures['description'];?></textarea><hr>
                    <span class="btn btn-success btn-sm m2px" onclick="saveDescription(<?=$id?>)"><i class="fa fa-floppy-o" aria-hidden="true"></i> Cохранить</span>
                    </td>
                    <td  class='greenBC'>
                    <select name="solvUser<?=$id;?>" id="solvUser<?=$id;?>" onchange="onChangeSolvUser(<?=$id;?>)">
                        <option value="0" disabled selected>Кто устранил неисправность:</option>
                        <?php foreach($solvUsers as $user):?>
                            <?php var_dump($user);?>
                            <option value="<?=$user->getId();?>"><?=$user->getName()?></option>
                        <?php endforeach;?>
                    </select> 
                        <hr>
                        <b> Комментарий сервисной службы:</b> <br>
                    <textarea name="comment<?=$id;?>" id="comment<?=$id;?>" cols="30" rows="5"></textarea>
                    </td>
                    <td class='greenBC'>
                        <span id='photoFiles<?=$id;?>'></span>
                    </td>
                    
                </tr>
       
                <?php endforeach;?>
                <?php else:?>
                    <table class="noActualTask">
                        <tr>
                            <td class='greenBC'>
                                Оборудование ГЛОНАСС в рабочем состоянии
                            </td>
                        </tr>
                    </table>
        <?php endif;?>
        </table>
        <hr>
        <?php if($allow):?>
        <span id="selectFailureBtn" class="btn btn-primary btn-sm" onclick="addFailures('<?=$machine['uid']?>')"><i class="fa fa-plus" aria-hidden="true"></i> Добавить неисправность</span>
        <?php endif;?>
  
        <div id="addFailures">

        </div>
        <hr>        
        <?php if(count($machine['doneFailures'])>0):?>
        <span class="btn btn-warning btn-sm" id="btnTxt<?=$machine['uid']?>" onclick="showArchiveFailures('<?=$machine['uid']?>',<?=count($machine['doneFailures'])?>,1)"><i class="fa fa-caret-square-o-down" aria-hidden="true"></i> Показать архив поломок (<?=count($machine['doneFailures'])?>)</span>
        <?php endif;?>
        <span id="archiveFailures<?=$machine['uid']?>" class="noVisible archiveFailures">

        </span>
       

        </div>

    </div>
</div>

<?php foreach($machine['activeFailures'] as $activeFailures):?>
                <?php $id = $activeFailures['id'];?>

    <div class="modal fade" id="confirmDelete<?=$id?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Подтвердите удаление</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body">
            Удалить <b>Неисправность #<?=$id?></b> без возможности восстановления?
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" onclick="deleteTask(<?=$id?>,'<?=$machine['uid'];?>')">Удалить</button>
        </div>
        </div>
    </div>
    </div>
<?php endforeach;?>