<div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <table class="doneFailure">
            <?php foreach ($doneFailures as $failure):?>
                <tr>
                    <td>
                        <?=$failure['id']?>
                    </td>
                    <td>
                        <?=$failure['typeName']?>
                    </td>
                    <td>
                        <?=$failure['user_add_name']?>
                        <?=date('d.m.Y', strtotime($failure['date_create']))?><br>
                        <?=$failure['description']?>
                    </td>
                    <td class="vertical">
                        <?=$failure['user_service_name']?>
                        <?=date('d.m.Y', strtotime($failure['date_service']));?>
                    </td>
                    <td>
                        <?=$failure['service_text']?>
                    </td>
                    <td class="vertical">
                        <?php foreach($failuresPhoto[$failure['id']] as $photo):?>
                            <span id="photoId<?=$photo->getId();?>"><img class="failuresPhoto" src="photo/<?=$photo->getUrl();?>" alt="">
                            <span class='editBlock'>
                                <i class="fa fa-search-plus fa-2x zoom" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?=$photo->getId()?>" aria-hidden="true"></i>
                                </span>
                            </span>    
                        <?php endforeach;?>
                    </td>
                </tr>
                
            <?php endforeach;?>

            </table>

        </div>
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
                 <span id="pagePaginator<?=$i;?>" class="pagePaginator <?=$addClass;?>" onclick="showDoneFailures('<?=$uid;?>',<?=$i?>)"><?=$i;?></span>
            <?php endfor;?>
            <hr>
        </div> 
    </div>

<?php foreach($doneFailures as $doneF):?>
    <?php foreach($failuresPhoto[$doneF['id']] as $photo):?>
        <div class="modal fade" id="staticBackdrop<?=$photo->getId()?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Фотография #<?=$photo->getId()?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
            <img class="modalFailuresPhoto" src="photo/<?=$photo->getUrl();?>" alt="">
            </div>
            </div>
        </div>
        </div>
    <?php endforeach;?>
<?php endforeach;?>