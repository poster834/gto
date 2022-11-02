<h3>Добавить параметр в карточку машин</h3>
            <form id="propertiesTypesEditForm" action="" method="post">
                <label class="editInput" for="id"><b>id:</b></label> <input class="notEditInput" readonly name="id" type="text" value="<?=$propertiesType->getId();?>">
                <label class="editInput" for="name"><b>Имя:</b></label> <input class="notEditInput" readonly name="name" type="text" value="<?=$propertiesType->getName();?>">
                <label class="editInput" for="description"><b>Низвание в карточке:</b></label> <input class="editInput" name="description" type="text" value="<?=$propertiesType->getDescription();?>">
                <label class="editInput" for="sort"><b>Порядок сортировки:</b></label> <input class="editInput" name="sort" type="number" min="0" step="1" value="<?=$propertiesType->getSort();?>">
                <br>
               <br>
            </form>
            <div class='btn btn-success' onclick="editActiveRecord('propertiesTypes',<?=$propertiesType->getId();?>)"><i class="fa fa-save" aria-hidden="true"> Сохранить</i></div>
            <div class="btn btn-danger" onclick="cancelForm();"><i class="fa fa-window-close-o" aria-hidden="true"> Отмена</i></div>