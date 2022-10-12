<h3>Добавить пользователя</h3>
            <form id="usersAddForm" action="" method="post">

                <label class="editInput" for="login"><b>Логин:</b></label> <input class="editInput" name="login" type="text" value="">
                <br>
                <label class="editInput" for="fio"><b>ФИО:</b></label> <input class="editInput" name="name" type="text" value="">
                <br>
                <label class="editInput" for="email"><b>e-Mail:</b></label> <input class="editInput" name="email" type="email" value="">
                <br>
                <label class="editInput" for="phone"><b>Телефон:</b></label> <input class="editInput" name="phone" type="text" value="">
                <br>
                <label class="editInput" for="roleId"><b>Группа:</b></label> 
                <select class="editInput" name="roleId" id="roleId">
                <option value="null" disabled selected>Выберете группу пользователя</option>
                    <?php foreach($roles as $role):?>
                        <option value="<?=$role->getId();?>"><?=$role->getName();?></option>
                    <?php endforeach;?>
                </select>
                <br><br>
            </form>
            <div class='btn btn-success' onclick="save('users')"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
            <div class='btn btn-danger' onclick="cancelForm();"><i class="fa fa-window-close-o" aria-hidden="true"> Отмена</i></div>   