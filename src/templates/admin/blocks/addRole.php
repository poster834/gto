<h3>Добавить новую группу</h3>
            <form id="rolesAddForm" action="" method="post">
                <label class="editInput" for="name"><b>Имя:</b></label> <input class="editInput" name="name" type="text" value="">
                <br>
                <label class="editInput" for="description"><b>Описание:</b></label> <input class="editInput" name="description" type="text" value="">
                <br><br>
            </form>
            <div class='btn btn-success' onclick="save('roles')"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
            <div class='btn btn-danger' onclick="cancelForm();"><i class="fa fa-save" aria-hidden="true"> Отмена</i></div>