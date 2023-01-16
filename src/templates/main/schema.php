<h3>Список машин</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3">
            
        <?php echo $groupsTree;?>

        </div>
        <div class="col-lg-9">
            <div id="cartMachine" class="cartMachine">

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#search").val('');
        $("#search").keyup(function(){
            let root = document.querySelectorAll('#menu > span:first-child');
            let rootId = document.querySelectorAll('#menu > span:first-child')[0].id;
            collapsGroup(rootId, root);

            _this = this;
            $.each($("#menu span.menuMachine"), function() {
                if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                    $(this).addClass('noVisible');
                    $(this).removeClass('visible');
                } else {
                    $(this).addClass('visible');
                    $(this).removeClass('noVisible');
                    $(this).addClass('menuMachineActive');
                    $(this).removeClass('menuMachineDeActive');

                }                
            });
            if ($("#search").val()=='') {
                $.each($(".menuMachine"), function() {
                    $(this).addClass('noVisible');
                    $(this).removeClass('visible');
                    $(this).removeClass('menuMachineActive');
                    $(this).addClass('menuMachineDeActive');
                });
                let root = document.querySelectorAll('#menu > span:first-child');
                let rootId = document.querySelectorAll('#menu > span:first-child')[0].id;
                collapsGroup(rootId, root);
                
                $('#'+rootId).removeClass('open');
                $.each($("#menu span.menuMachine"), function() {
                    $(this).removeClass('menuMachineActive');
                    $(this).addClass('menuMachineDeActive');
                });
            }
        });
    });
</script>