<h3>Наемный транспорт</h3>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3">
        <div style="position: relative;">
            <input class="form-control" type="text" name="search" id="search">
            <span id="HC_closeSearchBtn" onclick="HC_clearSearch()">[X]</span>
            </div>            
            <hr>
            <div class="menuHC" id="menuHC">
                <?php foreach($hiredCars as $hCar):?>
                    <span class="hCar noVisible" id="hCar<?=$hCar->getId();?>" onclick="selectHCar(<?=$hCar->getId();?>)"><?=$hCar->getName();?></span>
                <?php endforeach;?>
            </div>

        </div>
        <div class="col-lg-9">

            <div id="hCarInfo" class="hCarInfo">
            Всего <?=count($wialonAccounts)?> аккаунтов компаний в базе данных:<br>
            <ul>
                <?php foreach($wialonAccounts as $account):?>
                    <?php if($account->getCarsCount()>0):?>
                        <li><?=$account->getName() ." (".$account->getCarsCount().")";?> </li>
                    <?php else:?>
                        <li><?=$account->getName() ." (нет машин в БД)";?> </li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
            Всего <?=count($hiredCars)?> наемных машин в базе данных
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#search").val('');
        $("#search").keyup(function(){
            // let root = document.querySelectorAll('#menu > span:first-child');
            // let rootId = document.querySelectorAll('#menu > span:first-child')[0].id;
            // collapsGroup(rootId, root);

            _this = this;
            $.each($("#menuHC span.hCar"), function() {
                if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                    $(this).addClass('noVisible');
                    $(this).removeClass('visible');
                } else {
                    $(this).addClass('visible');
                    $(this).removeClass('noVisible');
                    $(this).addClass('menuMachineActiveHC');
                    $(this).removeClass('menuMachineDeActiveHc');

                }                
            });
            // if ($("#search").val()=='') {
            //     $.each($(".menuHC"), function() {
            //         $(this).addClass('noVisible');
            //         $(this).removeClass('visible');
            //         $(this).removeClass('menuMachineActive');
            //         $(this).addClass('menuMachineDeActive');
            //     });
            // }
        });
    });
</script>