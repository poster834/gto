<h3>Добавить Wialon аккаунт</h3>
            <form id="wialonAccountsAddForm" action="" method="post">

                <label class="editInput" for="name"><b>Наименование:</b></label> <input class="editInput" name="name" type="text" value="">
                <br>
                <label class="editInput" for="login"><b>Логин:</b></label> <input class="editInput" name="login" type="text" value="">             
                <br>
                <label class="editInput" for="password"><b>Пароль:</b></label> <input class="editInput" name="password" type="text" value="">             
                <br>
                <label class="editInput" for="url"><b>URL адрес:</b></label> <input class="editInput" name="url" type="text" value="">
                <br>
                <br>
            </form>
            <div class='btn btn-success' onclick="save('wialonAccounts')"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
            <div class='btn btn-danger' onclick="cancelForm();"><i class="fa fa-window-close-o" aria-hidden="true"> Отмена</i></div>