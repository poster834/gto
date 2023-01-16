<?php 
$allow = false;
    if ($user->getRoleId() == 1 || $user->getRoleId() == 3) {
        $allow = true;
    }
?>

<?php foreach($failuresPhoto as $photo):?>
    <span id="photoId<?=$photo->getId();?>"><img class="failuresPhoto" src="photo/<?=$photo->getUrl();?>" alt="">
    <span class='editBlock'>
        <i class="fa fa-search-plus fa-2x zoom" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?=$photo->getId()?>" aria-hidden="true"></i>
        <?php if($allow):?>
            <span class="deletePhoto" onclick="deletePhoto(<?=$photo->getId()?>, 'failuresPhoto')">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </span>
            <?php endif;?>
        </span>
    </span>    
<?php endforeach;?>

<?php foreach($failuresPhoto as $photo):?>
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