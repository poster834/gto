<h3>Добавить новый район</h3>
            <form id="regionsAddForm" action="" method="post">
                <label class="editInput" for="name"><b>Имя:</b></label> <input class="editInput" name="name" type="text" value="">
                <br>
                <label class="editInput" for="direction"><b>Направление:</b></label>
                <select class="editInput" name="direction" id="direction">
                <option value="null" disabled selected>Выберете направление</option>
                    <?php foreach($directions as $direction):?>
                        <option value="<?=$direction->getId();?>"><?=$direction->getName();?></option>
                    <?php endforeach;?>
                </select>
                <br><br>
            </form>
            <div class='btn btn-success' onclick="save('regions')"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
            <div class='btn btn-danger' onclick="cancelForm();"><i class="fa fa-save" aria-hidden="true"> Отмена</i></div>